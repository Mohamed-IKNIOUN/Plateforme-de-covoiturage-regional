<?php

namespace App\Enum;

enum ReviewDirection: string
{
    case PASSENGER_TO_DRIVER = 'PASSENGER_TO_DRIVER';
    case DRIVER_TO_PASSENGER = 'DRIVER_TO_PASSENGER';
}