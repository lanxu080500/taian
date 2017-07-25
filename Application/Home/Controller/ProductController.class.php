<?php
namespace Home\Controller;

use Think\Controller;

class ProductController extends Controller
{
	// 产品列表页
	public function showList()
	{
		// 查询分页数据
		$page_data = D('Product')->getPage();
        // 将分页数据传递给模板
        $this->assign('pageshow', $page_data['pageShow']); // 分页页码数据
		$this->assign('pro_data', $page_data['pro_data']); // 分页产品数据

		// 查询分类数据
		$cate_data = M('Cate')
			->field('id,pid,cate_name')
			->where('cate_status=1')
			->select();
		// 获得分类树
		$cate_data = getTree($cate_data);
		// 将分类树传递给模板
		$this->assign('cate_data', $cate_data);
		// 获得当前分类的名称
		foreach ($cate_data as $v) {
			if ($v['id'] == I('id')) {
				$cate_name = $v['cate_name'];
			}
		}
		// 将当前分类的名称传递给模板
		$this->assign('cate_name', $cate_name);

		$this->display();
	}

	// 产品详情页
    public function detail()
    {
    	if ($id = I('id')) {
			// 查询分类数据
			$cate_data = M('Cate')
				->field('id,pid,cate_name')
				->where('cate_status=1')
				->select();
			// 获得分类树
			$cate_data = getTree($cate_data);
			// 将分类树传递给模板
			$this->assign('cate_data', $cate_data);

			// 查询产品数据
			$pro_data = D('Product')
				->where('product_status=1')
				->find($id);
			if (!$pro_data) {
				$this->error('访问的页面不存在',U('index/index'));
			}
			foreach ($cate_data as $v) {
				if ($v['id'] == $pro_data['cate_id']) {
					$pro_data['cate_name'] = $v['cate_name'];
				}
			}
			// 将产品数据传递给模板
			$this->assign('pro_data', $pro_data);

			// 查询规格/价格数据
			$price_data = M('Price')->where('product_id='.$id)->select();
			// 将规格/价格数据传递给模板
			$this->assign('price_data', $price_data);

			// 查询上一页、下一页
			$pro_datas = D('Product')
				->field('id,product_name')
				->where('product_status=1 and cate_id=' . $pro_data['cate_id'])
				->select();
			for ($i=0; $i < count($pro_datas); $i++) {
				if ($pro_datas[$i]['id'] == $pro_data['id']) {
					$num = $i;
				}
			}
			$prev = $pro_datas[$num-1] ? $pro_datas[$num-1] : array();
			$next = $pro_datas[$num+1] ? $pro_datas[$num+1] : array();
			// 将上一页、下一页传递给模板
			$this->assign('prev', $prev);
			$this->assign('next', $next);

			// 展示模板
    		$this->display();
    	}
    }
}