<?php

namespace Dungeon\Events;

use Dungeon\Room;
use Dungeon\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserSaysToRoom implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $room;

    protected $message;

    protected $author;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Room $room, string $message, User $author)
    {
        $this->room = $room;
        $this->message = $message;
        $this->author = $author;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('Room.' . $this->room->uuid);
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'message' => $this->message,
            'author_name' => $this->author->name,
        ];
    }
}
