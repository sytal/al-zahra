<?php

use App\Modules\Course\Models\Course;

it('renders full seo on the courses list', function (string $locale) {
    $html = $this->get("/{$locale}/courses?page=1&search=x")->assertOk()->getContent();

    expect($html)
        ->toContain('<meta name="description"')
        ->toContain('<link rel="canonical" href="'.url("/{$locale}/courses").'">')
        ->toContain('property="og:title"')
        ->toContain('hreflang="ur-roman"')
        ->toContain('"@type":"CollectionPage"')
        ->toContain('"@type":"ItemList"');
})->with(['en', 'ur']);

it('keeps course json-ld on the detail page', function () {
    $course = Course::where('is_published', true)->first();
    if (! $course) {
        $this->markTestSkipped('no published course');
    }

    $this->get("/en/courses/{$course->slug}")->assertOk()->assertSee('"@type":"Course"', false);
});
