<?php
$rs = new Redis();
$rs->connect('127.0.0.1');
$key = 'list-key';
//echo $rs->rPush($key,1) . PHP_EOL;
//echo $rs->rPush($key,2) . PHP_EOL;
//echo $rs->rPush($key,66) . PHP_EOL; //返回当前列表的长度
//var_dump($rs->lRange($key,0,2)) . PHP_EOL;
//var_dump($rs->lRange($key,0,$rs->lLen($key))) . PHP_EOL;
//var_dump($rs->lRange($key,0,-1)) . PHP_EOL;
//$rs->del($key);
//echo $rs->lPush($key,2);
//echo $rs->lPush($key,22);
echo $rs->rPush($key,2);
echo $rs->rPush($key,22);
var_dump($rs->lRange($key,0,-1));