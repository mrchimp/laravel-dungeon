<?php

namespace Dungeon\Commands;

use Dungeon\Events\AfterAttack;
use Dungeon\Events\BeforeAttack;

class AttackCommand extends Command
{
    /**
     * Patterns that this command handles
     *
     * @return array
     */
    public function patterns(): array
    {
        return [
            '/^attack$/',
            '/^attack (?<target>.*) with (?<weapon>.*)$/',
        ];
    }

    /**
     * Run the command
     */
    protected function run(): self
    {
        if (!$this->user->getRoom()) {
            return $this->fail('You cannot attack the void.');
        }

        if ($this->user->getRoom()->isSafeRoom()) {
            return $this->fail('You can\'t attack people in a safe room.');
        }

        $target_name = $this->inputPart('target');
        $target = $this->entityFinder->findUsers($target_name, $this->user->getRoom());

        if (!$target) {
            return $this->fail('Don\'t know who to attack.');
        }

        if (!$target->canBeAttacked()) {
            return $this->fail('User cannot be attacked.');
        }

        $weapon_name = $this->inputPart('weapon');
        $weapon = $this->entityFinder->findWeaponInInventory($weapon_name, $this->user);

        if (!$weapon) {
            return $this->fail('Can\'t use that as a weapon.');
        }

        event(new BeforeAttack($this->user, $target, $weapon));

        $target->owner->update([
            'can_be_attacked' => false,
        ]);

        $result = $weapon->attack($target);

        event(new AfterAttack($this->user, $target, $weapon));

        $this->success = true;
        $this->setMessage('You hit for a total ' . $result['total'] . ' damage.');

        return $this;
    }
}
