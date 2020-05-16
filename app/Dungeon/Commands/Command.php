<?php

namespace Dungeon\Commands;

use Dungeon\CurrentLocation;
use Dungeon\EntityFinder;
use Dungeon\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

abstract class Command
{
    /**
     * The user running this command
     */
    protected User $user;

    /**
     * The input string given by the user
     */
    protected string $input = '';

    /**
     * The input string exploded on spaces
     */
    protected array $input_array = [];

    /**
     * The matched parts of the query.
     *
     * E.g. with the pattern '/look at (?<target>.*)/' and the input
     * 'look at thing', this array would include 'target' => 'thing'
     */
    protected array $matches = [];

    /**
     * Whether this command matched the given input
     */
    public bool $matched = false;

    /**
     * Any extra data to be sent in the response to the user
     */
    protected Collection $extra_data;

    /**
     * The text response to send to the user
     */
    protected string $message = '';

    /**
     * The Room that the user is currently in
     */
    public CurrentLocation $current_location;

    /**
     * Whether the command succeeded.
     *
     * If there is any issue such as the user trying to interact with an
     * item that doesn't exist or a program error such as the database
     * not being accessible, this should be false.
     */
    public bool $success = true;

    /**
     * EntityFinder for finding entities in the vicinity
     */
    public EntityFinder $entityFinder;

    /**
     * Create a new Command
     */
    public function __construct(string $input, User $user = null)
    {
        $this->extra_data = new Collection;
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
     */
    public function patterns(): array
    {
        return [];
    }

    /**
     * Perform the inner workings of this command. This is the part
     * that will vary from command to command. The objective of
     * this method is to populate the output array.
     */
    abstract protected function run(): self;

    /**
     * Run the command
     *
     * This sets up the command and then calls the 'run' method
     * which does the interesting bits.
     */
    public function execute(): self
    {
        $this->run();

        return $this;
    }

    /**
     * Test the given input agains this command's patterns and
     * populate the matches array
     */
    public function matches(string $input = null): bool
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
     */
    public function matchesArray(): array
    {
        return $this->matches;
    }

    /**
     * Get a input match by name
     */
    public function inputPart(string $key): ?string
    {
        return Arr::get($this->matches, $key);
    }

    /**
     * Set the message that will be sent to the user
     */
    protected function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Append some message output
     */
    protected function appendMessage(string $message): self
    {
        $this->message .= $message;

        return $this;
    }

    /**
     * Get the message to be sent to the user
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Set an item in the array that will be sent to the user
     */
    protected function setExtraItem(string $key, $value): self
    {
        $this->extra_data[$key] = $value;

        return $this;
    }

    /**
     * Get an item from the array that will be sent to the user
     */
    public function getExtraItem(string $key)
    {
        return Arr::get($this->extra_data, $key);
    }

    /**
     * Get the array that will be sent to the user
     */
    public function getExtraData(): Collection
    {
        return $this->extra_data;
    }

    /**
     * Set the array that will be sent to the user.
     *
     * You probably want to use setOutputItem instead
     */
    protected function setExtraData(Collection $extra_data): self
    {
        $this->extra_data = $extra_data;

        return $this;
    }

    /**
     * Convert this command to an array
     *
     * Actually just returns the output array
     */
    public function toArray(): array
    {
        // Get new current location - User might have moved.
        $this->current_location->refresh();

        return [
            'environment' => [
                'exits' => $this->current_location->getExits(true)->toArray(),
                'items' => $this->current_location->getItems(true)->values()->toArray(),
                'players' => $this->current_location->getPlayers(true)->values()->toArray(),
                'npcs' => $this->current_location->getNpcs(true)->values()->toArray(),
                'inventory' => $this->user->getInventory(true)->values()->toArray(),
                'room' => $this->current_location->getRoom()->toArray(),
            ],
            'extra' => [
                $this->extra_data->toArray(),
            ]
        ];
    }

    /**
     * A shorthand method for when thigns go wrong
     *
     * Sets the success status to false and sets a message
     * to send to the user.
     */
    public function fail(string $message): self
    {
        $this->setMessage($message);

        $this->success = false;

        return $this;
    }
}
