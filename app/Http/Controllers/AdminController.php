<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use App\Models\User;
use App\Models\BodyComposition;
use App\Models\RecommendationTemplate;
use App\Support\AdminSecuritySettings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    // Dashboard summary with chart data and recent activity
    public function dashboard()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();

        // Last 6 months labels, cumulative user growth, and monthly record counts
        $months = [];
        $userGrowth = [];
        $monthlyRecords = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthStart = $now->copy()->subMonths($i)->startOfMonth();
            $monthEnd   = $now->copy()->subMonths($i)->endOfMonth();

            $months[]         = $monthStart->format('M');
            $userGrowth[]     = User::where('created_at', '<=', $monthEnd)->count();
            $monthlyRecords[] = BodyComposition::whereBetween('created_at', [$monthStart, $monthEnd])->count();
        }

        // Recent activity — 5 most recent registrations
        $recentActivity = User::orderBy('created_at', 'desc')
            ->take(5)
            ->get(['id', 'name', 'created_at'])
            ->map(fn($u) => [
                'id'     => $u->id,
                'user'   => $u->name,
                'action' => 'Account created',
                'time'   => $u->created_at->diffForHumans(),
            ]);

        return response()->json([
            'total_users'        => User::count(),
            'new_registrations'  => User::where('created_at', '>=', $startOfMonth)->count(),
            'active_users'       => BodyComposition::where('created_at', '>=', $now->copy()->subDays(30))
                                        ->distinct('user_id')
                                        ->count('user_id'),
            'total_records'      => BodyComposition::count(),
            'records_this_month' => BodyComposition::where('created_at', '>=', $startOfMonth)->count(),
            'months'             => $months,
            'user_growth'        => $userGrowth,
            'monthly_records'    => $monthlyRecords,
            'recent_activity'    => $recentActivity,
        ]);
    }

    // View all users with activity info
    public function users()
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);

        $users = User::withCount('bodyCompositions')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn ($user) => $this->formatUserForAdmin($user, $thirtyDaysAgo));

        return response()->json($users);
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:user,admin',
            'account_status' => 'required|in:Active,Inactive',
        ]);

        $user = User::create($validated);

        return response()->json(
            $this->formatUserForAdmin($user->loadCount('bodyCompositions'), Carbon::now()->subDays(30)),
            201
        );
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|min:2|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => 'required|in:user,admin',
            'account_status' => 'required|in:Active,Inactive',
            'password' => 'nullable|string|min:8',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json(
            $this->formatUserForAdmin($user->fresh()->loadCount('bodyCompositions'), Carbon::now()->subDays(30))
        );
    }

    // Delete user
    public function deleteUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        abort_if($request->user()->id === $user->id, 422, 'You cannot delete your own admin account.');

        $user->delete();

        return response()->json(['message' => 'User deleted']);
    }

    // View all records (metadata only, no health values)
    public function records()
    {
        $records = BodyComposition::orderBy('created_at', 'desc')
            ->get(['id', 'user_id', 'created_at'])
            ->map(fn($r) => [
                'id'        => $r->id,
                'recordId'  => $r->created_at->format('Ymd') . '-' . str_pad($r->id, 3, '0', STR_PAD_LEFT),
                'timestamp' => $r->created_at->format('Y-m-d H:i:s'),
                'status'    => 'Submitted',
            ]);

        return response()->json($records);
    }

    // ─── Recommendation Templates CRUD ───────────────────────────────────────

    public function templates()
    {
        return response()->json(RecommendationTemplate::orderBy('id')->get());
    }

    public function storeTemplate(Request $request)
    {
        $validated = $request->validate([
            'template_id'   => 'required|string|unique:recommendation_templates,template_id',
            'template_code' => 'required|string|unique:recommendation_templates,template_code',
            'type'          => 'required|in:Hydration,Exercise,Nutrition,Recovery,Sleep',
            'title'         => 'required|string|max:255',
            'summary'       => 'required|string',
            'details'       => 'required|array|min:1',
            'details.*'     => 'required|string',
            'priority'      => 'required|in:high,medium,low',
            'status'        => 'required|in:Active,Archived',
            'icon'          => 'nullable|string',
        ]);

        $template = RecommendationTemplate::create($validated);

        return response()->json($template, 201);
    }

    public function updateTemplate(Request $request, $id)
    {
        $template = RecommendationTemplate::findOrFail($id);

        $validated = $request->validate([
            'type'     => 'sometimes|in:Hydration,Exercise,Nutrition,Recovery,Sleep',
            'title'    => 'sometimes|string|max:255',
            'summary'  => 'sometimes|string',
            'details'  => 'sometimes|array|min:1',
            'details.*'=> 'required|string',
            'priority' => 'sometimes|in:high,medium,low',
            'status'   => 'sometimes|in:Active,Archived',
            'icon'     => 'nullable|string',
        ]);

        $template->update($validated);

        return response()->json($template);
    }

    public function destroyTemplate($id)
    {
        RecommendationTemplate::findOrFail($id)->delete();

        return response()->json(['message' => 'Template deleted']);
    }

    public function settings()
    {
        $securityDefaults = AdminSecuritySettings::defaults();

        $defaults = [
            'notifications' => [
                'emailOnRegister' => true,
                'emailOnGoalAchieved' => true,
                'emailOnInactivity' => false,
                'weeklyDigest' => false,
            ],
            'mailer' => config('mail.default', 'smtp'),
            'smtp' => [
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port', 587),
                'username' => '',
                'password' => '',
                'from' => config('mail.from.address'),
                'from_name' => config('mail.from.name', config('app.name')),
                'encryption' => match (config('mail.mailers.smtp.scheme')) {
                    'smtps' => 'ssl',
                    null => 'none',
                    default => 'tls',
                },
            ],
            'postmark' => [
                'token' => config('services.postmark.token'),
                'from' => config('mail.from.address'),
                'from_name' => config('mail.from.name', config('app.name')),
                'stream_id' => env('POSTMARK_MESSAGE_STREAM_ID', ''),
            ],
            'sessionTimeout' => $securityDefaults['sessionTimeout'],
            'maxLoginAttempts' => $securityDefaults['maxLoginAttempts'],
            'maintenanceMode' => $securityDefaults['maintenanceMode'],
        ];

        $mailer = SystemSetting::get('mailer', $defaults['mailer']);
        $smtp = SystemSetting::getDecoded('smtp_settings', $defaults['smtp']);
        $postmark = SystemSetting::getDecoded('postmark_settings', $defaults['postmark']);
        $notificationSettings = SystemSetting::getDecoded('admin_notification_settings', $defaults['notifications']);
        $securitySettings = AdminSecuritySettings::all();

        if (is_array($smtp) && !empty($smtp['password'])) {
            try {
                $smtp['password'] = Crypt::decryptString($smtp['password']);
            } catch (\Throwable) {
                $smtp['password'] = '';
            }
        }

        return response()->json([
            'notifications' => array_merge($defaults['notifications'], is_array($notificationSettings) ? $notificationSettings : []),
            'mailer' => $mailer,
            'smtp' => array_merge($defaults['smtp'], is_array($smtp) ? $smtp : []),
            'postmark' => array_merge($defaults['postmark'], is_array($postmark) ? $postmark : []),
            'sessionTimeout' => $securitySettings['sessionTimeout'] ?? $defaults['sessionTimeout'],
            'maxLoginAttempts' => $securitySettings['maxLoginAttempts'] ?? $defaults['maxLoginAttempts'],
            'maintenanceMode' => (bool) ($securitySettings['maintenanceMode'] ?? $defaults['maintenanceMode']),
        ]);
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'notifications' => 'required|array',
            'notifications.emailOnRegister' => 'required|boolean',
            'notifications.emailOnGoalAchieved' => 'required|boolean',
            'notifications.emailOnInactivity' => 'required|boolean',
            'notifications.weeklyDigest' => 'required|boolean',
            'mailer' => 'required|in:smtp,postmark',
            'smtp' => 'required|array',
            'smtp.host' => 'nullable|string|max:255',
            'smtp.port' => 'nullable|integer|min:1|max:65535',
            'smtp.username' => 'nullable|string|max:255',
            'smtp.password' => 'nullable|string|max:255',
            'smtp.from' => 'nullable|email',
            'smtp.from_name' => 'nullable|string|max:255',
            'smtp.encryption' => 'required|in:tls,ssl,none',
            'postmark' => 'required|array',
            'postmark.token' => 'nullable|string|max:255',
            'postmark.from' => 'nullable|email',
            'postmark.from_name' => 'nullable|string|max:255',
            'postmark.stream_id' => 'nullable|string|max:255',
            'sessionTimeout' => 'required|integer|min:5|max:1440',
            'maxLoginAttempts' => 'required|integer|min:3|max:20',
            'maintenanceMode' => 'required|boolean',
        ]);

        $smtp = $validated['smtp'];
        $smtp['password'] = !empty($smtp['password']) ? Crypt::encryptString($smtp['password']) : '';
        $postmark = $validated['postmark'];

        SystemSetting::put('mailer', $validated['mailer']);
        SystemSetting::putEncoded('smtp_settings', $smtp);
        SystemSetting::putEncoded('postmark_settings', $postmark);
        SystemSetting::putEncoded('admin_notification_settings', $validated['notifications']);
        SystemSetting::putEncoded('admin_security_settings', [
            'sessionTimeout' => $validated['sessionTimeout'],
            'maxLoginAttempts' => $validated['maxLoginAttempts'],
            'maintenanceMode' => $validated['maintenanceMode'],
        ]);

        return response()->json([
            'message' => 'Settings saved successfully.',
            'data' => $this->settings()->getData(true),
        ]);
    }

    public function sendTestEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => 'nullable|email',
        ]);

        $recipient = $validated['email'] ?? $request->user()->email;

        try {
            Mail::to($recipient)->send(new class($request->user()->name ?? 'Admin') extends \Illuminate\Mail\Mailable {
                public function __construct(private readonly string $adminName)
                {
                }

                public function build(): self
                {
                    return $this
                        ->subject('SMTP test email from Smart Body Composition')
                        ->html(
                            '<h2>SMTP configuration is working</h2>'
                            . '<p>Hello ' . e($this->adminName) . ',</p>'
                            . '<p>This is a test email sent from the Admin Settings SMTP configuration.</p>'
                            . '<p>If you received this, the saved mail settings are being applied successfully.</p>'
                        );
                }
            });
        } catch (\Throwable $exception) {
            return response()->json([
                'message' => 'Failed to send test email.',
                'error' => $exception->getMessage(),
            ], 422);
        }

        return response()->json([
            'message' => 'Test email sent successfully to ' . $recipient . '.',
        ]);
    }

    private function formatUserForAdmin(User $user, Carbon $thirtyDaysAgo): array
    {
        $lastRecord = $user->bodyCompositions()->latest('created_at')->first();
        $activityStatus = ($lastRecord && $lastRecord->created_at->gte($thirtyDaysAgo)) ? 'Active' : 'Inactive';

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role->value,
            'created_at' => $user->created_at->format('Y-m-d'),
            'last_activity' => $lastRecord ? $lastRecord->created_at->diffForHumans() : 'No records',
            'record_count' => $user->body_compositions_count ?? $user->bodyCompositions()->count(),
            'status' => $user->account_status ?? 'Active',
            'activity_status' => $activityStatus,
        ];
    }
}
