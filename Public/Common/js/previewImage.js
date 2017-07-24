// 获取并展示缩略图
function previewImage(file)
{
    // 设置缩略图的最大尺寸
    var MAXWIDTH  = 60;
    var MAXHEIGHT = 60;
    // 保存用于放置缩略图的div
    var div = document.getElementById('preview');
    // 如果文件域获得了文件
    if (file.files[0])
    {
        // 在div中写入提示文字
        div.innerHTML ='<p style="font-size: 14px;">图片预览：</p><img id=imghead>';
        // 使用HTML5的FileReader特性，获得内存中的图片文件路径
        var reader = new FileReader();
        reader.onload = function(evt){ img.src = evt.target.result; };
        reader.readAsDataURL(file.files[0]);
        // 调用函数，设置缩略图的尺寸和位置
        var img = document.getElementById('imghead');
        img.onload = function(){
            var rect = imgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
            img.width  =  rect.width;
            img.height =  rect.height;
            img.style.marginLeft = rect.left+'px';
            img.style.marginTop = rect.top+'px';
        };
        // 让div变得可见
        div.style.display = "block";
    }
}
// 设置缩略图尺寸与位置的函数
function imgZoomParam( maxWidth, maxHeight, width, height )
{
    var param = { top:0, left:0, width:width, height:height };
    // 设置缩略图尺寸
    if( width>maxWidth || height>maxHeight )
    {
        rateWidth = width / maxWidth;
        rateHeight = height / maxHeight;

        if( rateWidth > rateHeight )
        {
            param.width =  maxWidth;
            param.height = Math.round(height / rateWidth);
        }else
        {
            param.width = Math.round(width / rateHeight);
            param.height = maxHeight;
        }
    }
    // 设置缩略图位置
    param.left = Math.round((maxWidth - param.width) / 2);
    param.top = Math.round((maxHeight - param.height) / 2);
    return param;
}