<?php

namespace Dungeon\Traits;

trait HasSerializableAttributes
{
    /**
     * Name of the JSON columnt that the serialized data
     * will be stored in
     *
     * @return string
     */
    public function serializedDataColumnName()
    {
        return 'serialized_data';
    }

    /**
     * Get the attributes to be serialized
     *
     * @return array
     */
    public function getSerializable()
    {
        return [];
    }

    /**
     * Get the names of attributes to be serialized
     *
     * @return array
     */
    public function getSerializableAttributes()
    {
        return array_keys($this->getSerializable());
    }

    /**
     * Take attributes and put them in the data array
     *
     * @return void
     */
    public function serializeAttributes()
    {
        // If we try modifying $this->serialized_data directly, we get an
        // error "ErrorException: Indirect modification of
        // overloaded property App\User::$data has no effect"
        // I'm not sure why, but we can just do this instead...
        $data = [];

        foreach ($this->getSerializableAttributes() as $field_name) {
            $data[$field_name] = $this->$field_name;
            unset($this->$field_name);
        }

        $this->{$this->serializedDataColumnName()} = $data;
    }

    /**
     * Take the attributes from the data array and make them attributes
     *
     * @return void
     */
    public function deserializeAttributes()
    {
        foreach ($this->getSerializableAttributes() as $field_name) {
            if (isset($this->{$this->serializedDataColumnName()}[$field_name])) {
                $this->$field_name = $this->{$this->serializedDataColumnName()}[$field_name];
            } elseif (isset($this->{$this->serializedDataColumnName()}[$field_name])) {
                $this->$field_name = $this->{$this->serializedDataColumnName()}[$field_name];
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
        foreach ($this->getSerializableAttributes() as $field_name) {
            if (array_key_exists($field_name, $this->getSerializable())
                && !isset($this->$field_name)
            ) {
                $this->$field_name = $this->getSerializable()[$field_name];
            }
        }
    }
}
