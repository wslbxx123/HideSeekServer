window.onload = myStart;



function myStart(){
	
//	document.getElementById("get1").onclick = moneyPay;
	
	document.getElementById("buy").onclick = function(){
		document.getElementById("buy").className = "selected";
		document.getElementById("change").className ="";
		$("#partbuy").fadeIn(); 
		$("#partchange").fadeOut(); 
	}
	document.getElementById("change").onclick = function(){
		document.getElementById("change").className = "selected";
		document.getElementById("buy").className ="";
		$("#partchange").fadeIn(); 
		$("#partbuy").fadeOut(); 
	}
	
	$("#test1").click(function(){
		  	$("#newWin").fadeIn(); 
	});
	
	$("#test2").click(function(){
		  	$("#newWin1").fadeIn(); 
	});
	
	$("#submitButton1").click(function(){
		var allGood = true;
		var allTags = document.getElementById("newWin1").getElementsByTagName("*");
		for (var i=0; i<allTags.length; i++) {
		if (!validTag(allTags[i])) {
			allGood = false;
     		}
		}
		return allGood;
		alert(allGood);
		
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
		default:
		if (allGood) {
		classBack = "invalid ";
		}
		classBack += thisClass;
		}
		return classBack;
		         }
		function invalidLabel(parentTag) {
		if (parentTag.nodeName == "LABEL") {
		parentTag.className += " invalid";
		}
	 }
  }
});
	
	
	$("#submitButton").click(function() {
		var allGood = true;
		var allTags = document.getElementById("newWin").getElementsByTagName("*");
		for (var i=0; i<allTags.length; i++) {
		if (!validTag(allTags[i])) {
			alert(1);
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
		default:
		break;;
		}
		return classBack;
		}
		
		function invalidLabel(parentTag) {
		if (parentTag.nodeName == "LABEL") {
		parentTag.className += " invalid";
		}
	 }
  }
		if(allGood){
		var params = [];
		params["phone"] = document.getElementById("fname").value;
		params["password"] = document.getElementById("lname").value;
		alert(1);
		$.post("code.html", params, function(data) {
			if(data["code"]=="10000"){
				Num = data["result"]["record"];
				alert(2);
				document.getElementById("Num").innerHTML = Num;
				Num1 = data["result"]["nickname"];
				document.getElementById("name").innerHTML = Num1;
		  		$(".inner_menu").fadeOut();  
		  		$("#myplace").fadeIn(); 
		  		$("#newWin").fadeOut(); 
		  		pointBuy();
			}
			else{
				$("#fault").fadeIn();  
			}
		}, "json");
		
	}	
});
	
	
	function pointBuy(){
    var mydd = $('.get2');
	mydd.each(function(i){
             $(this).click(function(){
             	if (confirm("是否确认兑换?")) {
             	   var thisId =mydd.eq(i).attr("id");
                   var moneyId = "money"+thisId;
				   var n = document.getElementById(moneyId).innerHTML;
				   var Num = document.getElementById("Num").innerHTML;
				   var a = Num - n;
				   document.getElementById("Num").innerHTML= a;
				   }
             })			
 		 });
	  } 	 
	}


