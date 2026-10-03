<?php

namespace App\Modules\Course\Livewire;

use App\Modules\Certificate\Models\Certificate;
use App\Modules\Course\Models\Course;
use App\Modules\Course\Models\Enrollment;
use App\Support\Enums\EnrollmentStatus;
use Illuminate\Http\RedirectResponse;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Final screen for a completed course (docs/COURSE-BUILDER-DEV-PLAN.md
 * Phase 5) — shows the final score and a certificate download link.
 * CourseLearn::mount() redirects here server-side for any completed
 * enrollment, so there is no URL loophole back into the course content.
 */
#[Layout('components.layouts.dashboard')]
class CourseCompleteScreen extends Component
{
    public Course $course;

    public Enrollment $enrollment;

    public ?Certificate $certificate = null;

    public function mount(Course $course): ?RedirectResponse
    {
        $user = auth()->user();

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (! $enrollment || $enrollment->status !== EnrollmentStatus::COMPLETED) {
            return redirect()
                ->route('dashboard.courses.index', ['locale' => app()->getLocale()])
                ->with('error', __('courses.not_enrolled_error'));
        }

        $this->course = $course;
        $this->enrollment = $enrollment;
        $this->certificate = Certificate::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->latest('issued_at')
            ->first();

        return null;
    }

    public function render()
    {
        $seo = [
            'title' => $this->course->title,
            'description' => $this->course->title,
            'image' => null,
            'type' => 'website',
            'schema' => null,
        ];

        return view('livewire.course.course-complete', ['seo' => $seo]);
    }
}
