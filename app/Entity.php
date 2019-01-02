<?php

namespace App;

use App\Dungeon\Collections\EntityCollection;
use App\Dungeon\Traits\HasSerializableAttributes;
use App\Observers\EntityObserver;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    use HasUuid, HasSerializableAttributes;
    
    protected $casts = [
        'data' => 'array',
    ];

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
}