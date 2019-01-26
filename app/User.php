<?php

namespace App;

use App\Dungeon\Contracts\Interactable;
use App\Dungeon\Traits\Findable;
use App\Dungeon\Traits\HasApparel;
use App\Dungeon\Traits\HasHealth;
use App\Dungeon\Traits\HasInventory;
use App\Dungeon\Traits\HasSerializableAttributes;
use App\Observers\SerializableObserver;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements Interactable
{
    use Notifiable,
        HasHealth,
        HasSerializableAttributes,
        HasInventory,
        HasApparel,
        Findable;

    protected $casts = [
        'data' => 'array',
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

    public function getType()
    {
        return 'user';
    }

    public function isEquipable()
    {
        return false;
    }

    public function getSerializable()
    {
        return [
            'health',
        ];
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function moveTo(Room $room)
    {
        return $this->room()->associate($room);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getInventory($refresh = false)
    {
        if ($refresh) {
            $this->load('inventory');
        }

        return $this->inventory;
    }

    /**
     * @todo
     * This is needed because the entity finder
     * tries to replace the class. Need to find a
     * better way to handle this.
     */
    public static function replaceClass($model)
    {
        return $model;
    }

    public function kill()
    {
        $this->room_id = null;
        $this->save();
    }
}
