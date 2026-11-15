<?php

namespace App\Enum;

enum BookingStatus: string
{
    case PENDING                = 'PENDING';
    case CONFIRMED              = 'CONFIRMED';
    case CANCELLED_BY_PASSENGER = 'CANCELLED_BY_PASSENGER';
    case CANCELLED_BY_DRIVER    = 'CANCELLED_BY_DRIVER';
    case COMPLETED              = 'COMPLETED';
}