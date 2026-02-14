<?php
date_default_timezone_set('Asia/Jakarta');
echo "Current time (WIB): " . date('H:i:s') . "\n\n";

// Test: Snack close at 22:30
$closeTime = '22:30:00';
$nowTime = date('H:i:s');
$openTime = '10:00:00';
$closeHour = 22;

echo "=== Snack (close: 22:30) ===\n";
if ($closeHour >= 10) {
    $closed = ($nowTime >= $closeTime || $nowTime < $openTime);
} else {
    $closed = ($nowTime >= $closeTime && $nowTime < $openTime);
}
echo "Status: " . ($closed ? "🚫 CLOSED" : "✅ OPEN") . "\n\n";

// Test: Kopi close at 02:00
$closeTime2 = '02:00:00';
$closeHour2 = 2;

echo "=== Kopi (close: 02:00) ===\n";
if ($closeHour2 >= 10) {
    $closed2 = ($nowTime >= $closeTime2 || $nowTime < $openTime);
} else {
    $closed2 = ($nowTime >= $closeTime2 && $nowTime < $openTime);
}
echo "Status: " . ($closed2 ? "🚫 CLOSED" : "✅ OPEN") . "\n";
