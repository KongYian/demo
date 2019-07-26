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
$num = '4';
echo intToRoman($num);

function intToRoman($num) {
    if($num < 1 || $num > 3999) {
        return false;
    }
    $arr = [
        1 => 'I',
        4 => 'IV',
        5 => 'V',
        9 => 'IX',
        10 => 'X',
        40 => 'XL',
        50 => 'L',
        90 => 'XC',
        100 => 'C',
        400 => 'CD',
        500 => 'D',
        900 => 'CM',
        1000 => 'M',
    ];
    $s = '';
    if($num < 10) {
        if(array_key_exists($num,$arr)) {
            return $arr[$num];
        } else {
            echo 111;
        }
    }
}













