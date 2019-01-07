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

    protected $attributes = [
        'can_have_contents' => false,
        'data' => '[]',
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

    public function contents()
    {
        return $this->hasMany(Entity::class, 'container_id');
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

    public function givetoUser(User $user)
    {
        $this->container()->dissociate();
        $this->room()->dissociate();

        $this->owner()->associate($user);
    }

    public function moveToContainer(Entity $container)
    {
        $this->owner()->dissociate();
        $this->room()->dissociate();

        $this->container()->associate($container);
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getName()
    {
        return $this->name;
    }

    public function nameMatchesQuery($query)
    {
        return str_contains(
            strtolower($this->name),
            strtolower($query)
        );
    }

    public function findContents($query)
    {
        return $this->contents->first(function ($entity) use ($query) {
            return $entity->nameMatchesQuery($query);
        });
    }
}