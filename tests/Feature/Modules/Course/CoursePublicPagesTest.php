<?php

it('lists the Course index in every locale', function (string $locale) {
    $this->get("/{$locale}/courses")->assertOk();
})->with(['en', 'ur', 'hi', 'fa', 'ur-roman']);

it('shows a published Course detail page', function () {
    $item = makeCourse();

    $this->get("/en/courses/{$item->slug}")->assertOk();
    $this->get("/ur/courses/{$item->slug}")->assertOk();
});

it('returns 404 for a draft Course', function () {
    $item = makeCourse(['is_published' => false]);

    $this->get("/en/courses/{$item->slug}")->assertNotFound();
});

it('returns 404 for an unknown Course slug', function () {
    $this->get('/en/courses/does-not-exist')->assertNotFound();
});

it('rejects an unsupported locale prefix for Course', function () {
    $this->get('/xx/courses')->assertNotFound();
});
