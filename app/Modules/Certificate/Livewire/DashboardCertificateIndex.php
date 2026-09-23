<?php

namespace App\Modules\Certificate\Livewire;

use App\Modules\Certificate\Models\Certificate;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
#[Title('My Certificates')]
class DashboardCertificateIndex extends Component
{
    public function render()
    {
        $certificates = Certificate::query()
            ->with('course')
            ->where('user_id', Auth::id())
            ->latest('issued_at')
            ->get();

        $seo = [
            'title' => __('dashboard.certificates_page_title'),
            'description' => __('dashboard.certificates_page_title'),
            'image' => null,
            'type' => 'website',
            'schema' => null,
        ];

        return view('livewire.dashboard.certificate-index', [
            'certificates' => $certificates,
            'seo' => $seo,
        ]);
    }
}
