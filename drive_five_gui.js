var sync  = document.getElementById("sync_wheels");
var spin  = document.getElementById("spin_wheels");

var vstop  = document.getElementById("vstop");
var wstop  = document.getElementById("wstop");
var xstop  = document.getElementById("xstop");
var ystop  = document.getElementById("ystop");
var zstop  = document.getElementById("zstop");
var allstop  = document.getElementById("allstop");

var vslider = document.getElementById("v");
var vlabel  = document.getElementById("v_pos");
var wslider = document.getElementById("w");
var wlabel  = document.getElementById("w_pos");
var xslider = document.getElementById("x");
var xlabel  = document.getElementById("x_pos");
var yslider = document.getElementById("y");
var ylabel  = document.getElementById("y_pos");
var zslider = document.getElementById("z");
var zlabel  = document.getElementById("z_pos");


vlabel.innerHTML = vslider.value;
wlabel.innerHTML = wslider.value;
xlabel.innerHTML = xslider.value;
ylabel.innerHTML = yslider.value;
zlabel.innerHTML = zslider.value;

vstop.onclick = function() {   
	vslider.value = 0;	vlabel.innerHTML = vslider.value;	
	if ((sync.checked) || (spin.checked))
	{	wslider.value = 0;		wlabel.innerHTML = wslider.value;	}
	updatePWMs();	
}
wstop.onclick = function() {   wslider.value = 0;	wlabel.innerHTML = wslider.value;	updatePWMs();	}
xstop.onclick = function() {   xslider.value = 0;	xlabel.innerHTML = xslider.value;	updatePWMs();	}
ystop.onclick = function() {   yslider.value = 0;	ylabel.innerHTML = yslider.value;	updatePWMs();	}
zstop.onclick = function() {   zslider.value = 0;	zlabel.innerHTML = zslider.value;	updatePWMs();	}


RobotAjax = function( TheSentence ) 
{
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("result_text").innerHTML =
      this.responseText;
    }
  };  
  var str = "text="+TheSentence;
  xhttp.open("GET", "shm_joy.php?"+str, true);
  xhttp.send();	
}

allstop.onclick = function() 
{ 
 vslider.value = 0;	vlabel.innerHTML = vslider.value;	
 wslider.value = 0;	wlabel.innerHTML = wslider.value;	
 xslider.value = 0;	xlabel.innerHTML = xslider.value;	
 yslider.value = 0;	ylabel.innerHTML = yslider.value;	
 zslider.value = 0;	zlabel.innerHTML = zslider.value;	
 //updatePWMs();	
 
 // TEMPORARY FOR DEBUG ONLY:
 //RobotAjax("stop");
/*  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("result_text").innerHTML =
      this.responseText;
    }
  };  
  var str = "text=stop";
  xhttp.open("GET", "shm_joy.php?"+str, true);
  xhttp.send("wduty=1.0");	*/
}


vslider.oninput = function() { 
  vlabel.innerHTML = this.value;  
	if (sync.checked) {		
		wslider.value    = vslider.value;
		wlabel.innerHTML = vslider.value;
	} else if (spin.checked) {
		wslider.value    = 1. - vslider.value;
		wlabel.innerHTML = wslider.value;
	
	}
  updatePWMs();
}
wslider.oninput = function() { 
  wlabel.innerHTML = this.value;
  updatePWMs();  
}
xslider.oninput = function() { 
  xlabel.innerHTML = this.value;
  updatePWMs();
}
yslider.oninput = function() { 
  ylabel.innerHTML = this.value;
  updatePWMs();
}
zslider.oninput = function() { 
  zlabel.innerHTML = this.value;
  updatePWMs();
}

// VIA AJAX, this 
function updatePWMs() 
{
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("result_text").innerHTML =
      this.responseText;
    }
  };

  var str = "pwm v"+(vslider.value/100.0).toFixed(2);
  str += " w" + (wslider.value/100.0 ).toFixed(2);
  str += " x" + (xslider.value/100.0 ).toFixed(2);  
  str += " y" + (yslider.value/100.0 ).toFixed(2);
  str += " z" + (zslider.value/100.0 ).toFixed(2);

	var payload;
	payload = "path="+drive_five_boards[1].path;
	payload += "&data="+str;

	xhttp.open("POST", "usb_send_receive.php", true);		 
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.send(payload);
}

/*function getDeviceType() 
{
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("result_text").innerHTML =
      this.responseText;
    }
  };
  var str = "device type";
  xhttp.open("GET", "shm_joy.php?"+str, true);
  xhttp.send("wduty=1.0");
};*/

