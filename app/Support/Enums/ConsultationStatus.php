<?php

namespace App\Support\Enums;

enum ConsultationStatus: string
{
    case PENDING = 'pending';
    case ANSWERED = 'answered';
    case SCHEDULED = 'scheduled';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
}
