<?php

namespace Tests\Feature;

use App\Room;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoomTest extends TestCase
{
    /**
     * Rooms have descriptions
     *
     * @test
     */
    public function has_a_description()
    {
        $room = new Room([
            'description' => 'This is a description.',
        ]);

        $this->assertEquals('This is a description.', $room->description);
    }
}
