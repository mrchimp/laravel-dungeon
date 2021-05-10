<?php

namespace Dungeon\Actions\Entities;

use Dungeon\Actions\Action;
use Dungeon\Entity;

class Hurt extends Action
{
    /**
     * @var Entity
     */
    protected $entity;

    /**
     * @var int
     */
    protected $amount;

    /**
     * @var string
     */
    protected $type;

    /**
     * Damage actually dealt after reductions from armour etc.
     *
     * @var int
     */
    public $damage_dealt;

    public function __construct(Entity $entity, int $amount, string $type)
    {
        $this->entity = $entity;
        $this->amount = $amount;
        $this->type = $type;
    }

    public function perform()
    {
        $this->entity->attackable->attack($this->amount, $this->type);
        $this->entity->save();

        $this->damage_dealt = $this->amount;
    }
}
