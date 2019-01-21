<?php

namespace App\Dungeon;

use Exception;

class CommandParser
{
    protected $command_name;

    protected $subjects = [];

    protected $objects = [];

    public function __construct($input)
    {
        $matches = [];

        $verb_object_on_subject_pattern = '/([^ ]+) (.*) on (.*)/';

        if (preg_match($verb_object_on_subject_pattern, $input, $matches)) {
            $this->command_name = $matches[1];
            $this->objects[] = $matches[2];
            $this->subjects[] = $matches[3];
            return;
        }

        $verb_subject_with_object_pattern = '/([^ ]+) (.*) with (.*)/';

        if (preg_match($verb_subject_with_object_pattern, $input, $matches)) {
            $this->command_name = $matches[1];
            $this->objects[] = $matches[3];
            $this->subjects[] = $matches[2];
            return;
        }

        $verb_object_in_object = '/([^ ]+) (.*) in (.*)/';

        if (preg_match($verb_object_in_object, $input, $matches)) {
            $this->command_name = $matches[1];
            $this->objects[] = $matches[2];
            $this->objects[] = $matches[3];
            return;
        }

        $verb_object_pattern = '/([^ ]+) (.*)/';

        if (preg_match($verb_object_pattern, $input, $matches)) {
            $this->command_name = $matches[1];
            $this->objects[] = $matches[2];
            return;
        }

        throw Exception('Failed to parse input.');
    }

    public function getCommandName()
    {
        return $this->command_name;
    }

    public function getSubjects()
    {
        return $this->subjects;
    }

    public function getObjects()
    {
        return $this->objects;
    }
}
