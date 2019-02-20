<?php

namespace Tests\Unit\Dungeon;

use Dungeon\CommandParser;
use Tests\TestCase;

class CommandParserTest extends TestCase
{
    /** @test */
    public function handles_verb_object_structure()
    {
        $parser = new CommandParser('eat potato');

        $this->assertEquals('eat', $parser->getCommandName());
        $this->assertCount(1, $parser->getObjects());
        $this->assertCount(0, $parser->getSubjects());
        $this->assertEquals('potato', $parser->getObjects()[0]);
    }

    /** @test */
    public function handles_verb_object_structure_with_spaces()
    {
        $parser = new CommandParser('eat hot potato');

        $this->assertEquals('eat', $parser->getCommandName());
        $this->assertCount(1, $parser->getObjects());
        $this->assertCount(0, $parser->getSubjects());
        $this->assertEquals('hot potato', $parser->getObjects()[0]);
    }

    /** @test */
    public function handles_verb_object_on_subject_structure()
    {
        $parser = new CommandParser('use key on door');

        $this->assertEquals('use', $parser->getCommandName());
        $this->assertCount(1, $parser->getObjects());
        $this->assertCount(1, $parser->getSubjects());
        $this->assertEquals('key', $parser->getObjects()[0]);
        $this->assertEquals('door', $parser->getSubjects()[0]);
    }

    /** @test */
    public function handles_verb_object_on_subject_structure_with_spaces()
    {
        $parser = new CommandParser('use gold key on wooden door');

        $this->assertEquals('use', $parser->getCommandName());
        $this->assertCount(1, $parser->getObjects());
        $this->assertCount(1, $parser->getSubjects());
        $this->assertEquals('gold key', $parser->getObjects()[0]);
        $this->assertEquals('wooden door', $parser->getSubjects()[0]);
    }

    /** @test */
    public function handles_verb_subject_with_object_structure()
    {
        $parser = new CommandParser('combine chicken with pesto');

        $this->assertEquals('combine', $parser->getCommandName());
        $this->assertCount(1, $parser->getObjects());
        $this->assertCount(1, $parser->getSubjects());
        $this->assertEquals('pesto', $parser->getObjects()[0]);
        $this->assertEquals('chicken', $parser->getSubjects()[0]);
    }

    /** @test */
    public function handles_verb_subject_with_object_structure_with_spaces()
    {
        $parser = new CommandParser('combine fried chicken with green pesto');

        $this->assertEquals('combine', $parser->getCommandName());
        $this->assertCount(1, $parser->getObjects());
        $this->assertCount(1, $parser->getSubjects());
        $this->assertEquals('green pesto', $parser->getObjects()[0]);
        $this->assertEquals('fried chicken', $parser->getSubjects()[0]);
    }

    /** @test */
    public function handles_verb_object_in_object_structure()
    {
        $parser = new CommandParser('put potato in box');

        $this->assertEquals('put', $parser->getCommandName());
        $this->assertCount(2, $parser->getObjects());
        $this->assertCount(0, $parser->getSubjects());
        $this->assertEquals('potato', $parser->getObjects()[0]);
        $this->assertEquals('box', $parser->getObjects()[1]);
    }

    public function handles_verb_object_in_object_structure_with_spaces()
    {
        $parser = new CommandParser('put raw potato in pre-heated oven');

        $this->assertEquals('put', $parser->getCommandName());
        $this->assertCount(2, $parser->getObjects());
        $this->assertCount(0, $parser->getSubjects());
        $this->assertEquals('raw potato', $parser->getObjects()[0]);
        $this->assertEquals('pre-heated box', $parser->getObjects()[1]);
    }
}
