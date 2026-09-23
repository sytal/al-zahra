<?php

namespace App\Modules\Consultation\Livewire;

use App\Modules\Consultation\Models\Consultation;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
#[Title('My Consultations')]
class DashboardConsultationIndex extends Component
{
    public ?string $viewing = null;

    public function view(string $uuid): void
    {
        $this->viewing = $uuid;

        $this->dispatch('open-modal', 'consultation-detail');
    }

    public function render()
    {
        $consultations = Consultation::query()
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $activeConsultation = $this->viewing
            ? $consultations->firstWhere('uuid', $this->viewing)
            : null;

        $seo = [
            'title' => __('dashboard.consultations_page_title'),
            'description' => __('dashboard.consultations_page_title'),
            'image' => null,
            'type' => 'website',
            'schema' => null,
        ];

        return view('livewire.dashboard.consultation-index', [
            'consultations' => $consultations,
            'activeConsultation' => $activeConsultation,
            'seo' => $seo,
        ]);
    }
}
