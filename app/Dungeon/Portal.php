<?php

namespace Dungeon;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Portal extends Pivot
{
    protected $table = 'portals';

    protected $fillable = [
        'description',
        'code',
        'locked',
    ];

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
     * @param string $code
     *
     * @return bool true if the action was successful
     *              or if the portal was already locked
     */
    public function unlockWithCode($code)
    {
        if (!$this->locked) {
            return true;
        }

        if ($this->code === $code) {
            $this->locked = false;
            return true;
        }

        return false;
    }

    /**
     * Lock the door using a new code
     *
     * @param string $code
     *
     * @return bool true if the action was successful
     */
    public function lockWithCode($code)
    {
        if ($this->locked) {
            return false;
        }

        $this->locked = true;
        $this->code = $code;

        return true;
    }
}
