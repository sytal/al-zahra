<?php

use App\Models\User;
use App\Modules\Certificate\Livewire\DashboardCertificateIndex;
use App\Modules\Consultation\Livewire\DashboardConsultationIndex;
use App\Modules\User\Livewire\ConfirmPassword;
use App\Modules\User\Livewire\ForgotPassword;
use App\Modules\User\Livewire\Login;
use App\Modules\User\Livewire\ProfileEdit;
use App\Modules\User\Livewire\Register;
use App\Modules\User\Livewire\ResetPassword;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;

it('renders guest auth pages', function (string $route) {
    $this->get(route($route, 'en'))->assertOk();
})->with(['login', 'register', 'password.request']);

it('logs in and redirects to the dashboard', function () {
    $user = User::factory()->create();

    Livewire::test(Login::class)
        ->set('email', $user->email)->set('password', 'password')
        ->call('login')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard', 'en'));

    $this->assertAuthenticatedAs($user);
});

it('rejects wrong credentials', function () {
    $user = User::factory()->create();

    Livewire::test(Login::class)
        ->set('email', $user->email)->set('password', 'wrong')
        ->call('login')
        ->assertHasErrors(['email']);

    $this->assertGuest();
});

it('throttles repeated failed logins', function () {
    $user = User::factory()->create();

    $component = Livewire::test(Login::class)->set('email', $user->email)->set('password', 'wrong');
    foreach (range(1, 6) as $i) {
        $component->call('login');
    }
    $component->set('password', 'password')->call('login')->assertHasErrors(['email']);

    $this->assertGuest();
});

it('redirects logged in users away from guest pages', function () {
    $this->actingAs(User::factory()->create())->get(route('login', 'en'))->assertRedirect();
});

it('registers a user and fires Registered', function () {
    Event::fake([Registered::class]);

    Livewire::test(Register::class)
        ->set('name', 'New User')->set('email', 'new@example.com')
        ->set('password', 'Password123!')->set('password_confirmation', 'Password123!')
        ->call('register')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard', 'en'));

    expect(User::where('email', 'new@example.com')->exists())->toBeTrue();
    Event::assertDispatched(Registered::class);
});

it('validates registration', function () {
    User::factory()->create(['email' => 'taken@example.com']);

    Livewire::test(Register::class)
        ->set('name', '')->set('email', 'taken@example.com')->set('password', 'short')->set('password_confirmation', 'x')
        ->call('register')
        ->assertHasErrors(['name', 'email', 'password']);
});

it('sends a reset link and resets the password', function () {
    Notification::fake();
    $user = User::factory()->create();

    Livewire::test(ForgotPassword::class)->set('email', $user->email)->call('sendResetLink')->assertHasNoErrors();

    Notification::assertSentTo($user, ResetPasswordNotification::class, function ($n) use ($user) {
        Livewire::test(ResetPassword::class, ['token' => $n->token])
            ->set('email', $user->email)
            ->set('password', 'NewPassword123!')->set('password_confirmation', 'NewPassword123!')
            ->call('resetPassword')
            ->assertHasNoErrors();

        return true;
    });

    expect(Hash::check('NewPassword123!', $user->fresh()->password))->toBeTrue();
});

it('confirms the password for a logged in user', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('password.confirm', 'en'))->assertOk();
    Livewire::actingAs($user)->test(ConfirmPassword::class)->set('password', 'wrong')->call('confirm')->assertHasErrors(['password']);
    Livewire::actingAs($user)->test(ConfirmPassword::class)->set('password', 'password')->call('confirm')->assertHasNoErrors();
});

it('enforces verified email on the dashboard', function () {
    $user = User::factory()->unverified()->create();

    $this->actingAs($user)->get(route('dashboard', 'en'))->assertRedirect(route('verification.notice', 'en'));
    $this->actingAs($user)->get(route('verification.notice', 'en'))->assertOk();
});

it('verifies email through the signed link', function () {
    $user = User::factory()->unverified()->create();
    $url = URL::temporarySignedRoute('verification.verify', now()->addHour(), ['locale' => 'en', 'id' => $user->getKey(), 'hash' => sha1($user->email)]);

    $this->actingAs($user)->get($url)->assertRedirect();
    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
});

it('rejects a tampered verification link', function () {
    $user = User::factory()->unverified()->create();
    $url = URL::temporarySignedRoute('verification.verify', now()->addHour(), ['locale' => 'en', 'id' => $user->getKey(), 'hash' => sha1($user->email)]);

    $this->actingAs($user)->get($url.'x')->assertForbidden();
    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
});

it('logs out', function () {
    $this->actingAs(User::factory()->create())->post(route('logout', 'en'))->assertRedirect();
    $this->assertGuest();
});

it('redirects guests from dashboard pages to login', function (string $route) {
    $this->get(route($route, 'en'))->assertRedirect(route('login', 'en'));
})->with(['dashboard', 'dashboard.consultations.index', 'dashboard.certificates.index', 'dashboard.profile.edit', 'dashboard.courses.index']);

it('renders dashboard pages for a student', function (string $route) {
    $this->actingAs(userWithRole())->get(route($route, 'en'))->assertOk();
})->with(['dashboard', 'dashboard.consultations.index', 'dashboard.certificates.index', 'dashboard.profile.edit', 'dashboard.courses.index']);

it('shows a student only their own consultations and certificates', function () {
    $me = userWithRole();
    $other = userWithRole();
    $theirs = makeConsultation($other);
    makeCertificate($other);
    makeConsultation($me);

    Livewire::actingAs($me)->test(DashboardConsultationIndex::class)
        ->assertViewHas('consultations', fn ($c) => $c->count() === 1 && $c->first()->user_id === $me->id)
        ->call('view', $theirs->uuid)
        ->assertViewHas('activeConsultation', null);

    Livewire::actingAs($me)->test(DashboardCertificateIndex::class)
        ->assertViewHas('certificates', fn ($c) => $c->count() === 0);
});

it('updates the profile name in place', function () {
    $user = userWithRole();

    Livewire::actingAs($user)->test(ProfileEdit::class)
        ->set('name', 'Renamed')
        ->call('updateProfile')
        ->assertHasNoErrors();

    expect($user->fresh()->name)->toBe('Renamed');
});
