<?php

namespace App\Support\Enums;

enum ContactMessageStatus: string
{
    case NEW = 'new';
    case READ = 'read';
    case REPLIED = 'replied';
}
