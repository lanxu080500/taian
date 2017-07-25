<?php

// 是否显示
function getStatus($s){
	$status = array(
		'0' => '已删除',
		'1' => '显示',
		'-1' => '不显示'
	);
	return $status[$s];
}