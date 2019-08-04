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
