<?php

namespace App\Dungeon\Commands;

use App\Dungeon\Entities\Finder;


class InspectCommand extends Command
{
    public function run()
    {
        $finder = new Finder;
        $entity = $finder->find($this->query, $this->user);

        if (!$entity) {
            return 'Could not find ' . e($this->query) . '.';
        }

        $output = e($entity->getDescription());

        if (count($entity->contents) > 0) {
            $output .= '<br>It contains:<br>';

            $output .= $entity
                ->contents
                ->map(function($item) {
                    return $item->getName();
                })
                ->implode('<br>');
        }

        return $output;
    }
}