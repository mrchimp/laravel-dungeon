<?php

namespace Dungeon\Events;

use Dungeon\Entities\People\Body;
use Dungeon\Entities\Weapon;
use Dungeon\Entity;
use Dungeon\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BeforeAttack
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The the body of the User attacking
     *
     * @var User
     */
    public $attacker;

    /**
     * The Body of the User being attacked
     *
     * @var Body
     */
    public $target;

    /**
     * The Weapon used to attack
     *
     * @var Weapon
     */
    public $weapon;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $attacker, Entity $target, Entity $weapon)
    {
        $this->attacker = $attacker;
        $this->target = $target;
        $this->weapon = $weapon;
    }
}
