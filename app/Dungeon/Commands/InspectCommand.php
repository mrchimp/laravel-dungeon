<?php

namespace Dungeon\Commands;

class InspectCommand extends Command
{
    /**
     * Patterns that this command handles
     */
    public function patterns(): array
    {
        return [
            '/^inspect (?<target>.*)$/',
        ];
    }

    /**
     * Run the command
     */
    protected function run(): self
    {
        $query = $this->inputPart('target');
        $entity = $this->entityFinder->find($query, $this->user);

        if (!$entity) {
            return $this->fail('Could not find ' . e($query) . '.');
        }

        $output = e($entity->getDescription());

        if (count($entity->contents) > 0) {
            $output .= '<br>It contains:<br>';

            $output .= $entity
                ->contents
                ->map(function ($item) {
                    return $item->getName();
                })
                ->implode('<br>');
        }

        $this->setExtraItem('contents', $entity->contents);
        $this->setMessage($output);

        return $this;
    }
}
