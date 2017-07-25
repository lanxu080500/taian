<?php
namespace Admin\Model;

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

    // 自动验证
    protected $_validate = array(
        array('product_name','require','产品名称不能为空'),
        array('product_number','require','产品编号不能为空'),
        array('cate_id','require','产品分类不能为空')
    );

    // 处理图片
    public function getImage()
    {
        if ($_FILES['image']['name']) {
            // 判断上传图片后缀是否合规
            $arr = array('image/png','image/jpeg');
            if (!in_array($_FILES['image']['type'], $arr)) {
                $img_res = array(
                    'status' => -1,
                    'msg' => '上传的图片格式不正确，只允许jpg/jpeg/png'
                );
                return $img_res;
            }
            // 上传图片
            $imgInfo = getUploadFile(C('UPLOAD_PIC'));
            // 获得上传图片的路径
            $img_res['product_big_pic'] = $imgInfo['image']['savepath'].$imgInfo['image']['savename'];
            // 拼接缩略图的路径
            $img_res['product_lit_pic'] = $imgInfo['image']['savepath'].'thumb_'.$imgInfo['image']['savename'];
            // 获得缩略图，缩略图的默认尺寸50*50
            getThumb($img_res['product_big_pic'],$img_res['product_lit_pic']);
            return $img_res;
        }
    }

    // 获得分页
    public function getPage()
    {
        // 初始化where条件的sql语句
        $whereSql = ' c.cate_status=1 ';
        // 选择分类
        if (I('cid')) {
            $whereSql .= ' and pd.cate_id=' . I('cid');
        }
        // 是否推送
        if (I('push') && I('push')==1) {
            $whereSql .= ' and pd.is_push=1';
        } else if (I('push') && I('push')==-1) {
            $whereSql .= ' and pd.is_push=-1';
        }
        // 拼接order条件的sql语句
        $orderSql = 'pd.id desc';
        if (I('order')=='asc') {
            $orderSql = 'pd.id asc';
        }
        // 获得总记录数
        $count = $this
            ->alias('pd')
            ->join('left join ta_cate as c on pd.cate_id=c.id')
            ->where($whereSql)
            ->count();
        // 实例化分页类
        $page = new \Think\Page($count, 10);
        // 配置页码参数
        $page->rollPage=3; // 最多显示3个页码
        $page->lastSuffix=false; // 尾页不显示页码
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('first','首页');
        $page->setConfig('last','尾页');
        $page->setConfig('theme',' %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        // 获得页码
        $pageShow = $page->show();
        // 获得产品数据，包括分类名称
        $pro_data = $this
            ->alias('pd')
            ->join('left join ta_cate as c on pd.cate_id=c.id')
            ->where($whereSql)
            ->limit($page->firstRow.','.$page->listRows)
            ->order($orderSql)
            ->field('pd.*,c.cate_name')
            ->select();
        // 获得价格数据，并写入产品数据中
        foreach ($pro_data as $k => $v) {
            $pro_data[$k]['price'] = M('Price')
                ->where('product_id=' . $v['id'])
                ->select();
        }
        // 返回分页数据
        $page_data = array(
            'pageShow' => $pageShow, // $pageShow 页码信息
            'pro_data' => $pro_data // $pro_data 每页要展示的商品信息
        );
        return $page_data;
    }
}