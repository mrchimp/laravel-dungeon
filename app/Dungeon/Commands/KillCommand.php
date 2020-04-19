<?php

namespace Dungeon\Commands;

class KillCommand extends Command
{
    /**
     * Patterns that this command handles
     */
    public function patterns(): array
    {
        return [
            '/^kill$/',
            '/^kill (?<target>.*)$/',
        ];
    }

    /**
     * Run the command
     */
    protected function run(): self
    {
        $target_name = $this->inputPart('target');

        if (!$target_name) {
            return $this->fail('Just in general?');
        }

        $user = $this->entityFinder->find($target_name, $this->user);

        if (!$user) {
            return $this->fail('Who?');
        }

        // @todo inefficient
        foreach ($user->inventory as $item) {
            $item->moveToRoom($user->room)->save();
        }

        $user->setHealth(0)->save();

        $this->setMessage('Deaded.');

        return $this;
    }
}
