<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BodyComposition;
use App\Models\RecommendationTemplate;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
            ->map(function ($user) use ($thirtyDaysAgo) {
                $lastRecord = $user->bodyCompositions()->latest('created_at')->first();

                return [
                    'id'            => $user->id,
                    'name'          => $user->name,
                    'email'         => $user->email,
                    'role'          => $user->role->value,
                    'created_at'    => $user->created_at->format('Y-m-d'),
                    'last_activity' => $lastRecord ? $lastRecord->created_at->diffForHumans() : 'No records',
                    'record_count'  => $user->body_compositions_count,
                    'status'        => ($lastRecord && $lastRecord->created_at->gte($thirtyDaysAgo))
                                        ? 'Active' : 'Inactive',
                ];
            });

        return response()->json($users);
    }

    // Delete user
    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();

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
}
