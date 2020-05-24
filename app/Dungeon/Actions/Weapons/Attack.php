<?php

namespace Dungeon\Actions\Weapons;

use Dungeon\Actions\Action;
use Dungeon\Actions\Entities\Hurt;
use Dungeon\Contracts\Damageable;
use Dungeon\Contracts\WeaponInterface;
use Dungeon\Entities\Weapons\Weapon;
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
     * @var Weapon
     */
    protected $weapon;

    /**
     * @var Damageable
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

    public function __construct(User $attacker, Weapon $weapon, Damageable $target)
    {
        $this->attacker = $attacker;
        $this->weapon = $weapon;
        $this->target = $target;
    }

    public function perform()
    {
        if (!($this->weapon instanceof WeaponInterface)) {

            throw new InvalidEntityException;
        }

        if (!($this->target instanceof Damageable)) {
            throw new EntityCannotBeDamagedException;
        }

        foreach ($this->weapon->damageTypes() as $damage_type => $damage_amount) {
            $action = Hurt::do($this->target, $damage_amount, $damage_type);
            $this->damages[$damage_type] = $action->damage_dealt;
        }

        $this->total_damage = array_sum($this->damages);
    }
}
