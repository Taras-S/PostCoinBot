<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\Psr7\Response;

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
        if ($request->input('type') == 'url_verification')
            return Response($request->input('challenge'));

        if ($request->input('event.type') != 'reaction_added')
            return Response('Wrong event type', 200);

        if ($request->input('event.reaction') != config('bot.slack.sendingReaction'))
            return Response('Wrong reaction', 200);

        if ($request->input('event.user') == $request->input('event.item_user'))
            return Response('Cant send to yourself', 200);

        return $next($request);
    }
}
