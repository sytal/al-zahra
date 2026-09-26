<?php

it('redirects guests from the admin panel to the admin login', function () {
    $this->get('/admin')->assertRedirect();
});

it('forbids a student from the admin panel', function () {
    $this->actingAs(userWithRole('student'))->get('/admin')->assertForbidden();
});

it('allows an admin into the admin panel', function () {
    $this->actingAs(userWithRole('admin'))->get('/admin')->assertOk();
});

it('allows the director into the admin panel', function () {
    $this->actingAs(userWithRole('director'))->get('/admin')->assertOk();
});
