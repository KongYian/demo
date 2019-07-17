<?php
test();

function test()
{
    try{
        $key = 'shoe5_';
        $handler = new MiaoSha($key);
        $handler->setStock(10);
        $user = rand(1,10000);
        $ip = $user;
        $handler->checkIp($ip);
        $handler->checkUser($user);
        $handler->checkStock($user,$ip);
    } catch (\Exception $e) {
        echo $e->getMessage() . PHP_EOL;
        error_log('fail' . $e->getMessage() .PHP_EOL,3,'/var/www/html/demo/log/debug.log');
    }
}

function testStock()
{
    try{
        $key = 'computer_';
        $handler = new MiaoSha($key);
        $handler->setStock(10);

        for($i = 10;$i < 40 ;$i ++ ){
            $ip = '127.2.0.' . $i;
            $handler->checkIp($ip);
            $handler->checkUser($i);
            $handler->checkStock($i,$ip);
        }

    } catch (\Exception $e) {
        echo $e->getMessage() . PHP_EOL;
    }
}



class MiaoSha{

    const MSG_REPEAT_USER = '请勿重复参与';
    const MSG_EMPTY_STOCK = '库存不足';
    const MSG_KEY_NOT_EXIST = 'key不存在';

    const IP_POOL = 'ip_pool';
    const USER_POOL = 'user_pool';

    /** @var Redis  */
    public $redis;
    public $key;

    public function __construct($key = '')
    {
        $this->checkKey($key);
        $this->redis = new Redis(); //todo  连接池
        $this->redis->connect('127.0.0.1');
    }

    public function checkKey($key = '')
    {
        if(!$key) {
            throw new Exception(self::MSG_KEY_NOT_EXIST);
        } else {
            $this->key = $key;
        }
    }

    public function setStock($value = 0)
    {
        if($this->redis->exists($this->key) == 0) {
            $this->redis->set($this->key,$value);
        }
    }

    public function checkIp($ip = 0)
    {
        $sKey = $this->key . self::IP_POOL;
        if(!$ip || $this->redis->sIsMember($sKey,$ip)) {
            throw new Exception(self::MSG_REPEAT_USER);
        }
    }

    public function checkUser($user = 0)
    {
        $sKey = $this->key . self::USER_POOL;
        if(!$user || $this->redis->sIsMember($sKey,$user)) {
            throw new Exception(self::MSG_REPEAT_USER);
        }
    }

    public function checkStock($user = 0, $ip = 0)
    {
        $num = $this->redis->decr($this->key);
        if($num < 0 ) {
            throw new Exception(self::MSG_EMPTY_STOCK);
        } else {
            $this->redis->sAdd($this->key . self::USER_POOL, $user);
            $this->redis->sAdd($this->key . self::IP_POOL, $ip);
            //todo add to mysql
            echo 'success' . PHP_EOL;
            error_log('success' . $user . PHP_EOL,3,'/var/www/html/demo/log/debug.log');
        }
    }

    /**
     * @note:此种做法不能防止并发
     * @func checkStockFail
     * @param int $user
     * @param int $ip
     * @throws Exception
     */
    public function checkStockFail($user = 0,$ip = 0) {
        $num = $this->redis->get($this->key);
        if($num > 0 ){
            $this->redis->sAdd($this->key . self::USER_POOL, $user);
            $this->redis->sAdd($this->key . self::IP_POOL, $ip);
            //todo add to mysql
            echo 'success' . PHP_EOL;
            error_log('success' . $user . PHP_EOL,3,'/var/www/html/demo/log/debug.log');
            $num--;
            $this->redis->set($this->key,$num);
        } else {
            throw new Exception(self::MSG_EMPTY_STOCK);
        }
    }
}