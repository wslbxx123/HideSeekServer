$(function(){
//	alert($("#sessionid").val());
//	alert($("#goalid").val());
	var roleImages = new Array(fairyImage,magicianImage,knightImage,monsterImage,giantImage);
	var refreshAccountData = {
				url: "/index.php/home/user/refreshAccountData",	
				type: 'POST',
				data: "session_id=" + $("#sessionid").val(),
				dataType: "json",
				
				success: function(result, status) {
					switch(result["code"]){
						case "10000":
							$("#name").html(result["result"]["nickname"]);
							$("#role").attr('src',roleImages[result["result"]["role"]]); 
							break;
					  	case "10003":
					  		alert("发送信息失败！")
					  		break;
					  	case "11000":
					  		break;
					}	
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert("网络出现问题！");
				}
		};
		$.ajax(refreshAccountData);		
	
	var getGoalById = {
		url: "/index.php/home/map/getGoalById",
		type: 'POST',
		data:"goal_id=" + $("#goalid").val(),
		
		success: function(result, status) {
			switch(result["code"]){
				case "10000":
					if(result["result"]["type"]==1||result["result"]["type"]=="1"){
						$("#monster").attr('src',mushroom); 
					}
					if(result["result"]["type"]==3||result["result"]["type"]=="3"){
						$("#monster").attr('src',bomb); 
					}
					if(result["result"]["type"]==2||result["result"]["type"]=="2"){
						$("#monster").attr('src',result["result"]["show_type_name"]); 
					}
					break;
				case "11000":
					break;
			}
		},
	};
	$.ajax(getGoalById);
	
	var btn_open = document.getElementById('openapp');
	btn_open.addEventListener('click', function() {
//			alert('https://www.hideseek.cn/index.php/home/index/hideseek_m'+'?goal_id='+$("#goalid").val());
			window.location.href = 'https://www.hideseek.cn/index.php/home/index/hideseek_m'+'?goal_id='+$("#goalid").val()+;
			
			setTimeout(function () {
//	           window.location.href = 'https://m.hideseek.cn/';
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
	
	$("#openstore").click(function(){
		window.location.href = 'https://m.hideseek.cn/';
	});
});