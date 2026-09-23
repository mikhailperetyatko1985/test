<?php

namespace App\Enums;

enum PaymentType: string
{
    case Card = 'card';
    case Sbp = 'sbp';
    case Promo = 'promo';
    case Trial = 'trial';
}
