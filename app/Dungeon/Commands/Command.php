<?php

namespace App\Dungeon\Commands;

use Auth;
use App\User;

abstract class Command
{
    protected $user;

    protected $input = '';

    protected $input_array = [];

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

    abstract function run();

    public function execute(string $input)
    {
        $this->input = $input;
        $this->input_array = explode(' ', $input);

        return $this->run();
    }
}