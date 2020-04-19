<?php

namespace Dungeon;

use Dungeon\Entities\People\Body;
use Dungeon\Observers\SerializableObserver;
use Dungeon\Room;
use Dungeon\Traits\Findable;
use Dungeon\Traits\HasApparel;
use Dungeon\Traits\HasBody;
use Dungeon\Traits\HasSerializableAttributes;
use Illuminate\Database\Eloquent\Relations\HasOne;
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

    public function isEquipable(): bool
    {
        return false;
    }

    public function getSerializable(): array
    {
        return [
            'health' => 100,
            'can_be_taken' => false,
        ];
    }

    public function body(): HasOne
    {
        return $this->hasOne(Body::class, 'owner_id');
    }

    public function getRoom(): ?Room
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

    public function getName(): string
    {
        return $this->name;
    }

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

    /**
     * Kill a user
     *
     * @throws \Exception
     */
    public function kill()
    {
        throw new \Exception('refactor User::kill');
    }

    public function respawn(): self
    {
        $room = Room::first();

        $this->setHealth(self::DEFAULT_HEALTH);
        $this->moveTo($room);

        // @todo create new body
        throw new \Exception('refactor respawn command');

        return $this;
    }

    /**
     * @todo need to add an interface for hurtable things
     */
    public function hurt(int $amount)
    {
        if ($this->hasBody()) {
            return $this->body->hurt($amount);
        }

        return null;
    }

    /**
     * @todo need to add an interface for healable things
     */
    public function heal(int $amount)
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

    public function canBeAttacked(): bool
    {
        return $this->can_be_attacked;
    }
}
