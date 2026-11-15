<?php

namespace App\Entity\Enum;

enum TripStatus: string
{
    case PUBLISHED = 'published';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
}