<?php

// Reviewed by AI (Claude); native-speaker review recommended before launch.

return [
    'default' => [
        'created' => 'ne :label ":title" banaya',
        'updated' => 'ne :label ":title" update kiya',
        'deleted' => 'ne :label ":title" delete kiya',
    ],

    'article' => [
        'created' => 'ne article ":title" banaya',
        'updated' => 'ne article ":title" update kiya',
        'published' => 'ne article ":title" publish kiya',
        'deleted' => 'ne article ":title" delete kiya',
    ],
    'category' => [
        'created' => 'ne category ":title" banaya',
        'updated' => 'ne category ":title" update kiya',
        'deleted' => 'ne category ":title" delete kiya',
    ],
    'certificate' => [
        'created' => 'ne ":title" ko certificate issue kiya',
        'updated' => 'ne ":title" ka certificate update kiya',
        'deleted' => 'ne ":title" ka certificate delete kiya',
    ],
    'consultation' => [
        'created' => 'ne consultation request bheji (":title")',
        'updated' => 'ne consultation (":title") update ki',
        'status_answered' => 'ne consultation (":title") ka jawab diya',
        'status_scheduled' => 'ne consultation (":title") schedule ki',
        'status_completed' => 'ne consultation (":title") complete ki',
        'status_cancelled' => 'ne consultation (":title") cancel ki',
        'deleted' => 'ne consultation (":title") delete ki',
    ],
    'contact_message' => [
        'created' => 'ko ":title" se contact message mila',
        'updated' => 'ne ":title" ka contact message update kiya',
        'deleted' => 'ne ":title" ka contact message delete kiya',
    ],
    'course' => [
        'created' => 'ne course ":title" banaya',
        'updated' => 'ne course ":title" update kiya',
        'published' => 'ne course ":title" publish kiya',
        'deleted' => 'ne course ":title" delete kiya',
    ],
    'director' => [
        'created' => 'ne director ":title" add kiya',
        'updated' => 'ne director ":title" update kiya',
        'deleted' => 'ne director ":title" remove kiya',
    ],
    'research_paper' => [
        'created' => 'ne research paper ":title" banaya',
        'updated' => 'ne research paper ":title" update kiya',
        'published' => 'ne research paper ":title" publish kiya',
        'deleted' => 'ne research paper ":title" delete kiya',
    ],
    'resource' => [
        'created' => 'ne resource ":title" banaya',
        'updated' => 'ne resource ":title" update kiya',
        'published' => 'ne resource ":title" publish kiya',
        'deleted' => 'ne resource ":title" delete kiya',
    ],

    'tag' => [
        'created' => 'ne tag ":title" banaya',
        'updated' => 'ne tag ":title" update kiya',
        'deleted' => 'ne tag ":title" delete kiya',
    ],
    'course_lesson' => [
        'created' => 'ne lesson ":title" ko :course mein add kiya',
        'updated' => 'ne lesson ":title" (:course) update kiya',
        'deleted' => 'ne lesson ":title" ko :course se hataya',
    ],
    'enrollment' => [
        'created' => ':title ne :course mein enroll kiya',
        'updated' => 'ne :title ki :course mein enrollment update ki',
        'status_completed' => ':title ne course :course complete kiya',
        'deleted' => 'ne :title ki :course mein enrollment hatayi',
    ],
    'lesson_progress' => [
        'created' => ':course mein lesson ":title" shuru kiya',
        'completed' => ':course mein lesson ":title" complete kiya',
        'updated' => 'lesson ":title" (:course) ki progress update ki',
        'deleted' => 'lesson ":title" (:course) ki progress hatayi',
    ],
    'newsletter_subscriber' => [
        'created' => ':title ne newsletter subscribe kiya',
        'confirmed' => ':title ne newsletter subscription confirm ki',
        'unsubscribed' => ':title ne newsletter se unsubscribe kiya',
        'updated' => 'ne :title ki newsletter subscription update ki',
        'deleted' => 'ne newsletter subscriber :title ko hataya',
    ],
    'setting' => [
        'created' => 'ne setting ":title" add ki',
        'updated' => 'ne setting ":title" update ki',
        'deleted' => 'ne setting ":title" hatayi',
    ],
    'user' => [
        'created' => 'ne :title ka account banaya',
        'updated' => 'ne :title ka account update kiya',
        'deleted' => 'ne :title ka account hataya',
    ],
];
