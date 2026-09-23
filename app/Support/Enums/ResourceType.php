<?php

namespace App\Support\Enums;

enum ResourceType: string
{
    case PDF = 'pdf';
    case TEMPLATE = 'template';
    case QUESTIONNAIRE = 'questionnaire';
    case GUIDE = 'guide';
}
