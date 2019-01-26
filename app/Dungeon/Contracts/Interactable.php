<?php

namespace App\Dungeon\Contracts;

interface Interactable
{
    public function getType();

    public function isEquipable();
}