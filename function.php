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

/**
 * 验证是否是邮箱
 * @param  string $email 邮箱
 * @return bool          是否是邮箱
 */
function is_email($email){
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
        return true;
    }else{
        return false;
    }
}

/**
 * 验证是否是url
 * @param  string $url url
 * @return bool        是否是url
 */
function is_url($url){
    if(filter_var($url, FILTER_VALIDATE_URL)){
        return true;
    }else{
        return false;
    }
}

/**
 * 获取客户端IP
 * @return array|false|null|string
 */
function get_client_ip(){
    static $ip;
    if($ip == null){
        if(isset($_SERVER)){
            if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }elseif(isset($_SERVER['HTTP_CLIENT_IP'])){
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            }else{
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        }else{
            // 不允许就使用getenv获取
            if(getenv("HTTP_X_FORWARDED_FOR")){
                $ip = getenv("HTTP_X_FORWARDED_FOR");
            }elseif(getenv("HTTP_CLIENT_IP")){
                $ip = getenv("HTTP_CLIENT_IP");
            }else{
                $ip = getenv("REMOTE_ADDR");
            }
        }
    }
    return $ip;
}

/**
 * 验证是否是ip
 * @param  string $ip ip
 * @return bool       是否是ip
 */
function is_ip($ip){
    if(filter_var($ip, FILTER_VALIDATE_IP)){
        return true;
    }else{
        return false;
    }
}

/**
 * 获取文件夹文件列表
 * @param $filename 路径
 * @return array    文件列表
 */
function getFileList($filename){
    $tplDir = realpath($filename);
    if(!is_dir($tplDir)){
        return false;
    }
    $listFile = scandir($tplDir);
    if(is_array($listFile)){
        $list = array();
        foreach($listFile as $key => $value){
            if($value != "." && $value != ".."){
                $list[$key]['file'] = $value;
                $list[$key]['name'] = substr($value, 0, -5);
            }
        }
    }
    return $list;
}

/**
 * 获取字符串中数字部分
 * @param string $str 字符串
 * @return string
 */
function findNum($str=''){
    $str = trim($str);
    if(empty($str))
        return '';

    $res = '';
    $length = strlen($str);
    for($i=0; $i<$length; $i++){
        if(is_numeric($str[$i])){
            $res .= $str[$i];
        }
    }
    return $res;
}

/**
 * UUID含义是 通用唯一识别码 (Universally Unique Identifier)，这是一个软件建构的标准，也是被开源软件基金会 (Open Software Foundation，OSF) 的组织应用在分布式计算环境 (Distributed Computing Environment，DCE) 领域的一部分。
 *
 * UUID 的目的，是让分布式系统中的所有元素，都能有唯一的辨识资讯，而不需要透过中央控制端来做辨识资讯的指定。如此一来，每个人都可以建立不与其他人冲突的 UUID。在这样的情况下，就不需要考虑数据库建立时的名称重复问题。目前最广泛应用的 UUID，即是微软的 Microsoft's Globally Unique Identifiers(GUIDs)，而其它重要的应用，则有 Linux ext2/ext3 档案系统、LUKS 加密分割区、GNOME、KDE、Mac OS X 等等。
 *
 * UUID 是指在一台机器上生成的数字，它保证对在同一时空中的所有机器都是唯一的。通常平台会提供生成的 API。按照开放软件基金会 (OSF) 制定的标准计算，用到了以太网卡地址、纳秒级时间、芯片ID码和许多可能的数字
 * UUID 有以下几部分的组合：
 * ① 当前日期和时间，UUID 的第一个部分与时间有关，如果你在生成一个 UUID 之后，过几秒又生成一个 UUID，则第一个部分不同，其余相同。
 * ② 时钟序列。
 * ③ 全局唯一的 IEEE 机器识别号，如果有网卡，从网卡MAC地址获得，没有网卡以其他方式获得。
 *
 * UUID 的唯一缺陷在于生成的结果串会比较长。关于 UUID 这个标准使用最普遍的是微软的 GUID(Globals Unique Identifiers)。在 ColdFusion 中可以用 CreateUUID() 函数很简单地生成 UUID，其格式未：xxxxxxxx-xxxx- xxxx-xxxxxxxxxxxxxxxx(8-4-4-16)，其中每个 x 是 0-9 或 a-f 范围内的一个十六进制的数字。而标准的 UUID 格式为：xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx(8-4-4-4-12)，可以从 cflib 下载 CreateGUID() UDF 进行转换。
 */
// 第一种方式:
function create_guid(){
    $microTime = microtime();
    list($a_dec, $a_sec) = explode(" ", $microTime);
    $dec_hex = dechex($a_dec * 1000000);
    $sec_hex = dechex($a_sec);
    ensure_length($dec_hex, 5);
    ensure_length($sec_hex, 6);
    $guid = "";
    $guid .= $dec_hex;
    $guid .= create_guid_section(3);
    $guid .= '-';
    $guid .= create_guid_section(4);
    $guid .= '-';
    $guid .= create_guid_section(4);
    $guid .= '-';
    $guid .= create_guid_section(4);
    $guid .= '-';
    $guid .= $sec_hex;
    $guid .= create_guid_section(6);
    return $guid;
}

function ensure_length(&$string, $length){
    $strlen = strlen($string);
    if($strlen < $length){
        $string = str_pad($string, $length, "0");
    }elseif ($strlen > $length){
        $string = substr($string, 0, $length);
    }
}

function create_guid_section($characters){
    $return = "";
    for($i=0; $i<$characters; $i++){
        $return .= dechex(mt_rand(0, 15));
    }
    return $return;
}

// 第二种方式：
/**
 * @param string $prefix 前缀
 * @return string
 */
function create_uuid($prefix=""){
    $str = md5(uniqid(mt_rand(), true));
    $uuid = substr($str, 0, 8) . '-';
    $uuid .= substr($str, 8, 4) . '-';
    $uuid .= substr($str, 12, 4) . '-';
    $uuid .= substr($str, 16, 4) . '-';
    $uuid .= substr($str, 20, 12);
    return $prefix . $uuid;
}