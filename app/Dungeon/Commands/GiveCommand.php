<?php

namespace Dungeon\Commands;

use Dungeon\Actions\Entities\Give;
use Dungeon\Exceptions\EntityNotPossessedException;
use Dungeon\Exceptions\MissingEntityException;
use Dungeon\Exceptions\TargetUserIsDeadException;
use Dungeon\Exceptions\TargetUserNotProvidedException;

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
        $target = $this->entityFinder->findUsers($target_name, $this->user->room);

        try {
            Give::do($this->user, $item, $target);
        } catch (MissingEntityException $e) {
            return $this->fail('You don\'t have that to give.');
        } catch (TargetUserNotProvidedException $e) {
            return $this->fail('Give to who now?');
        } catch (EntityNotPossessedException $e) {
            return $this->fail('You don\'t have that to give.');
        } catch (TargetUserIsDeadException $e) {
            return $this->fail('I think they might be dead.');
        }

        $this->setMessage('You give it away.');

        return $this;
    }
}
