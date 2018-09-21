<?php
	/*		This file creates the XYZ,W,WR Slider HTML controls 
		AND the Servo Angle Table
		AND contains the onclick and other functions related to them.	
	*/
?>
<div>
<table style="float:left" border="1"> <tr><th></th><th>Left</th><th></th><th>Right</th></tr>
<tr>
<td><span>X:</td>
<td><input id='l_arm_position_x' type="range" min="-300" max="300" value="0.5" class="xyzslider" style="width:150" ></td>
<td><span id="l_arm_position_x_label" style="width:50" >0</span></td>
<td><input id='r_arm_position_x' type="range" min="-300" max="300" value="0.5" class="xyzslider" style="width:150" ></td>
<td><span id="r_arm_position_x_label" width="50">0</span></span></td></tr>

<tr>
<td>Y: </td>
<td><input id='l_arm_position_y' type="range" min="-300" max="300" value="0.5" class="xyzslider" style="width:150" ></td>
<td><span id="l_arm_position_y_label">0</span></td>
<td><input id='r_arm_position_y' type="range" min="-300" max="300" value="0.5" class="xyzslider" style="width:150" ></td>
<td><span id="r_arm_position_y_label" width="50">0</span></span></td></tr>

<tr>
<td>Z: </td>
<td><input id='l_arm_position_z' type="range" min="-320" max="320" value="0.5" class="xyzslider" style="width:150" ></td>
<td><span id="l_arm_position_z_label">0</span></td>
<td><input id='r_arm_position_z' type="range" min="-300" max="300" value="0.5" class="xyzslider" style="width:150" ></td>
<td><span id="r_arm_position_z_label" width="50">0</span></span></td>

<tr>
<td>W: </td>
<td><input id='l_arm_position_approach' type="range" min="-320" max="320" value="0.5" class="xyzslider" style="width:150" ></td>
<td><span id="l_arm_position_approach_label">0</span></td>
<td><input id='r_arm_position_approach' type="range" min="-320" max="320" value="0.5" class="xyzslider" style="width:150" ></td>
<td><span id="r_arm_position_approach_label" width="50">0</span></span></td></tr>

</tr>
<td>WR: </td>
<td><input id='l_arm_position_wrist_rotate' type="range" min="-3600" max="3600" value="0.5" class="xyzslider" style="width:150" ></td>
<td><span id="l_arm_position_wrist_rotate_label">0</span></td>
<td><input id='r_arm_position_wrist_rotate' type="range" min="-3600" max="3600" value="0.5" class="xyzslider" style="width:150" ></td>
<td><span id="r_arm_position_wrist_rotate_label" width="50">0</span></span></td></tr>

<tr>
<td>Gripper:</td>
<td><input id='l_gripper_position' type="range" min="0" max="100.00" value="0.5" class="xyzslider" style="width:150" ></td>
<td><span id="l_grip">0</span></td>
<td><input id='r_gripper_position' type="range" min="0" max="100.00" value="0.5" class="xyzslider" style="width:150" ></td>
<td><span id="r_grip">0</span></td>
</tr>
</table>

<table id="servo_table" style="font-size:12px">
<tr><th>Angular Results</th><th>Left Arm</th><th>Right Arm</th></tr>
<tr><td>0 - Base</td><td>0.0</td><td>0.0</td></tr>
<tr><td>1 - Shoulder</td><td>0.0</td><td>0.0</td></tr>
<tr><td>2 - Elbow</td><td>0.0</td><td>0.0</td></tr>
<tr><td>3 - Wrist</td><td>0.0</td><td>0.0</td></tr>
<tr><td>4 - WristRotate</td><td>0.0</td><td>0.0</td></tr>
<tr><td>5 - Gripper</td><td>0.0</td><td>0.0</td></tr>
</table>
</div>


<script>
	var inp_xyz 		= document.getElementById( 'xyz' );
	var inp_approach 	= document.getElementById( 'approach' );
	
	// THE SLIDERS :
	var l_arm_slider_x 		 = document.getElementById( 'l_arm_position_x'   );	
	var l_arm_slider_y 		 = document.getElementById( 'l_arm_position_y'   );	
	var l_arm_slider_z 		 = document.getElementById( 'l_arm_position_z'   );		
	var l_grip_slider 		 = document.getElementById( 'l_gripper_position' );	
	var l_arm_slider_ap       = document.getElementById( 'l_arm_position_approach'   );	
	var l_arm_slider_wr 	  = document.getElementById( 'l_arm_position_wrist_rotate'   	   );

	// TEXT TO RIGHT of the control : 
	var l_arm_slider_label_x = document.getElementById( 'l_arm_position_x_label' );
	var l_arm_slider_label_y = document.getElementById( 'l_arm_position_y_label' );	
	var l_arm_slider_label_z = document.getElementById( 'l_arm_position_z_label' );		
	var l_grip_label  		 = document.getElementById( 'l_grip'   );
	var l_arm_slider_label_ap = document.getElementById( 'l_arm_position_approach_label'   );	
	var l_arm_slider_label_wr = document.getElementById( 'l_arm_position_wrist_rotate_label'   );	

	// THE SLIDERS 
	var r_arm_slider_x 		 = document.getElementById( 'r_arm_position_x'   	 );	
	var r_arm_slider_y 		 = document.getElementById( 'r_arm_position_y'   	 );	
	var r_arm_slider_z 		 = document.getElementById( 'r_arm_position_z'   	 );		
	var r_grip_slider 		 = document.getElementById( 'r_gripper_position' 	 );	
	var r_arm_slider_ap 	  = document.getElementById( 'r_arm_position_approach'   );	
	var r_arm_slider_wr 	  = document.getElementById( 'r_arm_position_wrist_rotate' );

	// TEXT TO RIGHT of the control : 
	var r_arm_slider_label_x = document.getElementById( 'r_arm_position_x_label' );	
	var r_arm_slider_label_y = document.getElementById( 'r_arm_position_y_label' );	
	var r_arm_slider_label_z = document.getElementById( 'r_arm_position_z_label' );	
	var r_grip_label  		 = document.getElementById( 'r_grip'   				 );
	var r_arm_slider_label_ap = document.getElementById( 'r_arm_position_approach_label'   );	
	var r_arm_slider_label_wr = document.getElementById( 'r_arm_position_wrist_rotate_label'   );	


	var angle_table = document.getElementById( "servo_table");
	
// HANDLERS FOR LEFT HAND SLIDER CHANGES : 
l_arm_slider_x.oninput = function() { 
  update_input();
  activate("left");
  l_arm_slider_label_x.innerHTML = parseFloat(this.value/10).toFixed(2);
  populate_angle_table(l_rad_servo_angle_set, 1);
};
l_arm_slider_y.oninput = function() { 
  update_input();
  activate("left");
  l_arm_slider_label_y.innerHTML = parseFloat(this.value/10.).toFixed(2);
  populate_angle_table(l_rad_servo_angle_set, 1);  
};
l_arm_slider_z.oninput = function() { 
  update_input();
  activate("left");
  l_arm_slider_label_z.innerHTML = parseFloat(this.value/10.).toFixed(2);
  populate_angle_table(l_rad_servo_angle_set, 1);  
};
l_arm_slider_ap.oninput = function() { 
  l_rad_servo_angle_set.Wrist = this.value/100;
  set_servo_angles_rad( l_arm_meshes, l_grip_meshes, arm_sizes, l_rad_servo_angle_set );  
						update_object_position(xyz);  
  l_arm_slider_label_ap.innerHTML = parseFloat(this.value/100).toFixed(2);
  populate_angle_table(l_rad_servo_angle_set, 1);  
};
l_arm_slider_wr.oninput = function() { 
	var angle_deg = this.value/10;
  l_rad_servo_angle_set.WristRotate = (angle_deg*Math.PI/180.);
  set_servo_angles_rad( l_arm_meshes, l_grip_meshes, arm_sizes, l_rad_servo_angle_set );  
  						update_object_position(xyz);
  l_arm_slider_label_wr.innerHTML = parseFloat(angle_deg).toFixed(2);
  populate_angle_table(l_rad_servo_angle_set, 1);  
};
l_grip_slider.oninput = function() { 
	open_gripper( this.value/100., "Left", l_grip_meshes );
  l_grip_label.innerHTML = this.value;
  populate_angle_table(l_rad_servo_angle_set, 1);  
};


// HANDLERS FOR RIGHT HANDE SLIDER CHANGES : 
r_arm_slider_x.oninput = function() { 
  update_input_r();
  activate("right");
  r_arm_slider_label_x.innerHTML = parseFloat(this.value/10).toFixed(2);
  populate_angle_table(l_rad_servo_angle_set, 2);  
};
r_arm_slider_y.oninput = function() { 
  update_input_r();
  activate("right");  
  r_arm_slider_label_y.innerHTML = parseFloat(this.value/10.).toFixed(2);
  populate_angle_table(l_rad_servo_angle_set, 2);    
};
r_arm_slider_z.oninput = function() { 
  update_input_r();
  activate("right");  
  r_arm_slider_label_z.innerHTML = parseFloat(this.value/10.).toFixed(2);
  populate_angle_table(l_rad_servo_angle_set, 2);    
};
r_arm_slider_ap.oninput = function() { 
  r_rad_servo_angle_set.Wrist = this.value/100;
  set_servo_angles_rad( r_arm_meshes, r_grip_meshes, arm_sizes, r_rad_servo_angle_set );
						update_object_position(xyz);  
  r_arm_slider_label_ap.innerHTML = Number(this.value/100).toFixed(2);
  populate_angle_table(l_rad_servo_angle_set, 2);    
};
r_arm_slider_wr.oninput = function() { 
  var angle_deg = this.value/10;
  r_rad_servo_angle_set.WristRotate = angle_deg*Math.PI/180.;
  set_servo_angles_rad( r_arm_meshes, r_grip_meshes, arm_sizes, r_rad_servo_angle_set );
						update_object_position(xyz);  
  r_arm_slider_label_wr.innerHTML = Number(angle_deg).toFixed(2);
  populate_angle_table(l_rad_servo_angle_set, 2);    
};
r_grip_slider.oninput = function() { 
  open_gripper( this.value/100., "Right", r_grip_meshes );
  r_grip_label.innerHTML = this.value;
  populate_angle_table(l_rad_servo_angle_set, 2);    
};	


function set_sliders(XYZ)
{
	if (XYZ.hand=='Left') {
		l_arm_slider_x.value = XYZ.x*10.;
		l_arm_slider_y.value = XYZ.y*10.;	
		l_arm_slider_z.value = XYZ.z*10.;
		if (typeof XYZ.Approach != "undefined") 
		{
			l_arm_slider_ap.value 		    = XYZ.Approach*10.;	
			l_arm_slider_label_ap.innerHTML = parseFloat(l_arm_slider_x.value/10.).toFixed(2);
		}				
		l_arm_slider_label_x.innerHTML = parseFloat(l_arm_slider_x.value/10.).toFixed(2);
		l_arm_slider_label_y.innerHTML = parseFloat(l_arm_slider_y.value/10.).toFixed(2);
		l_arm_slider_label_z.innerHTML = parseFloat(l_arm_slider_z.value/10.).toFixed(2);		

	} else {
		r_arm_slider_x.value = XYZ.x*10.;
		r_arm_slider_y.value = XYZ.y*10.;	
		r_arm_slider_z.value = XYZ.z*10.;		
		if (typeof XYZ.Approach != "undefined") {
			r_arm_slider_ap.value 			= XYZ.Approach*10.;
			r_arm_slider_label_ap.innerHTML = parseFloat(l_arm_slider_x.value/10.).toFixed(2);			
		}
		r_arm_slider_label_x.innerHTML = parseFloat(r_arm_slider_x.value/10.).toFixed(2);
		r_arm_slider_label_y.innerHTML = parseFloat(r_arm_slider_y.value/10.).toFixed(2);
		r_arm_slider_label_z.innerHTML = parseFloat(r_arm_slider_z.value/10.).toFixed(2);
	}


}

function format_Number( a )
{
	return (a<0?"":"+") + (((a<100)&&(a>=0))?" ":"") + (((a>-100)&&(a<0))?" ":"") + Number(a).toFixed(2);
}

function populate_angle_table(angle_set, cell_1_or_2)
{
	var deg_set = {};
	if (angle_set.unit=="Radians") 
		convert_to_degrees( angle_set, deg_set);
	else 
		deg_set = angle_set;

	angle_table.rows[1].cells[cell_1_or_2].innerHTML = format_Number(deg_set.Base);
	angle_table.rows[2].cells[cell_1_or_2].innerHTML = format_Number(deg_set.Shoulder);
	angle_table.rows[3].cells[cell_1_or_2].innerHTML = format_Number(deg_set.Elbow);

	angle_table.rows[4].cells[cell_1_or_2].innerHTML = format_Number(deg_set.Wrist);
	angle_table.rows[5].cells[cell_1_or_2].innerHTML = format_Number(deg_set.WristRotate);
	angle_table.rows[6].cells[cell_1_or_2].innerHTML = format_Number(deg_set.Gripper);
}

</script>

