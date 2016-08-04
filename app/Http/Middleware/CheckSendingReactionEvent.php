<?php

namespace App\Http\Middleware;

use Closure;

class CheckSendingReactionEvent
{
    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->input('event.type') != 'reaction_added') abort(200);
        if ($request->input('event.reaction') != config('bot.slack.sendingReaction')) abort(200);
        if ($request->input('event.user') == $request->input('event.item_user')) abort(200);

        return $next($request);
    }
}
