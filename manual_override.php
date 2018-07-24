
<div id="ManualOverrides" class="tabcontent">

<style>
.slider {
    -webkit-appearance: none;
    width: 100%;
    height: 25px;
    background: #E3c3c3;
    outline: none;
    opacity: 0.7;
    -webkit-transition: .2s;
    transition: opacity .2s;
}
.slider:hover {
    opacity: 1;
}
.slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 25px;
    height: 25px;
    background: #4CAF50;
    cursor: pointer;
}

.eyeranger {
   margin-top: 50px;
   transform: rotate(270deg);
   -moz-transform: rotate(270deg); /*do same for other browsers if required*/
}

</style>

<?php
	$MotorCounts[0] = 100;
	$MotorCounts[1] = 200;
	$MotorCounts[2] = 300;
	$MotorCounts[3] = 400;
	$MotorCounts[4] = 500;
?>

<fieldset>
<legend>Motors</legend>

<table>
<tr></tr>
<tr>
<td>V</td><td><input type="range" min="-100" max="100" value="0" class="slider" id="v" width="200"></td><td width="50"><span id="v_pos">VPos</span></td>
  <td><button class="stop" id="vstop"  >Stop</button></td>
  <td><input type="radio" id="sync_wheels" name="wheels" onclick="" value="male"> Sync<br></td>
  <td><input type="radio" id="spin_wheels" name="wheels" value="male"> Spin<br></td>
  <td><input type="radio" id="free_wheels" name="wheels" value="male"> Free<br></td>
  <td width="150"><p> <?php echo $MotorCounts[0]; ?> </p></td>
  </tr>
<tr><td>W</td><td><input type="range" min="-100" max="100" value="0" class="slider" id="w"></td><td><span id="w_pos">WPos</span></td>
  <td><button class="stop" id="wstop"  >Stop</button></td>
  <td><p> <?php echo $MotorCounts[1]; ?> </p></td>
  </tr>
<tr><td>X</td><td><input type="range" min="-100" max="100" value="0" class="slider" id="x"></td><td><span id="x_pos">XPos</span></td>
  <td><button class="stop"  id="xstop" >Stop</button></td>
  <td><p> <?php echo $MotorCounts[2]; ?> </p></td>
  </tr>
<tr><td>Y</td><td><input type="range" min="-100" max="100" value="0" class="slider" id="y"></td><td><label id="y_pos">YPos</label></td>
  <td><button class="stop"  id="ystop" >Stop</button></td>
  <td><p> <?php echo $MotorCounts[3]; ?> </p></td>
  </tr>
<tr><td>Z</td><td><input type="range" min="-100" max="100" value="0" class="slider" id="z"></td><td><label id="z_pos">ZPos</label></td>
  <td><button class="stop"  id="zstop" >Stop</button></td>
  <td><p> <?php echo $MotorCounts[4]; ?> </p></td>
  </tr>
</table>
<span id="result_text"></span>
<button class="stop"  id="allstop" >Stop All</button>
</fieldset>

<script>
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
 RobotAjax("stop");
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
  var str = "text=pwm v"+(vslider.value/100.0).toFixed(2);
  str += " w" + (wslider.value/100.0 ).toFixed(2);
  str += " x" + (xslider.value/100.0 ).toFixed(2);  
  str += " y" + (yslider.value/100.0 ).toFixed(2);
  str += " z" + (zslider.value/100.0 ).toFixed(2);
  xhttp.open("GET", "shm_joy.php?"+str, true);
  xhttp.send("wduty=1.0");
}

function getDeviceType() 
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
};

</script>




<fieldset id="EyeGroup" >
<legend>Eyes</legend>
<canvas id="eyeCanvas" width="200" height="100" style="border:1px solid #000000;"
	onmousemove="update_eye_position(event); send_to_usb_device(); draw_eyes();" 
	onclick="toggle_mouse_track();"> 
</canvas>
<script type="text/javascript" src="eye_script.js"></script>
</fieldset>
<?php
	function ani_eyes_present()
	{
	
	}
	
	$available = ani_eyes_present();
	if ($available==false)
	{
		// make eye group INVISIBLE
	}
?>

<fieldset>
<legend>Load Cell</legend>
<canvas id="loadcellCanvas" width="100" height="100" style="border:1px solid #000000;"
	onmousemove="update_cell_forces(event); draw_cells();" > 
</canvas>
<script type="text/javascript" src="loadcell_script.js"></script>
</fieldset>

<br>
<?php
	$AnalogInputs[0] = 0x15C;
	$AnalogInputs[1] = 0x16C;
	$AnalogInputs[2] = 0x17C;
	$AnalogInputs[3] = 0x18C;
	$AnalogInputs[4] = 0x19C;
	$AnalogInputs[5] = 0x17C;
	$AnalogInputs[6] = 0x18C;
	$AnalogInputs[7] = 0x19C;
	
	$Lowside[0] = "On";
	$Lowside[1] = "On";
	$Lowside[2] = "On";
	$Lowside[3] = "On";
	$Lowside[4] = "Off";
	$Lowside[5] = "Off";
	$Lowside[6] = "Off";
	$Lowside[7] = "Off";
?>

<script>
	/*var Lowside;
	Lowside = <?php echo $Lowside[0]; ?>;
	
	function toggle_lowside(index) {
		Lowside[index] = !Lowside[index];
	}*/

</script>

<fieldset>
<legend>Pi Aux</legend>
<div>
<table style="float:left">
<tr><td width="150">Analog 1</td><td width="100"><?php echo $AnalogInputs[0]; ?></td></tr>
<tr><td>Analog 2</td><td><?php echo $AnalogInputs[1]; ?></td></tr>
<tr><td>Analog 3</td><td><?php echo $AnalogInputs[2]; ?></td></tr>
<tr><td>Analog 4</td><td><?php echo $AnalogInputs[3]; ?></td></tr>
<tr><td>Analog 5</td><td><?php echo $AnalogInputs[4]; ?></td></tr>
<tr><td>Analog 6</td><td><?php echo $AnalogInputs[5]; ?></td></tr>
<tr><td>Analog 7</td><td><?php echo $AnalogInputs[6]; ?></td></tr>
<tr><td>Analog 8</td><td><?php echo $AnalogInputs[7]; ?></td></tr>
</table>
<table>
<tr><td>Lowside Driver 1</td><td width="100"><button><?php echo $Lowside[0]; ?></button></td></tr>
<tr><td>Lowside Driver 2</td><td><button onclick=""><?php echo $Lowside[1]; ?></button></td></tr>
<tr><td>Lowside Driver 3</td><td><button><?php echo $Lowside[2]; ?></button></td></tr>
<tr><td>Lowside Driver 4</td><td><button><?php echo $Lowside[3]; ?></button></td></tr>
<tr><td>Lowside Driver 5</td><td><button><?php echo $Lowside[4]; ?></button></td></tr>
<tr><td>Lowside Driver 6</td><td><button><?php echo $Lowside[5]; ?></button></td></tr>
<tr><td>Lowside Driver 7</td><td><button><?php echo $Lowside[6]; ?></button></td></tr>
<tr><td>Lowside Driver 8</td><td><button><?php echo $Lowside[7]; ?></button></td></tr>
</table>
</div>

</fieldset>

</div>

