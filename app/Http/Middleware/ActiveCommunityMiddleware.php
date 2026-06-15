<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ActiveCommunityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !session()->has('active_community_id')) {
            $user = auth()->user();
            $firstCommunity = \App\Models\Community::whereHas('members', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->first();

            // Fallback if the user has no communities yet, try to get the first community in DB
            if (!$firstCommunity) {
                $firstCommunity = \App\Models\Community::first();
            }

            if ($firstCommunity) {
                session(['active_community_id' => $firstCommunity->id]);
            }
        }

        // Share the active community to all views
        if (session()->has('active_community_id')) {
            $activeCommunity = \App\Models\Community::find(session('active_community_id'));
            view()->share('activeCommunity', $activeCommunity);
        }

        return $next($request);
    }
}
