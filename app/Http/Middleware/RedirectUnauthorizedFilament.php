<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectUnauthorizedFilament
{
    /**
     * Previously enforced admin/author only access. Relaxed to allow any authenticated user to reach the panel.
     * Authorization for actions is handled via policies & resource navigation.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow Filament auth routes (login, logout, password reset) to pass through.
        $routeName = $request->route()?->getName();
        if (is_string($routeName) && str_starts_with($routeName, 'filament.admin.auth.')) {
            return $next($request);
        }

        // No forced logout; all authenticated users proceed.

        return $next($request);
    }
}
