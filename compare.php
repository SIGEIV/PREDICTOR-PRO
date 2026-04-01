<?php
$edge = $ai_home_prob - impliedProbability($book_home_odds);

if ($edge >= 8) {
    $valueBet = "YES";
} else {
    $valueBet = "NO";
}
