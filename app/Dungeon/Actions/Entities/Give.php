<?php

namespace Dungeon\Actions\Entities;

use Dungeon\Actions\Action;
use Dungeon\Entity;
use Dungeon\Exceptions\EntityNotPossessedException;
use Dungeon\Exceptions\MissingEntityException;
use Dungeon\Exceptions\TargetUserIsDeadException;
use Dungeon\Exceptions\TargetUserNotProvidedException;
use Dungeon\User;

class Give extends Action
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
     * @var User
     */
    protected $recipient;

    public function __construct(User $user, ?Entity $entity, ?User $recipient)
    {
        $this->user = $user;
        $this->entity = $entity;
        $this->recipient = $recipient;
    }

    /**
     * Perform the action
     *
     * @throws MissingEntityException
     */
    public function perform()
    {
        if (!$this->entity) {
            throw new MissingEntityException;
        }

        if (!$this->recipient) {
            throw new TargetUserNotProvidedException;
        }

        if (!$this->entity->ownedBy($this->user)) {
            throw new EntityNotPossessedException;
        }

        // @todo need to test this - maybe place item on body instead?
        if (!$this->recipient->owner) {
            throw new TargetUserIsDeadException;
        }

        $this->entity->giveToUser($this->recipient->owner);
        $this->entity->save();
    }
}
