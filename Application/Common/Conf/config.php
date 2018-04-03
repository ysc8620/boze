<?php
$config = array(
    'app' => array(
        'zq8bfc58935bf37o2e' => 'b8e586b6eb3530f1c5efad7ea3f1359e',
    ),
    'BASE_URL' => 'http://w.boze.cn/',
    //'配置项'=>'配置值'
    'URL_MODEL'            =>3,    //2是去除index.php
    'DB_FIELDTYPE_CHECK'   =>true,
    'TMPL_STRIP_SPACE'     =>true,
    'OUTPUT_ENCODE'        =>true, // 页面压缩输出

    'MODULE_ALLOW_LIST'    =>    array('Home','Admin','Api'),
    'DEFAULT_MODULE'       =>    'Admin',  // 默认模块

    //加密混合值
    'AUTH_CODE' => 'BoZe~~~',
    //数据库配置
    'URL_CASE_INSENSITIVE' => true,
    'URL_HTML_SUFFIX' => 'html',

    //    'SESSION_OPTIONS'=>array(
    //        'type'=> 'db',//session采用数据库保存
    //        'expire'=>604800,//session过期时间，如果不设就是php.ini中设置的默认值
    //        ),
    /////////////////////////////////////////////////////
    //'SESSION_TYPE' => 'Redis',
    //session保存类型
    //////////////////////////////////////////////////
    'TAGLIB_BUILD_IN' => 'cx',//标签库
    //'TAGLIB_PRE_LOAD' => '',//命名范围
);



$db_config = __DIR__ .'/db_config.php';
$db_config = file_exists($db_config) ? include "$db_config" : array();

return array_merge($db_config, $config);