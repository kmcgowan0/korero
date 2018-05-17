<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\I18n\Time;

class AgeComponent extends Component
{

    public function getAge($user_dob)
    {
        $from = new Time($user_dob);
        $to = Time::now();
        $user_age = $from->diff($to)->y;

        return $user_age;
    }

}