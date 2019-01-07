<?php

namespace App\Dungeon\Commands;

use Auth;
use App\User;

abstract class Command
{
    protected $user;

    public function __construct(User $user = null)
    {
        if (is_null($user)) {
            $this->user = Auth::user();
        } else {
            $this->user = $user;
        }

        if (!$this->user) {
            throw new \Exception('No user available for command.');
        }
    }

    abstract function run(string $input);
}