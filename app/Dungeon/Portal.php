<?php

namespace Dungeon;

use Dungeon\Contracts\KeyInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class Portal extends Model
{
    protected $fillable = [
        'description',
        'code',
        'locked',
    ];

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
     * Unlock the lock using a code or password
     *
     * Returns true if the action was successful
     * or if the portal was already locked
     */
    public function unlockWithCode($code): bool
    {
        if (!$this->locked) {
            return true;
        }

        if ($this->code === $code) {
            $this->locked = false;
            $this->save();
            return true;
        }

        return false;
    }

    /**
     * Lock the door using a new code
     */
    public function lockWithCode($code): bool
    {
        if ($this->locked) {
            return false;
        }

        if (!$this->code) {
            return false;
        }

        $this->locked = true;
        $this->save();

        return true;
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
}
