<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = false;


const ROLE_ADMIN = 0;
const ROLE_CUSTOMER = 1;
const ROLE_PERFORMER = 2;


    public static function getRoles()
    {
        return [
            self::ROLE_ADMIN => 'Админ',
            self::ROLE_CUSTOMER => 'Заказчик',
            self::ROLE_PERFORMER => 'Исполнитель',
        ];
    }

    const ACTION_CANCEL = 1;
    const ACTION_RESPONSE = 2;
    const ACTION_COMPLETE = 3;
    const ACTION_DENY = 4;


    public static function getActions()
    {
        return [
            self::ACTION_CANCEL => 'Отменить',
            self::ACTION_RESPONSE => 'Откликнуться',
            self::ACTION_COMPLETE => 'Завершить',
            self::ACTION_DENY => 'Отказаться',
        ];
    }




    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function responses()
    {
        return $this->hasMany(Response::class,  'user_id', 'id');
    }
}
