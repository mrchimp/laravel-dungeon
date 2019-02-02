<?php

namespace App;

use App\Dungeon\Collections\EntityCollection;
use App\Dungeon\Contracts\Interactable;
use App\Dungeon\Traits\Findable;
use App\Dungeon\Traits\HasSerializableAttributes;
use App\Observers\HasOwnClassObserver;
use App\Observers\SerializableObserver;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Entity extends Model implements Interactable
{
    use HasUuid, HasSerializableAttributes, Findable;

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

    public function isEquipable()
    {
        return false;
    }

    public function getVerbs()
    {
        return [
            'take',
            'drop',
            'inspect',
            'use',
        ];
    }

    public function supportsVerb($verb)
    {
        return in_array($verb, $this->getVerbs());
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

    public function npc()
    {
        return $this->belongsTo(NPC::class);
    }

    public function moveToRoom(Room $room)
    {
        $this->moveToVoid();

        $this->room()->associate($room);

        return $this;
    }

    public function givetoUser(User $user)
    {
        $this->moveToVoid();

        $this->owner()->associate($user);

        return $this;
    }

    public function moveToContainer(Entity $container)
    {
        $this->moveToVoid();

        $this->container()->associate($container);

        return $this;
    }

    public function giveToNPC(NPC $npc)
    {
        $this->moveToVoid();

        $this->npc()->associate($npc);

        return $this;
    }

    public function moveToVoid()
    {
        $this->owner()->dissociate();
        $this->room()->dissociate();
        $this->container()->dissociate();
        $this->npc()->dissociate();

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getName()
    {
        return $this->name;
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

    public function toArray()
    {
        $array = parent::toArray();

        foreach ($this->getSerializable() as $serializable) {
            $array[$serializable] = $this->$serializable;
        }

        $array['type'] = $this->getType();

        return $array;
    }
}
