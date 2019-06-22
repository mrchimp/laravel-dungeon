<?php

namespace Dungeon\Commands;

use Auth;
use Dungeon\CurrentLocation;
use Dungeon\EntityFinder;
use Dungeon\User;

abstract class Command
{
    /**
     * The user running this command
     *
     * @var User
     */
    protected $user;

    /**
     * The input string given by the user
     *
     * @var string
     */
    protected $input = '';

    /**
     * The input string exploded on spaces
     *
     * @var array
     */
    protected $input_array = [];

    /**
     * The matched parts of the query.
     *
     * E.g. with the pattern '/look at (?<target>.*)/' and the input
     * 'look at thing', this array would include 'target' => 'thing'
     *
     * @var array
     */
    protected $matches = [];

    /**
     * Whether this command matched the given input
     *
     * @var boolean
     */
    public $matched = false;

    /**
     * The output data to be sent in the response to the user
     *
     * @var array
     */
    protected $output = [];

    /**
     * The text response to send to the user
     *
     * @var string
     */
    protected $message = '';

    /**
     * The Room that the user is currently in
     *
     * @var App/Room
     */
    protected $current_location;

    /**
     * Whether the command succeeded.
     *
     * If there is any issue such as the user trying to interact with an
     * item that doesn't exist or a program error such as the database
     * not being accessible, this should be false.
     *
     * @var boolean
     */
    public $success = true;

    /**
     * EntityFinder for finding entities in the vicinity
     *
     * @var EntityFinder
     */
    public $entityFinder;

    /**
     * Create a new Command
     *
     * @param User $user the user running this command
     */
    public function __construct($input, User $user = null)
    {
        $this->input = $input;
        $this->matched = $this->matches($input);

        if (is_null($user)) {
            $this->user = Auth::user();
        } else {
            $this->user = $user;
        }

        if (!$this->user) {
            throw new \Exception('No user available for command.');
        }

        $this->current_location = new CurrentLocation($this->user);
        $this->entityFinder = new EntityFinder;
    }

    /**
     * Regex patterns that match this command
     *
     * @return array
     */
    public function patterns()
    {
        return [];
    }

    /**
     * Perform the inner workings of this command. This is the part
     * that will vary from command to command. The objective of
     * this method is to populate the output array.
     *
     * @return void
     */
    abstract protected function run();

    /**
     * Run the command
     *
     * This sets up the command and then calls the 'run' method
     * which does the interesting bits.
     *
     * @return void
     */
    public function execute()
    {
        $this->run();

        $this->setOutputItem('exits', $this->current_location->getExits(true));
        $this->setOutputItem('items', $this->current_location->getItems(true));
        $this->setOutputItem('players', $this->current_location->getPlayers(true));
        $this->setOutputItem('npcs', $this->current_location->getNpcs(true));
        $this->setOutputItem('inventory', $this->user->getInventory(true));
        $this->setOutputItem('room', $this->current_location->getRoom());
    }

    /**
     * Test the given input agains this command's patterns and
     * populate the matches array
     *
     * @param string $input
     * @return boolean
     */
    public function matches($input = null)
    {
        if (is_null($input)) {
            $input = $this->input;
        }

        foreach ($this->patterns() as $pattern) {
            if (preg_match($pattern, $input, $this->matches)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the array of input matches
     *
     * @return array
     */
    public function matchesArray()
    {
        return $this->matches;
    }

    /**
     * Get a input match by name
     *
     * @return string
     */
    public function inputPart($key)
    {
        return array_get($this->matches, $key);
    }

    /**
     * Set the message that will be sent to the user
     *
     * @param string $message
     * @return self
     */
    protected function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get the message to be sent to the user
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set an item in the array that wil be sent to the user
     *
     * @param string $key
     * @param mixed $value
     * @return self
     */
    protected function setOutputItem($key, $value)
    {
        $this->output[$key] = $value;

        return $this;
    }

    /**
     * Get an item from the array that will be sent to the user
     *
     * @param string $key
     * @return mixed
     */
    public function getOutputItem($key)
    {
        return array_get($this->output, $key);
    }

    /**
     * Get the array that will be sent to the user
     *
     * @return array
     */
    public function getOutputArray()
    {
        return $this->output;
    }

    /**
     * Set the array that will be sent to the user.
     *
     * You probably want to use setOutputItem instead
     *
     * @param array $output
     * @return self
     */
    protected function setOutputArray($output)
    {
        $this->output = $output;

        return $this;
    }

    /**
     * Convert this command to an array
     *
     * Actually just returns the output array
     *
     * @return array
     */
    public function toArray()
    {
        return $this->output;
    }

    /**
     * A shorthand method for when thigns go wrong
     *
     * Sets the success status to false and sets a message
     * to send to the user.
     *
     * @param string $message
     * @return self
     */
    public function fail($message)
    {
        $this->setMessage($message);

        $this->success = false;

        return $this;
    }
}
