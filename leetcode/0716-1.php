<?php

echo reverse(1534236469) . PHP_EOL;

function reverse($x) {
    if($x == 0) {
        return 0;
    } else if ($x>0) {
        $out = strrev($x);
        echo $out.PHP_EOL;
        if($out > pow(2,31) - 1) {
            return 0;
        }
        return (int)$out;
    } else {
        $out = - strrev($x);
        if($out < - pow(2,31)) {
            return 0;
        }
        return (int)$out;
    }
}