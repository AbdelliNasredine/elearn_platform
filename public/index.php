<?php

session_start();

require '../boot/app.php';
require '../boot/container.php';
require '../boot/middlewares.php';
require '../routes/web.php';
require '../routes/admin.php';
require '../routes/api.php';

$app->run();
