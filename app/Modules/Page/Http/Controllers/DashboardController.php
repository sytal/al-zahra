<?php

namespace App\Modules\Page\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Certificate\Models\Certificate;
use App\Modules\Consultation\Models\Consultation;
use App\Modules\Course\Models\Course;
use App\Modules\Course\Models\Enrollment;
use App\Modules\Course\Models\LessonProgress;
use App\Support\Enums\EnrollmentStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request, string $locale): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->hasAnyRole(['director', 'admin', 'editor'])) {
            return redirect('/admin');
        }

        $inProgress = Enrollment::with('course')
            ->where('user_id', $user->id)
            ->where('status', EnrollmentStatus::ACTIVE)
            ->latest('enrolled_at')
            ->limit(4)
            ->get();

        $recentConsultations = Consultation::where('user_id', $user->id)
            ->latest()
            ->limit(4)
            ->get();

        $stats = [
            'courses_in_progress' => Enrollment::where('user_id', $user->id)->where('status', EnrollmentStatus::ACTIVE)->count(),
            'certificates_earned' => Certificate::where('user_id', $user->id)->count(),
            'consultations' => Consultation::where('user_id', $user->id)->count(),
        ];

        $progressData = $inProgress->mapWithKeys(function (Enrollment $enrollment) {
            $lessons = $enrollment->course->lessons()->orderBy('sort_order')->get();
            $doneIds = LessonProgress::where('enrollment_id', $enrollment->id)
                ->whereNotNull('completed_at')
                ->pluck('course_lesson_id');

            return [$enrollment->id => [
                'total' => $lessons->count(),
                'done' => $lessons->whereIn('id', $doneIds)->count(),
                'next' => $lessons->whereNotIn('id', $doneIds)->first(),
            ]];
        });

        $enrolledCourseIds = Enrollment::where('user_id', $user->id)->pluck('course_id');
        $recommended = Course::where('is_published', true)
            ->whereNotIn('id', $enrolledCourseIds)
            ->latest()
            ->first();

        $hour = (int) now()->format('G');
        $greetingKey = $hour < 12 ? 'morning' : ($hour < 18 ? 'afternoon' : 'evening');

        return view('dashboard.index', compact('inProgress', 'recentConsultations', 'stats', 'progressData', 'recommended', 'greetingKey'));
    }
}
