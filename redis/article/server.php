<?php

run(6);

function run($mode = 0)
{
    $rs = new Redis();
    $rs->connect('127.0.0.1');

    switch ($mode) {
        case 0 :
            $rs->flushAll();break; //清空缓存
        case 1 :
            post_handler($rs);break;
        case 2 :
        //投票
            try {
                echo vote_article($rs,1,2);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
            break;
        case 3 :
            //获取文章
            var_dump(get_articles($rs,'score:' ));
        case 4 :
            $add = [
                'article:2',
                'article:3',
            ];
            add_group($rs, 'php', $add);
            break;
        case 5 :
            init_group_combine($rs,'score:php');
        case 6 :
            get_group_articles($rs,'score:php');
    }
}





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

function get_articles(Redis $rs, $zkey, $page = 1)
{
    $limit = 10;
    $start = ($page - 1) * $limit;
    $end = $start + $limit - 1;
    $keys = $rs->zRevRange($zkey, $start, $end); //获取多个文章ID
    $articles = [];
    foreach ($keys as $key) {
        $article = $rs->hGetAll($key);
        $articles[] = $article;
    }
    return $articles;
}

function add_group(Redis $rs, $group = '', $add = [])
{
    foreach ($add as $item) {
        $rs->sAdd('group:' . $group, $item);
    }
}

function init_group_combine(Redis $rs, $zkey)
{
    $rs->zInter($zkey,['group:php','score:']);
    return $rs->zRevRange($zkey,0,-1,true);
}

function get_group_articles(Redis $rs, $zkey)
{
    if ($rs->exists($zkey)) {
        var_dump(get_articles($rs,$zkey));
    } else {
        $rs->set($zkey,json_encode(init_group_combine($rs,$zkey)),60);
    }
}

















