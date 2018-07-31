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
