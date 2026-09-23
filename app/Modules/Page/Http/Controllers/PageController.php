<?php

namespace App\Modules\Page\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Director\Models\Director;
use App\Modules\Setting\Models\Setting;
use App\Support\SeoSchema;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(string $locale): View
    {
        $director = Director::where('is_published', true)->first();
        $locale = app()->getLocale();

        $mission = Setting::where('key', 'mission_text')->value('value');
        $vision = Setting::where('key', 'vision_text')->value('value');

        $seo = [
            'title' => __('about.page_title'),
            'description' => $director?->bio_short,
            'image' => $director?->getFirstMediaUrl('cover_photo') ?: null,
            'type' => 'website',
            'schema' => $director ? SeoSchema::person($director) : null,
        ];

        return view('about', [
            'director' => $director,
            'mission' => is_array($mission) ? ($mission[$locale] ?? $mission['en'] ?? null) : $mission,
            'vision' => is_array($vision) ? ($vision[$locale] ?? $vision['en'] ?? null) : $vision,
            'seo' => $seo,
        ]);
    }
}
