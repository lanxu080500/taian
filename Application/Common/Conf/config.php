<?php
return array(
	//'配置项'=>'配置值'
	'TMPL_PARSE_STRING'=>array(
        '__COMMON__'=>__ROOT__.'/Public/Common/',
        '__ADMIN__'=>__ROOT__.'/Public/Admin/',
        '__HOME__'=>__ROOT__.'/Public/Home/',
        '__UPLOAD__'=>__ROOT__.'/Public/Upload/'
    ), // 模板常数

    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  '127.0.0.1', // 服务器地址
    'DB_NAME'               =>  'anquan',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  '',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'ta_',    // 数据库表前缀

    'UPLOAD_PIC' => array(
        'rootPath' => UPLOAD_PATH // 上传文件保存路径
    ), // 上传文件配置项

    'READ_DATA_MAP'=>true,  // 读取数据时，也进行字段映射
);