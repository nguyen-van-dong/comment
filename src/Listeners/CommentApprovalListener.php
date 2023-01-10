<?php

namespace Module\Comment\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;

class CommentApprovalListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $comment = $event->comment;
        Http::get(config('comment_realtime.socket_url_server', 'https://comment.herokuapp.com').'/trigger/comment', $comment);
    }
}
