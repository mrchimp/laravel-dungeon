<?php

namespace Dungeon;

use Dungeon\Collections\EntityCollection;
use Dungeon\Entities\People\Body;
use Dungeon\Observers\HasOwnClassObserver;
use Dungeon\Observers\SerializableObserver;
use Dungeon\Observers\UuidObserver;
use Dungeon\Traits\Findable;
use Dungeon\Traits\HasSerializableAttributes;
use Dungeon\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entity extends Model
{
    use HasSerializableAttributes, Findable;

    public $can_be_taken = true;

    protected $table = 'entities';

    protected $casts = [
        'serialized_data' => 'array',
    ];

    protected $fillable = [
        'name',
        'description',
    ];

    protected $hidden = [
        'id',
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

        self::observe(new UuidObserver);
        self::observe(new SerializableObserver);
        self::observe(new HasOwnClassObserver);
    }

    /**
     * Get the attributes to be serialized
     */
    public function getSerializable(): array
    {
        return [
            'can_be_taken' => $this->can_be_taken,
        ];
    }

    /**
     * Can this entity be equipped as apparel
     */
    public function isEquipable(): bool
    {
        return false;
    }

    /**
     * Verbs that this entity can be used for
     */
    public function getVerbs(): array
    {
        return [
            'take',
            'drop',
            'inspect',
            'use',
        ];
    }

    /**
     * Does this entity support a given verb
     */
    public function supportsVerb(string $verb): bool
    {
        return in_array($verb, $this->getVerbs());
    }

    /**
     * Take a given Entity and return a new instance of whatever
     * class is stored in the `class` attribute with the model's
     * attributes applied to it
     */
    public static function replaceClass(Model $model): Model
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
     */
    public function newCollection(array $models = []): Collection
    {
        return new EntityCollection($models);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function container(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

    public function contents(): HasMany
    {
        return $this->hasMany(Entity::class, 'container_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function npc(): BelongsTo
    {
        return $this->belongsTo(NPC::class);
    }

    public function moveToRoom(Room $room = null): self
    {
        if ($room) {
            $this->room()->associate($room);
        } else {
            $this->room()->dissociate();
        }

        $this->save();

        return $this;
    }

    public function giveToUser(User $user = null): self
    {
        if (is_null($user)) {
            $this->owner()->dissociate();
            $this->npc()->dissociate();

            return $this;
        }

        if (!$user->hasBody()) {
            return $this;
        }

        $this->npc()->dissociate();
        $this->moveToContainer($user->body);

        return $this;
    }

    public function moveToContainer(Entity $container = null): self
    {
        if ($container) {
            $this->container()->associate($container);
        } else {
            $this->container()->dissociate();
        }

        $this->save();

        return $this;
    }

    public function giveToNPC(NPC $npc = null): ?self
    {
        if (!$npc->hasBody()) {
            return null;
        }

        $this->moveToContainer($npc->body);

        return $this;
    }

    public function moveToVoid(): self
    {
        $this->owner()->dissociate();
        $this->room()->dissociate();
        $this->container()->dissociate();
        $this->npc()->dissociate();

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function findContents(string $query): ?Entity
    {
        return $this->contents->first(function ($entity) use ($query) {
            return $entity->nameMatchesQuery($query);
        });
    }

    public function ownedBy(User $user): bool
    {
        if ($this->container instanceof Body) {
            return (int) $this->container->owner_id === (int) $user->id;
        }

        return (int) $this->owner_id === (int) $user->id;
    }

    public function toArray(): array
    {
        $array = parent::toArray();

        foreach ($this->getSerializable() as $serializable => $value) {
            $array[$serializable] = $this->$serializable;
        }

        return $array;
    }

    public function getRoom(): ?Room
    {
        if (!$this->room) {
            return null;
        }

        return $this->room;
    }

    public function canBeTaken(): bool
    {
        return $this->can_be_taken;
    }

    public function canBeAttacked(): bool
    {
        return false;
    }

    /**
     * Like create() but allows passing in serializable
     * attributes. It would be nice to override create()
     * instead but that looks like a hassle. Maybe later.
     *
     * @return self
     */
    public static function createWithSerializable($attributes)
    {
        $serializable = (new static)->getSerializable();

        $serializable_attributes = array_intersect_key($attributes, $serializable);
        $non_serializable_attributes = array_diff_key($attributes, $serializable);

        $model = self::create($non_serializable_attributes);

        $model->fill($serializable_attributes)->save();

        return $model;
    }
}
