<?php

$rs = new Redis();
$rs->connect('127.0.0.1');
$key = 'yyy';
//$ruleKey[] = 'YU';
//$ruleKey[] = '25';
//$ruleKey[] = '26';
//$ruleKey[] = '27';
//$ruleKey[] = '28';
//$ruleKey[] = '29';
//var_dump($ruleKey);

//$ruleKey[] = $ruleKey;
$ruleKey = [
    'x',23,'y',24
];
$rs->hMSet($key, ...$ruleKey);
$a =  $rs->hMGet($key, [0,1]);
var_dump($a);