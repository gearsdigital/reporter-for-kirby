<?php

/** @noinspection PhpUndefinedClassInspection */
require_once __DIR__.'/../../../../kirby/bootstrap.php';
new Kirby([
    'roots' => [
        'content' => __DIR__.'/kirby/content',
        'blueprints' => __DIR__.'/kirby/blueprints',
    ],
]);
