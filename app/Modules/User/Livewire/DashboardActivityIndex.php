<?php

namespace App\Modules\User\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

#[Layout('components.layouts.dashboard')]
#[Title('My Activity')]
class DashboardActivityIndex extends Component
{
    use WithPagination;

    public function render()
    {
        $activities = Activity::query()
            ->where('causer_id', Auth::id())
            ->where('causer_type', User::class)
            ->with('subject')
            ->latest()
            ->paginate(15);

        $isStaff = Auth::user()?->hasAnyRole(['director', 'admin', 'editor']) ?? false;

        $seo = [
            'title' => __('dash_activity_ui.page_title'),
            'description' => __('dash_activity_ui.page_title'),
            'image' => null,
            'type' => 'website',
            'schema' => null,
        ];

        return view('livewire.dashboard.activity-index', [
            'activities' => $activities,
            'isStaff' => $isStaff,
            'seo' => $seo,
        ]);
    }
}
