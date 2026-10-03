<?php

use App\Modules\Support\Livewire\DashboardSupportIndex;
use App\Modules\Support\Models\SupportTicket;
use Illuminate\Support\Facades\Gate;
use Livewire\Livewire;

it('lets a student create a ticket with an initial message', function () {
    $user = userWithRole();

    Livewire::actingAs($user)->test(DashboardSupportIndex::class)
        ->set('subject', 'Need help')
        ->set('body', 'My question')
        ->call('submit')
        ->assertHasNoErrors();

    $ticket = SupportTicket::firstOrFail();
    expect($ticket->user_id)->toBe($user->id)
        ->and($ticket->status)->toBe('open')
        ->and($ticket->messages()->count())->toBe(1);
});

it('lets a student reply to their own ticket', function () {
    $user = userWithRole();
    $ticket = SupportTicket::create(['user_id' => $user->id, 'subject' => 'Help', 'status' => 'open']);
    $ticket->messages()->create(['author_id' => $user->id, 'body' => 'First message']);

    Livewire::actingAs($user)->test(DashboardSupportIndex::class)
        ->set('viewing', $ticket->uuid)
        ->set('reply', 'Follow up')
        ->call('reply')
        ->assertHasNoErrors();

    expect($ticket->messages()->count())->toBe(2);
});

it('does not let a student see another student ticket', function () {
    $owner = userWithRole();
    $other = userWithRole();
    $ticket = SupportTicket::create(['user_id' => $owner->id, 'subject' => 'Help', 'status' => 'open']);

    expect(Gate::forUser($other)->allows('view', $ticket))->toBeFalse()
        ->and(Gate::forUser($owner)->allows('view', $ticket))->toBeTrue();
});

it('lets admin see all tickets and reply', function () {
    $owner = userWithRole();
    $admin = userWithRole('admin');
    $ticket = SupportTicket::create(['user_id' => $owner->id, 'subject' => 'Help', 'status' => 'open']);
    $ticket->messages()->create(['author_id' => $owner->id, 'body' => 'First message']);

    expect(Gate::forUser($admin)->allows('viewAny', SupportTicket::class))->toBeTrue()
        ->and(Gate::forUser($admin)->allows('view', $ticket))->toBeTrue()
        ->and(Gate::forUser($admin)->allows('update', $ticket))->toBeTrue()
        ->and(Gate::forUser($owner)->allows('viewAny', SupportTicket::class))->toBeFalse();

    $ticket->messages()->create(['author_id' => $admin->id, 'body' => 'Staff reply']);
    $ticket->update(['status' => 'answered']);

    expect($ticket->refresh()->status)->toBe('answered')
        ->and($ticket->messages()->count())->toBe(2);
});
