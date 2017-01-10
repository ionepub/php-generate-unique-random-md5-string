# 生成32位不重复的随机字符串方法

该方法保证在百万级数内重复概率低

```php
/**
 * 生成32位不重复随机字符串
 * 当需要循环的次数在百万以内时，重复概率低
 * @author lan
 */
function getRandString($prefix = ''){}
```

# 附：查重测试

> 以下做一些PHP中随机函数的查重测试

测试代码：

```php
$arr = array();

for ($i=0; $i < 1000; $i++) { 
    $k = getNum();
    $arr[$k] = $i;
}

var_dump(count($arr));
```

测试中不断调整getNum()函数的返回值

## 1. uniqid()

uniqid()基于毫秒值，重复概率很大

```php
function getNum(){
    return uniqid();
}


```

结果：`int(8)`

1000个循环中，只有8个数不重复

## 2. microtime()

microtime()即毫秒值，跟uniqid()类似，重复概率很大

```php
function getNum(){
    return md5(microtime());
}
```

结果：`int(9)`

多次重复，结果在8和9直接徘徊

microtime()函数可以传入一个bool类型的参数，传入true时，返回一个浮点数，秒为单位

```
var_dump(microtime()); # => string(21) "0.31520100 1483498881"

var_dump(microtime(true)); # => float(1483498971.2852)
```

```php
function getNum(){
    return md5(microtime(true));
}
```

结果：`int(7)`

效果相差无几

## 3. mt_rand()

mt_rand()比rand()产生随机数的的平均速度快4倍

当不传参数时，mt_rand返回0到RAND_MAX之间的随机数，传入(min, max)参数时，返回范围内的随机数

由此可见，不传参数会比传入参数得到的效果更好

```php
function getNum(){
    return mt_rand(111111, 999999);
}
```

这次将循环次数从1000改为1000000

结果：`int(600734)`

多次重复，结果均在 600000 左右

```php
function getNum(){
    return mt_rand();
}
```

结果：`int(999767)`

比带参数好很多

## 4. time() + mt_rand()

mt_rand()已经可以基本满足需求，但为了更好，可以多个函数结合使用

```php
function getNum(){
    return time() . mt_rand();
}
```

结果：`int(999759)`

跟单独使用mt_rand()差别不大

## 5. microtime() + mt_rand()

```php
function getNum(){
    return microtime() . mt_rand();
}
```

结果：`int(1000000)`

多次重复，并没有发现重复

注：microtime()是否传入参数关系不大

## 6. 总结

当需要循环的次数在百万以内时，可以采用 ` microtime() + mt_rand() ` 的方式获得不重复的随机值，重复概率低

```php
/**
 * 生成32位不重复随机字符串
 */
function getRandString($prefix = ''){
    return md5($prefix . microtime() . mt_rand());
}

var_dump(getRandString('attachment_'));
# => string(32) "a7e0d3baf3940bcaaf70833ba852de61"
```