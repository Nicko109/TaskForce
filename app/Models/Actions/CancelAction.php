<?php

namespace App\Models\Actions;

class CancelAction extends AbstractAction
{

    public static function getLabel()
    {
        return 'Отменить';
    }

    public static function getInternalName()
    {
        return 'act_cancel';
    }

    public static function checkRights($userId, $performerId, $customerId)
    {
        return $userId == $customerId;
    }
}