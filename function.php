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