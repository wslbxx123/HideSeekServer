window.onload = myStart;

function myStart(){
	$("#test1").click(function(){
		  	$("#newWin").fadeIn();  
	});
	
	$("#submitButton").click(function() {
		var params = [];
		params["phone"] = document.getElementById("fname").value;
		params["password"] = document.getElementById("lname").value;
		$.post("code.html", params, function(data) {
		r = data["result"]["record"];
		}, "json")
	})
}
