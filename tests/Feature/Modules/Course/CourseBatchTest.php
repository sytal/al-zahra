<?php

use App\Modules\Course\Models\CourseBatch;
use Spatie\Activitylog\Models\Activity;

it('saves a course batch and relates to its course', function () {
    $course = makeCourse();

    $batch = CourseBatch::create([
        'course_id' => $course->id,
        'label' => 'Batch 1',
        'starts_at' => now()->addWeek(),
        'seats' => 20,
    ]);

    expect($batch->exists)->toBeTrue();
    expect($batch->course->id)->toBe($course->id);
    expect($course->batches()->first()->id)->toBe($batch->id);

    expect(Activity::query()->where('subject_type', CourseBatch::class)->where('subject_id', $batch->id)->exists())->toBeTrue();
});
