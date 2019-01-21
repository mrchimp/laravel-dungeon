# Laravel Dungeon

A basic dungeon-crawler type game made on the [Laravel](https://laravel.com) PHP framework.

## Project Status

Just getting started making the engine. You can move around different rooms and perform some basic actions. There is no real content yet.

## Requirements

* Docker

## Installing

```bash
./vessel init # Set up docker containers etc
./vessel start # Start the docker containers
./vessel artisan migrate # Migrate the database
./vessel artisan db:seed # Seed the database with some basic info

# ... do some stuff ...

./vessel stop # Stop the docker containers
```

## Commands

* ✓ look
* ✓ go _direction_
* ✓ inventory

* ✓ take _object_
* ✓ drop _object_
* ✓ eat _object_
* ✓ inspect _object_
* ✗ use _object_
* ✗ use _object_ on _subject_
* ✗ wear _object_
* ✗ verb _object_ (extendable)
* ✗ verb _subject_ with _object_ (extendable)
* ✗ attack _subject_ with _object_
* ✗ put _object_ in _subject_

## In-Game Features

* ✗ User apparel
    * ✗ Damage reduction (armor)
* ✗ NPCs
    * ✗ Races
    * ✗ Apparel
    * ✗ Inventory
* ✗ Locked doors
    * ✗ Keys
    * ✗ Buttons ?
    * ✗ Breakable doors
* ✗ Levelling
* ✗ Player Stats

## Engine Features

* ✗ Command aliases
    * ✗ look at -> inspect
* ✗ Extendable interface

## Ideas

Big doors: Maybe have a door with a LOT of health that would take multiple players to break through. This could unlock a new area of the map.