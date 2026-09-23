<?php

namespace App\Providers;

use App\Modules\Article\Livewire\ArticleIndex;
use App\Modules\Certificate\Listeners\IssueCertificateListener;
use App\Modules\Certificate\Livewire\CertificateVerify;
use App\Modules\Certificate\Livewire\DashboardCertificateIndex;
use App\Modules\Consultation\Livewire\ConsultationForm;
use App\Modules\Consultation\Livewire\DashboardConsultationIndex;
use App\Modules\Contact\Livewire\ContactForm;
use App\Modules\Course\Events\CourseCompleted;
use App\Modules\Course\Livewire\CourseIndex;
use App\Modules\Course\Livewire\LessonViewer;
use App\Modules\Course\Livewire\MyCourses;
use App\Modules\Newsletter\Livewire\NewsletterForm;
use App\Modules\Research\Livewire\ResearchIndex;
use App\Modules\Resource\Livewire\ResourceIndex;
use App\Modules\User\Listeners\SendWelcomeEmail;
use App\Modules\User\Livewire\ConfirmPassword;
use App\Modules\User\Livewire\ForgotPassword;
use App\Modules\User\Livewire\Login;
use App\Modules\User\Livewire\ProfileEdit;
use App\Modules\User\Livewire\Register;
use App\Modules\User\Livewire\ResetPassword;
use App\Modules\User\Livewire\VerifyEmailNotice;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);

        Livewire::component('articles.article-index', ArticleIndex::class);
        Livewire::component('certificates.certificate-verify', CertificateVerify::class);
        Livewire::component('courses.course-index', CourseIndex::class);
        Livewire::component('courses.my-courses', MyCourses::class);
        Livewire::component('courses.lesson-viewer', LessonViewer::class);
        Livewire::component('consultation.consultation-form', ConsultationForm::class);
        Livewire::component('contact.contact-form', ContactForm::class);
        Livewire::component('newsletter.newsletter-form', NewsletterForm::class);
        Livewire::component('research.research-index', ResearchIndex::class);
        Livewire::component('resources.resource-index', ResourceIndex::class);
        Livewire::component('dashboard.consultation-index', DashboardConsultationIndex::class);
        Livewire::component('dashboard.certificate-index', DashboardCertificateIndex::class);
        Livewire::component('dashboard.profile-edit', ProfileEdit::class);
        Livewire::component('user.login', Login::class);
        Livewire::component('user.register', Register::class);
        Livewire::component('user.forgot-password', ForgotPassword::class);
        Livewire::component('user.reset-password', ResetPassword::class);
        Livewire::component('user.confirm-password', ConfirmPassword::class);
        Livewire::component('user.verify-email-notice', VerifyEmailNotice::class);

        Event::listen(Registered::class, SendWelcomeEmail::class);
        Event::listen(CourseCompleted::class, IssueCertificateListener::class);
    }
}
