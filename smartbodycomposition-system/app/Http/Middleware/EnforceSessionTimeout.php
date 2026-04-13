<?php

namespace App\Http\Middleware;

use App\Support\AdminSecuritySettings;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceSessionTimeout
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->user()?->currentAccessToken();

        if (!$token) {
            return $next($request);
        }

        $lastActivityAt = $token->last_used_at ?? $token->created_at;

        if ($lastActivityAt && $lastActivityAt->copy()->addMinutes(AdminSecuritySettings::sessionTimeout())->isPast()) {
            $token->delete();

            return response()->json([
                'message' => 'Your session has expired due to inactivity. Please log in again.',
            ], 401);
        }

        return $next($request);
    }
}
