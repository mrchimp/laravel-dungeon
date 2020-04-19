<?php

namespace Dungeon\Commands;

class GiveCommand extends Command
{
    /**
     * Patterns that this command handles
     */
    public function patterns(): array
    {
        return [
            '/^give$/',
            '/^give (?<item>.*) to (?<target>.*)$/'
        ];
    }

    /**
     * Run the command
     */
    protected function run(): self
    {
        if (!$this->user->room) {
            return $this->fail('There is nobody else in the void.');
        }

        $item_name = $this->inputPart('item');
        $target_name = $this->inputPart('target');

        if (!$item_name || !$target_name) {
            return $this->fail('Give what to who now?');
        }

        $item = $this->entityFinder->findInInventory($item_name, $this->user);

        if (!$item) {
            return $this->fail('You don\'t have that to give.');
        }

        $target = $this->entityFinder->findUsers($target_name, $this->user->room);

        if (!$target) {
            return $this->fail('Give to who now?');
        }

        // @todo need to test this - maybe place item on body instead?
        if (!$target->owner) {
            return $this->fail('I think they might be dead.');
        }

        $item->giveToUser($target->owner);
        $item->save();

        $this->setMessage('You give it away.');

        return $this;
    }
}
