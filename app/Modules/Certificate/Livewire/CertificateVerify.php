<?php

namespace App\Modules\Certificate\Livewire;

use App\Modules\Certificate\Models\Certificate;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.minimal')]
#[Title('Verify a Certificate')]
class CertificateVerify extends Component
{
    public string $code = '';

    public bool $checked = false;

    public ?Certificate $certificate = null;

    protected function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:100'],
        ];
    }

    public function verify(): void
    {
        $key = 'certificate-verify:'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, 10)) {
            $this->addError('code', __('certificates.rate_limited'));

            return;
        }

        RateLimiter::hit($key, 3600);

        $data = $this->validate();

        $this->certificate = Certificate::query()
            ->whereRaw('LOWER(verification_code) = ?', [mb_strtolower($data['code'])])
            ->with(['user', 'course'])
            ->first();

        $this->checked = true;
    }

    public function render()
    {
        $seo = [
            'title' => __('certificates.page_title'),
            'description' => __('certificates.page_intro'),
            'image' => null,
            'type' => 'website',
            'schema' => null,
        ];

        return view('livewire.certificate.certificate-verify', [
            'seo' => $seo,
        ]);
    }
}
