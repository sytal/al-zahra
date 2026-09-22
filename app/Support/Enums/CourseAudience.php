<?php

namespace App\Support\Enums;

enum CourseAudience: string
{
    case STUDENTS = 'students';
    case TEACHERS = 'teachers';
    case PARENTS = 'parents';
    case PROFESSIONALS = 'professionals';
}
