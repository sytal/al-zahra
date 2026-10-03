<?php

namespace App\Modules\Course\Livewire;

use App\Modules\Course\Models\Course;
use App\Modules\Course\Models\CourseBlock;
use App\Modules\Course\Models\CourseBlockProgress;
use App\Modules\Course\Models\CourseDiscussionReply;
use App\Modules\Course\Models\CourseModule;
use App\Modules\Course\Models\Enrollment;
use App\Modules\Course\Services\CourseCompletionService;
use App\Support\Enums\EnrollmentStatus;
use Illuminate\Http\RedirectResponse;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Renders a course module's content blocks with lock/unlock/done progress,
 * per docs/COURSE-BUILDER-PLAN.md Part C/D and
 * docs/COURSE-BUILDER-DEV-PLAN.md Phase 3. Replaces LessonViewer for
 * courses built on the modules+blocks structure (flat course_lessons
 * courses still use LessonViewer, kept as-is).
 */
#[Layout('components.layouts.dashboard')]
class CourseLearn extends Component
{
    use WithFileUploads;

    public Course $course;

    public Enrollment $enrollment;

    public CourseModule $module;

    public array $openBlockId = [];

    /** @var array<int, string> block_id => quiz answer state (question index => selected option index) */
    public array $quizAnswers = [];

    public array $discussionBody = [];

    public $assignmentUpload = null;

    public function mount(Course $course, ?CourseModule $module = null): ?RedirectResponse
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

        if ($enrollment->status === EnrollmentStatus::COMPLETED) {
            return redirect()->route('dashboard.courses.complete', [
                'locale' => app()->getLocale(),
                'course' => $course->slug,
            ]);
        }

        $this->course = $course;
        $this->enrollment = $enrollment;

        if ($module && $module->course_id !== $course->id) {
            abort(404);
        }

        $this->module = $module ?? $course->modules()->orderBy('sort_order')->firstOrFail();

        return null;
    }

    public function title(): string
    {
        return $this->module->title;
    }

    /**
     * Blocks in the module decorated with lock state, in order. Order-lock
     * (Part D point 1): a block unlocks once the previous one is done;
     * free-preview blocks are irrelevant here since the student is already
     * enrolled (always unlocked by enrollment).
     */
    protected function decoratedBlocks(): array
    {
        $blocks = $this->module->blocks()->get();
        $progressByBlockId = CourseBlockProgress::where('enrollment_id', $this->enrollment->id)
            ->whereIn('course_block_id', $blocks->pluck('id'))
            ->get()
            ->keyBy('course_block_id');

        $result = [];
        $previousDone = true;

        foreach ($blocks as $block) {
            $progress = $progressByBlockId->get($block->id);
            $done = $progress?->status === 'done';
            $state = $done ? 'done' : ($previousDone ? 'unlocked' : 'locked');

            $result[] = [
                'block' => $block,
                'progress' => $progress,
                'state' => $state,
            ];

            $previousDone = $done;
        }

        return $result;
    }

    protected function progressFor(CourseBlock $block): CourseBlockProgress
    {
        return CourseBlockProgress::firstOrCreate(
            ['enrollment_id' => $this->enrollment->id, 'course_block_id' => $block->id],
            ['status' => 'unlocked']
        );
    }

    protected function assertUnlocked(CourseBlock $block): void
    {
        $decorated = collect($this->decoratedBlocks())->firstWhere('block.id', $block->id);

        if (! $decorated || $decorated['state'] === 'locked') {
            abort(403);
        }
    }

    /**
     * Read/case-study/research-reading/research-activity (mark-done mode)
     * blocks: a simple "mark done" action.
     */
    public function markDone(int $blockId): void
    {
        $block = $this->module->blocks()->findOrFail($blockId);
        $this->assertUnlocked($block);

        $progress = $this->progressFor($block);
        $progress->status = 'done';
        $progress->completed_at = now();
        $progress->save();

        $this->recomputeCourseProgress();
    }

    /**
     * Practical/graded quiz submission. Practical never blocks progress and
     * is always retakeable; graded requires pass_percent to count as done.
     */
    public function submitQuiz(int $blockId): void
    {
        $block = $this->module->blocks()->findOrFail($blockId);
        $this->assertUnlocked($block);

        $questions = $block->content['questions'] ?? [];
        $answers = $this->quizAnswers[$blockId] ?? [];
        $correct = 0;

        foreach ($questions as $i => $question) {
            $selected = $answers[$i] ?? null;
            $correctIndexes = array_keys(array_filter($question['options'] ?? [], fn ($o) => ! empty($o['correct'])));

            if ($selected !== null && in_array((int) $selected, $correctIndexes, true)) {
                $correct++;
            }
        }

        $total = count($questions);
        $score = $total > 0 ? (int) round($correct / $total * 100) : 0;

        $progress = $this->progressFor($block);
        $data = $progress->data ?? [];
        $data['score'] = $score;
        $data['attempts'] = ($data['attempts'] ?? 0) + 1;
        $progress->data = $data;

        $isGraded = $block->type === 'graded_quiz';
        $passPercent = (int) ($block->content['pass_percent'] ?? 70);

        if (! $isGraded || $score >= $passPercent) {
            $progress->status = 'done';
            $progress->completed_at = now();
        }

        $progress->save();

        $this->recomputeCourseProgress();
    }

    public function postDiscussionReply(int $blockId): void
    {
        $block = $this->module->blocks()->findOrFail($blockId);
        $this->assertUnlocked($block);

        $body = trim($this->discussionBody[$blockId] ?? '');

        if ($body === '') {
            return;
        }

        CourseDiscussionReply::create([
            'course_block_id' => $block->id,
            'user_id' => auth()->id(),
            'author_id' => auth()->id(),
            'body' => $body,
        ]);

        $this->discussionBody[$blockId] = '';

        $progress = $this->progressFor($block);

        if ($progress->status !== 'done') {
            $progress->status = 'done';
            $progress->completed_at = now();
            $progress->save();
        }

        $this->recomputeCourseProgress();
    }

    /**
     * Assignment submission — stores the uploaded file in the progress
     * row's `submission` media collection (Spatie Medialibrary, consistent
     * with Course/CourseLesson) and sets status to "submitted" (admin
     * reviews and marks Pass/Needs revision separately).
     */
    public function submitAssignment(int $blockId): void
    {
        $block = $this->module->blocks()->findOrFail($blockId);
        $this->assertUnlocked($block);

        $this->validate([
            'assignmentUpload' => ['required', 'file', 'max:10240'],
        ]);

        $progress = $this->progressFor($block);

        $data = $progress->data ?? [];
        $data['mark'] = 'submitted';
        $progress->data = $data;
        $progress->status = $progress->status === 'done' ? 'done' : 'unlocked';
        $progress->save();

        $progress->addMedia($this->assignmentUpload->getRealPath())
            ->usingName($this->assignmentUpload->getClientOriginalName())
            ->usingFileName($this->assignmentUpload->getClientOriginalName())
            ->toMediaCollection('submission');

        $this->assignmentUpload = null;
    }

    protected function recomputeCourseProgress(): void
    {
        $blockIds = CourseBlock::whereIn('course_module_id', $this->course->modules()->pluck('id'))->pluck('id');
        $totalBlocks = $blockIds->count();
        $doneBlocks = CourseBlockProgress::where('enrollment_id', $this->enrollment->id)
            ->where('status', 'done')
            ->whereIn('course_block_id', $blockIds)
            ->count();

        $this->enrollment->progress_percent = $totalBlocks > 0 ? (int) round($doneBlocks / $totalBlocks * 100) : 0;
        $this->enrollment->save();

        if ($totalBlocks > 0 && $doneBlocks === $totalBlocks) {
            app(CourseCompletionService::class)->finalize($this->enrollment);
        }
    }

    public function render()
    {
        $modules = $this->course->modules()->get();

        $seo = [
            'title' => $this->module->title,
            'description' => $this->course->title,
            'image' => null,
            'type' => 'website',
            'schema' => null,
        ];

        return view('livewire.course.course-learn', [
            'modules' => $modules,
            'decoratedBlocks' => $this->decoratedBlocks(),
            'seo' => $seo,
        ]);
    }
}
