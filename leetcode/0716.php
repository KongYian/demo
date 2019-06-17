<?php
/**
给定一个整数数组 nums 和一个目标值 target，请你在该数组中找出和为目标值的那 两个 整数，并返回他们的数组下标。
你可以假设每种输入只会对应一个答案。但是，你不能重复利用这个数组中同样的元素。
示例:
给定 nums = [2, 7, 11, 15], target = 9
因为 nums[0] + nums[1] = 2 + 7 = 9
所以返回 [0, 1]
 *
 */

$nums = [3,3];
$target = 6;
var_dump(hi($nums,$target));

function hi ($nums,$target){
    $out = [];
    array_key_exists();
    foreach ($nums as $key => $value) {
        $left = $target - $value;
        $keys = array_keys($nums,$left);
        foreach ($keys as $k) {
            if($k && $k != $key) {
                $out = [$key,$k];
                break;
            }
        }
    }
    return $out;
}



exit;

$out = [];
foreach ($nums as $k => $v) {
    foreach ($nums as $kk => $vv) {
        if($k != $kk) {
            if($v + $vv == $target) {
                if(empty($out)) {
                    var_dump([$k,$kk]);
                    $out[] = [$k,$kk];
                }
            }
        }
    }
}