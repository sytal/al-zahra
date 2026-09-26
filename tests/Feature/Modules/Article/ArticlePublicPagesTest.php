<?php

it('lists the Article index in every locale', function (string $locale) {
    $this->get("/{$locale}/articles")->assertOk();
})->with(['en', 'ur', 'hi', 'fa', 'ur-roman']);

it('shows a published Article detail page', function () {
    $item = makeArticle();

    $this->get("/en/articles/{$item->slug}")->assertOk();
    $this->get("/ur/articles/{$item->slug}")->assertOk();
});

it('returns 404 for a draft Article', function () {
    $item = makeArticle(['is_published' => false]);

    $this->get("/en/articles/{$item->slug}")->assertNotFound();
});

it('returns 404 for an unknown Article slug', function () {
    $this->get('/en/articles/does-not-exist')->assertNotFound();
});

it('rejects an unsupported locale prefix for Article', function () {
    $this->get('/xx/articles')->assertNotFound();
});
