<?php
$rs = new Redis();
$rs->connect('127.0.0.1');
$key = 'set-key';
echo $rs->sAdd($key,111) . PHP_EOL;
echo $rs->sAdd($key,222) . PHP_EOL;
var_dump($rs->sMembers($key)) . PHP_EOL;
echo $rs->sIsMember($key,111) . PHP_EOL;
echo $rs->sIsMember($key,333) . PHP_EOL;
echo $rs->sRem($key,111,222) . PHP_EOL;
$rs->sAdd($key,333);
var_dump($rs->sMembers($key)) . PHP_EOL;
