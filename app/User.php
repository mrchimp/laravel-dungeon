<?php

namespace App;

use App\Dungeon\Traits\HasHealth;
use App\Dungeon\Traits\HasInventory;
use App\Dungeon\Traits\HasSerializableAttributes;
use App\Observers\SerializableObserver;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, HasHealth, HasSerializableAttributes, HasInventory;

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
}
