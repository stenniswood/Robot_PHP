
<fieldset>
<legend>Servo Set (IOExpander board)</legend>

<table style="float:left" border="1"> 
<tr><th></th><th>Left</th><th></th><th>Right</th></tr>

<tr><td><span>1:</td>
<td><input id='servo_pos_1' type="range" min="-50" max="360" value="0.5" class="xyzslider" style="width:250" ></td>
<td><span id="servo_pos_1_label">0</span></td></tr>

<tr><td><span>2:</td>
<td><input id='servo_pos_2' type="range" min="-50" max="360" value="0.5" class="xyzslider" style="width:250" ></td>
<td><span id="servo_pos_2_label">0</span></td></tr>

<tr><td><span>3:</td>
<td><input id='servo_pos_3' type="range" min="-50" max="360" value="0.5" class="xyzslider" style="width:250" ></td>
<td><span id="servo_pos_3_label">0</span></td></tr>

<tr><td><span>4:</td>
<td><input id='servo_pos_4' type="range" min="-50" max="360" value="0.5" class="xyzslider" style="width:250" ></td>
<td><span id="servo_pos_4_label">0</span></td></tr>

<tr><td><span>5:</td>
<td><input id='servo_pos_5' type="range" min="-50" max="360" value="0.5" class="xyzslider" style="width:250" ></td>
<td><span id="servo_pos_5_label">0</span></td></tr>

<tr><td><span>6:</td>
<td><input id='servo_pos_6' type="range" min="-50" max="360" value="0.5" class="xyzslider" style="width:250" ></td>
<td><span id="servo_pos_6_label">0</span></td></tr>

<tr><td><span>7:</td>
<td><input id='servo_pos_7' type="range" min="-50" max="360" value="0.5" class="xyzslider" style="width:250" ></td>
<td><span id="servo_pos_7_label">0</span></td></tr>

<tr><td><span>8:</td>
<td><input id='servo_pos_8' type="range" min="-50" max="360" value="0.5" class="xyzslider" style="width:250" ></td>
<td><span id="servo_pos_8_label">0</span></td></tr>
</table>
</fieldset>

<script>
	var s_servo_pos = [];
	s_servo_pos[1] 		 = document.getElementById( 'servo_pos_1'   );	
	s_servo_pos[2] 		 = document.getElementById( 'servo_pos_2'   );		
	s_servo_pos[3]		 = document.getElementById( 'servo_pos_3'   );	
	s_servo_pos[4] 		 = document.getElementById( 'servo_pos_4'   );		
	s_servo_pos[5] 		 = document.getElementById( 'servo_pos_5'   );	
	s_servo_pos[6] 		 = document.getElementById( 'servo_pos_6'   );		
	s_servo_pos[7] 		 = document.getElementById( 'servo_pos_7'   );	
	s_servo_pos[8] 		 = document.getElementById( 'servo_pos_8'   );	

	var l_servo_pos = [];
	l_servo_pos[1] 	= document.getElementById( 'servo_pos_1_label'   );	
	l_servo_pos[2] 	= document.getElementById( 'servo_pos_2_label'   );		
	l_servo_pos[3] 	= document.getElementById( 'servo_pos_3_label'   );	
	l_servo_pos[4] 	= document.getElementById( 'servo_pos_4_label'   );		
	l_servo_pos[5] 	= document.getElementById( 'servo_pos_5_label'   );	
	l_servo_pos[6] 	= document.getElementById( 'servo_pos_6_label'   );		
	l_servo_pos[7] 	= document.getElementById( 'servo_pos_7_label'   );	
	l_servo_pos[8] 	= document.getElementById( 'servo_pos_8_label'   );	

// HANDLERS FOR LEFT HAND SLIDER CHANGES : 
s_servo_pos[1].oninput = function() { 
	actuate_hardware_servos();
	l_servo_pos[1].innerHTML = this.value;
};
s_servo_pos[2].oninput = function() { 
	actuate_hardware_servos();
	l_servo_pos[2].innerHTML = this.value;
};
s_servo_pos[3].oninput = function() { 
	actuate_hardware_servos();
	l_servo_pos[3].innerHTML = this.value;
};
s_servo_pos[4].oninput = function() { 
	actuate_hardware_servos();
	l_servo_pos[4].innerHTML = this.value;
};

s_servo_pos[5].oninput = function() { 
	actuate_hardware_servos();
	l_servo_pos[5].innerHTML = this.value;
};
s_servo_pos[6].oninput = function() { 
	actuate_hardware_servos();
	l_servo_pos[6].innerHTML = this.value;
};
s_servo_pos[7].oninput = function() { 
	actuate_hardware_servos();
	l_servo_pos[7].innerHTML = this.value;
};
s_servo_pos[8].oninput = function() { 
	actuate_hardware_servos();
	l_servo_pos[8].innerHTML = this.value;
};

function actuate_hardware_servos()
{
	var all_servo_str="";
	for (i=1; i<8+1; i++) 
	{
		all_servo_str += "servo "+(i-1)+" "+s_servo_pos[i].value+"\r\n";
	}
	var dev = load_cell_boards[0];
	usb_send_ajax("/dev/ttyACM1", all_servo_str)
}

</script>	


