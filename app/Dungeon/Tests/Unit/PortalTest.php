<?php

namespace Tests\Unit;

use Dungeon\Entities\Locks\Key;
use Dungeon\Portal;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PortalTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** @test */
    public function a_portal_can_have_a_description()
    {
        $portal = $this->makePortal([
            'description' => 'This is a portal',
        ]);

        $this->assertEquals('This is a portal', $portal->getDescription());
    }

    /** @test */
    public function portal_can_be_locked_and_unlocked_with_a_code()
    {
        $portal = $this->makePortal([
            'locked' => false,
        ]);

        $this->assertFalse($portal->isLocked());
        $this->assertTrue($portal->lockWithCode(1234));
        $this->assertTrue($portal->isLocked());
        $this->assertTrue($portal->unlockWithCode(1234));
        $this->assertFalse($portal->isLocked());
    }

    /** @test */
    public function portal_can_have_a_key_that_fits()
    {
        $portal = $this->makePortal();
        $good_key = factory(Key::class)->create();
        $bad_key = factory(Key::class)->create();

        $portal->keys()->attach($good_key->id);

        $portal->refresh();

        $this->assertTrue($portal->keyFits($good_key));
        $this->assertFalse($portal->keyFits($bad_key));
    }

    /** @test */
    public function you_can_test_multiple_keys_at_once()
    {
        $portal = $this->makePortal();
        $other_portal = $this->makePortal();
        $good_key = factory(Key::class)->create();
        $bad_key = factory(Key::class)->create();
        $potato = $this->makePotato();

        $portal->keys()->attach($good_key->id);

        $portal->refresh();

        $collection = collect([
            $potato,
            $bad_key,
            $good_key,
        ]);

        $this->assertEquals($good_key->id, $portal->whichKeyFits($collection)->id);
        $this->assertNull($other_portal->whichKeyFits($collection));
    }

    /** @test */
    public function portal_cannot_be_locked_with_a_code_if_it_doesnt_have_a_code()
    {
        $portal = $this->makePortal([
            'locked' => false,
        ]);

        $this->assertFalse($portal->lockWithCode(1234));
        $this->assertFalse($portal->isLocked());
    }

    /** @test */
    public function portal_can_be_locked_and_unlocked_with_a_key()
    {
        $portal = $this->makePortal([
            'locked' => false,
        ]);

        $key = factory(Key::class)->create();

        $portal->keys()->attach($key->id);

        $this->assertFalse($portal->isLocked());
        $this->assertTrue($portal->lockWithKey($key));
        $this->assertTrue($portal->isLocked());
        $this->assertTrue($portal->unlockWithKey($key));
        $this->assertFalse($portal->isLocked());
    }
}
