<?php

namespace Dungeon\Actions\Users;

use Dungeon\Actions\Action;
use Dungeon\Exceptions\UserIsAliveException;
use Dungeon\User;

class Respawn extends Action
{

    /**
     * @var User
     */
    protected $user;

    /**
     * @var string
     */
    public $direction;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Perform the action
     *
     * @throws UserIsAliveException
     */
    public function perform()
    {
        if ($this->user->isAlive()) {
            throw new UserIsAliveException;
        }

        $this->user->respawn()->save();
    }
}
