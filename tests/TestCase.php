<?php

namespace Tests;

use Dungeon\Collections\EntityCollection;
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
}
