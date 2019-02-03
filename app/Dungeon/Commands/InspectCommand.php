<?php

namespace App\Dungeon\Commands;

class InspectCommand extends Command
{
    /**
     * Patterns that this command handles
     *
     * @return array
     */
    public function patterns()
    {
        return [
            '/^inspect (?<target>.*)$/',
        ];
    }

    /**
     * Run the command
     *
     * @return null
     */
    protected function run()
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

        $this->setOutputItem('contents', $entity->contents);
        $this->setMessage($output);
    }
}
