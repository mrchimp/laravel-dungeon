<?php

namespace App;

use App\Dungeon\Collections\EntityCollection;
use App\Dungeon\Traits\HasSerializableAttributes;
use App\Observers\HasOwnClassObserver;
use App\Observers\SerializableObserver;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    use HasUuid, HasSerializableAttributes;

    protected $table = 'entities';

    protected $casts = [
        'data' => 'array',
    ];

    protected $serializable = [];

    protected $fillable = [
        'description',
        'name',
    ];

    public static function boot()
    {
        parent::boot();

        self::observe(new SerializableObserver);
        self::observe(new HasOwnClassObserver);
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

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function container()
    {
        return $this->belongsTo(Entity::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function moveToRoom(Room $room)
    {
        $this->owner()->dissociate();
        $this->container()->dissociate();

        $this->room()->associate($room);
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getName()
    {
        return $this->name;
    }
}