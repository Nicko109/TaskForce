<?php

namespace App\Models;


use App\Models\Actions\CancelAction;
use App\Models\Actions\AbstractAction;
use App\Models\Actions\CompleteAction;
use App\Models\Actions\DenyAction;
use App\Models\Actions\ResponseAction;
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
        return $this->status;
    }

    public function getPerformerId()
    {
        return $this->performerId;
    }

    public function getCustomerId()
    {
        return $this->customerId;
    }

    public function getAvailableActions(string $role, int $id)
    {
        $statusActions = $this->statusAllowedActions()[$this->status];

        $user = User::find($id);

        $roleActions = $user->roleAllowedActions()[$role];

        $allowedActions = array_intersect($statusActions, $roleActions);

        $allowedActions = array_filter($allowedActions, function ($action) use ($id) {
            return $action::checkRights($id, $this->performerId, $this->customerId);
        });

        return array_values($allowedActions);
    }



    public function statusAllowedActions()
    {
        $statusAllowedActions = [
            self::STATUS_NEW => [CancelAction::class, ResponseAction::class],
            self::STATUS_IN_PROGRESS => [CompleteAction::class, DenyAction::class],
            self::STATUS_CANCELED => [],
            self::STATUS_COMPLETED => [],
            self::STATUS_FAILED => [],
        ];

        return $statusAllowedActions[$this->status] ?? [];
    }

    public function getNextStatus($action)
    {
        $nextStatuses = [
            CancelAction::class => self::STATUS_CANCELED,
            ResponseAction::class => [],
            CompleteAction::class => self::STATUS_COMPLETED,
            DenyAction::class => self::STATUS_FAILED,

        ];
        return $nextStatuses[$action] ?? $this->status;
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
