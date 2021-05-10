<?php

namespace Dungeon\Tests;

use Dungeon\Entity;

class TestEntity extends Entity
{
    public $test_value = 'test';

    /**
     * Get the attributes to be serialized
     */
    // public function getSerializable(): array
    // {
    //     return [
    //         'test_value' => $this->test_value,
    //     ];
    // }
}
