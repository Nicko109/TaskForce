<?php

namespace App\Models\Actions;

class DenyAction extends AbstractAction
{

    public static function getLabel()
    {
        return 'Отказаться';
    }

    public static function getInternalName()
    {
        return 'act_deny';
    }

    public static function checkRights($userId, $performerId, $customerId)
    {
        return $userId == $performerId;
    }
}