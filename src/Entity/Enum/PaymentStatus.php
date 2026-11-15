<?php
namespace App\Enum;

enum PaymentStatus: string
{
    case PAID = 'PAID';
    case REFUNDED = 'REFUNDED';
    case PARTIALLY_REFUNDED = 'PARTIALLY_REFUNDED';
}