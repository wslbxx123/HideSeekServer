window.onload = myStart;

function myStart(){
	document.getElementById("purchase").onclick = function(){
		document.getElementById("purchase").className = "selected";
		document.getElementById("exchange").className ="";
		$("#purArea").fadeIn(); 
		$("#exArea").fadeOut(); 
	}
	
	document.getElementById("exchange").onclick = function(){
		document.getElementById("exchange").className = "selected";
		document.getElementById("purchase").className ="";
		$("#exArea").fadeIn(); 
		$("#purArea").fadeOut(); 
	}
	
	
	$("#test1").click(function(){
		$("#newWin").fadeIn(); 
	});
	
	$("#test2").click(function(){
		  	$("#newWin1").fadeIn(); 
	});
	
	$("#register").click(function(){
		var allGood = true;
		var allTags = document.getElementById("newWin1").getElementsByTagName("*");
		for (var i=0; i<allTags.length; i++) {
				if (!validTag(allTags[i])) {
					allGood = false;
		     		}
		}
		return allGood;
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
	});
	
	
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
					if (allGood ) {
					     classBack = "invalid ";
					}
					classBack += thisClass;
					break;
					default:break;
				}
		
			function invalidLabel(parentTag) {
				if (parentTag.nodeName == "LABEL") {
					parentTag.className += " invalid";
				}
			}
		}
	}		
	
	if(allGood){
		var params = [];
		params["phone"] = document.getElementById("fname").value;
		params["password"] = document.getElementById("lname").value;
		$.post("http://120.25.252.252/index.php/home/user/login", params, function(data) {
			alert(JSON.stringify(data));
			if(data["code"]=="10000"){
				Num = data["result"]["record"];
				document.getElementById("Num").innerHTML = Num;
				Num1 = data["result"]["nickname"];
				document.getElementById("nickname").innerHTML = Num1;
		  		$(".inner_menu").fadeOut();  
		  		$("#myprofile").fadeIn(); 
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
				   var Num = document.getElementById("scoreNum").innerHTML;
				   var a = Num - n;
				   document.getElementById("Num").innerHTML= a;
				}
            });			
 		});
	} 	 
}


