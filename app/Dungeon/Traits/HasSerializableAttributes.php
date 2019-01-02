<?php

namespace App\Dungeon\Traits;

trait HasSerializableAttributes
{
    /**
     * Attributes to serialize in `data` when saving
     */
    // protected $serializable = [];

    /**
     * Take attributes and put them in the data array
     */
    public function serialiseAttributes()
    {
        // If we try modifying $this->data directly, we get an 
        // error "ErrorException: Indirect modification of 
        // overloaded property App\User::$data has no effect"
        // I'm not sure why, but we can just do this instead...
        $data = $this->data;

        foreach ($this->serializable as $field) {
            $data[$field] = $this->$field;
        }

        $this->data = $data;
    }

    /**
     * Take the attributes from the data array and make them attributes
     */
    public function deserialiseAttributes()
    {
        foreach ($this->serializable as $field) {
            $this->$field = $this->data[$field];
        }
    }
}