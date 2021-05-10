<?php

namespace Dungeon\Entities\Locks;

use Dungeon\Contracts\KeyInterface;
use Dungeon\Entity;

class Code extends Entity implements KeyInterface
{
    protected $fillable = [
        'name',
        'description',
        'code',
    ];

    /**
     * Get the names of attributes to be serialized
     *
     * @return array
     */
    // public function getSerializable(): array
    // {
    //     return array_merge(
    //         parent::getSerializable(),
    //         [
    //             'code' => '0000'
    //         ]
    //     );
    // }
}
