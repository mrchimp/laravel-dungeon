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
        'can_have_contents',
    ];

    public static function boot()
    {
        parent::boot();

        self::observe(new SerializableObserver);
        self::observe(new HasOwnClassObserver);
    }

    /**
     * Take a given Entity and return a new instance of whatever
     * class is stored in the `class` attribute with the model's
     * attributes applied to it
     *
     * @return mixed
     */
    public static function replaceClass($model)
    {
        $attributes = $model->getAttributes();
        $data = $model->data;
        $exists = $model->exists;

        $model = new $model->class;

        foreach ($attributes as $name => $value) {
            $model->$name = $value;
        }

        // @todo Not sure why this is being set as a string
        // and not being cast to an array. Just gonna do it
        // manually for now.
        $model->data = $data;
        $model->exists = $exists;

        $model->deserializeAttributes();

        return $model;
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

    public function getType()
    {
        return 'generic';
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

    public function moveToVoid()
    {
        $this->owner()->dissociate();
        $this->room()->dissociate();
        $this->container()->dissociate();
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

    public function ownedBy(User $user)
    {
        return (int)$this->owner_id === (int)$user->id;
    }
}