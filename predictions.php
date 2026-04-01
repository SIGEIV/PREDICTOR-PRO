<?php
require "config.php";
header("Content-Type: application/json");

$stmt = $pdo->query("
SELECT *,
CONCAT(home_team,' vs ',away_team) AS match_name
FROM predictions
ORDER BY created_at DESC
LIMIT 50
");

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
