<?php

namespace App\Dungeon\Traits;

trait HasSerializableAttributes
{
    /**
     * Get the names of attributes to be serialized
     *
     * @return array
     */
    public function getSerializable()
    {
        return [];
    }

    /**
     * Take attributes and put them in the data array
     *
     * @return void
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
            unset($this->$field);
        }

        $this->data = $data;
    }

    /**
     * Take the attributes from the data array and make them attributes
     *
     * @return void
     */
    public function deserializeAttributes()
    {
        foreach ($this->getSerializable() as $field) {
            if (isset($this->data[$field])) {
                $this->$field = $this->data[$field];
            } elseif (isset($this->serializable[$field])) {
                $this->$field = $this->serializable[$field];
            }
        }
    }

    /**
     * Take attributes from the defaults array and apply
     * them as attributes
     *
     * @return void
     */
    public function applyDefaultSerializableAttributes()
    {
        foreach ($this->getSerializable() as $field) {
            if (array_key_exists($field, $this->serializable)
                && !isset($this->$field)
            ) {
                $this->$field = $this->serializable[$field];
            }
        }
    }
}
