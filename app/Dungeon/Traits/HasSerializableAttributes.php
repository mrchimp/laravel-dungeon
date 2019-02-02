<?php

namespace App\Dungeon\Traits;

trait HasSerializableAttributes
{
    public function getSerializable()
    {
        return [];
    }

    /**
     * Take attributes and put them in the data array
     */
    public function serializeAttributes()
    {
        // If we try modifying $this->data directly, we get an
        // error "ErrorException: Indirect modification of
        // overloaded property App\User::$data has no effect"
        // I'm not sure why, but we can just do this instead...
        $data = [];

        foreach ($this->getSerializable() as $field) {
            $data[$field] = $this->$field;
        }

        $this->data = $data;
    }

    /**
     * Take the attributes from the data array and make them attributes
     */
    public function deserializeAttributes()
    {
        foreach ($this->getSerializable() as $field) {
            $this->$field = $this->data[$field];
        }
    }
}
