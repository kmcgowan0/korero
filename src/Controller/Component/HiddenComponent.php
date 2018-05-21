<?php

namespace App\Controller\Component;

use Cake\Controller\Component;

class HiddenComponent extends Component
{

    public function hiddenUser($user, $current_user)
    {
        $hidden_users = explode(',',$current_user['hidden_users']);
        if (in_array($user['id'], $hidden_users)) {
            $hidden_user = true;
        } else {
            $hidden_user = false;
        }
        return $hidden_user;

    }
}