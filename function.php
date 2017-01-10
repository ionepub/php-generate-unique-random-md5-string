<?php

/**
 * 生成32位不重复随机字符串
 * 当需要循环的次数在百万以内时，重复概率低
 * @api 调用示例
 *
 *		var_dump(getRandString('attachment_'));
 *		# => string(32) "a7e0d3baf3940bcaaf70833ba852de61"
 * @author lan
 */
function getRandString($prefix = ''){
    return md5($prefix . microtime() . mt_rand());
}