<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />

        <title>产品列表</title>
        <link rel="stylesheet" type="text/css" href="/Public/Admin/css/mine.css" />
        <script type="text/javascript" src="/Public/Common/js/jquery-1.8.3.min.js"></script>
    </head>
    <body>
        <style>
            .tr_color { background-color: #9F88FF; }
            .div_search span { float: left;padding: 0 1em; }
        </style>
        <div class="div_head">
            <span>
                <span style="float: left;">当前位置是：产品管理-》产品列表</span>
                <span style="float: right; margin-right: 8px; font-weight: bold;">
                    <a style="text-decoration: none;" href="<?php echo U('add');?>">【添加产品】</a>
                </span>
            </span>
        </div>
        <div></div>
        <div class="div_search">
            <span>
                分类 <select name="cid" class="cid">
                    <option selected="selected" value="0">全部分类</option>
                    <?php if(is_array($cate_data)): foreach($cate_data as $key=>$c): if($cid == $c[id]): ?><option value="<?php echo ($c["id"]); ?>" selected="true"><?php echo ($c["cate_name"]); ?></option>
                    <?php else: ?>
                        <option value="<?php echo ($c["id"]); ?>"><?php echo ($c["cate_name"]); ?></option><?php endif; endforeach; endif; ?>
                </select>
            </span>
            <span>
                排序 <select name="order" class="order">
                    <?php if($_GET['order']== 'asc'): ?><option value="desc">降序排列</option>
                        <option value="asc" selected="true">升序排列</option>
                    <?php else: ?>
                        <option value="desc" selected="true">降序排列</option>
                        <option value="asc">升序排列</option><?php endif; ?>
                </select>
            </span>
            <span>
                推送 <select name="push" class="push">
                    <option value="0" selected="true">显示所有</option>
                    <option value="1" <?php if($_GET['push']== 1): ?>selected<?php endif; ?> >只显示推送</option>
                    <option value="-1" <?php if($_GET['push']== -1): ?>selected<?php endif; ?> >只显示不推送</option>
                </select>
            </span>
        </div>
        <em style="color: #f00;">显示的价格会随规格改变而改变</em>
        <div style="font-size: 13px; margin: 10px 5px;">
            <table class="table_a" border="1" width="100%">
                <tbody><tr style="font-weight: bold;">
                        <td>序号</td>
                        <td>产品名称</td>
                        <td>封面图片</td>
                        <td>产品编号</td>
                        <td>产品规格</td>
                        <td>产品价格（元）</td>
                        <td>添加时间</td>
                        <td>更新时间</td>
                        <td>产品分类</td>
                        <td>状态</td>
                        <td>推送</td>
                        <td align="center" colspan="2">操作</td>
                    </tr>

                <?php if(is_array($pro_data)): foreach($pro_data as $key=>$p): ?><tr id="product<?php echo ($p["id"]); ?>">
                        <td><?php echo ($p["id"]); ?></td>
                        <td><a href="<?php echo U('home/product/detail','id='.$p[id]);?>"><?php echo ($p["name"]); ?></a></td>
                        <td><img src="/Public/Upload/<?php echo ($p["lit_pic"]); ?>" height="60" width="60"></td>
                        <td><?php echo ($p["number"]); ?></td>
                        <td>
                            <select name="" class="type">
                                <?php if(is_array($p["price"])): foreach($p["price"] as $key=>$pp): ?><option value="<?php echo ($pp["id"]); ?>"><?php echo ($pp["product_type"]); ?></option><?php endforeach; endif; ?>
                            </select>
                        </td>
                        <td>
                            <?php if(is_array($p["price"])): foreach($p["price"] as $key=>$pp): if($key == 0): ?><div data-id="<?php echo ($pp["id"]); ?>"><?php echo ($pp["product_price"]); ?></div>
                            <?php else: ?>
                            <div data-id="<?php echo ($pp["id"]); ?>" style="display: none"><?php echo ($pp["product_price"]); ?></div><?php endif; endforeach; endif; ?>
                        </td>
                        <td>
                            <?php echo (date("Y-m-d",$p["addtime"])); ?><br />
                            <?php echo (date("H:i:s",$p["addtime"])); ?>
                        </td>
                        <td>
                            <?php echo (date("Y-m-d",$p["updatetime"])); ?><br />
                            <?php echo (date("H:i:s",$p["updatetime"])); ?>
                        </td>
                        <td><?php echo ($p["cate_name"]); ?></td>
                        <td><?php echo (getStatus($p["status"])); ?></td>
                        <td><input type="checkbox" name="is_push" id="" class="is_push" data-id="<?php echo ($p["id"]); ?>" value="<?php echo ($p[is_push]); ?>" <?php if($p[is_push] == 1): ?>checked<?php endif; ?> /></td>
                        <td><a href="<?php echo U('edit','id='.$p[id]);?>">修改</a></td>
                        <td><a href="javascript:;" onclick="confirm('是否确认删除<?php echo ($p["name"]); ?>')?window.location.href='<?php echo U('del','id='.$p[id]);?>':''">删除</a></td>
                    </tr><?php endforeach; endif; ?>

                    <tr>
                        <td colspan="20" style="text-align: center;">
                            <?php echo ($pageshow); ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <script type="text/javascript">
            $(function(){
                // 筛选-产品分类
                $(".cid").change(function(){
                    window.location.href = '/index.php/Admin/Product/showlist/cid/' + $(this).val();
                });
                // 筛选-排序方式
                $(".order").change(function(){
                    <?php if(isset($_GET[cid])): ?>var cid = <?php echo ($_GET['cid']); ?>;
                    <?php else: ?>
                    var cid = 0;<?php endif; ?>
                    window.location.href = '/index.php/Admin/Product/showlist/cid/' + cid + '/order/' + $(this).val();
                });
                // 筛选-是否推送
                $(".push").change(function(){
                    <?php if(isset($_GET[cid])): ?>var cid = <?php echo ($_GET['cid']); ?>;
                    <?php else: ?>
                    var cid = 0;<?php endif; ?>
                    <?php if(isset($_GET[order])): ?>var order = '<?php echo ($_GET['order']); ?>';
                    <?php else: ?>
                    var order = 'desc';<?php endif; ?>
                    window.location.href = '/index.php/Admin/Product/showlist/cid/' + cid + '/order/' + order + '/push/' + $(this).val();
                });
                // 切换-产品规格
                $(".type").change(function(){
                    _this = $(this);
                    divs = $(this).parents('td').next('td').children('div');
                    divs.each(function(){
                        $(this).css('display','none');
                        if ($(this).attr('data-id')==_this.val()) {
                            $(this).css('display','block')
                        }
                    });
                });
                // 切换-是否推送
                $(".is_push").change(function(){
                    var is_push = $(this).val();
                    var pro_id = $(this).attr("data-id");
                    var _this = $(this);
                    $.ajax({
                        url:"<?php echo U('switchPush');?>",
                        data:{is_push:is_push,id:pro_id},
                        success:function(data){
                            _this.val(data["info"]);
                        }
                    })
                })
            })
        </script>
    </body>
</html>