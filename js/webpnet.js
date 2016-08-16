//window.onload = myStart;
//
$(function(){
//	document.domain="www.hideseek.cn";
	// 加载商城信息
	var z;
	var f;
	var t;
	var sessionid;
	var getClick = false;
//	var codeNumber;
	
	// 获取缓存里面的数据
	nickname = sessionStorage.getItem("nickname");
	record = sessionStorage.getItem("record");
	myimgpath = sessionStorage.getItem("myimgpath");
	
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
	
	
	var purStore = {
			url: "/index.php/home/store/refreshProducts",
			type: 'POST',
			data: "version=0&product_min_id=0",
			dataType: "json",
			
//			jsonp: 'callback',
//			jsonpCallback:"success_jsonpCallback",
			success: function(result, status) {
				z = result.result.products.length;
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
//				  	var newImg = document.createElement('img');
//				  	newImg.className = "productImg";
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
				 $(".purGet").click(function(){
				 	alert(getClick);
					   if (!getClick&&sessionStorage.getItem("nickname")==null){
					   		alert("请先登录！");
					   }
					   else{
					   		$(".goodsNum").val("1");
					   		t = $(this).attr("id");
					   		$(".goodsName").html(result.result.products[t].product_name);
					   		$(".goodsprice").html($(".goodsNum").val()*result.result.products[t].price+"元");
					   		$('input[type=number]').change(function(){
					   			$(".goodsprice").html($(".goodsNum").val()*result.result.products[t].price+"元");
					   		});
					   		$("#confirmpurchase").fadeIn();
					   		$("#enterAlipay").click(function(){
//								alert($(".goodsNum").val());
//								alert(2-parseInt(t));
								sessionid = sessionStorage.getItem("sessionid");
//								alert(sessionid);
								var data = "session_id=" + sessionid
										  + "&store_id=" + (2-parseInt(t))
										  + "&count=" + $(".goodsNum").val();
								var enteralipay = {
									url: "/index.php/home/store/createOrderFromWeb",
									type: 'POST',
									data:data,
									success: function(result, status) {
//								        alert(JSON.stringify(result));
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
	
	
	var exStore = {
			url: "/index.php/home/store/refreshReward",
			type: 'POST',
			data: "version=0&reward_min_id=0",
			dataType: "json",
			
//			jsonp: 'callback',
//			jsonpCallback:"success_jsonpCallback",
			success: function(result, status) {
				f = result.result.reward.length;
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
				 $(".exGet").click(function(){
				 		alert(getClick);
					   if (!getClick&&sessionStorage.getItem("nickname")==null){
					   		alert("请先登录！");
					   }
					   else{
					   		t = $(this).attr("id");
					   		var gNum = $(".goodsNum1").val()*result.result.reward[t].record+"积分"
					   		
					   		$(".goodsName").html(result.result.reward[t].reward_name);
					   		$(".goodsprice1").html(gNum);
					   		$('input[type=number]').change(function(){
					   			$(".goodsprice1").html($(".goodsNum1").val()*result.result.reward[t].record+"元");
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
		var r = 590 + Math.ceil(z/2)*240+"px";
		document.getElementById("purchase").className = "selected";
		document.getElementById("exchange").className ="";
		document.getElementById("appdownload").className ="";
		$("#purArea").fadeIn(); 
		$("body").css("height",r);
		$("#exArea").fadeOut(); 
		$("#downArea").fadeOut(); 
	}
	
	document.getElementById("exchange").onclick = function(){
		var s = 590 + Math.ceil(f/2)*240+"px";
		document.getElementById("exchange").className = "selected";
		document.getElementById("purchase").className ="";
		document.getElementById("appdownload").className ="";
		$("#exArea").fadeIn(); 
		$("body").css("height",s);
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
		$("#listarea .orderlist").remove();
	});
	
	var time = true;
	var time1 = true;
//发送和检验验证码
	document.getElementById("verifiCode").onclick = function(){
	   	if(time1){    
			var verificode = {
				url: "/index.php/home/user/sendVerificationCode",
				type: 'POST',
				data: "phone=" + $("#userphone").val(),
				dataType: "json",
				
				success: function(result, status){   
					switch(result["code"]){
						case "10000":
							codeNumber = result["result"]["sms_code"];
							if(result["result"]["content"]["error_code"]==0){
							 	
								time = true;
								time1 = false;
								var tim = 59;
								$("#verifiCode").val("发送成功!");
								$("#verifiCode").css("background-color","darkgrey"); 
								
								setTimeout(function(){
									round();
									
								},1000);
								
								
							 	function round(){
							    	$("#verifiCode").val(tim+"秒");
							    	tim--;
							    	if(tim == 0){
							    		time = false;
							    		time1 = true;
							        	$("#verifiCode").css("background-color","#FFCC00"); 
							        	$("#verifiCode").val("发送验证码");
							        }
							        if(time){
							        	setTimeout(function(){
											round();
										},1000);
							        }
								}
							}	
							break;
					  	case "10001":
					  		$("#fault").fadeIn();
					  		break;
					}
					
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert("发送验证码失败！");
				}
			};
			$.ajax(verificode);
		}
	}
	

	
	//	检验注册界面填写框
	$("#register").click(function(){
		var allGood = true;
		var allTags = document.getElementById("newWin1").getElementsByTagName("*");
		for (var i=0; i<allTags.length; i++) {
			if (!validTag(allTags[i])) {
 				allGood = false;
		    }
		}
		
		checkPhone();
		
		function checkPhone(){
			var tel = document.getElementById("userphone").value;
 
 			if(/^1\d{10}$/g.test(tel)){      
     			allGood = true;
    		}
 			else{
      			alert("手机号错误");
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
					default:
						if (allGood && !crossCheck (thisTag,thisClass)) {
								classBack = "invalid ";
						}
						classBack += thisClass;
				}
				return classBack;
			}
	
			function crossCheck (inTag,otherFieldID) {
				if (!document.getElementById (otherFieldID)) {
						return false;
				}
				return (inTag.value == document. getElementById(otherFieldID).value);
			}
			function invalidLabel(parentTag) {
				if (parentTag.nodeName == "LABEL") {
						parentTag.className += " invalid";
				}
		 	}
  		}
		
		if($("#codeNum").val() == codeNumber){
		   allGood = true;
		}
		else{
			allGood = false;
			alert("验证码错误！");
		}		

	
		if(allGood) {
			//	跳转到第二注册界面
			$("#newWin2").fadeIn(); 
			$("#newWin1").fadeOut(); 
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
//		        invalidLabel(thisTag.parentNode);
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
//				crossDomain:true,
				data: $("#loginForm").serialize(),
				dataType: "json",
				
//				jsonp: 'callback',
//				jsonpCallback:"success_jsonpCallback",
				success: function(result, status) {
					alert(JSON.stringify(result));
					switch(result["code"]){
						case "10000":
							sessionid = result["result"]["session_id"];
							Num = result["result"]["record"];
							document.getElementById("scoreNum").innerHTML = Num;
							Num1 = result["result"]["nickname"];
							document.getElementById("nickname").innerHTML = Num1;
							document.getElementById("myimg").src = result["result"]["photo_url"];
					  		$(".inner_menu").fadeOut();  
					  		$("#myprofile").fadeIn(); 
					  		$("#myimg").fadeIn(); 
					  		$("#newWin").fadeOut(); 
					  		$("#storecover").fadeOut(); 
					  		$("#myorder").fadeIn(); 
					  		getClick = true;
					  		//存储登录数据
					  		sessionStorage.setItem("nickname", $("#nickname").html());
							sessionStorage.setItem("record", $("#scoreNum").html());
							sessionStorage.setItem("myimgpath", result["result"]["photo_url"]);
							sessionStorage.setItem("sessionid", result["result"]["session_id"]);
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

});

function displaySubMenu() {
	var subMenu = document.getElementById("exit");
	subMenu.style.display = "block";
}

function hideSubMenu() {
	var subMenu = document.getElementById("exit");
	subMenu.style.display = "none";
}




