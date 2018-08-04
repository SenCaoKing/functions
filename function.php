<?php
/**
 * Created by PhpStorm.
 * User: Sen
 * Date: 2018/7/30
 * Time: 23:14
 */
header("Content-type:text/html;charset=utf-8");

/**
 * 删除指定标签
 *
 * @param array  $tags    删除的标签 数组形式
 * @param string $str     html字符串
 * @param bool   $content true保留标签的内容text
 * @return mixed
 */
function stripHtmlTags($tags, $str, $content = true)
{
    $html = [];
    // 是否保留标签内的text字符
    if($content){
        foreach ($tags as $tag) {
            $html[] = '/(<' . $tag . '.*?>(.|\n)*?<\/' . $tag . '>)/is';
        }
    }else{
        foreach ($tags as $tag) {
            $html[] = "/(<(?:\/" . $tag . "|" . $tag . ")[^>]*>)/is";
        }
    }
    $data = preg_replace($html, '', $str);
    return $data;
}

/**
 * 按符号截取字符串的指定部分
 *
 * @param  string $str    需要截取的字符串
 * @param  string $sign   需要截取的符号
 * @param  int    $number 如是正数以0为起点从左向右截   负数则从右向左截
 * @return string         返回截取的内容
 */
function cut_str($str, $sign, $number)
{
    $array = explode($sign, $str);
    $length = count($array);
    if($number < 0){
        $new_array = array_reverse($array);
        $abs_number = abs($number);
        if($abs_number > $length){
            return 'error';
        }else{
            return $new_array[$abs_number-1];
        }
    }else{
        if($number >= $length){
            return 'error';
        }else{
            return $array[$number];
        }
    }
}

/**
 * 传递数据以易于阅读的样式格式化后输出
 *
 * @param $data 需要输出的数据
 */
function p($data)
{
    // 定义样式
    $str = '<pre style="display: block; padding: 9.5px; margin: 44px 0 0 0; font-size: 13px; line-height:  1.42857; color: #333; word-break: break-all; word-wrap: break-word; background-color: #F5F5F5; border: 1px solid #CCC; border-radius: 4px;">';
    // 如果是boolean或者null直接显示文字；否则print_r
    if(is_bool($data)){
        $show_data = $data ? 'true' : 'false';
    }elseif(is_null($data)){
        $show_data = 'null';
    }else{
        $show_data = print_r($data, true);
    }
    $str .= $show_data;
    $str .= '</pre>';
    echo $str;
}

// 自定义排序函数
function my_sort($a, $b){
    $prev = isset($a['sortnumber']) ? $a['sortnumber'] : 0;
    $next = isset($b['sortnumber']) ? $b['sortnumber'] : 0;
    if($prev == $next) return 0;
    return ($prev<$next) ? -1 : 1;
}

/**
 * 生成不重复的随机数
 * @param  int $start  需要生成的数字开始范围
 * @param  int $end    结束范围
 * @param  int $length 需要生成的随机数个数
 * @return array       生成的随机数
 */
function get_rand_number($start=1, $end=10, $length=4){
    $connt = 0;
    $temp = array();
    while($connt < $length){
        $temp[] = mt_rand($start, $end);
        $data = array_unique($temp);
        $connt = count($data);
    }
    sort($data);
    return $data;
}

/**
 * 将字符串分割为数组
 * @param  string $str 字符串
 * @return array       分割得到的数组
 */
function mb_str_split($str){
    return preg_split('/(?<!^)(?!$)/u', $str);
}

function is_email($email){
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
        return true;
    }else{
        return false;
    }
}
p(is_email('123456789@qq.com')); // true
p(is_email('baidu.com'));        // false
