<?php
$rs = new Redis();
$rs->connect('127.0.0.1');
$key = 'string-key';
//echo $rs->set($key,123).PHP_EOL;
//echo $rs->get($key).PHP_EOL;
//echo $rs->del($key).PHP_EOL;
//echo $rs->incr($key);
//echo $rs->set($key,'helloworld').PHP_EOL;
//echo $rs->getRange($key,0,4).PHP_EOL;
//echo $rs->setRange($key,5,'taotao').PHP_EOL;
//echo $rs->get($key) .PHP_EOL;

//echo $rs->append('hi','xxxxxx') . PHP_EOL;

 echo $rs->set('key', "*"). PHP_EOL;     // ord("*") = 42 = 0x2f = "0010 1010"
 echo $rs->setBit('key', 5, 1). PHP_EOL; // returns 0
 echo $rs->setBit('key', 7, 1). PHP_EOL; // returns 0
 echo $rs->get('key'). PHP_EOL;          // chr(0x2f) = "/" = b("0010 1111")
