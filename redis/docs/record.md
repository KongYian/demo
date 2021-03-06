## 1、使用redis的理由
### 与memcache比较
membcached通过黑名单的方式隐藏列表中的元素，从而避免对元素执行读取、更新、写入等操作，而redis的List和Set允许用户直接添加或者删除元素
### 与关系型和硬盘存储数据库比较
- 可以直接使用原子的INCR命令及其变种来计算聚合数据，避免更新引起一次随机读和随机写的可能
- 数据存储在内存之中，发送给redis的命令请求不需要经过查询分析器和查询优化器处理，因此随机写的速度非常迅速
- 避免写入临时数据和对临时数据进行扫描或者删除的麻烦

## 2、数据结构
- 字符串(STRING) 
- 列表(LIST)
- 集合(SET)
- 有序集合(ZSET)
- 散列(HASH)


### 2.1 字符串
- 可存储值的类型
    - 字符串
    - 整数
    - 浮点数
- 可以对整数和浮点数进行'自增自减'操作
    - incr
    - decr
    - incrby
    - decrby
    - incrbyfloat 


## 4 持久化选项

###4.1 快照持久化
`SAVE`
`BGSAVE`
BGSAVE创建子进程处理，会导致系统停顿
SAVE会一直阻塞Redis直到快照生成完毕，但是不需要创建子进程，比BGSAVE要快
数据快照会有滞后性，可能会丢失部分数据

在redis.conf中配置了`SAVE 900 1`  如果服务器距离上次成功生成快照已经超过了900秒，并且在此期间至少一次写入操作，那么Redis就会自动开始一次新的BGSAVE操作

###4.2 AOF持久化

AOF持久化会将被执行的写命令写到AOF文件的末尾，以此来记录数据发生的变化
配置redis.conf文件中的`appendonly yes`开启，默认是关闭的

参数`appendfsync`选项
`always` 将每个Redis的写命令都同步写入磁盘，会严重降低Redis的速度
`everysec` 每秒执行一次同步，显式的将多个写命令同步到磁盘，【默认方式】
`no` 让OS决定应该何时执行同步

AOF文件的大小是AOF持久化的缺陷之一

###4.3 性能测试
`redis-benchmark -c 1 -q` 查询redis在当前服务器上的各种性能特征


