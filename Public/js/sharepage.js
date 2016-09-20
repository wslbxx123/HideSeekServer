$(function(){
//	alert($("#sessionid").val());
//	alert($("#goalid").val());
	var roleImages = new Array(fairyImage,magicianImage,knightImage,monsterImage,giantImage);
	var width = document.body.clientWidth;
	var height = document.body.clientHeight;
	alert($("#nickname").val());
	alert($("#role").val());
	alert($("#goalid").val());
	$("#name").html($("#nickname").val());
	if($("#role").val()!=""&&$("#role").val()!=null){
		$("#role").attr('src',roleImages[$("#role").val()]); 
	}						
	else{
		$("#role").attr('src',roleImages[0]); 
	}
	var getGoalById = {
		url: "/index.php/home/map/getGoalById",
		type: 'POST',
		data:"goal_id=" + $("#goalid").val(),
		dataType: "json",
		
		success: function(result, status) {
			alert(JSON.stringify(result));
			alert(result["code"]);
			switch(result["code"]){
				case "10000":
					if(result["result"]["type"]==1||result["result"]["type"]=="1"){
						$("#monster").attr('src',mushroom); 
						$("#monster").css('width', 0.7*width);
						$("#monster").css('margin-top', 0.34*height);
						$("#monster").css('margin-left', 0.15*width);
						$("rm").html("蘑菇兽");
					}
					if(result["result"]["type"]==3||result["result"]["type"]=="3"){
						$("#monster").attr('src',bomb); 
						$("#monster").css('width', 0.86*width);
						$("#monster").css('margin-top', 0.45*height);
						$("#monster").css('margin-left', 0.12*width);
						$("rm").html("诡诈兽");
					}
					if(result["result"]["type"]==2||result["result"]["type"]=="2"){
						switch(result["result"]["show_type_name"]){
							case "egg":
								$("#monster").attr('src',egg); 
								$("#monster").css('width', 0.51*width);
								$("#monster").css('margin-top', 0.34*height);
								$("#monster").css('margin-left', 0.25*width);
								$("rm").html("怪诞兽");
								break;
							case "bird":
								$("#monster").attr('src',bird); 
								$("#monster").css('width', 0.90*width);
								$("#monster").css('margin-top', 0.25*height);
								$("#monster").css('margin-left', 0.5*width);
								$("rm").html("飞魂兽");
								break;
							case "dragon":
								$("#monster").attr('src',bird); 
								$("#monster").css('width', 0.7*width);
								$("#monster").css('margin-top', 0.32*height);
								$("#monster").css('margin-left', 0.15*width);
								$("rm").html("龙冠兽");
								break;
							case "giraffe":
								$("#monster").attr('src',giraffe); 
								$("#monster").css('width', 0.8*width);
								$("#monster").css('margin-top', 0.27*height);
								$("#monster").css('margin-left', 0.10*width);
								$("rm").html("长悠兽");
								break;
							case "cow":
								$("#monster").attr('src',cow); 
								$("#monster").css('width', 0.76*width);
								$("#monster").css('margin-top', 0.31*height);
								$("#monster").css('margin-left', 0.12*width);
								$("rm").html("呲牙兽");
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
	btn_open.addEventListener('click', function() {
//			alert('https://www.hideseek.cn/index.php/home/index/hideseek_m'+'?goal_id='+$("#goalid").val());
			window.location.href = 'https://www.hideseek.cn/home/mindex/index'+'?goal_id='+$("#goalid").val();
			
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