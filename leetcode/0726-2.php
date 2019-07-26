<?php

/**
 *
I             1
V             5
X             10
L             50
C             100
D             500
M             1000

I 可以放在 V (5) 和 X (10) 的左边，来表示 4 和 9。
X 可以放在 L (50) 和 C (100) 的左边，来表示 40 和 90。 
C 可以放在 D (500) 和 M (1000) 的左边，来表示 400 和 900。

 */
$num = '1994';
echo intToRoman($num);

function intToRoman($num) {
    if($num > 1 || $num > 3999) {
        return false;
    }
    $arr = [
        1 => 'I',
        4 => 'IV',
        5 => 'V',
        9 => 'IX',
        10 => 'X',
        40 => 'XL',
        40 => 'XL',
        'XC' => 90,
        'CD' => 400,
        'CM' => 900,
        'L' => 50,
        'C' => 100,
        'D' => 500,
        'M' => 1000,
    ];
    $a =array_reverse($arr);
    var_dump($a);
    if($num < 10) {

    }
}













