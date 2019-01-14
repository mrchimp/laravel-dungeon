<?php

namespace App\Dungeon\Commands;

use App\User;
use Auth;

abstract class Command
{
    protected $user;

    protected $input = '';

    protected $input_array = [];

    protected $query;

    protected $output = [];

    protected $message = '';

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
        $this->input_array = explode(' ', $this->input);
        $this->query = implode(' ', array_slice($this->input_array, 1));

        $this->run();
    }

    protected function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    protected function setOutputItem($key, $value)
    {
        $this->output_array[$key] = $value;

        return $this;
    }

    protected function setOutputArray($output)
    {
        $this->output = $output;

        return $this;
    }

    public function toArray()
    {
        return $this->output;
    }
}