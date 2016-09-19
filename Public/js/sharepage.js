$(function(){
	alert($("#sessionid").val());
	
	var getGoalById = {
		url: "/index.php/home/map/getGoalById",
		type: 'POST',
		data:$("#goalid").val(),
		
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
	$.ajax(getGoalById);
	
	
	
});