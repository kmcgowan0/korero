<?php

namespace App\Controller\Component;

use Cake\Controller\Component;

class DistanceComponent extends Component
{
    public function getDistance($central_location, $location, $earthRadius = 3959)
    {
        //work out latitude and longitude based on user location in db
        list($lat_from, $long_from) = explode(',', $central_location);
        list($lat_to, $long_to) = explode(',', $location);

        $latFrom = deg2rad($lat_from);
        $lonFrom = deg2rad($long_from);
        $latTo = deg2rad($lat_to);
        $lonTo = deg2rad($long_to);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        return $angle * $earthRadius;

    }
}