<?php

it('lists the Research index in every locale', function (string $locale) {
    $this->get("/{$locale}/research")->assertOk();
})->with(['en', 'ur', 'hi', 'fa', 'ur-roman']);

it('shows a published Research detail page', function () {
    $item = makeResearch();

    $this->get("/en/research/{$item->slug}")->assertOk();
    $this->get("/ur/research/{$item->slug}")->assertOk();
});

it('returns 404 for a draft Research', function () {
    $item = makeResearch(['is_published' => false]);

    $this->get("/en/research/{$item->slug}")->assertNotFound();
});

it('returns 404 for an unknown Research slug', function () {
    $this->get('/en/research/does-not-exist')->assertNotFound();
});

it('rejects an unsupported locale prefix for Research', function () {
    $this->get('/xx/research')->assertNotFound();
});
