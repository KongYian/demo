<?php
$rs = new Redis();
$rs->connect('127.0.0.1');
$rs->set('hello',111);
echo $rs->get('hello');