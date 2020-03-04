<?php

namespace Dungeon;

use Dungeon\Entities\People\Body;
use Dungeon\Observers\SerializableObserver;
use Dungeon\Room;
use Dungeon\Traits\Findable;
use Dungeon\Traits\HasApparel;
use Dungeon\Traits\HasBody;
use Dungeon\Traits\HasSerializableAttributes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasBody,
        Notifiable,
        HasSerializableAttributes,
        HasApparel,
        Findable;

    const DEFAULT_HEALTH = 100;

    protected $casts = [
        'serialized_data' => 'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'health',
        'room_id',
        'can_be_attacked',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function boot()
    {
        parent::boot();

        self::observe(new SerializableObserver);
    }

    public function isEquipable()
    {
        return false;
    }

    public function getSerializable()
    {
        return [
            'health' => 100,
            'can_be_taken' => false,
        ];
    }

    public function body()
    {
        return $this->hasOne(Body::class, 'owner_id');
    }

    public function getRoom()
    {
        if (!$this->hasBody()) {
            return null;
        }

        return $this->body->getRoom();
    }

    public function room()
    {
        throw new \Exception('Dont use user-room relationship - go through body instead');
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @todo remove - go through bdy instead
     *
     * @param boolean $refresh
     * @return void
     */
    // public function getInventory($refresh = false)
    // {
    //     if (!$this->body) {
    //         return null;
    //     }

    //     if ($refresh) {
    //         $this->body->load('contents');
    //     }

    //     return $this->body->content;
    // }

    /**
     * @todo
     * This is needed because the entity finder
     * tries to replace the class. Need to find a
     * better way to handle this.
     */
    public static function replaceClass($model)
    {
        throw new \Exception('Shouldnt be using finder on users any more');
    }

    public function kill()
    {
        throw new \Exception('refactor User::kill');
    }

    public function respawn()
    {
        $room = Room::first();

        $this->setHealth(self::DEFAULT_HEALTH);
        $this->moveTo($room);

        // @todo create new body
        throw new \Exception('refactor respawn command');

        return $this;
    }

    public function hurt($amount)
    {
        if ($this->hasBody()) {
            return $this->body->hurt($amount);
        }

        return null;
    }

    public function heal($amount)
    {
        if ($this->hasBody()) {
            return $this->body->heal($amount);
        }

        return null;
    }

    public function setHealth($amount)
    {
        if ($this->hasBody()) {
            return $this->body->setHealth($amount);
        }

        return null;
    }

    public function getHealth()
    {
        if ($this->hasBody()) {
            return $this->body->getHealth();
        }

        return null;
    }

    public function isDead()
    {
        if ($this->hasBody()) {
            return $this->body->isDead();
        }

        return true;
    }

    public function canBeAttacked()
    {
        return $this->can_be_attacked;
    }
}
