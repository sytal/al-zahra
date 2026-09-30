<?php

use App\Models\User;
use App\Modules\Article\Models\Tag;
use App\Modules\Course\Models\Enrollment;
use App\Modules\Newsletter\Models\NewsletterSubscriber;
use App\Modules\Setting\Models\Setting;
use App\Support\Enums\EnrollmentStatus;
use Spatie\Activitylog\Models\Activity;

it('logs activity for the 7 newly-covered models', function () {
    $tag = Tag::create(['name' => ['en' => 'Neuro'], 'slug' => 'neuro']);
    expect(Activity::where('subject_type', Tag::class)->where('subject_id', $tag->id)->exists())->toBeTrue();

    $setting = Setting::create(['key' => 'site_name', 'value' => ['en' => 'Al Zahra']]);
    expect(Activity::where('subject_type', Setting::class)->where('subject_id', $setting->id)->exists())->toBeTrue();

    $subscriber = NewsletterSubscriber::create(['email' => 'log-test@example.com', 'locale' => 'en', 'is_confirmed' => false]);
    expect(Activity::where('subject_type', NewsletterSubscriber::class)->where('subject_id', $subscriber->id)->exists())->toBeTrue();

    $user = userWithRole('student');
    expect(Activity::where('subject_type', User::class)->where('subject_id', $user->id)->exists())->toBeTrue();

    $course = makeCourse();
    $enrollment = Enrollment::create([
        'user_id' => $user->id,
        'course_id' => $course->id,
        'status' => EnrollmentStatus::ACTIVE,
        'progress_percent' => 0,
        'enrolled_at' => now(),
    ]);
    expect(Activity::where('subject_type', Enrollment::class)->where('subject_id', $enrollment->id)->exists())->toBeTrue();
});

it('never stores password or remember_token values for User activity', function () {
    $user = userWithRole('editor');
    $user->update(['name' => 'Updated Name', 'password' => 'a-new-password']);

    $activities = Activity::where('subject_type', User::class)->where('subject_id', $user->id)->get();

    expect($activities)->not->toBeEmpty();

    foreach ($activities as $activity) {
        $attributeChanges = $activity->getAttributes()['attribute_changes'] ?? '';
        expect($attributeChanges)->not->toContain('password');
        expect($attributeChanges)->not->toContain('remember_token');
    }
});

it('produces a human readable, translated description', function () {
    $tag = Tag::create(['name' => ['en' => 'Linguistics'], 'slug' => 'linguistics']);

    app()->setLocale('en');
    $enDescription = $tag->getDescriptionForEvent('created');
    expect($enDescription)->toContain('Linguistics')
        ->and($enDescription)->toBe('created the tag "Linguistics"');

    app()->setLocale('ur');
    $urDescription = $tag->getDescriptionForEvent('created');
    expect($urDescription)->toContain('Linguistics')
        ->and($urDescription)->not->toBe($enDescription);

    app()->setLocale('en');
});

it('shows activity for a non-original model via the widget\'s underlying query', function () {
    // Mirrors RecentActivityWidget::table()'s query (App\Filament\Widgets\RecentActivityWidget) —
    // not restricted to the original 9 models, so a Setting change must appear.
    Setting::create(['key' => 'newsletter_from_name', 'value' => ['en' => 'Al Zahra']]);

    $rows = Activity::query()->with(['causer', 'subject'])->latest()->limit(10)->get();

    expect($rows->pluck('subject_type'))->toContain(Setting::class);
});
