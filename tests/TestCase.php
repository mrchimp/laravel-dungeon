<?php

namespace Tests;

use Dungeon\Collections\EntityCollection;
use Dungeon\DamageTypes\MeleeDamage;
use Dungeon\Entities\People\Body;
use Dungeon\Entities\Weapons\Melee\MeleeWeapon;
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

    protected function makeUser($attributes = [], $health = 100)
    {
        $user = factory(User::class)->create(array_merge([
            'name' => 'Test User',
        ], $attributes));

        $body = factory(Body::class)->create();
        $body->setHealth($health)
            ->giveToUser($user)
            ->save();

        return $user->fresh()->load('body');
    }

    protected function makeRoom($attributes = [])
    {
        return factory(Room::class)->create($attributes);
    }

    protected function makeRock()
    {
        return factory(MeleeWeapon::class)->create([
            'name' => 'Rock',
            'description' => 'You can hit people with it.',
            'damage_types' => [
                MeleeDamage::Class => 10
            ],
        ]);
    }
}
