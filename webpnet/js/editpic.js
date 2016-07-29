$('input[type=file]').change(function(){
	$("#newWin3").fadeIn(); 
	var fileimg = document.getElementById("fileimg");
	var mycamera = document.getElementById('mycamera');

	getPath(fileimg,mycamera,fileimg) ;
});

document.getElementById("matchId").onclick = function(){
	$("#newWin4").fadeIn(); 
	$("#newWin2").fadeOut(); 
	var m = 0;
	var roleImages = new Array("img/grassfairy.png","img/watermagician.png","img/fireknight.png","img/stonemonster.png","img/lightninggiant.png");
	var roleNames = new Array("草魅精灵","水影巫师","火光骑士","岩石兽族","闪电巨人");
	var thisId = Math.floor ((Math.random() * roleImages.length));
	document.getElementById("roleimages").src = roleImages[thisId];
	document.getElementById("rolenames").innerHTML = roleNames[thisId];
	rotate();
	
	function rotate(){
		
		if(m<20){
			
			m++;
			thisId++;
			if (thisId == roleImages.length) {
				thisId = 0;
			}
			document.getElementById("roleimages").src = roleImages[thisId];
			document.getElementById("rolenames").innerHTML = roleNames[thisId];
			setTimeout(rotate, 100);
		}
		else{
			var index=document.getElementById("sex").selectedIndex ;
			document.getElementById("entrance").onclick = function(){
				var mymessages = {
					url: "http://120.25.252.252/index.php/home/user/register",
					data: "phone=" + document.getElementById("userphone").value 
							+ "&nickName="+ document.getElementById("userName").value
							+ "&password="+ document.getElementById("passwd1").value
							+ "&sex="+ document.getElementById("sex").options[index].text
							+ "&region=" + document.getElementById("citySelect").value
							+ "&photo=" + document.getElementById("photo").src
							+ "&role=" + thisId,
					type: 'POST',
					dataType: "json",
					success: function(result, status) {
						switch(result["code"]){
							case "10000":
								alert("注册成功!")
						  	case "10001":
						  		alert("注册失败!")
						}	
					},
					error: function(XMLHttpRequest, textStatus, errorThrown) {
						alert("网络出现问题！");
					}
				};
				$.ajax(mymessages);		
			}
		}
	}
}
			
function getPath(obj,fileQuery,transImg){
	var imgSrc = '', imgArr = [], strSrc = '' ;
	if(window.navigator.userAgent.indexOf("MSIE")>=1){ // IE浏览器判断
	    if(obj.select){
		    obj.select();
		    var path=document.selection.createRange().text;
		    alert(path) ;
		    obj.removeAttribute("src");
		    imgSrc = fileQuery.value ;
		    imgArr = imgSrc.split('.') ;
		    strSrc = imgArr[imgArr.length - 1].toLowerCase() ;
		    if(strSrc.localeCompare('jpg') === 0 || strSrc.localeCompare('jpeg') === 0 || strSrc.localeCompare('gif') === 0 || strSrc.localeCompare('png') === 0){
			    obj.setAttribute("src",transImg);
			    obj.style.filter=
			    "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+path+"', sizingMethod='scale');"; // IE通过滤镜的方式实现图片显示
		    }
		    else{
		    	throw new Error('上传图片格式有误，请重新上传!'); 
		    }
	    }
	    else{	 
		    imgSrc = fileQuery.value ;
		    imgArr = imgSrc.split('.') ;
		    strSrc = imgArr[imgArr.length - 1].toLowerCase() ;
		    if(strSrc.localeCompare('jpg') === 0 || strSrc.localeCompare('jpeg') === 0 || strSrc.localeCompare('gif') === 0 || strSrc.localeCompare('png') === 0){
		    	obj.src = fileQuery.value ;
	   		 }
			else{ 
		    	throw new Error('上传图片格式有误，请重新上传!') ;
		    }
	 
	    }
	} 
	else{
	    var file =fileQuery.files[0];
	    var reader = new FileReader();
	    
	    reader.onload = function(e){
		    imgSrc = fileQuery.value ;
		    
		    imgArr = imgSrc.split('.') ;
		    strSrc = imgArr[imgArr.length - 1].toLowerCase() ;
	    	if(strSrc.localeCompare('jpg') === 0 || strSrc.localeCompare('jpeg') === 0 || strSrc.localeCompare('gif') === 0 || strSrc.localeCompare('png') === 0){
	    		obj.setAttribute("src", e.target.result) ;
	    	}
	    	else{
	    		throw new Error('上传图片格式有误，请重新上传!') ;
	    	}
	    }
	}
	    
	reader.readAsDataURL(file);
					
	reader.onloadend = function (e) {
		showXY(e.target.result,file.fileName);  
	}  
	
	function showXY(source){  
	    var fileimg = document.getElementById("fileimg"); 
	    fileimg.src = source;

	}  
					    
	$('img').load(function(){
		var width = fileimg.naturalWidth;
		var height = fileimg.naturalHeight;
		var rangeBar = document.getElementById("rangeBar");
		var coverpic = document.getElementById("coverpic");
		var upframe = document.getElementById("upframe");
		var downframe = document.getElementById("downframe");
		var newWin3 = document.getElementById("newWin3");
		var confirmedit = document.getElementById("confirmedit");
		var mypicture = document.getElementById("mypicture");
		var title = document.getElementById("title");
		var ctx = coverpic.getContext('2d');
		var cover = mypicture.getContext('2d');
		
		var isMove;
		var startx;
		var starty;
		var endx;
		var endy;
		var x;
		var n;
		

	    
		if(width>document.body.clientWidth*0.8){
	    	height = (height*document.body.clientWidth*0.8)/width;
	    	width = document.body.clientWidth*0.8;
		}
		if(height>document.body.clientWidth*0.8){
			width = (width*document.body.clientWidth*0.8)/height;
			height = document.body.clientWidth*0.8;
		}
		fileimg.width = width;
		fileimg.height = height;
		var y = fileimg.naturalWidth/fileimg.width;
		var images = new Image();
		
		
		
		coverpic.width = fileimg.width;
		coverpic.height = fileimg.height;
		ctx.fillStyle = "rgba(0, 0, 0, 0.5)";
		ctx.fillRect (0,0,coverpic.width,coverpic.height);
		n =1;
		x =1;
		if(coverpic.width>coverpic.height){
			ctx.clearRect(0, 0, coverpic.height, coverpic.height);	
			n = coverpic.width - coverpic.height;
			setFrame(coverpic.height);
		}
		else{
			ctx.clearRect(0, 0, coverpic.width, coverpic.width);
			n = coverpic.width - coverpic.height;
			setFrame(coverpic.width);
		}
		
		//初始化trackBar	
		rangeBar.min = 1;
		rangeBar.max = 11;
		rangeBar.step = 1;
		rangeBar.value = 1;
	
	    rangeBar.addEventListener("change",function(){
	    	ctx.clearRect (0,0,coverpic.width,coverpic.height);
	    	ctx.fillRect (0,0,coverpic.width,coverpic.height);
	  
			x = rangeBar.value;
			if(width>height){
				n = width-height;
				ctx.clearRect (0.1*n*(x-1),0,coverpic.height,coverpic.height);
				upframe.style.left = document.body.clientWidth*0.8*0.1+0.1*n*(x-1)+"px";
				downframe.style.left = height+document.body.clientWidth*0.8*0.1-20+0.1*n*(x-1)+"px";
			}
			else{
				n = width-height;
				ctx.clearRect (0,0.1*(-n)*(x-1),coverpic.width,coverpic.width);
				upframe.style.marginTop = 50+0.1*(-n)*(x-1)+"px";
				downframe.style.marginTop = width+30-0.1*n*(x-1)+"px";
			}
	    });		
		
		

		function setFrame(Num){
			upframe.style.marginTop = 50+"px";
			upframe.style.left = document.body.clientWidth*0.8*0.1+"px";
			downframe.style.marginTop = Num+30+"px";
			downframe.style.left = Num+document.body.clientWidth*0.8*0.1-20+"px";
		}
		
		
		confirmedit.onclick = function(){
			var images = new Image();
			images.src = fileimg.src;
			images.onload = function(){
				if(n>0){
					cover.drawImage(fileimg,0.1*n*(x-1)*y,0,coverpic.height*y,coverpic.height*y,0,0,200,200);
					$("#newWin3").fadeOut(); 
				}
				else{
					cover.drawImage(fileimg,0,0.1*(-n)*(x-1)*y,coverpic.width*y,coverpic.width*y,0,0,200,200);
					$("#newWin3").fadeOut(); 
				}
			}
			mypicture.getElementsByTagName('img')[0].src = mypicture.toDataURL("image/png");
		}			
	});

	
}			

