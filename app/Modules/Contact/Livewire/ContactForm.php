<?php

namespace App\Modules\Contact\Livewire;

use App\Modules\Contact\Services\ContactService;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.public')]
#[Title('Contact')]
class ContactForm extends Component
{
    public string $name = '';

    public string $email = '';

    public string $subject = '';

    public string $message = '';

    public bool $submitted = false;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ];
    }

    public function submit(ContactService $service): void
    {
        $key = 'contact-submit:'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('message', __('contact.rate_limited'));

            return;
        }

        $data = $this->validate();

        RateLimiter::hit($key, 3600);

        $service->submit($data);

        $this->reset(['name', 'email', 'subject', 'message']);
        $this->submitted = true;
    }

    public function render()
    {
        $seo = [
            'title' => __('forms_ui.seo_contact_title'),
            'description' => __('contact.page_intro'),
            'image' => null,
            'type' => 'website',
            'schema' => ['@context' => 'https://schema.org', '@type' => 'ContactPage', 'name' => __('forms_ui.seo_contact_title'), 'description' => __('contact.page_intro'), 'url' => url()->current(), 'inLanguage' => app()->getLocale(), 'isPartOf' => ['@type' => 'WebSite', 'name' => config('app.name'), 'url' => url('/')]],
        ];

        $seoHead = new HtmlString(Blade::render('<x-seo :title="$t" :description="$d" :type="$ty" :schema="$sc" />', ['t' => $seo['title'], 'd' => $seo['description'], 'ty' => $seo['type'], 'sc' => $seo['schema']]));

        return view('livewire.contact.contact-form', compact('seo'))->layoutData(['seo' => $seoHead]);
    }
}
