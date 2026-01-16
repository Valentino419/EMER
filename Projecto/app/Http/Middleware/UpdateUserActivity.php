<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UpdateUserActivity
{
    /**
     * Define the duration in minutes for which a user is considered online.
     * @var int
     */
    protected int $onlineTimeoutMinutes = 5;

    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $cacheKey = 'user-online-' . $userId;
//\//Log::info('Activity Middleware Running for User:', ['user_id' => $userId, 'key' => $cacheKey]); // <-- ADD THIS LINE
            // Refactored: Use the timeout in minutes directly instead of a Carbon instance.
            // This is more concise for simple time limits.
            Cache::put($cacheKey, true, $this->onlineTimeoutMinutes);
        }

        return $next($request);
    }
}
