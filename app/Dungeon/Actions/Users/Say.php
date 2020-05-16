<?php

namespace Dungeon\Actions\Users;

use Dungeon\Actions\Action;
use Dungeon\Events\UserSaysToRoom;
use Dungeon\Exceptions\EmptyMessageException;
use Dungeon\Exceptions\UserNotInRoomException;
use Dungeon\User;

class Say extends Action
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var string
     */
    protected $message;

    public function __construct(User $user, ?string $message)
    {
        $this->user = $user;
        $this->message = $message;
    }

    /**
     * Perform the action
     */
    public function perform()
    {
        if (empty($this->message)) {
            throw new EmptyMessageException;
        }

        $room = $this->user->getRoom();

        if (!$room) {
            throw new UserNotInRoomException;
        }

        event(new UserSaysToRoom($room, $this->message, $this->user));
    }
}
