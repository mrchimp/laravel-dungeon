<?php

namespace Dungeon\Actions\Users;

use Dungeon\Actions\Action;
use Dungeon\Entities\People\Body;

/**
 * If a user has taken enough damage to die, this action will handle
 * the post-death arrangements, organise the funeral etc.
 */
class Expire extends Action
{
    /**
     * @var Body
     */
    protected $body;

    public function __construct(Body $body)
    {
        $this->body = $body;
    }


    /**
     * Perform the action
     */
    public function perform()
    {
        $this->body->owner()->dissociate();
        $this->body->save();
    }
}
