<?php

namespace Dungeon\Commands;

use Dungeon\Notifications\WhisperToUser;

class WhisperCommand extends Command
{
    /**
     * Patterns that this command matches
     */
    public function patterns(): array
    {
        return [
            '/^whisper (?<target>[^ ]*) (?<message>.*)$/',
            '/^w (?<target>[^ ]*) (?<message>.*)$/',
        ];
    }

    public function run(): self
    {
        $target_name = $this->inputPart('target');
        $message = $this->inputPart('message');

        if (!$target_name) {
            return $this->fail('You must give a name.');
        }

        if (!$message) {
            return $this->fail('You must give a message');
        }

        $target = $this->entityFinder->findUsers($target_name, $this->user->getRoom());

        if (!$target) {
            return $this->fail('You don\'t see anyone to whisper to with that name.');
        }

        $target->owner->notify(new WhisperToUser($this->user, $message));

        $this->setMessage("You whisper to {$target->name}: {$message}");

        return $this;
    }
}
