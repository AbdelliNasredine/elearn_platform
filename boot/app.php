<?php

require __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/config.php';
$app = new Slim\App($config);
