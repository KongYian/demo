<?php
$rs = new Redis();
$rs->connect('127.0.0.1');
$key = 'string-key';
echo $rs->set($key,123).PHP_EOL;
echo $rs->get($key).PHP_EOL;
echo $rs->del($key).PHP_EOL;