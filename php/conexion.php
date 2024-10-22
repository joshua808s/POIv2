<?php
$host = 'bljtwn9jd6tis9ai2gto-mysql.services.clever-cloud.com';
$db   = 'bljtwn9jd6tis9ai2gto';
$user = 'uddb9vac8jhigopl';
$pass = 'gGvObRoRybEJh9syxUmx';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>