<?php

session_start();

require '../boot/app.php';
require '../boot/container.php';
require '../routes/web.php';

$app->run();
