<?php

function e($html) {
    return htmlspecialchars($html, ENT_QUOTES, 'UTF-8', true);
}

// Needs two timestamps ... @strtotime(...);
function echoDate( $start, $end ){
    $current = $start;
    $ret = array();
    while( $current<$end ){
        $next = @date('Y-M-01', $current) . "+1 month";
        $current = @strtotime($next);
        $ret[] = date('M Y', $current);
    }
    array_unshift($ret, date('M Y', $start));
    array_pop($ret);
    return $ret;
}
