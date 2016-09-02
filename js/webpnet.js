$(function(){
	// 加载商城信息
	var purNum;	//购买区的产品数；
	var exNum;	//兑换区的产品数；
	var getId;	//兑换或者购买按钮的对应ID值；
	var sessionid;	//session_id变量；

	
	// 重新刷新页面获取缓存的数据
	nickname = sessionStorage.getItem("nickname");
	record = sessionStorage.getItem("record");
	myimgpath = sessionStorage.getItem("myimgpath");
	sessionid = sessionStorage.getItem("sessionid");
	sex = sessionStorage.getItem("sex");
	region = sessionStorage.getItem("region");
	
	if(nickname!=null){  
		$("#nickname").html(nickname);
		$("#scoreNum").html(record);
		$("#myimg").attr('src',myimgpath); 
		$(".inner_menu").fadeOut();
		$("#myimg").fadeIn();
		$("#myprofile" ).fadeIn();
		$("#myorder").fadeIn();
	}
	
	
	// 清除缓存
	$("#exit").click(function(){
		sessionStorage.clear();
		$(".inner_menu").fadeIn();
		$("#myimg").fadeOut();
		$("#myprofile" ).fadeOut();
		$("#myorder").fadeOut();
		$("#orderArea").fadeOut();
		getClick = false;
	});
	
	
	
	//点击右上角叉号删除页面
	$(".closeBox").click(function(){
		$("#newWin").fadeOut(); 
		$("#newWin1").fadeOut(); 
		$("#newWin2").fadeOut(); 
		$("#newWin3").fadeOut();
		$("#newWin4").fadeOut();
		$("#confirmpurchase").fadeOut();
		$("#orderArea").fadeOut();
		$("#storecover").fadeOut();
		$("#confirmexchange").fadeOut();
		$("#dataArea").fadeOut();
		$("#listarea .orderlist").remove();	
	});
	
	//获取购买商场信息
	var purStore = {
			url: "/index.php/home/store/refreshProducts",
			type: 'POST',
			data: "version=0&product_min_id=0",
			dataType: "json",
			
			success: function(result, status) {
				purNum = result.result.products.length;
				for(var i = 0;i < result.result.products.length;i++){	
					//创建商品橱窗框
					var purArea = document.getElementById("purArea");
				  	var newDiv = document.createElement('div');
				  	if(i%2 == 0){
				  		newDiv.className = "N2";
				  	    var newImg = document.createElement('img');
				  		newImg.className = "productImg";
				  	}
				  	else{
				  		newDiv.className = "N1";
				  		var newImg = document.createElement('img');
				  		newImg.className = "productImg1";
				  	}
				  	purArea.appendChild(newDiv);
				  	
				  	//创建商品名称
				  	var nameSpan = document.createElement('span');
				  
				  	nameSpan.className = "productName";
				  	nameSpan.innerHTML = result.result.products[i].product_name;
				  	newDiv.appendChild(nameSpan);
				  	
				  	//创建商品图片
				  	newImg.src = result.result.products[i].product_image_url;
				  	newDiv.appendChild(newImg);
			  	
				  	//创建商品兑换信息框
				  	var messageDiv = document.createElement('div');
				  	newDiv.appendChild(messageDiv);
				  	
				  	//商品兑换信息框：商品积分图标
				  	var messageImg = document.createElement('img');
				  	messageImg.src = "img/score.png";
				  	messageImg.className = "scoreImg";
				  	messageDiv.appendChild(messageImg);
				  	
				  	//商品兑换信息框：商品积分数字
				  	var pointNum = document.createElement('span');
				  	pointNum.className = "pointNum";
				  	pointNum.id = "pointNumb"+i;
				  	pointNum.innerHTML = result.result.products[i].price+"元";
				  	messageDiv.appendChild(pointNum);
				  
				  	//商品兑换信息框：商品人物图标
				  	var peopleImg = document.createElement('img');
				  	peopleImg.src = "img/people.png";
				  	peopleImg.className = "peopleImg";
				  	messageDiv.appendChild(peopleImg);
				  	
				  	//商品兑换信息框：商品购买人数
				  	var peopleNum = document.createElement('span');
				  	peopleNum.className = "peopleNum";
				  	peopleNum.innerHTML = result.result.products[i].purchase_count+"人购买";
				  	messageDiv.appendChild(peopleNum);

                    //创建商品介绍信息
				  	var introDiv = document.createElement('div');
					introDiv.id = "intro";
				  	introDiv.innerHTML = result.result.products[i].introduction;
				    newDiv.appendChild(introDiv);
				    
				    //创建商品购买按钮
				    var getDiv = document.createElement('div');
				    getDiv.className = "purGet";
				    getDiv.id = i;
					getDiv.innerHTML= "购买";
				    newDiv.appendChild(getDiv);   
				        
				} 
				
				//点击购买后是否有用户名存在判断
				 $(".purGet").click(function(){	
					   if (sessionStorage.getItem("nickname")==null){
					   		alert("请先登录！");
					   }
					   
					   else{
					   		$("#storecover").fadeIn();
					   		$(".goodsNum").val("1");
					   		getId = $(this).attr("id");
					   		$(".goodsName").html(result.result.products[getId].product_name);
					   		$(".goodsprice").html($(".goodsNum").val()*result.result.products[getId].price+"元");
					   		
					   		$('input[type=number]').change(function(){
					   			$(".goodsprice").html($(".goodsNum").val()*result.result.products[getId].price+"元");
					   		});
					   		
					   		//进入购买支付确认界面
					   		$("#confirmpurchase").fadeIn();
					   		
					   		//进入支付宝界面
					   		$("#enterAlipay").click(function(){
								var data = "session_id=" + sessionStorage.getItem("sessionid")
										  + "&store_id=" + result.result.products[getId].pk_id
										  + "&count=" + $(".goodsNum").val(); 
								var enteralipay = {
									url: "/index.php/home/store/createOrderFromWeb",
									type: 'POST',
									data:data,
									success: function(result, status) {
										document.getElementById("alipaypage").innerHTML = result;
										document.getElementById("alipaysubmit").submit();
									},
									error: function(XMLHttpRequest, textStatus, errorThrown) {
										alert("网络出现问题！");
									}
							};
							$.ajax(enteralipay);
						});
					}
				});
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert("网络出现问题！");
			}
	};
	$.ajax(purStore);
	
	//获取兑换商场信息
	var exStore = {
			url: "/index.php/home/store/refreshReward",
			type: 'POST',
			data: "version=0&reward_min_id=0",
			dataType: "json",
			
			success: function(result, status) {
				exNum = result.result.reward.length;
				for(var i = 0;i < result.result.reward.length;i++){	
					//创建商品橱窗框
					var exArea = document.getElementById("exArea");
				  	var newDiv = document.createElement('div');
				  	if(i%2 == 0){
				  		newDiv.className = "N2";
				  	    var newImg = document.createElement('img');
				  		newImg.className = "productImg";
				  	}
				  	else{
				  		newDiv.className = "N1";
				  		var newImg = document.createElement('img');
				  		newImg.className = "productImg1";
				  	}
				  	exArea.appendChild(newDiv);
				  	
				  	//创建商品名称
				  	var nameSpan = document.createElement('span');
				  
				  	nameSpan.className = "productName";
				  	nameSpan.innerHTML = result.result.reward[i].reward_name;
				  	newDiv.appendChild(nameSpan);
				  	
				  	//创建商品图片
				  	newImg.src = result.result.reward[i].reward_image_url;
				  	newDiv.appendChild(newImg);
			  	
				  	//创建商品兑换信息框
				  	var messageDiv = document.createElement('div');
				  	newDiv.appendChild(messageDiv);
				  	
				  	//商品兑换信息框：商品积分图标
				  	var messageImg = document.createElement('img');
				  	messageImg.src = "img/score1.png";
				  	messageImg.className = "scoreImg";
				  	messageDiv.appendChild(messageImg);
				  	
				  	//商品兑换信息框：商品积分数字
				  	var pointNum = document.createElement('span');
				  	pointNum.className = "pointNum";
				  	pointNum.id = "pointNumc"+i;
				  	pointNum.innerHTML = result.result.reward[i].record+"积分";
				  	messageDiv.appendChild(pointNum);
				  
				  	//商品兑换信息框：商品人物图标
				  	var peopleImg = document.createElement('img');
				  	peopleImg.src = "img/people.png";
				  	peopleImg.className = "peopleImg";
				  	messageDiv.appendChild(peopleImg);
				  	
				  	//商品兑换信息框：商品购买人数
				  	var peopleNum = document.createElement('span');
				  	peopleNum.className = "peopleNum";
				  	peopleNum.innerHTML = result.result.reward[i].exchange_count+"人兑换";
				  	messageDiv.appendChild(peopleNum);

                    //创建商品介绍信息
				  	var introDiv = document.createElement('div');
					introDiv.id = "intro";
				  	introDiv.innerHTML = result.result.reward[i].introduction;
				    newDiv.appendChild(introDiv);
				    
				    //创建商品购买按钮
				    var getDiv = document.createElement('div');
				    getDiv.className = "exGet";
				    getDiv.id = i;
					getDiv.innerHTML= "兑换";
				    newDiv.appendChild(getDiv);   
				    
				}
				//点击兑换后是否有用户名存在判断
				$(".exGet").click(function(){
//					alert(1);
				    if (sessionStorage.getItem("nickname")==null){
				   		alert("请先登录！");
				    }
				   
				    else{
				    	$("#storecover").fadeIn();
				   		getId = $(this).attr("id");
				   		var gNum = $(".goodsNum1").val()*result.result.reward[getId].record+"积分";
				   		$(".goodsName").html(result.result.reward[getId].reward_name);
				   		$(".goodsprice1").html(gNum);
				   		$('input[type=number]').change(function(){
				   			$(".goodsprice1").html($(".goodsNum1").val()*result.result.reward[getId].record+"元");
				   		});
				   		$("#confirmexchange").fadeIn();
				   		$("#confirmpay").click(function(){
				   			if($("#scoreNum").html()>=gNum){
				   				$("#scoreNum").html($("#scoreNum").html()-gNum);
				   			}
				   			else{
				   				alert("亲，积分不足！")
				   			}
				   		});	
				    }
				});
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert("网络出现问题！");
			}
	};
	$.ajax(exStore);
	
	
	// 实现内部导航的切换
	document.getElementById("purchase").onclick = function(){
		var bodyHeight = 590 + Math.ceil(purNum/2)*240+"px";
		document.getElementById("purchase").className = "selected";
		document.getElementById("exchange").className ="";
		document.getElementById("appdownload").className ="";
		$("#purArea").fadeIn(); 
		$("body").css("height",bodyHeight);
		$("#exArea").fadeOut(); 
		$("#downArea").fadeOut(); 
	}
	
	document.getElementById("exchange").onclick = function(){
		var bodyHeight = 590 + Math.ceil(exNum/2)*240+"px";
		document.getElementById("exchange").className = "selected";
		document.getElementById("purchase").className ="";
		document.getElementById("appdownload").className ="";
		$("#exArea").fadeIn(); 
		$("body").css("height",bodyHeight);
		$("#purArea").fadeOut(); 
		$("#downArea").fadeOut(); 
	}
	
	document.getElementById("appdownload").onclick = function(){
		document.getElementById("purchase").className = "";
		document.getElementById("exchange").className ="";
		document.getElementById("appdownload").className ="selected";
		$("#downArea").fadeIn(); 
		$("body").css("height","800px");
		$("#exArea").fadeOut(); 
		$("#purArea").fadeOut(); 
	}
	
	
	// 登录按钮
	$("#test1").click(function(){
		if($("#newWin").css("display")=='none'){
			$("#storecover").css("height","800px");
			$("#storecover").fadeIn(); 
			$("#newWin").fadeIn(); 
			$("#newWin1").fadeOut(); 
			
		}
		else{
			$("#newWin").fadeOut(); 
			$("#storecover").fadeOut(); 
		}
	});
	
	//	注册按钮
	$("#test2").click(function(){
		if($("#newWin1").css("display")=='none'){
			$("#storecover").fadeIn(); 
			$("#newWin1").fadeIn(); 
			$("#newWin").fadeOut(); 
			
		}
		else{
			$("#newWin1").fadeOut(); 
			$("#storecover").fadeOut(); 
		}
	});
	
	//	检验登录界面填写框
	$("#login").click(function() {
		var allGood = true;
		var allTags = document.getElementById("newWin").getElementsByTagName("*");
		for (var i=0; i<allTags.length; i++) {
			if (!validTag(allTags[i])) {
				allGood = false;
	     	}
		}
		
		
		function validTag(thisTag) {
			var outClass = "";
			var allClasses = thisTag.className. split(" ");
			
			for (var j=0; j<allClasses.length; j++) {
				outClass += validBasedOnClass (allClasses[j]) + " ";
			}
			
			thisTag.className = outClass;
			
			if (outClass.indexOf("invalid") > -1) {
				thisTag.focus();
				
				if (thisTag.nodeName == "INPUT") {
					thisTag.select();
				}
		
				return false;
			}
			return true;
		
			
			function validBasedOnClass(thisClass) {
				var classBack = "";
				switch(thisClass) {
					case "":
					case "invalid":
					break;
					case "reqd":
						if (allGood && thisTag. value == "") {
							classBack = "invalid ";
						}
						classBack += thisClass;
						break;
					default:
						classBack += thisClass;
						break;
				}
				return classBack;
			}
		}
		
		//	实现登录界面和服务器的交互
		if(allGood) {			
			var options = {
				url: "/index.php/home/user/login",
				type: 'POST',
				data: $("#loginForm").serialize(),
				dataType: "json",
				
				success: function(result, status) {
//					alert(JSON.stringify(result));
					switch(result["code"]){
						case "10000":
							sessionid = result["result"]["session_id"];
							Num = result["result"]["record"];
							document.getElementById("scoreNum").innerHTML = Num;
							Num1 = result["result"]["nickname"];
							document.getElementById("nickname").innerHTML = Num1;
							document.getElementById("myimg").src = result["result"]["small_photo_url"];
					  		$(".inner_menu").fadeOut();  
					  		$("#myprofile").fadeIn(); 
					  		$("#myimg").fadeIn(); 
					  		$("#newWin").fadeOut(); 
					  		$("#storecover").fadeOut(); 
					  		$("#myorder").fadeIn(); 
					  		
					  		//存储登录数据
					  		sessionStorage.setItem("nickname", $("#nickname").html());
							sessionStorage.setItem("record", $("#scoreNum").html());
							sessionStorage.setItem("myimgpath", result["result"]["small_photo_url"]);
							sessionStorage.setItem("sessionid", result["result"]["session_id"]);
							sessionStorage.setItem("sex", result["result"]["sex"]);
							sessionStorage.setItem("region", result["result"]["region"]);
//							nickname = sessionStorage.getItem("nickname");
//							record = sessionStorage.getItem("record");
//							myimgpath = sessionStorage.getItem("myimgpath");
//							sessionid = sessionStorage.getItem("sessionid");
//							sex = sessionStorage.getItem("sex");
//							region = sessionStorage.getItem("region");
					  		break;
					  	case "10001":
					  		$("#fault").fadeIn();
					  		break;
					}
					
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert("网络出现问题！");
				}
			};
			$.ajax(options);
		}		
	});	
	
	
	var timeKeeper = true;	//控制计时器的启动；
	var verifiClick;	//控制发送验证码按钮；
	var phoneFormat;	//检测手机号格式；
	var phoneregistered; 	//检测手机是否被注册；
	var codeNumber; //发送的手机验证码；
	
	//发送和检验验证码
//	document.getElementById("verifiCode").onclick = function(){
//	
//		//验证用户填写手机格式是否正确
//		var tel = document.getElementById("userphone").value;
//	 	if(/^1\d{10}$/g.test(tel)){      
//			phoneFormat = true;
//		}
//		else{
//			alert("手机格式不正确！")
//			phoneFormat = false;
//		}
//	
//		//验证注册界面用户手机号码是否被注册
//		var myphone = {
//			url: "/index.php/home/user/checkIfUserExist",	
//			type: 'POST',
//			data: "phone=" + $("#userphone").val(),
//			dataType: "json",
//			
//			success: function(result, status) {
////				alert(JSON.stringify(result));
//				switch(result["code"]){
//					case "10000":
//						phoneregistered = false;
//						break;
//				  	case "10015":
//				  		phoneregistered = true;
//				  		break;
//				}	
//			},
//			error: function(XMLHttpRequest, textStatus, errorThrown) {
//				alert("网络出现问题！");
//				phoneregistered = true;
//			}
//		};
//		$.ajax(myphone);	
//
//		//检验是否可以发送验证码
//		if(phoneFormat&&!phoneregistered){
//			verifiClick = true;
//			document.getElementById("userphone").className = "reqd";
//		}
//		else{
//			verifiClick = false;
//			document.getElementById("userphone").className += " invalid";
//		}
//	
//		//开始发送验证码
//	   	if(verifiClick){    
//			var verificode = {
//				url: "/index.php/home/user/sendVerificationCode",
//				type: 'POST',
//				data: "phone=" + $("#userphone").val(),
//				dataType: "json",
//				
//				success: function(result, status){   
//					switch(result["code"]){
//						case "10000":
//							codeNumber = result["result"]["sms_code"];
//							if(result["result"]["content"]["error_code"]==0){ 	
//								timeKeeper = true;
//								verifiClick = false;
//								var tim = 59;
//								$("#verifiCode").val("发送成功!");
//								$("#verifiCode").css("background-color","darkgrey"); 
//								
//								setTimeout(function(){
//									round();
//									
//								},1000);
//								
//								
//							 	function round(){
//							    	$("#verifiCode").val(tim+"秒");
//							    	tim--;
//							    	if(tim == 0){
//							    		timeKeeper = false;
//							    		verifiClick = true;
//							        	$("#verifiCode").css("background-color","#FFCC00"); 
//							        	$("#verifiCode").val("发送验证码");
//							        }
//							        if(timeKeeper){
//							        	setTimeout(function(){
//											round();
//										},1000);
//							        }
//								}
//							}	
//							break;
//					  	case "10001":
//					  		$("#fault").fadeIn();
//					  		break;
//					}
//					
//				},
//				error: function(XMLHttpRequest, textStatus, errorThrown) {
//					alert("发送验证码失败！");
//				}
//			};
//			$.ajax(verificode);
//		}
//	}
		
	//	检验注册界面填写框
	$("#register").click(function(){
		var allGood = true;
		var allTags = document.getElementById("newWin1").getElementsByTagName("*");
		var phone_test;
		
		if($('#test').val().length>=6){
			phone_test = true;
		}
		else{
			phone_test = false;
		}
		
		for (var i=0; i<allTags.length; i++) {
			if (!validTag(allTags[i])) {
 				allGood = false;
		    }
		}
		
		
		function validTag(thisTag) {
			var outClass = "";
			var allClasses = thisTag.className. split(" ");
			
			for (var j=0; j<allClasses.length; j++) {
				outClass += validBasedOnClass (allClasses[j]) + " ";
			}
			thisTag.className = outClass;
			
			if (outClass.indexOf("invalid") > -1) {
		        invalidLabel(thisTag.parentNode);
				thisTag.focus();
				if (thisTag.nodeName == "INPUT") {
						thisTag.select();
				}
				return false;
			}
			return true;
	
			function validBasedOnClass(thisClass) {
				var classBack = "";
				switch(thisClass) {
					case "":
					case "invalid":
					break;
					case "reqd":
						if (allGood && thisTag. value == "") {
						    	classBack = "invalid ";
						}
						classBack += thisClass;
						break;
					case "icon":
					case "line":
						classBack += thisClass;
						break;
					case "passwd1":
						if (allGood && !crossCheck (thisTag,thisClass)) {
								classBack = "invalid ";
						}
						classBack += thisClass;
						break;
//					case "reqc":
//						if (allGood && $("#codeNum").val() != codeNumber) {
//								classBack = "invalid ";
//						}
//						classBack += thisClass;
//						break;
					default:
				}
				return classBack;
			}
	
			function crossCheck (inTag,otherFieldID) {
				if (!document.getElementById (otherFieldID)) {
						return false;
				}
				return ((inTag.value == document.getElementById(otherFieldID).value)&&phone_test);
			}
			function invalidLabel(parentTag) {
				if (parentTag.nodeName == "LABEL") {
						parentTag.className += " invalid";
				}
		 	}
  		}
		
		if(allGood) {
			//	跳转到第二注册界面
			$("#newWin2").fadeIn(); 
			$("#newWin1").fadeOut(); 
		}		
	});
});

//	右上角菜单列的显示
function displaySubMenu() {
	var subMenu = document.getElementById("flipframe");
	subMenu.style.display = "block";
}

//	右上角菜单列的隐藏
function hideSubMenu() {
	var subMenu = document.getElementById("flipframe");
	subMenu.style.display = "none";
}




