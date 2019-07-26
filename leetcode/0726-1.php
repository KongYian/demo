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
$s = 'MCMXCIV';
echo romanToInt($s);

function romanToInt($s) {
    $arr = [
        'IV' => 4,
        'IX' => 9,
        'XL' => 40,
        'XC' => 90,
        'CD' => 400,
        'CM' => 900,
        'I' => 1,
        'V' => 5,
        'X' => 10,
        'L' => 50,
        'C' => 100,
        'D' => 500,
        'M' => 1000,
    ];
    $len = strlen($s);
    $num = 0;
    for($i = 0; $i < $len ;$i++) {
        $char = substr($s,$i,1);
        if(isset($arr[$char])) {
            if($char == 'I' || $char == 'X' || $char == 'C') {
                if($i + 1 < $len) {
                    $charcomb = $char . substr($s,$i+1,1);
                    if($charcomb == 'IV' ||
                    $charcomb == 'IX' ||
                    $charcomb == 'XL' ||
                    $charcomb == 'XC' ||
                    $charcomb == 'CD' ||
                    $charcomb == 'CM'
                    ) {
                        $i++;
                        $char = $charcomb;
                    }
                }
            }
            $num += $arr[$char];
        }
    }
    return $num;
}













