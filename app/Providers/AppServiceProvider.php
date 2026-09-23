<?php

namespace App\Providers;

use App\Modules\Article\Livewire\ArticleIndex;
use App\Modules\Consultation\Livewire\ConsultationForm;
use App\Modules\Contact\Livewire\ContactForm;
use App\Modules\Course\Livewire\CourseIndex;
use App\Modules\Newsletter\Livewire\NewsletterForm;
use App\Modules\Research\Livewire\ResearchIndex;
use App\Modules\Resource\Livewire\ResourceIndex;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * Livewire components live in each module's own Livewire/ folder
     * (docs/CLAUDE.md Section 4), so auto-discovery (which only scans
     * app/Livewire) can't find them — register each one's tag manually.
     */
    public function boot(): void
    {
        Livewire::component('articles.article-index', ArticleIndex::class);
        Livewire::component('courses.course-index', CourseIndex::class);
        Livewire::component('consultation.consultation-form', ConsultationForm::class);
        Livewire::component('contact.contact-form', ContactForm::class);
        Livewire::component('newsletter.newsletter-form', NewsletterForm::class);
        Livewire::component('research.research-index', ResearchIndex::class);
        Livewire::component('resources.resource-index', ResourceIndex::class);
    }
}
