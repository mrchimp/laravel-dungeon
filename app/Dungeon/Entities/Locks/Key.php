<?php

namespace Dungeon\Entities\Locks;

use Dungeon\Contracts\KeyInterface;
use Dungeon\Entity;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Key extends Entity implements KeyInterface
{
    /**
     * Things this key can unlock
     *
     * @return BelongsToMany
     */
    public function unlockables(): BelongsToMany
    {
        return $this->belongsToMany(Entity::class, 'key_portal', 'key_id', 'portal_id');
    }
}
