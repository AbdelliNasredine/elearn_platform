<?php

/**
 * GLOBAL MIDDLEWARES
 */

use App\Middlewares\LanguageMiddleware;

// CSRF Guard
$app->add($app->getContainer()->get("csrf"));

// Language
$app->add(new LanguageMiddleware($app->getContainer()));