<?php
require 'config.php';
header('Content-Type: application/json');

$logFile = __DIR__ . '/logs/predictions.log';
if (!file_exists(dirname($logFile))) {
    mkdir(dirname($logFile), 0777, true);
}

try {
    // Fetch latest predictions only
    $stmt = $pdo->query("
        SELECT 
            id,
            home_team,
            away_team,
            match_date,
            league,
            home_prob,
            draw_prob,
            away_prob,
            over25,
            btts,
            score,
            confidence
        FROM predictions
        ORDER BY id DESC
        LIMIT 100
    ");

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $results = [];

    foreach ($rows as $row) {
        // Normalize probabilities
        $home = floatval($row['home_prob']);
        $draw = floatval($row['draw_prob']);
        $away = floatval($row['away_prob']);

        $total = $home + $draw + $away;
        if ($total > 0 && abs($total - 1) > 0.01) {
            $home /= $total;
            $draw /= $total;
            $away /= $total;
        }

        $results[] = [
            "home_team" => $row['home_team'],
            "away_team" => $row['away_team'],
            "match_date" => $row['match_date'],
            "league" => $row['league'] ?: "Unknown",
            "home_prob" => round($home, 2),
            "draw_prob" => round($draw, 2),
            "away_prob" => round($away, 2),
            "over25" => (int)$row['over25'],
            "btts" => (int)$row['btts'],
            "score" => $row['score'] ?: "N/A",
            "confidence" => ucfirst(strtolower($row['confidence'] ?: "Medium"))
        ];
    }

    echo json_encode($results);
} catch (Exception $e) {
    file_put_contents(
        $logFile,
        "[" . date('Y-m-d H:i:s') . "] DB fetch error: " . $e->getMessage() . "\n",
        FILE_APPEND
    );
    echo json_encode([]);
}
