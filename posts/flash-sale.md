### 1 说明
前段时间面试的时候，一直被问到如何设计一个秒杀活动，但是无奈没有此方面的实际经验，所以只好凭着自己的理解和一些资料去设计这么一个程序
主要利用到了redis的string和set,string主要是利用它的k-v结构去对库存进行处理，也可以用list的数据结构来处理商品的库存，set则用来确保用户进行重复的提交
其中我们最主要解决的问题是
-防止并发产生超抢/超卖
### 2 流程设计
![](https://puui.qpic.cn/fans_admin/0/3_823781639_1563266176473/0)
### 3 代码
#### 3.1 服务端代码
```php
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
```
#### 3.2 客户端测试代码
```php
function test()
{
    try{
        $key = 'cup_';
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

function test2()
{
    try{
        $key = 'cup_';
        $handler = new MiaoSha($key);
        $handler->setStock(10);
        $user = rand(1,10000);
        $ip = $user;
        $handler->checkIp($ip);
        $handler->checkUser($user);
        $handler->checkStockFail($user,$ip); //不能防止并发的
    } catch (\Exception $e) {
        echo $e->getMessage() . PHP_EOL;
        error_log('fail' . $e->getMessage() .PHP_EOL,3,'/var/www/html/demo/log/debug.log');
    }
}
```
### 4 测试
测试环境说明
- ubantu16.04
- redis2.8.4
- php5.5
在服务端代码里面我们有两个函数分别是checkStock和checkStockFail,其中checkStockFail不能在高并发的情况下效果很差，不能在redis层面保证库存为0的时候终止操作。
我们利用ab工具进行测试
其中`www.hello.com` 是配置的虚拟主机名称 `flash-sale.php`是我们脚本的名称
```
 #第1种情况 500并发下 用客户端的test2()去执行
 ab -n 500 -c 100 www.hello.com/flash-sale.php 
```
log日志的记录结果:
![](https://puui.qpic.cn/fans_admin/0/3_823781639_1563266176340/0)
```
 #第2种情况 5000并发下 用客户端的test2()去执行
 ab -n 5000 -c 1000 www.hello.com/flash-sale.php 
```
log日志的记录结果:
![](https://puui.qpic.cn/fans_admin/0/3_15881579_1563266176459/0)
```
 #第3种情况 500并发下 用客户端的test()去执行
 ab -n 500 -c 100 www.hello.com/flash-sale.php 
```
log日志的记录结果:
![](https://puui.qpic.cn/fans_admin/0/3_15881579_1563266176864/0)
```
 #第4种情况 5000并发下 用客户端的test()去执行
 ab -n 5000 -c 1000 www.hello.com/flash-sale.php 
```
 log日志的记录结果:
![](https://puui.qpic.cn/fans_admin/0/3_823781639_1563266284176/0)
### 5 总结
我们从日志中可以很明显的看出第3、4中情况下，可以保证商品的数量总是我们设置的库存值10，但是在情况1、2下，则产生了超卖的现象
redis来控制并发主要是利用了其api都是原子性操作的优势，从checkStock和checkStockFail中可以看出，一个是直接decr对库存进行减一操作，所以不存在并发的情况，但是另一个方法是将库存值先取出做减一操作然后再重新赋值，这样的话，在并发下，多个进程会读取到多个库存为1的值，因此会产生超卖的情况