<?php
$rs = new Redis();
$rs->connect('127.0.0.1');
$key = 'zset-key';
//$rs->zRem($key,'m1',1,2,3);
$rs->zAdd($key,11,'m1');
$rs->zAdd($key,22,'m2');
$rs->zAdd($key,33,'m3');
$rs->zAdd($key,44,'m0');
var_dump($rs->zRange($key,0,-1));