<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware for checking the plan limit
 * @version
 * @author Utkarsh Shukla <demo>
 */
class CheckWebsiteLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        $latestPlan = $user->plans()->orderByDesc('pivot_start_date')->first();

        if (!$latestPlan || now()->greaterThan(optional($latestPlan->pivot)->end_date)) {
            return response()->json(['error' => 'Your plan is expired or not found'], 403);
        }

        $limit = $latestPlan->website_limit ?? 0;
        $currentWebsites = $user->websites()->count();

        if ($currentWebsites >= $limit) {
            return response()->json(['error' => 'Website limit reached for your plan'], 403);
        }

        return $next($request);
    }
}
