<?php

namespace App\Modules\Course\Events;

use App\Modules\Course\Models\Enrollment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CourseCompleted
{
    use Dispatchable, SerializesModels;

    public function __construct(public Enrollment $enrollment) {}
}
