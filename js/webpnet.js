window.onload = myStart;

function myStart(){
//	document.domain="www.hideseek.cn";
	// 加载商城信息
	var z;
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
				  	}
				  	else{
				  		newDiv.className = "N1";
				  	}
				  	purArea.appendChild(newDiv);
				  	
				  	//创建商品名称
				  	var nameSpan = document.createElement('span');
				  
				  	nameSpan.className = "productName";
				  	nameSpan.innerHTML = result.result.products[i].product_name;
				  	newDiv.appendChild(nameSpan);
				  	
				  	//创建商品图片
				  	var newImg = document.createElement('img');
				  	newImg.className = "productImg";
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
				  	pointNum.innerHTML = result.result.products[i].price;
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
				    getDiv.id = "b"+i;
					getDiv.innerHTML= "购买";
				    newDiv.appendChild(getDiv);   
				    
				} 		
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
				alert(JSON.stringify(result));
				z = result.result.reward.length;
				for(var i = 0;i < result.result.reward.length;i++){	
					//创建商品橱窗框
					var exArea = document.getElementById("exArea");
				  	var newDiv = document.createElement('div');
				  	if(i%2 == 0){
				  		newDiv.className = "N2";
				  	}
				  	else{
				  		newDiv.className = "N1";
				  	}
				  	exArea.appendChild(newDiv);
				  	
				  	//创建商品名称
				  	var nameSpan = document.createElement('span');
				  
				  	nameSpan.className = "productName";
				  	nameSpan.innerHTML = result.result.reward[i].reward_name;
				  	newDiv.appendChild(nameSpan);
				  	
				  	//创建商品图片
				  	var newImg = document.createElement('img');
				  	newImg.className = "productImg";
				  	newImg.src = result.result.reward[i].reward_image_url;
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
				  	pointNum.id = "pointNumc"+i;
				  	pointNum.innerHTML = result.result.reward[i].record;
				  	messageDiv.appendChild(pointNum);
				  
				  	//商品兑换信息框：商品人物图标
				  	var peopleImg = document.createElement('img');
				  	peopleImg.src = "img/people.png";
				  	peopleImg.className = "peopleImg";
				  	messageDiv.appendChild(peopleImg);
				  	
				  	//商品兑换信息框：商品购买人数
				  	var peopleNum = document.createElement('span');
				  	peopleNum.className = "peopleNum";
				  	peopleNum.innerHTML = result.result.reward[i].exchange_count+"人购买";
				  	messageDiv.appendChild(peopleNum);

                    //创建商品介绍信息
				  	var introDiv = document.createElement('div');
					introDiv.id = "intro";
				  	introDiv.innerHTML = result.result.reward[i].introduction;
				    newDiv.appendChild(introDiv);
				    
				    //创建商品购买按钮
				    var getDiv = document.createElement('div');
				    getDiv.className = "exGet";
				    getDiv.id = "c"+i;
					getDiv.innerHTML= "购买";
				    newDiv.appendChild(getDiv);   
				    
				} 		
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert("网络出现问题！");
			}
	};
	$.ajax(exStore);
	
	// 实现内部导航的切换
	
	document.getElementById("purchase").onclick = function(){
		var t = Math.floor(z/2)*830+"px";
		document.getElementById("purchase").className = "selected";
		document.getElementById("exchange").className ="";
		document.getElementById("appdownload").className ="";
		$("#purArea").fadeIn(); 
		$("body").css("height",t);
		$("#exArea").fadeOut(); 
		$("#downArea").fadeOut(); 
		$(".purGet").click(function(){
			$("#confirmpurchase").fadeIn(); 
		});
	}
	
	document.getElementById("exchange").onclick = function(){
		document.getElementById("exchange").className = "selected";
		document.getElementById("purchase").className ="";
		document.getElementById("appdownload").className ="";
		$("#exArea").fadeIn(); 
		$("body").css("height","800px");
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
							Num = result["result"]["record"];
							document.getElementById("scoreNum").innerHTML = Num;
							Num1 = result["result"]["nickname"];
							document.getElementById("nickname").innerHTML = Num1;
					  		$(".inner_menu").fadeOut();  
					  		$("#myprofile").fadeIn(); 
					  		$("#newWin").fadeOut(); 
					  		checkBox();
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
	
	//	弹出兑换确认框
	function checkBox() {
	    var mydd = $('.exGet');
	    mydd.each(function(i){
            $(this).click(function(){
             	if (confirm("是否确认兑换?")) {
             	   var thisId =mydd.eq(i).attr("id");
                   var pointNumId = "pointNum"+thisId;
				   var n = document.getElementById(moneyId).innerHTML;
				   var Num = document.getElementById("scoreNum").innerHTML;
				   var a = Num - n;
				   document.getElementById("scoreNum").innerHTML= a;
				}
            });			
 		});
	} 	 
}

