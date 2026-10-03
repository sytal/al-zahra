<?php

namespace App\Modules\Support\Livewire;

use App\Modules\Support\Models\SupportTicket;
use App\Modules\Support\Models\SupportTicketMessage;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
#[Title('Support')]
class DashboardSupportIndex extends Component
{
    public ?string $viewing = null;

    public string $subject = '';

    public string $body = '';

    public string $reply = '';

    protected function rules(): array
    {
        return [
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:5000'],
        ];
    }

    public function view(string $uuid): void
    {
        $this->viewing = $uuid;

        $this->dispatch('open-modal', 'support-ticket-detail');
    }

    public function submit(): void
    {
        $data = $this->validate();

        $ticket = SupportTicket::create([
            'user_id' => Auth::id(),
            'subject' => $data['subject'],
            'status' => 'open',
        ]);

        $ticket->messages()->create([
            'author_id' => Auth::id(),
            'body' => $data['body'],
        ]);

        $this->reset(['subject', 'body']);
        $this->dispatch('close-modal', 'support-ticket-new');
    }

    public function reply(): void
    {
        $this->validate(['reply' => ['required', 'string', 'max:5000']]);

        $ticket = SupportTicket::where('uuid', $this->viewing)->firstOrFail();

        $this->authorize('view', $ticket);

        $ticket->messages()->create([
            'author_id' => Auth::id(),
            'body' => $this->reply,
        ]);

        $ticket->update(['status' => 'open']);

        $this->reset('reply');
    }

    public function render()
    {
        $tickets = SupportTicket::query()
            ->where('user_id', Auth::id())
            ->with('messages')
            ->latest()
            ->get();

        $activeTicket = $this->viewing
            ? $tickets->firstWhere('uuid', $this->viewing)
            : null;

        $seo = [
            'title' => __('dashboard.support_page_title'),
            'description' => __('dashboard.support_page_title'),
            'image' => null,
            'type' => 'website',
            'schema' => null,
        ];

        return view('livewire.dashboard.support-index', [
            'tickets' => $tickets,
            'activeTicket' => $activeTicket,
            'seo' => $seo,
        ]);
    }
}
