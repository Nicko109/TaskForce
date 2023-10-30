<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Actions\CancelAction;
use App\Models\Actions\CompleteAction;
use App\Models\Actions\DenyAction;
use App\Models\Actions\ResponseAction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $guarded = false;


    const ROLE_ADMIN = 'admin';
    const ROLE_CUSTOMER = 'customer';
    const ROLE_PERFORMER = 'performer';


    const ACTION_CANCEL = 'act_cancel';
    const ACTION_RESPONSE = 'act_response';
    const ACTION_COMPLETE = 'act_complete';
    const ACTION_DENY = 'act_deny';


    public static function getRoles()
    {
        return [
            self::ROLE_ADMIN => 'Админ',
            self::ROLE_CUSTOMER => 'Заказчик',
            self::ROLE_PERFORMER => 'Исполнитель',
        ];
    }


    public static function getActions()
    {
        return [
            self::ACTION_CANCEL => 'Отменить',
            self::ACTION_RESPONSE => 'Откликнуться',
            self::ACTION_COMPLETE => 'Выполнено',
            self::ACTION_DENY => 'Отказаться',
        ];
    }


    public static function roleAllowedActions()
    {
        $roleActions = [
            self::ROLE_CUSTOMER => [CancelAction::class, CompleteAction::class],
            self::ROLE_PERFORMER => [ResponseAction::class, DenyAction::class],
        ];

        return $roleActions;
    }


    public function canPerformAction($action)
    {
        return in_array($action, $this->roleAllowedActions());
    }

    //Условие в будущем контроллере
//    public function someAction(Request $request)
//    {
//        $user = User::find(1); // Пример получения пользователя
//
//        if ($user->canPerformAction(User::ACTION_CANCEL)) {
//            // Заказчик может отменить задание
//            // Ваш код для отмены задания
//        } elseif ($user->canPerformAction(User::ACTION_RESPONSE)) {
//            // Исполнитель может откликнуться на задание
//            // Ваш код для отклика на задание
//        } elseif ($user->canPerformAction(User::ACTION_COMPLETE)) {
//            // Заказчик может завершить задание
//            // Ваш код для завершения задания
//        } elseif ($user->canPerformAction(User::ACTION_DENY)) {
//            // Исполнитель может отказаться от задания
//            // Ваш код для отказа от задания
//        } else {
//            // Действие не разрешено для данного пользователя
//            // Ваш код в случае отсутствия прав
//        }
//    }


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

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }


    public function responses()
    {
        return $this->hasMany(Response::class, 'user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class,  'user_id', 'id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class,  'user_id', 'id');
    }
}
