<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = false;
    protected $table = 'tasks';


    const STATUS_NEW = 'new';
    const STATUS_CANCELED = 'canceled';
    const STATUS_IN_PROGRESS = 'proceed';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    private string $status;
    private ?int $performerId;
    private int $customerId;

    public function __construct(string $status, int $customerId, ?int $performerId = null)
    {
        $this->status = $status;
        $this->performerId = $performerId;
        $this->customerId = $customerId;
    }

    public static function getStatus()
    {
        return [
            self::STATUS_NEW => 'Новое',
            self::STATUS_CANCELED => 'Отменено',
            self::STATUS_IN_PROGRESS => 'В работе',
            self::STATUS_COMPLETED => 'Выполнено',
            self::STATUS_FAILED => 'Провалено',
        ];
    }


    public function getCurrentStatus()
    {
        return $this->currentStatus;
    }

    public function getPerformerId()
    {
        return $this->performerId;
    }

    public function getCustomerId()
    {
        return $this->customerId;
    }


    public function getAvailableActions()
    {
        $statusActions = [
            self::STATUS_NEW => [User::ACTION_CANCEL, User::ACTION_RESPONSE],
            self::STATUS_IN_PROGRESS => [User::ACTION_COMPLETE, User::ACTION_DENY]
        ];

        return $statusActions[$this->status] ?? [];
    }

    public function getNextStatus($action)
    {
        $actionStatuses = [
            User::ACTION_CANCEL => self::STATUS_CANCELED,
            User::ACTION_RESPONSE => self::STATUS_IN_PROGRESS,
            User::ACTION_COMPLETE => self::STATUS_COMPLETED,
            User::ACTION_DENY => self::STATUS_FAILED,

        ];
        return $actionStatuses[$action] ?? $this->status;
    }



    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function responses()
    {
        return $this->hasMany(Response::class, 'task_id', 'id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class,  'task_id', 'id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class,  'task_id', 'id');
    }
}
