<?php
function timeAgo($datetime) {
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;

    if ($diff < 60) {
        return "il y a $diff seconde" . ($diff > 1 ? "s" : "");
    } elseif ($diff < 3600) {
        $minutes = floor($diff / 60);
        return "il y a $minutes minute" . ($minutes > 1 ? "s" : "");
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return "il y a $hours heure" . ($hours > 1 ? "s" : "");
    } else {
        $days = floor($diff / 86400);
        return "il y a $days jour" . ($days > 1 ? "s" : "");
    }
}
