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

class CommentCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Comment
     */
    public $comment;

    /**
     * Create a new event instance.
     *
     * @param  Comment  $comment
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
//        return new PrivateChannel('channel-name');
        return 'chat-message-channel';
    }

    public function broadcastWith()
    {
        return ['message' => $this->comment];
    }
}
