<?php

namespace App\Controller\Component;

use Cake\Controller\Component;

class MutualComponent extends Component
{

    public function getMutual($user1_interests, $user2_interests)
    {
        $user_interests_array = array();
        foreach ($user1_interests as $an_interest) {
            array_push($user_interests_array, $an_interest->name);
        }

        $auth_interests_array = array();
        foreach ($user2_interests as $an_interest) {
            array_push($auth_interests_array, $an_interest['name']);
        }

        $mutual_interest_array = array_intersect($user_interests_array, $auth_interests_array);

        return $mutual_interest_array;
    }
}