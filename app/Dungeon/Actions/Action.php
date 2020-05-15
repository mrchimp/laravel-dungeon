<?php

namespace Dungeon\Actions;

abstract class Action
{
    /**
     * Response to the action
     *
     * @var string
     */
    protected $message;

    /**
     * True if the action was successfullly perfformed
     */
    protected bool $success = false;

    /**
     * Make a new instance of the action and perform it
     */
    public static function do(...$params)
    {
        $action = new static(...$params);
        $action->perform();

        return $action;
    }

    /**
     * The meat and potatos of the action
     */
    abstract public function perform();

    /**
     * True if the action was successful
     */
    public function succeeded(): bool
    {
        return $this->success;
    }

    /**
     * True if action failed
     */
    public function failed(): bool
    {
        return !$this->succeeded();
    }

    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Mark the action as successful
     */
    protected function succeed(): void
    {
        $this->success = true;
    }

    /**
     * Make the action as unsuccessful
     */
    protected function fail(): void
    {
        $this->success = false;
    }
}
