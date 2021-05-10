<?php

namespace Dungeon;

use Dungeon\Contracts\KeyInterface;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class Portal extends Entity
{
    protected $fillable = [
        'name',
        'description',
        'locked',
    ];

    public $can_be_taken = false;

    protected $table = 'entities';

    /**
     * Keys that fit this door
     */
    public function keys(): BelongsToMany
    {
        return $this->belongsToMany(Entity::class, 'key_portal', 'portal_id', 'key_id');
    }

    /**
     * Description of the Portal
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Whether or not the portal is locked
     */
    public function isLocked(): bool
    {
        return !!$this->locked;
    }

    /**
     * Lock the portal using a key
     */
    public function lockWithKey(KeyInterface $key): bool
    {
        if (!$this->keyFits($key)) {
            return false;
        }

        if ($this->locked) {
            return false;
        }

        $this->locked = true;
        $this->save();

        return true;
    }

    /**
     * Unlock the portal using a key
     */
    public function unlockWithKey(KeyInterface $key): bool
    {
        if (!$this->keyFits($key)) {
            return false;
        }

        if (!$this->locked) {
            return false;
        }

        $this->locked = false;
        $this->save();

        return true;
    }

    /**
     * Does $key fit the lock on this door?
     */
    public function keyFits(KeyInterface $key): bool
    {
        return in_array($key->id, $this->keys->pluck('id')->toArray());
    }

    /**
     * Find a key in a collection of Entities
     *
     * @param Collection $collection
     *
     * @return null|KeyInterface
     */
    public function whichKeyFits(Collection $collection)
    {
        return $collection->first(function ($item) {
            if (!($item instanceof KeyInterface)) {
                return false;
            }

            return $this->keyFits($item);
        });
    }

    /**
     * Get the attributes to be serialized
     */
    // public function getSerializable(): array
    // {
    //     return array_merge(parent::getSerializable(), [
    //         'locked' => false,
    //     ]);
    // }

    /**
     * Creates a generic portal between rooms.
     *
     * This is designed to be used as a null object
     * and not persisted to the database.
     */
    public static function makeGeneric($attributes = []): Portal
    {
        return static::make(array_merge([
            'name' => 'A way out',
            'description' => 'Looks like you can go that way.',
        ], $attributes));
    }

    /**
     * Convert Portal to an array
     */
    public function toArray(): array
    {
        return $this->only([
            'name',
            'description',
            'locked',
            'uuid',
        ]);
    }
}
