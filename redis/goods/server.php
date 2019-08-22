<?php
/**
 * 实现一个用户将自己的商品按照给定的价格放到市场上销售
 * 当另一个用户购买时，卖家就会收到钱
 */

Class Base
{
    const USER_1 = '1';
    const USER_2 = '2';
    const USER_3 = '3';
    const USER_4 = '4';
    const USER_5 = '5';

    const INVENTORY_KEY = 'inventory:';
    const USER_KEY = 'user:';
    const MARKET_KEY = 'market';

    public $redis = null;

    public function __construct()
    {
        $this->redis = new Redis();
        $this->redis->connect('127.0.0.1');
    }

    public function clear()
    {
        $this->redis->flushAll();
    }
}

class User extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @note: 定义两个用户的hash 记录姓名和拥有的金额
     *        定义两个包裹的set 记录了用户的商品编号
     * @date:2019-08
     * @func init
     */
    public function init()
    {
        $this->redis->hMSet(self::USER_KEY . self::USER_1, ['name' => 'blue','funds' => 100]);
        $this->redis->hMSet(self::USER_KEY . self::USER_2, ['name' => 'blue','funds' => 200]);
        $this->redis->sAdd(self::INVENTORY_KEY . self::USER_1, 'ItemA','ItemB','ItemC');
        $this->redis->sAdd(self::INVENTORY_KEY . self::USER_2, 'ItemO','ItemP','ItemQ');
    }

    public function userInfo($userId = 0, $hashKey = '')
    {
        return $this->redis->hGet(self::USER_KEY . $userId, $hashKey);
    }
}

Class Market extends Base
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @note: 初始化市场里面的一些用户的商品
     * @date:2019-08
     * @func init
     */
    public function init()
    {
        $this->redis->zAdd(self::MARKET_KEY, 10, 'ItemR.' . self::USER_3);
        $this->redis->zAdd(self::MARKET_KEY, 20, 'ItemS.' . self::USER_4);
        $this->redis->zAdd(self::MARKET_KEY, 30, 'ItemT.' . self::USER_5);
    }

    /**
     * @note: 上架商品
     * @date:2019-08
     * @func addGoods
     * @param int $userId
     * @param string $item
     * @param int $price
     * @return bool
     */
    public function addGoods($userId = 0, $item = '', $price = 0)
    {
        $end = time() + 5;
        while (time() < $end) {
            try {
                $this->redis->watch(self::INVENTORY_KEY . $userId); //监视用户的包裹信息
                if(!$this->redis->sIsMember(self::INVENTORY_KEY . $userId, $item)) { //判断提交的商品是否存在于用户的包裹之中
                    $this->redis->unwatch();
                    return true;
                }
                $this->redis->multi();
                $this->redis->zAdd(self::MARKET_KEY, $price, $item . '.' . $userId);
                $this->redis->sRem(self::INVENTORY_KEY . $userId, $item);
                $this->redis->exec();
            } catch (RedisException $e) {
                echo $e->getFile().$e->getLine().$e->getMessage().PHP_EOL;
            }
        }
    }

    public function bugGoods($buyerId = 0, $sellerId , $item = '' , $lprice)
    {
        $end = time() + 5;
        while (time() < $end) {
            try {
                //监视市场和用户的key
                $this->redis->watch([self::USER_KEY . $buyerId, self::MARKET_KEY]);
                //用户的账户余额
                $funds = $this->redis->hGet(self::USER_KEY . $buyerId,'funds');
                //商品的价格
                $price = $this->redis->zScore(self::MARKET_KEY,$item . '.' . $sellerId);
                if($lprice != $price) {
                    $this->redis->unwatch();
                    return true;
                }
                if($funds < $price) {
                    $this->redis->unwatch();
                    return true;
                }
                $this->redis->multi();
                $this->redis->hIncrBy(self::USER_KEY . $sellerId,'funds', (int)$price); //卖方钱包加钱
                $this->redis->hIncrBy(self::USER_KEY . $buyerId,'funds', (int)(-$price)); //买方钱包减钱
                $this->redis->sAdd(self::INVENTORY_KEY . $buyerId, $item);//买房包裹增加物品
                $this->redis->zRem(self::MARKET_KEY, $item . '.'. $sellerId); //物品从市场下架
                $this->redis->exec();
            } catch (RedisException $e) {
                echo $e->getFile().$e->getLine().$e->getMessage().PHP_EOL;
            }
        }
    }

    public function listGoods()
    {
        return $this->redis->zRange(self::MARKET_KEY, 0 , -1,true);
    }
}

Class Client
{
    public function init()
    {
        $user = new User();
        $user->init();
        $market = new Market();
        $market->init();
    }

    public function addGoods($userId,$item,$price)
    {
        $model = new Market();
        $model->addGoods($userId,$item,$price);
    }

    public function buyGoods($buyerId = 0, $sellerId , $item = '' , $lprice)
    {
        $model = new Market();
        $model->bugGoods($buyerId,$sellerId,$item,$lprice);
    }

    public function listGoods()
    {
        $model = new Market();
        $res = $model->listGoods();
        var_dump($res);
    }

    public function userInfo($userId, $hashKey)
    {
        $model = new User();
        $res = $model->userInfo($userId, $hashKey);
        var_dump($res);
    }

    public function clear()
    {
        $model = new Base();
        $model->clear();
    }
}

$clent = new Client();
//$clent->init();
//$clent->addGoods('1', 'ItemA', 50);
//$clent->listGoods();
//$clent->clear();
$clent->buyGoods(2,1,'ItemA',50);
$clent->listGoods();
$clent->userInfo(1,'funds');
$clent->userInfo(2,'funds');
