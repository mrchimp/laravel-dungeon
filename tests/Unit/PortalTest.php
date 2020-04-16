<?php

namespace Tests\Unit;

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
        $portal = factory(Portal::class)->make([
            'description' => 'This is a portal',
        ]);

        $this->assertEquals('This is a portal', $portal->getDescription());
    }

    /** @test */
    public function portal_can_be_locked_and_unlocked_with_a_code()
    {
        $portal = factory(Portal::class)->make([
            'locked' => false,
        ]);

        $this->assertFalse($portal->isLocked());
        $this->assertTrue($portal->lockWithCode('1234'));
        $this->assertTrue($portal->isLocked());
        $this->assertTrue($portal->unlockWithCode('1234'));
        $this->assertFalse($portal->isLocked());
    }
}
