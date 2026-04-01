

<?php
$host = "localhost";
$db   = "predictor_db";
$user = "phpmyadmin";
$pass = "your_password";
$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}

// OpenAI API Key
define('OPENAI_API_KEY', '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'); // <-- ADD your key here