# Laravel Dungeon

A basic dungeon-crawler type game made on the [Laravel](https://laravel.com) PHP framework.

## Project Status

Just getting started making the engine. You can move around different rooms and perform some basic actions. There is no real content yet.

## Requirements

-   Docker

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

-   ✓ look
-   ✓ go _direction_
-   ✓ inventory

-   ✓ take _entity_
-   ✓ drop _entity_
-   ✓ eat _entity_
-   ✓ inspect _item_
-   ✗ use _entity_
-   ✗ use _entity_ on _item_
-   ✓ equip _entity_
-   ✗ wear _entity_
-   ✗ attack _item_ with _weapon_
-   ✗ put _entity_ in _container_
-   ✗ combine _entity_ with _entity_

-   ✗ verb _item_ (extendable)
-   ✗ verb _item_ with _item_ (extendable)

## In-Game Features

-   ✗ User apparel
    -   ✗ Damage reduction (armor)
-   ✗ NPCs
    -   ✗ Races
    -   ✗ Apparel
    -   ✗ Inventory
-   ✗ Locked doors
    -   ✗ Keys
    -   ✗ Buttons ?
    -   ✗ Breakable doors
-   ✗ Levelling
-   ✗ Player Stats
-   ✗ Unpickupable Items

## Features

-   ✗ Command aliases
    -   ✗ look at -> inspect
-   ✗ Extendable interface
-   ✗ Skill Tree
    -   ✗ Speed
    -   ✗ Strength
    -   ✗ Stamina
    -   ✗ Healing
    -   ✗ Courage
    -   ✗ Willpower
    -   ✗ Perception
    -   ✗ Reason
    -   ✗ Intellect
    -   ✗ Stealth
    -   ✗ Memory
    -   ✗ Lockpicking
-   ✗ Traps
-   ✗ Followers
-   ✗ Character Creation Screen
-   ✗ Safe Rooms
-   ✗ Permadeath?
-   ✗ Multiple Bodies?

## Ideas

Big doors: Maybe have a door with a LOT of health that would take multiple players to break through. This could unlock a new area of the map.

How to handle offline players. Players cannot be interacted with while they are offline (to prevent them being murdered while not there to defend themselves). Maybe they turn to stone while offline. Alternatively, make it more roguelike. If you go AFK, you are abandoning your character...
