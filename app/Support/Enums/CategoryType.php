<?php

namespace App\Support\Enums;

enum CategoryType: string
{
    case ARTICLE = 'article';
    case RESEARCH = 'research';
    case RESOURCE = 'resource';
    case COURSE = 'course';
}
