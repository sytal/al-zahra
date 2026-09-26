<?php

use Illuminate\Support\Facades\Artisan;

it('renders home and about in every locale', function (string $locale) {
    $this->get("/{$locale}")->assertOk();
    $this->get("/{$locale}/about")->assertOk();
})->with(['en', 'ur', 'hi', 'fa', 'ur-roman']);

it('redirects the bare root to the default locale', function () {
    $this->get('/')->assertRedirect('/'.config('app.locale'));
});

it('sets the locale from the URL prefix', function () {
    $this->get('/ur');
    expect(app()->getLocale())->toBe('ur');

    $this->get('/fa');
    expect(app()->getLocale())->toBe('fa');
});

it('builds language switch links without duplicating the locale', function () {
    $html = $this->get('/ur/articles')->assertOk()->getContent();

    expect($html)->toContain('/en/articles')
        ->not->toContain('/ur/en')
        ->not->toContain('/ur/ur');
});

it('generates the sitemap with published content only', function () {
    $published = makeArticle();
    $draft = makeArticle(['is_published' => false]);

    expect(Artisan::call('sitemap:generate'))->toBe(0);

    $xml = file_get_contents(public_path('sitemap.xml'));
    expect($xml)->toContain("/en/articles/{$published->slug}")
        ->not->toContain($draft->slug);
});
