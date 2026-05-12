<?php
declare(strict_types=1);

function get_db_connection(): mysqli
{
    static $connection = null;

    if ($connection instanceof mysqli) {
        return $connection;
    }

    $host = '127.0.0.1';
    $username = 'root';
    $password = '';
    $database = 'paper02a_db';

    $connection = new mysqli($host, $username, $password, $database);

    if ($connection->connect_error) {
        throw new RuntimeException('Database connection failed: ' . $connection->connect_error);
    }

    $connection->set_charset('utf8mb4');

    return $connection;
}
