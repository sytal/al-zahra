<?php

namespace App\Support\Enums;

enum ConsultationType: string
{
    case FREE_QUESTION = 'free_question';
    case PAID_BOOKING = 'paid_booking';
}
