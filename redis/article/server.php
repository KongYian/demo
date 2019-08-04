<?php
$rs = new Redis();
$rs->connect('127.0.0.1');

//$rs->flushAll();exit; //清空缓存
//post_handler($rs);exit; //新增文章

//投票
try {
    echo vote_article($rs,1,2);
} catch (\Exception $e) {
    echo $e->getMessage();
}
exit;


function post_handler($rs)
{
    $articles = [
        ['title' => 'aaa', 'link' => 'www.blueyian.top', 'poster' => '1'],
        ['title' => 'bbb', 'link' => 'www.blueyian.top', 'poster' => '1'],
        ['title' => 'ccc', 'link' => 'www.blueyian.top', 'poster' => '1'],
    ];
    foreach ($articles as $article) {
        post_article($rs,$article['title'],$article['link'],$article['poster']);
        sleep(1);
    }
}

function post_article(Redis $rs, $title = '', $link = '', $poster = '1')
{
    //todo 事务提交
    $id = $rs->incr('article:');

    $now = time();
    //新增一篇文章
    $article = 'article:' . $id;
    $rs->hSet($article,'title', $title);
    $rs->hSet($article,'link', $link);
    $rs->hSet($article,'poster', $poster);
    $rs->hSet($article,'time', $now);
    $rs->hSet($article,'votes',0);

    //将文章发布者记录到一个已经投过票的集合中，将这个投票名单的有效时间为一周
    $voted = 'voted:' . $id;
    $rs->sAdd($voted, $poster);
    $rs->expire($voted,7 * 24 * 24 * 3600);

    //将文章添加到根据发布时间排序的有序集合和根据评分排序的有序集合中
    $rs->zAdd('score:', $now + 10, $article);
    $rs->zAdd('time:', $now, $article);
}

function vote_article(Redis $rs, $articleId = '', $user = '')
{
    $article = 'article:' . $articleId;
    $postTime = $rs->zScore('time:', $article);
    if(time() - $postTime > 7 * 24 * 3600) {
        throw new \Exception('投票活动已经过期');
    }
    if($rs->sIsMember('voted:' . $articleId, $user)) {
        throw new \Exception('你已经投过票了');
    } else {
        $rs->sAdd('voted:' . $articleId, $user);
    }
    $rs->zIncrBy('score:', 10 , $article);
    $rs->hIncrBy($article, 'votes', 1);
    return 'vote success';
}