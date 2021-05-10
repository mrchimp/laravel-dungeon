<?php

// namespace Dungeon\Traits;

// trait HasSerializableAttributes
// {
//     /**
//      * Name of the JSON column that the serialized data
//      * will be stored in
//      */
//     public function serializedDataColumnName(): string
//     {
//         return 'serialized_data';
//     }

//     /**
//      * Get the attributes to be serialized
//      */
//     public function getSerializable(): array
//     {
//         return [];
//     }

//     /**
//      * Get the names of attributes to be serialized
//      */
//     public function getSerializableAttributes(): array
//     {
//         return array_keys($this->getSerializable());
//     }

//     /**
//      * Take attributes and put them in the data array
//      */
//     public function serializeAttributes(): void
//     {
//         // If we try modifying $this->serialized_data directly, we get an
//         // error "ErrorException: Indirect modification of
//         // overloaded property App\User::$data has no effect"
//         // I'm not sure why, but we can just do this instead...
//         $data = [];

//         foreach ($this->getSerializableAttributes() as $field_name) {
//             $data[$field_name] = $this->$field_name;
//             unset($this->$field_name);
//         }

//         $this->{$this->serializedDataColumnName()} = $data;
//     }

//     /**
//      * Take the attributes from the data array and make them attributes
//      */
//     public function deserializeAttributes(): void
//     {
//         foreach ($this->getSerializableAttributes() as $field_name) {
//             if (isset($this->{$this->serializedDataColumnName()}[$field_name])) {
//                 $this->$field_name = $this->{$this->serializedDataColumnName()}[$field_name];
//             }
//         }
//     }

//     /**
//      * Take attributes from the defaults array and apply
//      * them as attributes
//      */
//     public function applyDefaultSerializableAttributes(): void
//     {
//         foreach ($this->getSerializableAttributes() as $field_name) {
//             if (!isset($this->$field_name)) {
//                 $this->$field_name = $this->getSerializable()[$field_name];
//             }
//         }
//     }
// }
