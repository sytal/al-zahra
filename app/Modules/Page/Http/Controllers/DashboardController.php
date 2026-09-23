<?php

namespace App\Modules\Page\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Certificate\Models\Certificate;
use App\Modules\Consultation\Models\Consultation;
use App\Modules\Course\Models\Enrollment;
use App\Support\Enums\EnrollmentStatus;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request, string $locale): View
    {
        $user = $request->user();

        $inProgress = Enrollment::with('course')
            ->where('user_id', $user->id)
            ->where('status', EnrollmentStatus::ACTIVE)
            ->latest('enrolled_at')
            ->limit(2)
            ->get();

        $recentConsultations = Consultation::where('user_id', $user->id)
            ->latest()
            ->limit(2)
            ->get();

        $stats = [
            'courses_in_progress' => Enrollment::where('user_id', $user->id)->where('status', EnrollmentStatus::ACTIVE)->count(),
            'certificates_earned' => Certificate::where('user_id', $user->id)->count(),
            'consultations' => Consultation::where('user_id', $user->id)->count(),
        ];

        return view('dashboard.index', compact('inProgress', 'recentConsultations', 'stats'));
    }
}
