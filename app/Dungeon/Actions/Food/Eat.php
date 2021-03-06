<?php

namespace Dungeon\Actions\Food;

use Dungeon\Actions\Action;
use Dungeon\Entity;
use Dungeon\Exceptions\MissingEntityException;
use Dungeon\Exceptions\UnsupportedVerbException;
use Dungeon\Exceptions\UserIsDeadException;
use Dungeon\User;

class Eat extends Action
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var Entity
     */
    protected $entity;

    public function __construct(User $user, ?Entity $entity)
    {
        $this->user = $user;
        $this->entity = $entity;
    }

    /**
     * Perform the action
     *
     * @throws MissingEntityException
     * @throws UnsupportedVerbException
     */
    public function perform()
    {
        if (!$this->entity) {
            throw new MissingEntityException;
        }

        if (!$this->entity->supportsVerb('eat')) {
            throw new UnsupportedVerbException('Eat verb is not supported by ' . static::class);
        }

        if ($this->user->isDead()) {
            throw new UserIsDeadException;
        }

        $this->user->body->heal($this->entity->healing);
        $this->user->body->save();
        $this->user->save();

        $this->entity->delete();
    }
}
