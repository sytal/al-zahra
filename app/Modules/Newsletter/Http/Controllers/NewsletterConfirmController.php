<?php

namespace App\Modules\Newsletter\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Newsletter\Models\NewsletterSubscriber;
use Illuminate\View\View;

class NewsletterConfirmController extends Controller
{
    // {locale}-group route with another URI param ({subscriber}) — $locale
    // must be declared explicitly, otherwise the locale value silently
    // lands in the $subscriber parameter (docs/CLAUDE.md Section 11).
    public function __invoke(string $locale, NewsletterSubscriber $subscriber): View
    {
        if (! $subscriber->is_confirmed) {
            $subscriber->forceFill([
                'is_confirmed' => true,
                'confirmed_at' => now(),
            ])->save();
        }

        return view('newsletter.confirmed', compact('subscriber'));
    }
}
