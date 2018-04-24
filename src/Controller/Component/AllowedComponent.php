<?php

namespace App\Controller\Component;

use Cake\Controller\Component;

class AllowedComponent extends Component
{

    public function checkAllowed($user, $auth_user)
    {
        $allowed_user = false;

        $auth_user_interests = $auth_user['interests'];
        $user_interests = $user->interests;
        $intersect = array_uintersect($auth_user_interests, $user_interests, array($this, 'compareDeepValue'));
        if ($intersect) {
            $allowed_user = true;
        } else {
            $allowed_user = false;
        }
        return $allowed_user;
    }

    public function compareDeepValue($val1, $val2)
    {
        return strcmp($val1['id'], $val2['id']);
    }
}