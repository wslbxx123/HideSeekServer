var sessionid;

$('input[type=file]').change(function(){
	$("#newWin3").fadeIn(); 
	$("#newWin2").fadeOut(); 
	var fileimg = document.getElementById("fileimg");
	var mycamera = document.getElementById('mycamera');

	getPath(fileimg,mycamera,fileimg);
});

var roleImages = new Array("img/grassfairy.jpg","img/watermagician.jpg","img/fireknight.jpg","img/stonemonster.jpg","img/lightninggiant.jpg");
var roleNames = new Array("草魅精灵","水影巫师","火光骑士","岩石兽族","闪电巨人");
var myId = Math.floor ((Math.random() * roleImages.length));
var ImgLoaded =0;

document.getElementById("matchId").onclick = function(){
	var m = 0;
	var thisId = 0;
//	document.getElementById("roleimages").src = roleImages[thisId];
	document.getElementById("rolenames").innerHTML = roleNames[thisId];
	$("#newWin4").fadeIn(); 
	$("#newWin2").fadeOut(); 
	
	
	preloadimages(roleImages).done(function(){
	  rolechange();
	})
	
	
	function preloadimages(arr){   
	    var newimages=[], loadedimages=0
	    var postaction=function(){}  //此处增加了一个postaction函数
	    var arr=(typeof arr!="object")? [arr] : arr
	    function imageloadpost(){
	        loadedimages++
	        if (loadedimages==arr.length){
	            postaction(newimages) //加载完成用我们调用postaction函数并将newimages数组做为参数传递进去
	        }
	    }
	    for (var i=0; i<arr.length; i++){
	        newimages[i]=new Image()
	        newimages[i].src=arr[i]
	        newimages[i].onload=function(){
	            imageloadpost()
	        }
	        newimages[i].onerror=function(){
	            imageloadpost()
	        }
	    }
	    return { //此处返回一个空白对象的done方法
	        done:function(f){
	            postaction=f || postaction
	        }
	    }
	}
	
	
	function rolechange(){	
		setTimeout(function(){
			rotate();
		}, 100);
		
		function rotate(){
			
			if(m<20){
				
				m++;
				thisId++;
				if (thisId == roleImages.length) {
					thisId  = 0;
				}
				document.getElementById("roleimages").src = roleImages[thisId];
				document.getElementById("rolenames").innerHTML = roleNames[thisId];
				setTimeout(function(){
					rotate();
				}, 100);
			}
			
			else{
				document.getElementById("roleimages").src = roleImages[myId];
				document.getElementById("rolenames").innerHTML = roleNames[myId];
				
				
				document.getElementById("entrance").onclick = function(){
					alert("注册成功!");
					$(".inner_menu").fadeOut(); 
					$("#myprofile").fadeIn(); 
					$("#myimg").fadeIn(); 
					document.getElementById("nickname").innerHTML = document.getElementById("userName").value;
					document.getElementById("scoreNum").innerHTML = "0";
					$("#newWin4").fadeOut(); 
					$("#storecover").fadeOut(); 
//					checkBox();
				}
			}
		}
	}
	
	
	
	var index=document.getElementById("sex").selectedIndex;
	var data = "phone=" + document.getElementById("userphone").value 
				+ "&nickname="+ document.getElementById("userName").value
				+ "&password="+ document.getElementById("passwd1").value
				+ "&sex="+ document.getElementById("sex").options[index].text
				+ "&region=" + document.getElementById("citySelect").value
				+ "&role=" + myId
				+ "&photo_url=" + encodeURIComponent(document.getElementById("photo").src);
				
	var mymessages = {
		
		url: "/index.php/home/user/register",	
		type: 'POST',
		data: data,
		dataType: "json",
		
//		jsonp: 'callback',
//		jsonpCallback:"success_jsonpCallback",
		success: function(result, status) {
//			alert(document.getElementById("photo").src);
//			alert(JSON.stringify(result));
			switch(result["code"]){
				case "10000":
					Num = result["result"]["record"];
			        sessionid = result["result"]["session_id"];
			        $("#myorder").fadeIn(); 
			        getClick = true;
			        //存储注册数据
			  		sessionStorage.setItem("nickname", $("#userName").val());
					sessionStorage.setItem("record", Num);
					sessionStorage.setItem("myimgpath", $("#photo").attr("src"));
					sessionStorage.setItem("sessionid", result["result"]["session_id"]);
					$("#nickname").html($("#userName").val());
					$("#scoreNum").html(Num);
					$("#myimg").attr('src',$("#photo").attr("src"));
					$("myorder").fadeIn();
					break;
			  	case "10003":
			  		alert("填写信息失败！")
			  		break;
			}	
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			alert("网络出现问题！");
		}
	};
	$.ajax(mymessages);		
	
}

var clickaction = true;
document.getElementById("myorder").onclick = function(){
	$("#orderArea").fadeIn();
		var orderArea1 = {
				url: "/index.php/home/store/refreshPurchaseOrders",
				type: 'POST',
				data: "version=0&order_min_id=0"+
				"&session_id=" + sessionStorage.getItem("sessionid"),
				dataType: "json",
				
	//			jsonp: 'callback',
	//			jsonpCallback:"success_jsonpCallback",	
				success: function(result, status) {
						alert(JSON.stringify(result));
						$("#listArea").fadeIn();
						var orderArea = document.getElementById("orderArea");
						var listArea = document.getElementById("listArea");
						for(var i = 0;i < result.result.orders.length;i++){	
							//创建商品橱窗框
							var listDiv = document.createElement('div');
							listDiv.className = "orderlist";
						  	listArea.appendChild(listDiv);
						    var listImg = document.createElement('img');
						    listImg.className = "orderprodct";
						    listImg.src = result.result.orders[i].product_image_url;
						    listDiv.appendChild(listImg);
						    var nameDiv = document.createElement('div');
						    nameDiv.className = "ordername";
						    nameDiv.innerHTML = result.result.orders[i].product_name;
						    listDiv.appendChild(nameDiv);
						    var sumDiv = document.createElement('div');
						    sumDiv.className = "ordersum";
						    sumDiv.innerHTML = "总计：";
						    listDiv.appendChild(sumDiv);
						    var numSpan = document.createElement('span');
						    numSpan.className = "orderNum";
						    numSpan.innerHTML = result.result.orders[i].count+"(个数)×"+result.result.orders[i].price+"(单价)="+result.result.orders[i].purchase_count*result.result.orders[i].price+"元"; 
						    sumDiv.appendChild(numSpan);
						    var statusDiv = document.createElement('div');
						    if(result.result.orders[i].status=="0"){
						    	statusDiv.className = "orderstatus1";
							    statusDiv.innerHTML = "未付款";
							    listDiv.appendChild(statusDiv);
							    var payDiv = document.createElement('div');
							    payDiv.className = "orderpay";
							    payDiv.innerHTML = "点我付款";
							    listDiv.appendChild(payDiv);
						    }
						    else{
						    	statusDiv.className = "orderstatus";
							    statusDiv.innerHTML = "交易成功";
							    listDiv.appendChild(statusDiv);
						    }    
						}
						clickaction = false;	
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert("网络出现问题！");
				}
		};
		$.ajax(orderArea1);
	
		var orderArea2 = {
				url: "/index.php/home/store/refreshExchangeOrders",
				type: 'POST',
				data: "version=0&order_min_id=0"+
				"&session_id=" + sessionStorage.getItem("sessionid"),
				dataType: "json",
				
	//			jsonp: 'callback',
	//			jsonpCallback:"success_jsonpCallback",	
				success: function(result, status) {
						alert(JSON.stringify(result));
						$("#listArea1").fadeIn();
						var orderArea = document.getElementById("orderArea");
						var listArea1 = document.getElementById("listArea1");
						for(var i = 0;i < result.result.orders.length;i++){	
							//创建商品橱窗框
							var listDiv = document.createElement('div');
							listDiv.className = "orderlist";
						  	listArea1.appendChild(listDiv);
						    var listImg = document.createElement('img');
						    listImg.className = "orderprodct";
						    listImg.src = result.result.orders[i].reward_image_url;
						    listDiv.appendChild(listImg);
						    var nameDiv = document.createElement('div');
						    nameDiv.className = "ordername";
						    nameDiv.innerHTML = result.result.orders[i].reward_name;
						    listDiv.appendChild(nameDiv);
						    var sumDiv = document.createElement('div');
						    sumDiv.className = "ordersum";
						    sumDiv.innerHTML = "总计：";
						    listDiv.appendChild(sumDiv);
						    var numSpan = document.createElement('span');
						    numSpan.className = "orderNum";
						    numSpan.innerHTML = result.result.orders[i].count+"(个数)×"+result.result.orders[i].record+"(单价)="+result.result.orders[i].count*result.result.orders[i].record+"元"; 
						    sumDiv.appendChild(numSpan);
						    var statusDiv = document.createElement('div');
						    if(result.result.orders[i].status=="0"){
						    	statusDiv.className = "orderstatus1";
							    statusDiv.innerHTML = "未付款";
							    listDiv.appendChild(statusDiv);
							    var payDiv = document.createElement('div');
							    payDiv.className = "orderpay";
							    payDiv.innerHTML = "点我付款";
							    listDiv.appendChild(payDiv);
						    }
						    else{
						    	statusDiv.className = "orderstatus";
							    statusDiv.innerHTML = "交易成功";
							    listDiv.appendChild(statusDiv);
						    }    
						}
						$("#listArea1").fadeOut();
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					("网络出现问题！");
				}
		};
		$.ajax(orderArea2);
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
		

	    
		if(width>document.body.clientWidth*0.3){
	    	height = (height*document.body.clientWidth*0.3)/width;
	    	width = document.body.clientWidth*0.3;
		}
		if(height>document.body.clientWidth*0.3){
			width = (width*document.body.clientWidth*0.3)/height;
			height = document.body.clientWidth*0.3;
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
				upframe.style.left = document.body.clientWidth*0.3*0.1+0.1*n*(x-1)+"px";
				downframe.style.left = height+document.body.clientWidth*0.3*0.1-20+0.1*n*(x-1)+"px";
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
			upframe.style.left = document.body.clientWidth*0.3*0.1+"px";
			downframe.style.marginTop = Num+30+"px";
			downframe.style.left = Num+document.body.clientWidth*0.3*0.1-20+"px";
		}
		
		
		confirmedit.onclick = function(){
			$("#newWin2").fadeIn(); 
				if(n>0){
					cover.drawImage(fileimg,0.1*n*(x-1)*y,0,coverpic.height*y,coverpic.height*y,0,0,500,500);
					$("#newWin3").fadeOut(); 
				}
				else{
					cover.drawImage(fileimg,0,0.1*(-n)*(x-1)*y,coverpic.width*y,coverpic.width*y,0,0,500,500);
					$("#newWin3").fadeOut(); 
				}
			
			var images = new Image();
			images.src = mypicture.toDataURL("image/jpg");
			document.getElementById("photo").src = images.src;
		}			
	});
}			

if(window.sessionStorage){
		 	// 获取缓存里面的数据
		nickname = sessionStorage.getItem("nickname");
		record = sessionStorage.getItem("record");
		myimgpath = sessionStorage.getItem("myimgpath");
		if(nickname!=null&&record!=null){  
			$("#nickname").html(nickname);
			$("#scoreNum").html(record);
			$("#myimg").attr('src',myimgpath); 
			$(".inner_menu").fadeOut();
			$("#myimg").fadeIn();
			$("#myprofile" ).fadeIn();
			$("#myorder").fadeIn();
			getClick = true;
		}
	// 清除缓存
		$("#exit").click(function(){
			sessionStorage.clear();
			$(".inner_menu").fadeIn();
			$("#myimg").fadeOut();
			$("#myprofile" ).fadeOut();
			$("#myorder").fadeOut();
			$("#orderArea").fadeOut();
		});

	}
	else{
		alert('对不起，您的浏览器不支持HTML5本地存储');
	}


$("#purOrder").click(function(){
	$("#purOrder").attr("class", "selected");
	$("#exOrder").attr("class", "");
	$("#listArea").fadeIn();
	$("#listArea1").fadeOut();
});
$("#exOrder").click(function(){
	$("#exOrder").attr("class", "selected");
	$("#purOrder").attr("class", "");
	$("#listArea1").fadeIn();
	$("#listArea").fadeOut();
});