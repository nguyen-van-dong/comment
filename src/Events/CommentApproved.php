<?php

namespace Module\Comment\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Module\Comment\Models\Comment;

class CommentApproved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;

    /**
     * CommentApproved constructor.
     * @param $comment
     */
    public function __construct($comment)
    {
        $this->comment = $comment;
    }
}
