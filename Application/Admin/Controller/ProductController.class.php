<?php
namespace Admin\Controller;

use Think\Controller;

// 产品控制器
class ProductController extends Controller
{
	// 展示产品列表页
	public function showList()
	{
        // 将指定的分类的ID传递给模板
        $cid = I('cid') ? I('cid') : 0;
        $this->assign('cid', $cid);

		// 查询分页数据
		$page_data = D('Product')->getPage();
        // 将分页数据传递给模板
        $this->assign('pageshow', $page_data['pageShow']);
		$this->assign('pro_data', $page_data['pro_data']);

		// 查询分类数据
		$cate_data = M('Cate')
			->field('id,pid,cate_name')
			->where('cate_status=1')
			->select();
		// 获得分类树
		$cate_data = getTree($cate_data);
		// 将分类树传递给模板
		$this->assign('cate_data', $cate_data);

		// 展示模板
		$this->display();
	}

	// 增加产品
	public function add()
	{
		// 判断是否收到post数据
		if (IS_POST) {
		    $data = I('post.');
		    $model = D('Product');
		    // 字段映射 自动验证
		    $datas = $model->create($data);
		    if (!$datas) {
				$this->error($model->getError());die;
		    }
	        // 处理图片
	        $img_res = $model->getImage();
	        if ($img_res && ($img_res['status'] == -1)) {
				$this->error($img_res['msg']);die;
	        }
	        $img_res['product_big_pic'] ? $datas['product_big_pic'] = $img_res['product_big_pic'] : '';
	        $img_res['product_lit_pic'] ? $datas['product_lit_pic'] = $img_res['product_lit_pic'] : '';
	        // 添加时间信息
	        $datas['product_addtime'] = time();
	        $datas['product_updatetime'] = time();
			// 将产品数据添加到product表
			$pro_res = $model->add($datas);
			// 判断产品表添加是否成功
			if ($pro_res) {
				// 组装price表数据
				foreach ($data['type'] as $key => $value) {
					$price_data['product_id'] = $pro_res;
					$price_data['product_type'] = $value;
					$price_data['product_price'] = $data['price'][$key];
					// 将价格数据添加到price表
					$price_res = D('Price')->add($price_data);
				}
				$this->success('操作成功',U('showList'));die;
			} else {
				$this->error('操作失败');die;
			}
		}
		// 查询分类数据
		$cate_data = M('Cate')
			->field('id,pid,cate_name')
			->where('cate_status=1')
			->select();
		// 获得分类树
		$cate_data = getTree($cate_data);
		// 将分类树传递给模板
		$this->assign('cate_data', $cate_data);
		// 展示模板
		$this->display();
	}

	// 修改产品
	public function edit()
	{
		if (IS_POST) {
		    $data = I('post.');
		    $model = D('Product');
		    // 字段映射 自动验证
		    $datas = $model->create($data);
		    if (!$datas) {
				$this->error($model->getError());die;
		    }
	        // 处理图片
	        $img_res = $model->getImage();
	        if ($img_res && ($img_res['status'] == -1)) {
				$this->error($img_res['msg']);die;
	        }
	        $img_res['product_big_pic'] ? $datas['product_big_pic'] = $img_res['product_big_pic'] : '';
	        $img_res['product_lit_pic'] ? $datas['product_lit_pic'] = $img_res['product_lit_pic'] : '';
	        // 添加时间信息
	        $datas['product_updatetime'] = time();
			// 修改product表的产品数据
			$pro_res = $model->save($datas);
			// 判断产品表添加是否成功
			if ($pro_res) {
				// 删除旧的价格数据
				D('Price')->where('product_id='.$data['id'])->delete();
				// 添加新的价格数据
				foreach ($data['type'] as $key => $value) {
					$price_data['product_id'] = $data['id'];
					$price_data['product_type'] = $value;
					$price_data['product_price'] = $data['price'][$key];
					$price_res = D('Price')->add($price_data);
				}
				$this->success('操作成功',U('showList'));die;
			} else {
				$this->error('操作失败');die;
			}
		}

		// 查询产品数据
		$id = I('get.id');
		$pro_data = D('Product')->find($id);
		// 将产品数据传递给模板
		$this->assign('pro_data', $pro_data);

		// 查询规格/价格数据
		$price_data = D('Price')->where('product_id='.$id)->select();
		// 将规格/价格数据传递给模板
		$this->assign('price_data', $price_data);

		// 查询分类数据
		$cate_data = M('Cate')
			->field('id,pid,cate_name')
			->where('cate_status=1')
			->select();
		// 获得分类树
		$cate_data = getTree($cate_data);
		// 将分类树传递给模板
		$this->assign('cate_data', $cate_data);

		// 展示模板
		$this->display();
	}

	// 删除产品
	public function del()
	{
		// 获得要删除的产品的ID
		$id = I('id');
		// 拼接删除的sql语句的参数
		$data = array(
			'id' => $id,
			'product_status' => 0
		);
		// 逻辑删除
		$res = D('Product')->save($data);
		// 判断删除是否成功，并返回相应的提示信息
		if ($res) {
			$this->success('操作成功');
		} else {
			$this->error('操作失败');
		}
	}

	// 切换是否推送
	public function switchPush()
	{
		// 判断是否收到ajax提交的数据
		if ($data =  I()) {
			// 切换是否推送
			if ($data['is_push'] == 1) {
				$data['is_push'] = 0;
			} else if ($data['is_push'] == 0) {
				$data['is_push'] = 1;
			}
			// 修改数据
			$res = D('Product')->save($data);
			// 判断修改是否成功，并返回相应的提示信息
			if ($res) {
				$this->success($data['is_push']);
			} else {
				$this->error($data['is_push']);
			}
		}
	}
}