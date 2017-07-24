<?php

// 递归方法实现无限极分类
function getTree($list,$pid=0,$level=0) {
	static $tree = array();
	foreach($list as $row) {
		if($row['pid']==$pid) {
			$row['level'] = $level;
			$tree[] = $row;
			getTree($list, $row['id'], $level + 1);
		}
	}
	return $tree;
}

// 上传文件
function getUploadFile($config){
	$upload = new \Think\Upload($config);
	$info = $upload->upload();
	if(!$info){
		echo $upload->getError();
		die;
	}
	return $info;
}

// 获得缩略图
function getThumb($big_pic_path, $lit_pic_path, $width = 60, $height = 60){
	// 实例化图片处理类
	$image = new \Think\Image();
	// 打开大图
	$image->open(UPLOAD_PATH.$big_pic_path);
	// 获得缩略图，并保存到指定路径
	$thumb = $image->thumb($width, $height)->save(UPLOAD_PATH.$lit_pic_path);
}