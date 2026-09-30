<?php

use App\Models\User;
use App\Modules\User\Livewire\DashboardActivityIndex;
use Livewire\Livewire;
use Spatie\Activitylog\Models\Activity;

it('redirects guests away from the activity page', function () {
    $this->get(route('dashboard.activity.index', 'en'))->assertRedirect(route('login', 'en'));
});

it('renders the activity page for a logged in user', function () {
    $this->actingAs(userWithRole())->get(route('dashboard.activity.index', 'en'))->assertOk();
});

it('only shows the logged in user their own activity rows', function () {
    $me = userWithRole();
    $other = userWithRole();

    Activity::create([
        'log_name' => 'default',
        'description' => 'Profile was updated',
        'causer_type' => User::class,
        'causer_id' => $me->id,
        'created_at' => now(),
    ]);

    Activity::create([
        'log_name' => 'default',
        'description' => 'Profile was updated',
        'causer_type' => User::class,
        'causer_id' => $other->id,
        'created_at' => now(),
    ]);

    Livewire::actingAs($me)->test(DashboardActivityIndex::class)
        ->assertViewHas('activities', fn ($activities) => $activities->total() === 1 && (int) $activities->first()->causer_id === $me->id);
});
