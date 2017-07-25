<?php
namespace Home\Model;

use Think\Model;

class ProductModel extends Model
{
	// 字段映射
	protected $_map = array(
		'name' =>'product_name', // 产品名称
		'number' =>'product_number', // 产品编号
		'big_pic' =>'product_big_pic', // 产品编号
		'lit_pic' =>'product_lit_pic', // 产品编号
        'content' =>'product_content', // 产品描述
		'addtime' =>'product_addtime', // 添加时间
		'updatetime' =>'product_updatetime', // 修改时间
		'status' =>'product_status' // 产品状态；0:删除 1:显示 -1:不显示
	);

    // 获得分页
    public function getPage()
    {
        // 初始化where条件的sql语句
        $whereSql = ' product_status=1 ';
        // 选择分类
        if (I('id')) {
            $whereSql .= ' and cate_id=' . I('id');
        }
        // 获得总记录数
        $count = $this
            ->where($whereSql)
            ->count();
        // 实例化分页类；每页显示6个产品
        $page = new \Think\Page($count, 6);
        // 配置页码参数
        $page->rollPage=0; // 不显示数字页码
        $page->lastSuffix=false; // 尾页不显示页码数字
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('first','首页');
        $page->setConfig('last','尾页');
        $page->setConfig('theme',' %FIRST% %UP_PAGE% %DOWN_PAGE% %END% %HEADER%');

        // 获得页码
        $pageShow = $page->show();
        // 临时去掉外围div标签的结束标签
        $pageShow = str_replace('<div>', '', $pageShow);
        $pageShow = str_replace('</div>', '', $pageShow);
		// 解决"下一页"样式冲突
		$pageShow = str_replace('class="next"', 'class="next_page"', $pageShow);
		// 修改说明文字
		$pageShow = str_replace('条记录', '件产品', $pageShow);
		// 增加页次说明
		$nowPage = I('p') ? I('p') : 1;
		$pageShow .= '<span>页次：' . $nowPage . '/' . $page->totalPages . '页</span>';
		// 增加页次下拉框
		$pageShow .= '<select name="" id="page">';
		for ($i=1; $i <= $page->totalPages; $i++) {
			if ($nowPage == $i ) {
				$pageShow .= '<option value="' . $i . '" selected>第' . $i . '页</option>';
			} else {
				$pageShow .= '<option value="' . $i . '">第' . $i . '页</option>';
			}
		}
		$pageShow .= '</select>';
		// 重新拼接外围div标签的结束标签
		// $pageShow .= '</div>';

        // 获得选中的分类下的产品数据
        $pro_data = $this
            ->where($whereSql)
            ->limit($page->firstRow.','.$page->listRows)
            ->order('id desc')
            ->field('id,product_name,product_big_pic')
            ->select();

        // 返回分页数据
        $page_data = array(
            'pageShow' => $pageShow, // $pageShow 页码信息
            'pro_data' => $pro_data // $pro_data 每页要展示的商品信息
        );
        return $page_data;
    }
}