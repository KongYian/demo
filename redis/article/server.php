<?php
$rs = new Redis();
$rs->connect('127.0.0.1');
$key = 'acticle:110';
$rs->hSet($key,'title','hello-world');
$rs->hSet($key,'link','blog.blueyian.top');
$rs->hSet($key,'poster','blue');
$rs->hSet($key,'time',time());
$rs->hSet($key,'votes',0);