<?php

namespace Dungeon\Actions\Weapons;

use Dungeon\Actions\Action;
use Dungeon\Actions\Entities\Hurt;
use Dungeon\Entity;
use Dungeon\Exceptions\EntityCannotBeDamagedException;
use Dungeon\Exceptions\InvalidEntityException;
use Dungeon\User;

class Attack extends Action
{
    /**
     * @var User
     */
    protected $attacker;

    /**
     * @var Entity
     */
    protected $weapon;

    /**
     * @var Entity
     */
    protected $target;

    /**
     * Damage amounts that were actually dealt
     *
     * @var array
     */
    public $damages = [];

    /**
     * Total amount of damage dealt
     *
     * @var integer
     */
    public $total_damage = 0;

    public function __construct(User $attacker, Entity $weapon, Entity $target)
    {
        $this->attacker = $attacker;
        $this->weapon = $weapon;
        $this->target = $target;
    }

    public function perform()
    {
        if (!$this->weapon->isWeapon()) {
            throw new InvalidEntityException;
        }

        if (!$this->target->isAttackable()) {
            throw new EntityCannotBeDamagedException;
        }

        if ($this->weapon->weapon->blunt > 0) {
            $action = Hurt::do($this->target, $this->weapon->weapon->blunt, 'blunt');
            $this->damages['blunt'] = $action->damage_dealt;
        }

        if ($this->weapon->weapon->stab > 0) {
            $action = Hurt::do($this->target, $this->weapon->weapon->stab, 'stab');
            $this->damages['stab'] = $action->damage_dealt;
        }

        if ($this->weapon->weapon->projectile > 0) {
            $action = Hurt::do($this->target, $this->weapon->weapon->projectile, 'projectile');
            $this->damages['projectile'] = $action->damage_dealt;
        }

        if ($this->weapon->weapon->fire > 0) {
            $action = Hurt::do($this->target, $this->weapon->weapon->fire, 'fire');
            $this->damages['fire'] = $action->damage_dealt;
        }

        $this->total_damage = array_sum($this->damages);
    }
}
