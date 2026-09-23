<?php

namespace App\Modules\Course\Livewire;

use App\Modules\Course\Models\Enrollment;
use App\Support\Enums\EnrollmentStatus;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
#[Title('My Courses')]
class MyCourses extends Component
{
    #[Url]
    public string $tab = 'all';

    public function setTab(string $tab): void
    {
        $this->tab = $tab;
    }

    public function render()
    {
        $user = auth()->user();

        $enrollments = Enrollment::with('course')
            ->where('user_id', $user->id)
            ->when($this->tab === 'in_progress', fn ($query) => $query->where('status', EnrollmentStatus::ACTIVE))
            ->when($this->tab === 'completed', fn ($query) => $query->where('status', EnrollmentStatus::COMPLETED))
            ->latest('enrolled_at')
            ->get();

        $hasAnyEnrollment = $this->tab === 'all'
            ? $enrollments->isNotEmpty()
            : Enrollment::where('user_id', $user->id)->exists();

        $seo = [
            'title' => __('dashboard.my_courses_title'),
            'description' => __('dashboard.my_courses_title'),
            'image' => null,
            'type' => 'website',
            'schema' => null,
        ];

        return view('livewire.course.my-courses', [
            'enrollments' => $enrollments,
            'hasAnyEnrollment' => $hasAnyEnrollment,
            'seo' => $seo,
        ]);
    }
}
