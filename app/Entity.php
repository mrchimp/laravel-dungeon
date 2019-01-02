<?php

namespace App;

use App\Traits\HasUuid;
use App\Observers\EntityObserver;
use Illuminate\Database\Eloquent\Model;
use App\Dungeon\Collections\EntityCollection;

class Entity extends Model
{
    use HasUuid;
    
    protected $casts = [
        'data' => 'array',
    ];

    /**
     * Attributes to serialize in `data` when saving
     */
    protected $serializable = [];

    public static function boot()
    {
        parent::boot();

        self::observe(new EntityObserver);
    }
    
    /**
     * Create a new Eloquent Collection instance
     * 
     * @param array $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new EntityCollection($models);
    }

    /**
     * Take attributes and put them in the data array
     */
    public function serialiseAttributes()
    {
        foreach ($this->serializable as $key => $value) {
            $this->data[$key] = $value;
        }
    }

    /**
     * Take the attributes from the data array and make them attributes
     */
    public function deserialiseAttributes()
    {
        foreach ($this->serializable as $key => $value) {
            $this->$key = $value;
        }
    }
}