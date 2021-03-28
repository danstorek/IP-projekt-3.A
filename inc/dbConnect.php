<?php
define("DB_HOST", 'localhost');
define("DB", 'example_db');
define("DB_USER", 'example_db_username');
define("DB_PASS", 'example_db_password');
define("CHARSET", 'utf8mb4');

function connectDb()
{
    $dsn = "mysql:host=".DB_HOST.";dbname=".DB.";charset=".CHARSET;

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    return new PDO($dsn, DB_USER, DB_PASS, $options);
}