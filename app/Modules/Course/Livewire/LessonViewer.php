<?php

namespace App\Modules\Course\Livewire;

use App\Modules\Course\Models\Course;
use App\Modules\Course\Models\CourseLesson;
use App\Modules\Course\Models\Enrollment;
use App\Modules\Course\Models\LessonProgress;
use App\Modules\Course\Services\CourseService;
use Illuminate\Http\RedirectResponse;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
class LessonViewer extends Component
{
    public Course $course;

    public ?CourseLesson $lesson = null;

    public Enrollment $enrollment;

    public bool $courseJustCompleted = false;

    /**
     * Livewire full-page components receive route-bound models directly in
     * mount() (docs/CLAUDE.md Section 11 pitfall only applies to classic
     * Controller method signatures, not Livewire).
     */
    public function mount(Course $course, ?CourseLesson $lesson = null): RedirectResponse|null
    {
        $user = auth()->user();

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (! $enrollment) {
            return redirect()
                ->route('courses.show', ['locale' => app()->getLocale(), 'slug' => $course->slug])
                ->with('error', __('courses.not_enrolled_error'));
        }

        $this->course = $course;
        $this->enrollment = $enrollment;

        if ($lesson && $lesson->course_id !== $course->id) {
            abort(404);
        }

        if (! $lesson) {
            $target = $this->firstIncompleteLesson() ?? $course->lessons()->orderBy('sort_order')->first();

            if (! $target) {
                abort(404);
            }

            return redirect()->route('dashboard.courses.lesson', [
                'locale' => app()->getLocale(),
                'course' => $course->slug,
                'lesson' => $target->uuid,
            ]);
        }

        $this->lesson = $lesson;

        return null;
    }

    public function title(): string
    {
        return $this->lesson?->title ?? __('lessons.viewer_title');
    }

    protected function firstIncompleteLesson(): ?CourseLesson
    {
        $completedIds = $this->completedLessonIds();

        return $this->course->lessons()
            ->orderBy('sort_order')
            ->get()
            ->first(fn (CourseLesson $lesson) => ! in_array($lesson->id, $completedIds, true));
    }

    protected function completedLessonIds(): array
    {
        return LessonProgress::where('enrollment_id', $this->enrollment->id)->pluck('course_lesson_id')->all();
    }

    public function markComplete(CourseService $service): void
    {
        $this->enrollment = $service->markLessonComplete(auth()->user(), $this->lesson);

        $this->dispatch('lesson-completed');
    }

    public function goToNextLesson(): void
    {
        $next = $this->course->lessons()
            ->where('sort_order', '>', $this->lesson->sort_order)
            ->orderBy('sort_order')
            ->first();

        if ($next) {
            $this->redirectRoute('dashboard.courses.lesson', [
                'locale' => app()->getLocale(),
                'course' => $this->course->slug,
                'lesson' => $next->uuid,
            ], navigate: true);

            return;
        }

        $this->courseJustCompleted = true;
    }

    public function render()
    {
        $completedIds = $this->completedLessonIds();

        $lessons = $this->course->lessons()->orderBy('sort_order')->get();

        $isCompleted = in_array($this->lesson->id, $completedIds, true);

        $hasNext = $lessons->firstWhere(fn (CourseLesson $l) => $l->sort_order > $this->lesson->sort_order) !== null;

        $seo = [
            'title' => $this->lesson->title,
            'description' => $this->course->title,
            'image' => null,
            'type' => 'website',
            'schema' => null,
        ];

        return view('livewire.course.lesson-viewer', [
            'lessons' => $lessons,
            'completedIds' => $completedIds,
            'isCompleted' => $isCompleted,
            'hasNext' => $hasNext,
            'seo' => $seo,
        ]);
    }
}
