$(function(){
	// 加载商城信息
	var purNum;	//购买区的产品数；
	var exNum;	//兑换区的产品数；
	var getId;	//兑换或者购买按钮的对应ID值；
	var sessionid;	//session_id变量；
	var reward_id;
	
	var btn_open = document.getElementById('btn_open');
	var open_app = document.getElementById('open_app');
	alert('https://www.hideseek.cn/index.php/home/index/hideseek_m'+'?goal_id='+$("#goalid").val());
	
	if($("#goalid").val()!=""){
		btn_open.addEventListener('click', function() {
			alert('https://www.hideseek.cn/index.php/home/index/hideseek_m'+'?goal_id='+$("#goalid").val());
			window.location.href = 'https://www.hideseek.cn/index.php/home/index/hideseek_m'+'?goal_id='+$("#goalid").val();
			
			setTimeout(function () {
	           window.location.href = 'https://m.hideseek.cn/';
	           var u = navigator.userAgent;
			   var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端 
			   var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
			   if(isAndroid){
			   	alert("Android近期上线，敬请期待！")
			   }
			   if(isIos){
			   	window.location.href = 'https://m.hideseek.cn/';
			   }
	        }, 1000);
		});
	}
	
	
	//刷新页面个人信息
	if(sessionStorage.getItem("sessionid")!=null){
		refreshdata();
	}
	
	function refreshdata(){
		var refreshAccountData = {
				url: "/index.php/home/user/refreshAccountData",	
				type: 'POST',
				data: "session_id=" + sessionStorage.getItem("sessionid"),
				dataType: "json",
				
				success: function(result, status) {
//					alert(JSON.stringify(result));
					switch(result["code"]){
						case "10000":
							sessionStorage["myimgpath"] = result["result"]["small_photo_url"];
							sessionStorage["nickname"] = result["result"]["nickname"];
							sessionStorage["record"] = result["result"]["record"];
							sessionStorage["region"] = result["result"]["region"];
							sessionStorage["sex"] = result["result"]["sex"];
							sessionStorage["default_address"] = result["result"]["default_address"];
							sessionStorage["default_area"] = result["result"]["default_area"];
							break;
					  	case "10003":
					  		alert("发送信息失败！")
					  		break;
					  	case "11000":
					  		clearStorage();
					  		break;
					}	
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert("网络出现问题！");
				}
		};
		$.ajax(refreshAccountData);		
	}
	
	// 重新刷新页面获取缓存的数据
	nickname = sessionStorage.getItem("nickname");
	record = sessionStorage.getItem("record");
	myimgpath = sessionStorage.getItem("myimgpath");
	sessionid = sessionStorage.getItem("sessionid");
	
	
	if(nickname!=null){  
		$("#nickname").html(nickname);
		$("#scoreNum").html(record);
		$("#myimg").attr('src',myimgpath); 
		$(".inner_menu").fadeOut();
		$("#myimg").fadeIn();
		$("#myprofile" ).fadeIn();
		$("#myorder").fadeIn();
	}
	
	//刷新时大头像出错，自动更换为默认图片
	$(".photo").error(function(){
		$(this).attr("src","./Public/Image/Web/mypicture.png");	
	}); 
	
	//刷新时小头像出错，自动更换为默认图片
	$("#myimg").error(function(){
		$(this).attr("src","./Public/Image/Web/mypicture.png");	
	});
	
	
	// 清除缓存
	$("#exit").click(function(){
		sessionStorage.clear();
		$(".inner_menu").fadeIn();
		$("#myimg").fadeOut();
		$("#myprofile" ).fadeOut();
		$("#myorder").fadeOut();
		$("#orderArea").fadeOut();
		getClick = false;
		$(".photo").attr("src","./Public/Image/Web/mypicture.png");
		$("#sex").val("未设置");
		$(".cityinput").val("未设置");
	});
	
	function clearStorage(){
		sessionStorage.clear();
		$(".inner_menu").fadeIn();
		$("#myimg").fadeOut();
		$("#myprofile" ).fadeOut();
		$("#myorder").fadeOut();
		$("#orderArea").fadeOut();
		getClick = false;
		$(".photo").attr("src","./Public/Image/Web/mypicture.png");
		$("#sex").val("未设置");
		$(".cityinput").val("未设置");
		alert("你已经被迫掉线！")
	}
	
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
		$("#passwordArea").fadeOut();
		$("#dataArea").fadeOut();
		$("#listarea .orderlist").remove();	
	});
	
	//此处需要判断是否支付成功。
	alert($("#alipaystatus").val());
	if($("#alipaystatus").val()=="TRADE_SUCCESS"){
		var alipaypurchase = {
				url: "/index.php/home/store/purchase",
				type: 'POST',
				data:"session_id=" + sessionStorage.getItem("sessionid")
				+ "&order_id=" + orderid,
				success: function(result, status) {
					alert(JSON.stringify(result));
					$("#nickname").html(sessionStorage.getItem("nickname"));
					$("#scoreNum").html(sessionStorage.getItem("record"));
					$("#myimg").attr('src',sessionStorage.getItem("myimgpath")); 
					$(".inner_menu").fadeOut();
					$("#myimg").fadeIn();
					$("#myprofile" ).fadeIn();
					$("#myorder").fadeIn();			
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
						alert("网络出现问题！");
				}
		};
		$.ajax(alipaypurchase); 
	}
	
	//获取购买商场信息
	var purStore = {
			url: "/index.php/home/store/refreshProducts",
			type: 'POST',
			data: "version=0&product_min_id=0",
			dataType: "json",
			
			success: function(result, status) {
				switch(result["code"]){
					case "10000":
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
						  	messageImg.src = "./Public/Image/Web/score.png";
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
						  	peopleImg.src = "./Public/Image/Web/people.png";
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
						   		$("#storecover").css("height",$("body").height()-58+"px");
						   		$("#storecover").fadeIn();
						   		$(".goodsNum").val("1");
						   		getId = $(this).attr("id");
						   		$(".goodsName").html(result.result.products[getId].product_name);
						   		$(".goodsprice").html($(".goodsNum").val()*result.result.products[getId].price+"元");
						   		
						   		$('.goodsNum').change(function(){
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
										url: "/index.php/home/store/createOrderFromH5",
										type: 'POST',
										data:data,
										success: function(result, status) {
//											alert(JSON.stringify(result));
											switch(result["code"]){
												case "10000":
													document.getElementById("alipaypage").innerHTML = result["result"]["html"];
													alert(result["result"]["html"]);
													document.getElementById("alipaysubmit").submit();
													order_id = result["result"]["order_id"];
													if(sessionStorage.getItem("orderid")==null){
														sessionStorage.setItem("orderid", order_id);
													}
													else{
														sessionStorage["orderid"] = order_id;
													}
													break;
												case "11000":
													clearStorage();
													break;
											}
										},
		//								error: function(XMLHttpRequest, textStatus, errorThrown) {
		//									alert("网络出现问题！");
		//								}
									};
									$.ajax(enteralipay);
								});
							}
						});
						break;
					case "11000":
						clearStorage();
						break;
				}
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
				switch(result["code"]){
					case "10000":
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
						  	messageImg.src = "./Public/Image/Web/score1.png";
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
						  	peopleImg.src = "./Public/Image/Web/people.png";
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
						    if (sessionStorage.getItem("nickname")==null){
						   		alert("请先登录！");
						    }
						   
						    else{
						    	$("#storecover").css("height",$("body").height()-58+"px");
						    	$("#storecover").fadeIn();
						   		getId = $(this).attr("id");
						   		reward_id = result.result.reward[getId].pk_id;
						   		var gNum = $(".goodsNum1").val()*result.result.reward[getId].record+"积分";
						   		$(".goodsName").html(result.result.reward[getId].reward_name);
						   		$(".goodsprice1").html(gNum);
						   		$('.goodsNum1').change(function(){
						   			$(".goodsprice1").html($(".goodsNum1").val()*result.result.reward[getId].record+"积分");
						   		});
						   		$("#confirmexchange").fadeIn();
						   		default_area = sessionStorage.getItem("default_area");
						   		default_address = sessionStorage.getItem("default_address");
						   		if(default_area!=null&&default_area!="null"&&default_area!=""){
							   		arr = default_area.split("-");
							   		$("#province1").val(arr[0]);
							   		$("#city1").val(arr[1]);
							   		$("#district1").val(arr[2]);
							   		$("#myaddress").val(default_address);
							   		$("input[name='radioselect']").eq(0).attr("checked","checked");
            						$("input[name='radioselect']").eq(1).removeAttr("checked");
						   		}
						    }
						});
						break;
					case "11000":
						clearStorage();
						break;
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert("网络出现问题！");
			}
	};
	$.ajax(exStore);
	
	$("#confirmpay").click(function(){
		refreshdata();
		if(parseInt($("#scoreNum").html())>=parseInt($(".goodsprice1").html())){
			$("#scoreNum").html(parseInt($("#scoreNum").html())-parseInt($(".goodsprice1").html()));
			var data = "session_id=" + sessionStorage.getItem("sessionid")
				  + "&reward_id=" + reward_id
				  + "&count=" + $(".goodsNum1").val()
				  + "&area=" + $("#province1").val()+"-"+$("#city1").val()+"-"+$("#district1").val()
				  + "&address=" + $("#myaddress").val()
				  + "&set_default=" + $("input[name='radioselect']:checked").val();
			var createExchangeOrder = {
				url: "/index.php/home/store/createExchangeOrder",
				type: 'POST',
				data:data,
				success: function(result, status) {
					switch(result["code"]){
						case "10000":
							sessionStorage["record"] = result["result"];
							document.getElementById("scoreNum").innerHTML = result["result"];
							break;
						case "11000":
							clearStorage();
							break;
					}
				},
			};
			$.ajax(createExchangeOrder);
		}
		
		else{
			alert("亲，积分不足！")
		}
		$("#confirmexchange").fadeOut();
		$("#storecover").fadeOut();
	});	
	
	// 实现内部导航的切换
	document.getElementById("purchase").onclick = function(){
		var bodyHeight = 322 + Math.ceil(purNum/2)*258+"px";
		document.getElementById("purchase").className = "selected";
		document.getElementById("exchange").className ="";
		document.getElementById("appdownload").className ="";
		$("#purArea").fadeIn(); 
		$("body").css("height",bodyHeight);
		$("#exArea").fadeOut(); 
		$("#downArea").fadeOut(); 
	}
	
	document.getElementById("exchange").onclick = function(){
		var bodyHeight = 322 + Math.ceil(exNum/2)*258+"px";
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
		$("body").css("height","590px");
		$("#exArea").fadeOut(); 
		$("#purArea").fadeOut(); 
	}
	
	
	// 登录按钮
	$("#test1").click(function(){
		if($("#newWin").css("display")=='none'){
			$("#storecover").css("height","590px");
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
			$("#storecover").css("height","590px");
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
					switch(result["code"]){
						case "10000":
							sessionid = result["result"]["session_id"];
							Num = result["result"]["record"];
							document.getElementById("scoreNum").innerHTML = Num;
							Num1 = result["result"]["nickname"];
							document.getElementById("nickname").innerHTML = Num1;
							
							//判断photo_url是否为空；
							if(result["result"]["photo_url"]==null){
								document.getElementById("myimg").src = "./Public/Image/Web/mypicture.png";
							}
							else{
								document.getElementById("myimg").src = result["result"]["small_photo_url"];
							}
							
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
							sessionStorage.setItem("default_area", result["result"]["default_area"]);
							sessionStorage.setItem("default_address", result["result"]["default_address"]);
					  		break;
					  	case "10001":
					  		$("#fault").fadeIn();
					  		break;
						case "11000":
							clearStorage();
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
	
	//	实现登录界面到修改界面的跳转
	$("#updatepassword").click(function() {
		$("#passwordArea").fadeIn();
		$("#newWin").fadeOut();
	});
	
	//	实现登录界面和服务器的交互
	document.getElementById("verifiCode1").onclick = function(){
		//验证用户填写手机格式是否正确
		var tel = document.getElementById("userphone1").value;
	 	if(/^1\d{10}$/g.test(tel)){      
			phoneFormat = true;
		}
		else{
			alert("手机格式不正确！")
			phoneFormat = false;
		}
	
		//验证注册界面用户手机号码是否被注册
		var myphone = {
			url: "/index.php/home/user/checkIfUserExist",	
			type: 'POST',
			data: "phone=" + $("#userphone1").val(),
			dataType: "json",
			
			success: function(result, status) {
//				alert(JSON.stringify(result));
				switch(result["code"]){
					case "10000":
						phoneregistered = true;
						alert("手机号尚未被注册！")
						break;
				  	case "10015":
				  		phoneregistered = false;
				  		break;
				}	
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert("网络出现问题！");
				phoneregistered = true;
			}
		};
		$.ajax(myphone);	

		//检验是否可以发送验证码
		if(phoneFormat&&!phoneregistered){
			verifiClick = true;
			document.getElementById("userphone1").className = "reqd";
		}
		else{
			verifiClick = false;
			document.getElementById("userphone1").className += " invalid";
		}
	
		//开始发送验证码
	   	if(verifiClick){    
			var verificode = {
				url: "/index.php/home/user/sendVerificationCode",
				type: 'POST',
				data: "phone=" + $("#userphone1").val(),
				dataType: "json",
				
				success: function(result, status){   
					switch(result["code"]){
						case "10000":
							codeNumber = result["result"]["sms_code"];
							if(result["result"]["content"]["error_code"]==0){ 	
								timeKeeper = true;
								verifiClick = false;
								var tim = 59;
								$("#verifiCode1").val("发送成功!");
								$("#verifiCode1").css("background-color","darkgrey"); 
								
								setTimeout(function(){
									round();
									
								},1000);
								
								
							 	function round(){
							    	$("#verifiCode1").val(tim+"秒");
							    	tim--;
							    	if(tim == 0){
							    		timeKeeper = false;
							    		verifiClick = true;
							        	$("#verifiCode1").css("background-color","#FFCC00"); 
							        	$("#verifiCode1").val("发送验证码");
							        }
							        if(timeKeeper){
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
	
	//检验修改界面填写框
	$("#register1").click(function(){
		var allGood = true;
		var allTags = document.getElementById("newWin1").getElementsByTagName("*");
		var phone_figures_test;
		var phone_identical_test;
		
		if($('#passwd3').val().length>=6){
			phone_figures_test = true;
		}
		else{
			phone_figures_test = false;
			alert("密码不能少于6位数！")
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
					case "passwd3":
						if (allGood && !crossCheck (thisTag,thisClass)) {
								classBack = "invalid ";
						}
						classBack += thisClass;
						break;
					case "reqc":
						if (allGood && $("#codeNum1").val() != codeNumber) {
								classBack = "invalid ";
						}
						classBack += thisClass;
						break;
					default:
				}
				return classBack;
			}
	
			function crossCheck (inTag,otherFieldID) {
				if (!document.getElementById (otherFieldID)) {
					return false;
				}
				if(inTag.value == document.getElementById(otherFieldID).value){
					phone_identical_test = true;
				}
				else{
					phone_identical_test = false;
					alert("两次输入密码不一致!")
				}
				return (phone_identical_test&&phone_figures_test);
			}
			function invalidLabel(parentTag) {
				if (parentTag.nodeName == "LABEL") {
						parentTag.className += " invalid";
				}
		 	}
  		}
		
		if(allGood) {
			//	修改界面淡出
			$("passwordArea").fadeOut(); 
		}		
	});
	
	
	var timeKeeper = true;	//控制计时器的启动；
	var verifiClick;	//控制发送验证码按钮；
	var phoneFormat;	//检测手机号格式；
	var phoneregistered; 	//检测手机是否被注册；
	var codeNumber; //发送的手机验证码；
	var codetest;//检验是否发送手机验证码；
	
	//发送和检验验证码
//	document.getElementById("verifiCode").onclick = function(){
//	    codetest = true;
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
		$("#storecover").css("height",$("body").height()-58+"px");
		var allGood = true;
		var allTags = document.getElementById("newWin1").getElementsByTagName("*");
		var phone_figures_test;
		alert(phone_figures_test);
		if($('#passwd1').val().length>=6){
			phone_figures_test = true;
		}
		else{
			phone_figures_test = false;
			alert("密码不能少于6位数！")
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
//								alert("填写验证码错误！")
//								classBack = "invalid ";
//						}
//						classBack += thisClass;
//						break;
					default:
						classBack += thisClass;
						break;
				}
				return classBack;
			}
	
			function crossCheck (inTag,otherFieldID) {
				if (!document.getElementById (otherFieldID)) {
						return false;
				}
				
				if(inTag.value == document.getElementById(otherFieldID).value){
					phone_identical_test = true;
				}
				else{
					phone_identical_test = false;
					alert("两次输入密码不一致!")
				}
				
				return (phone_identical_test&&phone_figures_test);
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
	if($("#flipframe").css("display")=='none'){
		$("#flipframe").fadeIn();
	}
	else{
		$("#flipframe").fadeOut();
	}
}

//	点击弹出购买框上箭头，数字随之增大
function purAddOne(){
	$(".goodsNum").val(parseInt($(".goodsNum").val())+1);
	$(".goodsNum").change();
}

//	点击弹出购买框上箭头，数字随之减小
function purRemoveOne(){
	if($(".goodsNum").val()>0){
		$(".goodsNum").val(parseInt($(".goodsNum").val())-1);
		$(".goodsNum").change();
	}
}

//	点击弹出兑换框上箭头，数字随之增大
function exAddOne(){
	$(".goodsNum1").val(parseInt($(".goodsNum1").val())+1);
	$(".goodsNum1").change();
}

//	点击弹出兑换框上箭头，数字随之减小
function exRemoveOne(){
	if($(".goodsNum1").val()>0){
		$(".goodsNum1").val(parseInt($(".goodsNum1").val())-1);
		$(".goodsNum1").change();
	}
}


