<?php

use App\Models\User;
use App\Modules\Article\Models\Article;
use App\Modules\Certificate\Models\Certificate;
use App\Modules\Consultation\Models\Consultation;
use App\Modules\Course\Models\Course;
use App\Modules\Research\Models\ResearchPaper;
use App\Modules\Resource\Models\Resource;
use App\Support\Enums\ConsultationStatus;
use App\Support\Enums\ConsultationType;
use App\Support\Enums\CourseAudience;
use App\Support\Enums\CourseLevel;
use App\Support\Enums\FullPaperType;
use App\Support\Enums\ResourceType;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Support\Str;

function userWithRole(string $role = 'student', array $attrs = []): User
{
    test()->seed(RolePermissionSeeder::class);
    $user = User::factory()->create($attrs);
    $user->assignRole($role);

    return $user;
}

function makeArticle(array $attrs = []): Article
{
    return Article::create($attrs + [
        'author_id' => User::factory()->create()->id,
        'title' => ['en' => 'Test article'],
        'slug' => 'article-'.Str::random(8),
        'excerpt' => ['en' => 'Excerpt'],
        'body' => ['en' => 'Body'],
        'is_published' => true,
        'published_at' => now()->subDay(),
    ]);
}

function makeCourse(array $attrs = []): Course
{
    return Course::create($attrs + [
        'instructor_id' => User::factory()->create()->id,
        'title' => ['en' => 'Test course'],
        'slug' => 'course-'.Str::random(8),
        'short_description' => ['en' => 'Short'],
        'full_description' => ['en' => 'Full'],
        'level' => CourseLevel::BEGINNER,
        'audience' => CourseAudience::STUDENTS,
        'learning_outcomes' => ['en' => ['Outcome one']],
        'is_free' => true,
        'is_published' => true,
    ]);
}

function makeResearch(array $attrs = []): ResearchPaper
{
    return ResearchPaper::create($attrs + [
        'author_id' => User::factory()->create()->id,
        'title' => ['en' => 'Test paper'],
        'slug' => 'paper-'.Str::random(8),
        'full_paper_type' => FullPaperType::EXTERNAL_LINK,
        'external_url' => 'https://example.com/paper',
        'published_year' => 2025,
        'is_published' => true,
    ]);
}

function makeResource(array $attrs = []): Resource
{
    return Resource::create($attrs + [
        'title' => ['en' => 'Test resource'],
        'slug' => 'resource-'.Str::random(8),
        'description' => ['en' => 'Desc'],
        'resource_type' => ResourceType::GUIDE,
        'is_free' => true,
        'is_published' => true,
    ]);
}

function makeConsultation(User $user, array $attrs = []): Consultation
{
    return Consultation::create($attrs + [
        'user_id' => $user->id,
        'type' => ConsultationType::FREE_QUESTION,
        'question' => 'A question',
        'status' => ConsultationStatus::PENDING,
    ]);
}

function makeCertificate(User $user, ?Course $course = null): Certificate
{
    return Certificate::create([
        'verification_code' => 'CERT-'.Str::upper(Str::random(8)),
        'user_id' => $user->id,
        'course_id' => ($course ?? makeCourse())->id,
        'issued_at' => now(),
    ]);
}
