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

$num = 320;
echo $num % 100;
exit;
$num = '96';
echo intToRoman($num);

function intToRoman($num) {
    if($num < 1 || $num > 3999) {
        return false;
    }
    $arr = [
        1 => 'I',
        2 => 'II',
        3 => 'III',
        4 => 'IV',
        5 => 'V',
        6 => 'VI',
        7 => 'VII',
        8 => 'VIII',
        9 => 'IX',
        10 => 'X',
        20 => 'XX',
        30 => 'XXX',
        40 => 'XL',
        50 => 'L',
        60 => 'LX',
        70 => 'LXX',
        80 => 'LXX',
        90 => 'XC',
        100 => 'C',
        200 => 'CC',
        300 => 'CCC',
        400 => 'CD',
        500 => 'D',
        600 => 'DD',
        700 => 'DDD',
        800 => 'DDD',
        900 => 'CM',
        1000 => 'M',
    ];
    $s = '';
    if($num <= 10) {
        return $arr[$num];
    }
    if($num <= 100) {
        if(array_key_exists($num,$arr)) {
            return $arr[$num];
        } else {
            $first = $num % 10 ;
            $second = $num - $first;
            if(array_key_exists($second,$arr)) {
                return $arr[$second] . $arr[$first];
            }
        }
    }
    if($num <= 1000) {
        if(array_key_exists($num,$arr)) {
            return $arr[$num];
        } else {
            $first = $num % 100 ;
            $second = ($num - $first) % 100;


        }
    }
}













