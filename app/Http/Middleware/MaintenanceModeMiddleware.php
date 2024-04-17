<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceModeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $settings = Setting::all()->mapWithKeys(function (Setting $item, int $key) {
            $itemValue = match ($item['type']) {
                'boolean' => boolval($item['value']),
                default => $item['value']
            };

            return [$item['key'] => $itemValue];
        });

        if ($settings['maintenance_mode'] && $request->user()->role != User::ROLE_ADMIN) {
            abort(442, 'Включен режим обслуживания, попробуйте позже!');
        }

        return $next($request);
    }
}
