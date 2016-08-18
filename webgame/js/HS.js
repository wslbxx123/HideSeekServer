    var myPos = new Array(40,33,72,65,41,61,15,63);
	var myNum = Math.random();
	var randomNum = Math.floor ((myNum * 4));
	var myPix = new Array("img/road0.png","img/road1.png","img/road2.png","img/road3.png");
	var n = 0;
	var score=0;
	var a = 1;
	var m = 0;
	var b = 0;
	var flag = true;
	
$(document).ready(function(){
	start();
	move();
});

function start(){
	$("#getit").click(function(){
		  if(flag) {
		  	$("#guidemap").fadeIn();  
		  	$("body").scrollLeft(100);
		  	chooseBoy();
		  	chooseMush();
		  }
		});
	$("#closeBox").click(function(){
		  $("#guidemap").fadeOut();
		   
	});
	
	setTimeout(function(){
		choosePic();
		$("#road").fadeIn("slow")},1000);


function choosePic() {
	document.getElementById("road").src = myPix[randomNum];
	document.getElementById("spot").style.marginTop = myPos[randomNum*2]+"%";
	document.getElementById("spot").style.marginLeft = myPos[randomNum*2+1]+"%";
	var mushPos = new Array(72,65,41,61,15,63,40,33);
	document.getElementById("box").style.marginTop = mushPos[randomNum*2]+"%";
	document.getElementById("box").style.marginLeft = mushPos[randomNum*2+1]+"%";
	var mushMeter = new Array(500,764,1000,800);
	document.getElementById("meter1").innerHTML= mushMeter[randomNum];
}

function chooseBoy() {
	document.getElementById("boy").style.top = myPos[randomNum*2]+12+"%";
	document.getElementById("boy").style.left = myPos[randomNum*2+1]+4+"%";
}

function chooseMush() {
	var mushPos = new Array(72,65,41,61,15,63,40,33);
	document.getElementById("mapbox").style.top = mushPos[randomNum*2]+12+"%";
	document.getElementById("mapbox").style.left = mushPos[randomNum*2+1]+4+"%";
	}
}

function move(){
    $("#mapbox").click(function(){
		 moveMush();
		 document.getElementById("step").currentTime=0;
		 document.getElementById("step").play();
	});
}

function moveMush(){
	if(randomNum==0){
		n++;
		if(n>0&&(52-n)>=40){
	    	if(n%2==0){
	    		document.getElementById("boy").src = "img/boyup.gif";
	    	}
	    	else{
	    		document.getElementById("boy").src = "img/boyup1.gif";
	    	}
	    	document.getElementById("boy").style.top =(52-n)+"%";	
		}
		if((52-n)<40&&(37+n-12)<=65){
	    	if(n%2==0){
	    		document.getElementById("boy").src = "img/boyright.gif";
	    	}
	    	else{
	    		document.getElementById("boy").src = "img/boyright1.gif";
	    	}
		    document.getElementById("boy").style.left =(37+n-12)+"%";
		}
		if((37+n-12)>65&&(40+n-40)<=64){
	    	if(n%2==0){
	    		document.getElementById("boy").src = "img/boydown.gif";
	    	}
	    	else{
	    		document.getElementById("boy").src = "img/boydown1.gif";
	    	}
		    document.getElementById("boy").style.top =(40+n-40)+"%";
	 	}
		if((40+n-40)>64&&(65+n-64)<=71){
	    	if(n%2==0){
	    		document.getElementById("boy").src = "img/boydown.gif";
	    	}
	    	else{
	    		document.getElementById("boy").src = "img/boydown1.gif";
	    	}
		    document.getElementById("boy").style.top =(65+n-64)+"%";
	 	}
		if((65+n-64)>71&&(64+n-70)<=71){
	    	if(n%2==0){
	    		document.getElementById("boy").src = "img/boyright.gif";
	    	}
	    	else{
	    		document.getElementById("boy").src = "img/boyright1.gif";
	    	}
		    document.getElementById("boy").style.left =(64+n-70)+"%";
	 	}
		if((64+n-70)>71&&(71+n-77)<=84){
	    	if(n%2==0){
	    		document.getElementById("boy").src = "img/boydown.gif";
	    	}
	    	else{
	    		document.getElementById("boy").src = "img/boydown1.gif";
	    	}
		    document.getElementById("boy").style.top =(71+n-77)+"%";
	 	}
		if((71+n-77)>84&&(71-n+90)>69){
	    	if(n%2==0){
	    		document.getElementById("boy").src = "img/boydown.gif";
	    	}
	    	else{
	    		document.getElementById("boy").src = "img/boydown1.gif";
	    	}
		    document.getElementById("boy").style.left =(71-n+90)+"%";
	 	}
		if((71-n+90)==69){
		    count();
		}
	}
	
	if(randomNum==1){
		n++;
		if(n>0&&(69+n)<=71){
	    	if(n%2==0){
	    		document.getElementById("boy").src = "img/boyright.gif";
	    	}
	    	else{
	    		document.getElementById("boy").src = "img/boyright1.gif";
	    	}
	    	document.getElementById("boy").style.left =(69+n)+"%";	
		}
		if((69+n)>71&&(84-n+2)>=53){
	    	if(n%2==0){
	    		document.getElementById("boy").src = "img/boyup.gif";
	    	}
	    	else{
	    		document.getElementById("boy").src = "img/boyup1.gif";
	    	}
	    	document.getElementById("boy").style.top =(84-n+2)+"%";	
		}
		if((84-n+2)<53&&(71-n+33)>65){
	    	if(n%2==0){
	    		document.getElementById("boy").src = "img/boyleft.gif";
	    	}
	    	else{
	    		document.getElementById("boy").src = "img/boyleft1.gif";
	    	}
	    	document.getElementById("boy").style.left =(71-n+33)+"%";	
		}
		if((71-n+33)==65){
	    	count();
		}
	}
	
	if(randomNum==2){
		n++;
	    if(n>0&&(53-n)>=27){
	    	if(n%2==0){
	    		document.getElementById("boy").src = "img/boyup.gif";
	    	}
	    	else{
	    		document.getElementById("boy").src = "img/boyup1.gif";
	    	}
	    	document.getElementById("boy").style.top =(53-n)+"%";
		}
	    if((53-n)<27&&(65+n-26)<=67){
	    	if(n%2==0){
	    		document.getElementById("boy").src = "img/boyright.gif";
	    	}
	    	else{
	    		document.getElementById("boy").src = "img/boyright1.gif";
	    	}
		    document.getElementById("boy").style.left =(65+n-26)+"%";
	 	}
	    if((65+n-26)==67){
	    	count();
	    	
	    } 
	}
	
	if(randomNum==3){
		n++;
	 	if(n>0&&(27+n)<=40){
	 		if(n%2==0){
	    		document.getElementById("boy").src = "img/boydown.gif";
	    	}
	    	else{
	    		document.getElementById("boy").src = "img/boydown1.gif";
	    	}
	    	document.getElementById("boy").style.top =(27+n)+"%";
		}
	    if((27+n)>40&&(67-n+13)>37){
	    	if(n%2==0){
	    		document.getElementById("boy").src = "img/boyleft.gif";
	    	}
	    	else{
	    		document.getElementById("boy").src = "img/boyleft1.gif";
	    	}
		    document.getElementById("boy").style.left =(67-n+13)+"%";
	 	}
        if((67-n+13)<=37&&(41+n-42)<52){
        	if(n%2==0){
	    		document.getElementById("boy").src = "img/boydown.gif";
	    	}
	    	else{
	    		document.getElementById("boy").src = "img/boydown1.gif";
	    	}
		    document.getElementById("boy").style.top =(41+n-42)+"%";
        }
	    if((41+n-42)>=52){		   
	    	count();
	 	}
	}	
}

function count(){
	flag = false;
    a++;
    document.getElementById("N").innerHTML = a;
	n = 0;
	var myTreasure = new Array("img/mushroom.gif","img/bomb.gif");
	var myN = 1;
	var myN = Math.floor ((Math.random() * myTreasure.length));
	document.getElementById("mushroomgif").src = myTreasure[myN];
	document.getElementById("guidemap").style.display= "none";
    randomNum++;
 	if(randomNum==myPix.length){
		randomNum = 0;
	}
	document.getElementById("road").src = myPix[randomNum];
	start();
   	document.getElementById("mushroomgif").style.display= "block";
   	if(myN==0){
   		document.getElementById("getButton").onclick = function() {
   		document.getElementById("score1").play();
    	document.getElementById("mushroomgif").style.display = "none";
    	m++;
    	score = score+2;
    		document.getElementById("score").innerHTML = "+"+2;
    		document.getElementById("score").style.display = "block"
    		$("#score").fadeOut(2000); 
    		sum();
    		flag = true;
   	}
   }
	if(myN==1){
		document.getElementById("getButton").onclick = function() {}
		setTimeout(function(){
			document.getElementById("bbomb").currentTime=0;
		    document.getElementById("bbomb").play();
			document.getElementById("getButton").style.backgroundImage = "url('img/get1.png')";
		    document.getElementById("mushroomgif").style.display = "none";
		    b++;
		    score = score-1;
    		document.getElementById("score").innerHTML = "-"+1;
    		document.getElementById("score").style.display = "block";
    		$("#score").fadeOut(1500); 
    		sum();
		},3000);
			document.getElementById("getit").onclick = function() {
    		document.getElementById("getButton").style.backgroundImage = "url('img/get.png')";
    		flag = true;
   		}	
	 }
	
  }

function sum(){
	if(a>4){
		document.getElementById("mushroomN").innerHTML = m;
		document.getElementById("bombN").innerHTML = b;
		document.getElementById("sum").innerHTML = score;
		document.getElementById("report").style.display = "block";
	}
}

