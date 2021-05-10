<?php

// namespace Dungeon\Observers;

// use Illuminate\Database\Eloquent\Model;

// class SerializableObserver
// {
//     /**
//      * Manipulate the model before saving
//      */
//     public function saving(Model $entity)
//     {
//         $entity->applyDefaultSerializableAttributes();
//         $entity->serializeAttributes();
//     }

//     /**
//      * After saving, deserialize attributes
//      */
//     public function saved(Model $entity)
//     {
//         $entity->deserializeAttributes();
//     }

//     /**
//      * Retrieved from database, extract attributes
//      */
//     public function retrieved(Model $entity)
//     {
//         $entity->deserializeAttributes();
//     }
// }
