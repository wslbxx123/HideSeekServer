$(function(){
	var roleImages = new Array(fairyImage,magicianImage,knightImage,monsterImage,giantImage);
	var width = document.body.clientWidth;
	var height = document.body.clientHeight;
	var openSuccess;
	$("#name").html($("#nickname").val());
	
	if($("#role").val()!=""&&$("#role").val()!=null){
		openSuccess = true;
		$("#myrole").attr('src',roleImages[$("#role").val()]); 
		$("#message").html('向您发出一个战斗邀请！'); 
	}						
	else{
		openSuccess = false;
		
	}
	
	var getGoalById = {
		url: "/index.php/home/map/getGoalById",
		type: 'POST',
		data:"goal_id=" + $("#goalid").val(),
		dataType: "json",
		
		success: function(result, status) {
			switch(result["code"]){
				case "10000":
					if(result["result"]["type"]==1||result["result"]["type"]=="1"){
						$("#monster").attr('src',mushroom); 
						$("#monster").css('width', 0.7*width);
						$("#monster").css('margin-top', 0.34*height);
						$("#monster").css('margin-left', 0.15*width);
						$("#rm").html("蘑菇兽");
					}
					if(result["result"]["type"]==3||result["result"]["type"]=="3"){
						$("#monster").attr('src',bomb); 
						$("#monster").css('width', 0.86*width);
						$("#monster").css('margin-top', 0.45*height);
						$("#monster").css('margin-left', 0.12*width);
						$("#rm").html("诡诈兽");
					}
					if(result["result"]["type"]==2||result["result"]["type"]=="2"){
						$("#monster").attr('src',eval(result["result"]["show_type_name"])); 
						switch(result["result"]["show_type_name"]){
							case "cow":
								$("#rm").html("呲牙兽");
								break;
							case "bird":
								$("#rm").html("飞魂兽");
								break;
							case "dragon":
								$("#rm").html("龙冠兽");
								break;	
							case "giraffe":
								$("#rm").html("长悠兽");
								break;
							case "egg":
								$("#rm").html("怪诞兽");
								break;
						}
					}
					break;
				case "11000":
					break;
			}
		},
	};
	$.ajax(getGoalById);
	
	var btn_open = document.getElementById('openapp');
	var u = navigator.userAgent;
   	var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端 
   	var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
    
	btn_open.addEventListener('click', function() {
		if(isAndroid){
		   	alert("Android近期上线，敬请期待！")
		}
		else if(isiOS){
			var ver = (navigator.appVersion).match(/OS (\d+)_(\d+)_?(\d+)?/);  
    		ver = parseInt(ver[1], 10);  
			if(ver >= 9){  
	        	window.location.href = 'https://www.hideseek.cn/home/mindex/sharePage'+'?goal_id='+$("#goalid").val();
			}  
			
			else{
				alert("请使用浏览器查看页面！");
		        window.location.href = 'hideseek://'; 
			} 
			
			setTimeout(function(){
	           window.location.href = 'https://itunes.apple.com/us/app/hideseek/id1154398844?ls=1&mt=8';
	        }, 100);
	   }
	   else{
	   	alert("亲，请使用手机浏览器打开链接！")
	   }
	   
	   if(!openSuccess){
	   	 alert(1);
	   	 $("#myrole").attr('src',warning); 
		 $("#message").html('跳转失败，请使用浏览器查看页面！'); 
	   }
	});
	
	$("#openstore").click(function(){
		window.location.href = 'https://m.hideseek.cn';
	});
});