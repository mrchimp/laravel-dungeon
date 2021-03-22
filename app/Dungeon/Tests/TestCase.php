<?php

namespace Tests;

use Dungeon\Collections\EntityCollection;
use Dungeon\DamageTypes\MeleeDamage;
use Dungeon\Entities\Apparel\Apparel;
use Dungeon\Entities\Containers\Box;
use Dungeon\Entities\Food\Food;
use Dungeon\Entities\Locks\Code;
use Dungeon\Entities\People\Body;
use Dungeon\Entities\Weapons\Melee\MeleeWeapon;
use Dungeon\Entity;
use Dungeon\NPC;
use Dungeon\Portal;
use Dungeon\Room;
use Dungeon\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function assertIsCollection($item)
    {
        $this->assertEquals(Collection::class, get_class($item));
    }

    public function assertIsEntityCollection($item)
    {
        $this->assertEquals(EntityCollection::class, get_class($item));
    }

    protected function makeUser($attributes = [], $health = 100, $room = null)
    {
        $user = factory(User::class)->create(array_merge([
            'name' => 'Test User',
        ], $attributes));

        $body = factory(Body::class)->create();
        $body->setHealth($health)
            ->giveToUser($user)
            ->save();

        if ($room) {
            $user->moveTo($room)->save();
        }

        return $user->fresh()->load('body');
    }

    /**
     * Create an enemy
     *
     * @param array $attributes
     * @param integer $health
     * @param Room $room
     *
     * @return User
     */
    protected function makeEnemy($attributes = [], $health = 100, $room = null)
    {
        return $this->makeUser(array_merge([
            'name' => 'Enemy',
        ], $attributes), $health, $room);
    }

    protected function makeRoom($attributes = [])
    {
        return factory(Room::class)->create(array_merge(
            [
                'description' => 'A room. Maybe with a potato in it.',
            ],
            $attributes
        ));
    }

    protected function makeRock(int $damage = 10)
    {
        return factory(Entity::class)
            ->create([
                'name' => 'Rock',
                'description' => 'You can hit people with it.',
            ])
            ->makeTakeable([
                'weight' => 10,
            ])
            ->makeWeapon([
                'blunt' => $damage,
            ]);
    }

    protected function makePotato(array $attributes = [], int $healing = 50)
    {
        $potato = factory(Food::class)
            ->create(array_merge([
                'name' => 'Potato',
                'description' => 'A potato.',
            ], $attributes))
            ->makeTakeable([
                'weight' => 1,
            ]);

        $potato->setHealing($healing)->save();

        return $potato;
    }

    protected function makeHat(array $attributes = [])
    {
        return factory(Entity::class)
            ->create(array_merge([
                'name' => 'Hat',
                'description' => 'Headwear',
            ], $attributes))
            ->makeEquipable([])
            ->makeTakeable([
                'weight' => 1
            ])
            ->makeProtects([
                'blunt' => 10,
                'stab' => 10,
                'projectile' => 0,
                'fire' => 10,
            ]);
    }

    protected function makeBox(array $attributes = [])
    {
        return factory(Box::class)->create(array_merge([
            'name' => 'Box',
            'description' => 'You can put things in it.',
            'class' => Entity::class,
            'serialized_data' => [],
        ], $attributes));
    }

    protected function makeNPC($attributes = [], $health = 100, $room = null)
    {
        $npc = factory(NPC::class)->create(array_merge([
            'name' => 'Test NPC',
            'description' => 'An NPC for testing',
        ], $attributes));

        $npc_body = factory(Body::class)
            ->create()
            ->giveToNPC($npc);

        $npc_body->save();

        if ($room) {
            $npc_body->moveToRoom($room);
            $npc_body->save();
        }

        $npc->save();

        $npc->load('body');
        $npc->fresh();

        return $npc;
    }

    protected function makePortal(array $attributes = [])
    {
        return Portal::createWithSerializable(
            array_merge([
                'name' => 'Test door',
                'description' => 'A simple door for testing purposes.',
            ], $attributes)
        );
    }

    protected function makeCode(int $code)
    {
        return factory(Code::class)->create([
            'code' => $code,
        ]);
    }
}
