<?php

namespace App\Models\Actions;

class CompleteAction extends AbstractAction
{

    public static function getLabel()
    {
        return 'Выполнено';
    }

    public static function getInternalName()
    {
        return 'act_complete';
    }

    public static function checkRights($userId, $performerId, $customerId)
    {
        return $userId == $customerId;
    }
}