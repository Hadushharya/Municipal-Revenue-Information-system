<?php
// ethiopian_date_converter.php

function gregorian_to_ethiopian_string($year, $month, $day) {
    // This is a simplified PHP port of a known conversion algorithm.
    $jdn = gregoriantojd($month, $day, $year);
    if ($jdn === false) {
        return false;
    }

    $r = ($jdn - 1723856) % 1461;
    $n = ($r % 365) + 365 * floor($r / 1460);
    
    $eth_year = 4 * floor(($jdn - 1723856) / 1461) + floor($r / 365) - floor($r / 1460);
    $eth_month = floor($n / 30) + 1;
    $eth_day = ($n % 30) + 1;
    
    return "{$eth_day}/{$eth_month}/{$eth_year}";
}
?>