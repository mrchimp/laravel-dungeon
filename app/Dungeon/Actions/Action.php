<?php

namespace Dungeon\Actions;

abstract class Action
{
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
}
