<?php

namespace App\Enum;

enum TripStatus: string
{
    case PUBLISHED   = 'PUBLISHED';
    case IN_PROGRESS = 'IN_PROGRESS';
    case COMPLETED   = 'COMPLETED';
    case CANCELLED   = 'CANCELLED';
}