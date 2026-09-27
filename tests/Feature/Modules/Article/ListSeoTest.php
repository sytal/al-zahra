<?php

use Illuminate\Support\Facades\Cache;

beforeEach(fn () => Cache::flush());

it('renders full SEO head data on the list pages', function (string $locale, string $path, string $titleKey, string $descKey) {
    $response = $this->get("/{$locale}/{$path}")->assertOk();

    $response
        ->assertSee('<title>'.e(__($titleKey)).' — '.config('app.name').'</title>', false)
        ->assertSee('<meta name="description" content="'.e(__($descKey)).'">', false)
        ->assertSee('<link rel="canonical" href="'.url("/{$locale}/{$path}").'">', false)
        ->assertSee('<meta property="og:title" content="'.e(__($titleKey)).'">', false)
        ->assertSee('"@type":"CollectionPage"', false)
        ->assertSee('"@type":"ItemList"', false);

    expect(substr_count($response->getContent(), 'rel="alternate" hreflang='))->toBe(count(config('app.locales')));
})->with(function () {
    foreach (['en', 'ur'] as $locale) {
        yield "{$locale} articles" => [$locale, 'articles', 'lists_ui.seo_art_title', 'lists_ui.seo_art_desc'];
        yield "{$locale} research" => [$locale, 'research', 'lists_ui.seo_rs_title', 'lists_ui.seo_rs_desc'];
        yield "{$locale} resources" => [$locale, 'resources', 'lists_ui.seo_rc_title', 'lists_ui.seo_rc_desc'];
    }
});

it('canonicalizes filtered list URLs to the base list URL', function () {
    $this->get('/en/articles?search=foo&category=1')
        ->assertOk()
        ->assertSee('<link rel="canonical" href="'.url('/en/articles').'">', false);
});
