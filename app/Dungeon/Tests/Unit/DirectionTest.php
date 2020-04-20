<?php

namespace Tests\Unit;

use Dungeon\Direction;
use Tests\TestCase;

class DirectionTest extends TestCase
{
    /** @test */
    public function name_returns_proper_case_direction_name()
    {
        $this->assertEquals('North', Direction::name('north'));
        $this->assertEquals('South', Direction::name('south'));
        $this->assertEquals('East', Direction::name('east'));
        $this->assertEquals('West', Direction::name('west'));
    }

    /** @test */
    public function isValid_returns_true_if_a_valid_direction_is_used()
    {
        $this->assertTrue(Direction::isValid('north'));
        $this->assertTrue(Direction::isValid('south'));
        $this->assertTrue(Direction::isValid('east'));
        $this->assertTrue(Direction::isValid('west'));

        // Case insensitive
        $this->assertTrue(Direction::isValid('North'));
        $this->assertTrue(Direction::isValid('NORTH'));
        $this->assertTrue(Direction::isValid('NoRtH'));
    }

    /** @test */
    public function isValid_returns_false_if_an_invalid_direction_is_used()
    {
        $this->assertFalse(Direction::isValid('up'));
        $this->assertFalse(Direction::isValid('down'));
        $this->assertFalse(Direction::isValid('sideways'));
        $this->assertFalse(Direction::isValid(1));
        $this->assertFalse(Direction::isValid('Banana'));
    }

    /** @test */
    public function sanitize_returns_a_lowercase_version_of_a_direction()
    {
        $this->assertEquals('north', Direction::sanitize('North'));
        $this->assertEquals('north', Direction::sanitize('NORTH'));
        $this->assertEquals('north', Direction::sanitize('NoRtH'));
    }

    /** @test */
    public function sanitize_returns_null_if_a_direction_is_invalid()
    {
        $this->assertNull(Direction::sanitize('foo'));
        $this->assertNull(Direction::sanitize('not a direction'));
        $this->assertNull(Direction::sanitize(6));
    }
}
