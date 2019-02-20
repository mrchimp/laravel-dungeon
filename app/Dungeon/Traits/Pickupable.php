<?php

namespace Dungeon\Traits;

use App\User;

trait Pickupable
{
    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function pickup(User $user)
    {
        $user->inventory->add($this);

        $this->owner = $user;

        return $this;
    }
}
