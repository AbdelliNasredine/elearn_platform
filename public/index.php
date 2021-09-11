<?php

session_start();

require '../boot/app.php';
require '../boot/container.php';
require '../routes/api.php';
require '../boot/middlewares.php';
require '../routes/web.php';
require '../routes/admin.php';

$app->run();
