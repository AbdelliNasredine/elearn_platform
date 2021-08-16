<?php

use Dotenv\Dotenv;

/**
 * LOADING ENV VARIABLES
 */

$dotenv = Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();

return [
    "settings" => [
        "displayErrorDetails" => $_ENV["APP_ENV"] == "development",
    ],
    "db" => [
        "driver" => $_ENV["DB_DRIVER"],
        "port" => $_ENV["DB_PORT"],
        "host" => $_ENV["DB_HOST"],
        "database" => $_ENV["DB_NAME"],
        "username" => $_ENV["DB_USERNAME"],
        "password" => $_ENV["DB_PASSWORD"],
        "charset" => $_ENV["DB_CHARSET"],
        "collation" => $_ENV["DB_COLLATION"],
    ],
    "twig" => [
        "path" => __DIR__ . "/../resources/views",
        "options" => [
            "cache" => false,
        ],
    ],
	"lang" => [
		"default" => "en",
		"available" => ["ar", "en", "fr"],
	],
	"translations" => [
		"path" => __DIR__ . "/../resources/i18n",
	]
];
