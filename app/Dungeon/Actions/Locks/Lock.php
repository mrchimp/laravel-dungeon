<?php

namespace Dungeon\Actions\Locks;

use Dungeon\Actions\Action;
use Dungeon\Direction;
use Dungeon\Entity;
use Dungeon\Exceptions\ActionFailedException;
use Dungeon\Exceptions\ExitUnavailableException;
use Dungeon\Exceptions\InvalidDirectionException;
use Dungeon\Exceptions\NoKeyAvailableException;
use Dungeon\Exceptions\PortalLockedException;
use Dungeon\User;

class Lock extends Action
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var Entity
     */
    protected $entity;

    /**
     * @var Room
     */
    public $destination;

    /**
     * @var string
     */
    public $direction;

    public function __construct(User $user, ?string $direction)
    {
        $this->user = $user;
        $this->direction = $direction;
    }

    /**
     * Perform the action
     *
     * @throws InvalidDirectionException
     * @throws ExitUnavailableException
     * @throws PortalLockedException
     * @throws ActionFailedException
     */
    public function perform()
    {
        $direction = Direction::sanitize($this->direction);

        if (!$direction) {
            throw new InvalidDirectionException;
        }

        $room = $this->user->body->room;
        $portal = $room->{$direction . '_portal'};

        if ($portal->isLocked()) {
            throw new PortalLockedException;
        }

        $key = $portal->whichKeyFits($this->user->getInventory());

        if (!$key) {
            throw new NoKeyAvailableException;
        }

        $result = $portal->lockWithKey($key);

        if (!$result) {
            throw new ActionFailedException;
        }
    }
}
