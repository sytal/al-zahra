<?php

namespace App\Modules\Support\Models;

use App\Models\User;
use App\Support\Traits\HasActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportTicketMessage extends Model
{
    use HasActivityLog;

    protected string $activityLogLabel = 'Support ticket message';

    protected $fillable = [
        'support_ticket_id',
        'author_id',
        'body',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class, 'support_ticket_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
