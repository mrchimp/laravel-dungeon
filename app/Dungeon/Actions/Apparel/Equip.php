<?php

namespace Dungeon\Actions\Apparel;

use Dungeon\Actions\Action;
use Dungeon\Contracts\ApparelInterface;
use Dungeon\Entity;
use Dungeon\Exceptions\EntityNotEquipableException;
use Dungeon\Exceptions\EntityNotPossessedException;
use Dungeon\Exceptions\MissingEntityException;
use Dungeon\User;

class Equip extends Action
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
     */
    public function perform()
    {
        if (!$this->entity) {
            throw new MissingEntityException;
        }

        if (!$this->entity->ownedBy($this->user)) {
            throw new EntityNotPossessedException;
        }

        if (!($this->entity->isEquipable())) {
            throw new EntityNotEquipableException;
        }

        if ($this->entity->equipable->isEquiped()) {
            $this->entity->equipable->unequip();
        } else {
            $this->entity->equipable->equip();
        }

        $this->entity->equipable->save();
    }
}
