<?php

it('lists the Resource index in every locale', function (string $locale) {
    $this->get("/{$locale}/resources")->assertOk();
})->with(['en', 'ur', 'hi', 'fa', 'ur-roman']);

it('shows a published Resource detail page', function () {
    $item = makeResource();

    $this->get("/en/resources/{$item->slug}")->assertOk();
    $this->get("/ur/resources/{$item->slug}")->assertOk();
});

it('returns 404 for a draft Resource', function () {
    $item = makeResource(['is_published' => false]);

    $this->get("/en/resources/{$item->slug}")->assertNotFound();
});

it('returns 404 for an unknown Resource slug', function () {
    $this->get('/en/resources/does-not-exist')->assertNotFound();
});

it('rejects an unsupported locale prefix for Resource', function () {
    $this->get('/xx/resources')->assertNotFound();
});
