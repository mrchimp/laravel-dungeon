<?php

namespace Dungeon\Commands;

class AttackCommand extends Command
{
    /**
     * Patterns that this command handles
     *
     * @return array
     */
    public function patterns()
    {
        return [
            '/^attack$/',
            '/^attack (?<target>.*) with (?<weapon>.*)$/',
        ];
    }

    /**
     * Run the command
     *
     * @return null
     */
    protected function run()
    {
        if (!$this->user->room) {
            return $this->fail('You cannot attack the void.');
        }

        $target_name = $this->inputPart('target');
        $weapon_name = $this->inputPart('weapon');

        $weapon = $this->entityFinder->findInInventory($weapon_name, $this->user);
        $target = $this->entityFinder->findUsers($target_name, $this->user->room);

        if (!$weapon) {
            return $this->fail('Can\'t use that weapon.');
        }

        if (!$target) {
            return $this->fail('Don\'t know who to attack.');
        }

        $result = $weapon->attack($target);

        $this->success = true;
        $this->setMessage('You hit for a total ' . $result['total'] . ' damage.');
    }
}
