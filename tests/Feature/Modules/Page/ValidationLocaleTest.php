<?php

use Illuminate\Support\Facades\Validator;

afterEach(fn () => app()->setLocale('en'));

it('localizes validation messages and attribute labels', function (string $locale) {
    app()->setLocale($locale);

    $errors = Validator::make(
        ['guest_email' => 'nope', 'message' => 'ab'],
        ['guest_name' => 'required', 'guest_email' => 'email', 'message' => 'min:5'],
    )->errors();

    $english = 'The guest name field is required.';
    $label = trans('validation.attributes.guest_name', [], $locale);

    expect($errors->first('guest_name'))->toContain($label)
        ->and($errors->first('guest_email'))->toContain(trans('validation.attributes.guest_email', [], $locale))
        ->and($errors->first('message'))->toContain('5')
        ->and($errors->first('guest_name'))->not->toContain('guest name');

    if ($locale !== 'en') {
        expect($errors->first('guest_name'))->not->toBe($english)
            ->and($errors->first('guest_email'))->not->toStartWith('The ')
            ->and($errors->first('message'))->not->toStartWith('The ');
    }
})->with(['en', 'ur', 'hi', 'fa', 'ur-roman']);

it('ships the same validation keys in every locale', function (string $locale) {
    $en = require lang_path('en/validation.php');
    $other = require lang_path("{$locale}/validation.php");

    expect(array_keys($other))->toBe(array_keys($en));
    foreach (['auth', 'passwords', 'pagination'] as $file) {
        expect(array_keys(require lang_path("{$locale}/{$file}.php")))
            ->toBe(array_keys(require lang_path("en/{$file}.php")));
    }
})->with(['ur', 'hi', 'fa', 'ur-roman']);
