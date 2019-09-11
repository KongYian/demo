<?php
$rs = new Redis();
$rs->connect('127.0.0.1');
$key = 'hash-key';
$rs->del($key);
$rs->hSet($key,'name','tao');
$rs->hSet($key,'sex','male');
$rs->hSet($key,'age',18);
//var_dump($rs->hGet($key,'name'));
var_dump($rs->hGetAll($key));
//var_dump($rs->hDel($key,'name'));
//var_dump($rs->hGetAll($key));