<?php

namespace Dungeon;

use Dungeon\Collections\EntityCollection;
use Dungeon\Entities\People\Body;
use Dungeon\Observers\HasOwnClassObserver;
use Dungeon\Observers\SerializableObserver;
use Dungeon\Traits\Findable;
use Dungeon\Traits\HasSerializableAttributes;
use Dungeon\Traits\HasUuid;
use Dungeon\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    use HasUuid, HasSerializableAttributes, Findable;

    public $can_be_taken = true;

    protected $table = 'entities';

    protected $casts = [
        'serialized_data' => 'array',
    ];

    /**
     * Which fields are prevented from mass assignment
     *
     * We set only ID here so that we can extend the
     * attributes with serializable attributes
     *
     * @var array
     */
    protected $guarded = ['id'];

    public static function boot()
    {
        parent::boot();

        self::observe(new SerializableObserver);
        self::observe(new HasOwnClassObserver);
    }

    public function getSerializable()
    {
        return [
            'can_be_taken' => true,
        ];
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
        $serialized_data = $model->serialized_data;
        $exists = $model->exists;

        $model = new $model->class;

        foreach ($attributes as $name => $value) {
            $model->$name = $value;
        }

        // @todo Not sure why this is being set as a string
        // and not being cast to an array. Just gonna do it
        // manually for now.
        $model->serialized_data = $serialized_data;
        $model->exists = $exists;

        $model->deserializeAttributes();

        return $model;
    }

    /**
     * Create a new Eloquent Collection instance
     *
     * @param array $models
     *
     * @return Collection
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

    public function npc()
    {
        return $this->belongsTo(NPC::class);
    }

    public function moveToRoom(Room $room = null)
    {
        $this->moveToVoid();

        $this->room()->associate($room);

        return $this;
    }

    public function giveToUser(User $user = null)
    {
        if (is_null($user)) {
            $this->moveToVoid();
            return $this;
        }

        if (!$user->hasBody()) {
            return $this;
        }

        $this->moveToContainer($user->body);

        return $this;
    }

    public function moveToContainer(Entity $container = null)
    {
        $this->moveToVoid();
        $this->container()->associate($container);
        $this->save();

        return $this;
    }

    public function giveToNPC(NPC $npc = null)
    {
        $this->moveToVoid();

        if (!$npc->hasBody()) {
            return;
        }

        $this->moveToContainer($npc->body);

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
        if ($this->container instanceof Body) {
            return (int) $this->container->owner_id === (int) $user->id;
        }
    }

    public function toArray()
    {
        $array = parent::toArray();

        foreach ($this->getSerializable() as $serializable) {
            $array[$serializable] = $this->$serializable;
        }

        return $array;
    }

    public function getRoom()
    {
        if (!$this->room) {
            return null;
        }

        return $this->room;
    }

    public function canBeTaken()
    {
        return $this->can_be_taken;
    }

    public function canBeAttacked()
    {
        return false;
    }
}
