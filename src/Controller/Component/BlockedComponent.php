<?php

namespace App\Controller\Component;

use Cake\Controller\Component;

class BlockedComponent extends Component
{

    public function blockedUser($user, $current_user)
    {
         $blocked_users = explode(',',$current_user['blocked_users']);
        if (in_array($user['id'], $blocked_users)) {
            $blocked_user = true;
        } else {
            $blocked_user = false;
        }
        return $blocked_user;
        
    }

    public function blockedBy($user, $current_user)
    {
        $blocked_by_list = explode(',',$user['blocked_users']);
        if (in_array($user['id'], $blocked_by_list)) {
            $blocked_by = true;
        } else {
            $blocked_by = false;
        }
        return $blocked_by;
    }
}