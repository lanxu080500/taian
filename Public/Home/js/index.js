/*---HotWard---*/

window.onload=function()
{
	gh.app.banner();
	gh.app.Return();
	gh.app.search();
	gh.app.company();
}
window.onscroll=function()
{
	gh.app.Return();
}

window.onresize=function()
{
	gh.app.Return();
}


var gh={};
gh.tools={}
gh.tools.getClass=function(oParent,iClass)
{
	var All=oParent.getElementsByTagName('*');
	var Arr=[];
	for(var i=0;i<All.length;i++)
	{
		if(All[i].className==iClass)
		{
			Arr.push(All[i]);
		}
	}
	return Arr;
}
gh.tools.getStyle=function(obj,attr)
{
	if(obj.currentStyle)
	{
		return obj.currentStyle[attr];
	}else
	{
		return getComputedStyle(obj,false)[attr];
	}
}

gh.ui={}
gh.ui.active1=function(obj)
{
	var iStr=[];
	for(var i=0; i<obj.length;i++)
	{
		iStr.push([parseInt(gh.tools.getStyle(obj[i],'left'))])
		obj[i].style.left=iStr[i][0]+'px';
	}
	iStr.push(iStr[0]);
	iStr.shift();
}
gh.ui.active2=function(obj,json,fn)
{
	clearInterval(obj.timer);
	obj.timer=setInterval(function()
	{
		var Bstop=true;
		for(var attr in json)
		{
			var iCur=0;
			if(attr=='opacity')
			{
				iCur=parseInt(parseFloat(gh.tools.getStyle(obj,attr))*100);
			}else
			{
				iCur=parseInt(gh.tools.getStyle(obj,attr));
			}
			var iSpeed=(json[attr]-iCur)/8;
			iSpeed=iSpeed>0?Math.ceil(iSpeed):Math.floor(iSpeed);
			if(json[attr]!=iCur)
			{
				Bstop=false;
			}
			if(attr=='opacity')
			{
				obj.style.opacity=(iCur+iSpeed)/100;
				obj.style.filter='alpha(opacity:'+(iCur+iSpeed)+')';
			}else
			{
				obj.style[attr]=iCur+iSpeed+'px'
			}
		}
		if(Bstop)
		{
			clearInterval(obj.timer)
			if(fn)
			{
				fn();
			}
		}
	},30)
}

gh.ui.speed=function(obj,old,now)
{
	clearInterval(obj.timer);
	obj.timer=setInterval(function()
	{
		var speed=(now-old)/10;
		speed=speed>0?Math.ceil(speed):Math.floor(speed);
		if(now==old)
		{
			clearInterval(obj.timer);
		}else
		{
			old+=speed;
			obj.style.left=old+'px';
		}
	},30)
}

gh.app={}

gh.app.banner=function()
{
	var oBanner=document.getElementById('banner');
	var oPre=gh.tools.getClass(oBanner,'pre')[0];
	var oNext=gh.tools.getClass(oBanner,'next')[0];
	
	var oActive=gh.tools.getClass(oBanner,'action clear')[0];
	var oLi=oActive.getElementsByTagName('li');
		
	var oDiv=oBanner.getElementsByTagName('div')[0];
	var oP=oDiv.getElementsByTagName('p')
	
		oActive.style.width=oLi.length*oLi[0].offsetWidth+'px';
	
	var iNow=0;
	var timer=null;
	
	
	oNext.onclick=action_right=function()
	{
		if(iNow==(oLi.length-1))
		{
			iNow=-1;
			for(var i=0; i<oP.length; i++)
			{
				oP[i].className='';
			}
			oP[iNow+1].className='index';
			gh.ui.speed(oActive,-(oLi.length)*oLi[0].offsetWidth,0);
		}else
		{
			for(var i=0; i<oP.length; i++)
			{
				oP[i].className='';
			}
			oP[iNow+1].className='index';
			gh.ui.speed(oActive,-iNow*oLi[0].offsetWidth,-(iNow+1)*oLi[0].offsetWidth);
		}
		
			
		iNow++;
	}
	oPre.onclick=action_left=function()
	{
		if(iNow==0)
		{
			iNow=oLi.length;
			for(var i=0; i<oP.length; i++)
			{
				oP[i].className='';
			}
			oP[iNow-1].className='index';
			gh.ui.speed(oActive,0,-(oLi.length-1)*oLi[0].offsetWidth);
		}else
		{
			for(var i=0; i<oP.length; i++)
			{
				oP[i].className='';
			}
			oP[iNow-1].className='index';
			gh.ui.speed(oActive,-iNow*oLi[0].offsetWidth,-(iNow-1)*oLi[0].offsetWidth);
		}
	
		iNow--;
	}
	clearInterval(timer);
	timer=setInterval(action_right,5000);
	
	oActive.onmouseover=oPre.onmouseover=oNext.onmouseover=function()
	{
		clearInterval(timer);
	}
	oActive.onmouseout=oPre.onmouseout=oNext.onmouseout=function()
	{
		timer=setInterval(action_right,5000);
	}
	
	for(var i=0; i<oP.length; i++)
	{
		oP[i].index=i;
		oP[i].onclick=function(ev)
		{
			var Ev= ev || window.event;
			for(var i=0; i<oP.length; i++)
			{
				oP[i].className='';
			}
			this.className='index';
				if(this.index==0)
				{
					iNow=0;
					action_left();
					for(var i=0; i<oP.length; i++)
					{
						oP[i].className='';
					}
					this.className='index';
				}else
				{
					iNow=this.index-1;
					action_right();
				}
			Ev.cancelBubble=true;
		}
	}
	
}

gh.app.Return=function()
{
	var oRn=document.getElementById('return');
	var top=0;
	var Y=0;
	var speed=0;
	var Bstop=true;
	var timer=null;
	var scrollTop=document.documentElement.scrollTop||document.body.scrollTop;
		Y=document.documentElement.clientHeight;
		
		top=scrollTop+Y-'330';
		//oRn.style.top=top+'px';
		gh.ui.active2(oRn,{top: top});
		if(scrollTop==0)
		{
			oRn.style.display='none';
		}else
		{
			oRn.style.display='block';
		}

		/* window.onscroll=function()
		{
			if(!Bstop)
			{
				clearInterval(timer);
			}
			Bstop=false;
		} */
		oRn.onclick=function()
		{
			clearInterval(timer);
			timer=setInterval(function()
			{
				var scrollTop=document.documentElement.scrollTop||document.body.scrollTop;
				speed=Math.floor(-scrollTop/8);
				document.body.scrollTop=document.documentElement.scrollTop=speed+scrollTop;
				if(scrollTop==0)
				{
					clearInterval(timer);
				}
				Bstop=true;
			},30)
			
		}
}

gh.app.company=function()
{
	var oContent=document.getElementById('content');
	var oWrap=gh.tools.getClass(oContent,'wrap')[0];
	var oCp=gh.tools.getClass(oWrap,'company')[0];
	var oUL=oCp.getElementsByTagName('ul')[0];
	var oLi=oUL.getElementsByTagName('li');
	
	var oPre=gh.tools.getClass(oWrap,'pre')[0];
	var oNext=gh.tools.getClass(oWrap,'next')[0];
	var iNow=0;
	oUL.innerHTML+=oUL.innerHTML;
	oUL.style.width=oLi.length*oLi[0].offsetWidth+'px';
		oNext.onclick=function()
		{
			if(iNow==oLi.length/2)
			{
				iNow=0;
				oUL.style.left=0;
			}
				gh.ui.speed(oUL,-iNow*oLi[0].offsetWidth,-(iNow+1)*oLi[0].offsetWidth)
				iNow++;
		}
		oPre.onclick=function()
		{
			if(iNow==0)
			{
				iNow=oLi.length/2;
				oUL.style.left=-oLi.length/2;
			}
				gh.ui.speed(oUL,-iNow*oLi[0].offsetWidth,-(iNow-1)*oLi[0].offsetWidth)
				iNow--;
		}
}

gh.app.search=function()
{
	var oForm=document.getElementById('search');
	var oText=gh.tools.getClass(oForm,'text')[0];
	
	oText.onfocus=function()
	{
		if(oText.value=='请输入关键字搜索')
		{
			oText.value='';
		}
	}
	oText.onblur=function()
	{
		if(oText.value=='')
		{
			oText.value='请输入关键字搜索';
		}
	}
}






