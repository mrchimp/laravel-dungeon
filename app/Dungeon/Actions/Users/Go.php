<?php

namespace Dungeon\Actions\Users;

use Dungeon\Actions\Action;
use Dungeon\Direction;
use Dungeon\Entity;
use Dungeon\Exceptions\EntityNotPossessedException;
use Dungeon\Exceptions\ExitUnavailableException;
use Dungeon\Exceptions\InvalidDirectionException;
use Dungeon\Exceptions\MissingEntityException;
use Dungeon\Exceptions\PortalLockedException;
use Dungeon\User;

class Go extends Action
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
     */
    public function perform()
    {
        $direction = Direction::sanitize($this->direction);

        if (!$direction) {
            throw new InvalidDirectionException;
        }

        $this->destination = $this->user->getRoom()->{$direction . 'Exit'};

        if (!$this->destination) {
            throw new ExitUnavailableException;
        }

        $portal = $this->user->getRoom()->{$direction . '_portal'};


        if ($portal && $portal->isLocked()) {
            throw new PortalLockedException;
        }

        $this->user
            ->moveTo($this->destination)
            ->save();
    }
}
