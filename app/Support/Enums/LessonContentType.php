<?php

namespace App\Support\Enums;

enum LessonContentType: string
{
    case TEXT = 'text';
    case VIDEO = 'video';
    case MIXED = 'mixed';
}
