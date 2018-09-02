
<script>

	var paths_xyz = [];
	
//	var store_xyz = document.getElementById( 'store_position' );
function store_xyz(l_or_r)
{
	var xyz  = {};
	if (l_or_r=="left") {
		xyz.x = l_arm_slider_x.value/10.;
		xyz.y = l_arm_slider_y.value/10.;
		xyz.z = l_arm_slider_z.value/10.;
		//xyz.Approach=;
		l_positions.push(xyz);
	} else {
		xyz.x = r_arm_slider_x.value/10.;
		xyz.y = r_arm_slider_y.value/10.;
		xyz.z = r_arm_slider_z.value/10.;	
		r_positions.push(xyz);
	}		
}	
function move_to_preset()
{
	var xyz={};
	xyz.x = l_positions[preset_position_index].x;
	xyz.y = l_positions[preset_position_index].y;
	xyz.z = l_positions[preset_position_index].z;
//	xyz.Approach = inp_approach.value * Math.PI/180.;

	l_arm_slider_x.value = l_positions[preset_position_index].x;
	l_arm_slider_y.value = l_positions[preset_position_index].y;
	l_arm_slider_z.value = l_positions[preset_position_index].z;		
	do_inverse_kinematics("left", xyz);


	xyz.x = r_positions[preset_position_index].x;
	xyz.y = r_positions[preset_position_index].y;
	xyz.z = r_positions[preset_position_index].z;
	r_arm_slider_x.value = r_positions[preset_position_index].x;
	r_arm_slider_y.value = r_positions[preset_position_index].y;
	r_arm_slider_z.value = r_positions[preset_position_index].z;
	do_inverse_kinematics("right", xyz);
}
function prev_preset()
{
	preset_position_index--;
	if (preset_position_index < 0)
		preset_position_index = 0;
	move_to_preset();
}
function next_preset()
{
	preset_position_index++;
	if (preset_position_index >= l_positions.length)
		preset_position_index = l_positions.length-1;
	move_to_preset();
}


var object_index = 0;	// 
function prev_object()
{
	object_index--;
	if (object_index < 0)
		object_index = 0;
	move_to_object();
}
function next_object()
{
	object_index++;
	if (object_index >= object_meshes.length)
		object_index = object_meshes.length-1;
	move_to_object();
}

function determine_smallest_axis(box3)
{
	var dx = box3.max.x - box3.min.x;
	var dy = box3.max.y - box3.min.y;
	var dz = box3.max.z - box3.min.z;
	var smallest_axis = 'x';
	if ((dx<dy) && (dx<dz))		smallest_axis = 'x';
	if ((dy<dx) && (dy<dz))		smallest_axis = 'y';
	if ((dz<dx) && (dz<dy))		smallest_axis = 'z';
	return smallest_axis;	
}

function move_to_object()
{
	var xyz={};
//	var zero_vec = new THREE.Vector3( 0,0,0 );
	var zero_vec;
	object_meshes[object_index].geometry.computeBoundingBox();
	var box3 = object_meshes[object_index].geometry.boundingBox;
	var smallest_axis = determine_smallest_axis(box3);	
	zero_vec = box3.getCenter();
	var distance_between = box3.max[smallest_axis] - box3.min[smallest_axis];
	
	object_meshes[object_index].localToWorld( zero_vec );
	
	var str_xyz = "<" + zero_vec.x + ", "+ zero_vec.y + ", "+zero_vec.z+"> ";
	var type = object_meshes[object_index].geometry.type;
	var info = type+ "; smallest axis="+smallest_axis+"; grip="+distance_between+" at "+str_xyz;

	xyz.Approach = inp_approach.value * Math.PI/180.;
	xyz.x = zero_vec.x;
	xyz.y = zero_vec.y;
	xyz.z = zero_vec.z;
	var result = do_inverse_kinematics("left", xyz);
	if (result==false) {
		r_object_grab_feedback.innerHTML = "Using right arm.";
		xyz.x = zero_vec.x;
		xyz.y = zero_vec.y;
		xyz.z = zero_vec.z;
		result = do_inverse_kinematics("right", xyz);
		if (result) {
			r_object_grab_feedback.innerHTML = "Grabbed with right arm. "+info;
		} else {
			r_object_grab_feedback.innerHTML = "Out of reach!"+type + str_xyz;			
			open_gripper_distance( distance_between, r_grip_meshes );
		}
	} else {
			r_object_grab_feedback.innerHTML = "Grabbed with Left arm"+info;
			open_gripper_distance( distance_between, l_grip_meshes );
	}
}
function parse_into_xyz_xyz(XYZ1,XYZ2)
{
	var str = path_params.value.split(" ");
	//var XYZ1 = {};		var XYZ2 = {};
	XYZ1.x = parseFloat(str[0]);	
	XYZ1.y = parseFloat(str[1]);	
	XYZ1.z = parseFloat(str[2]);
	XYZ2.x = parseFloat(str[3]);	
	XYZ2.y = parseFloat(str[4]);	
	XYZ2.z = parseFloat(str[5]);
}
function create_path()
{
	var path_obj = {};
	path_obj.name = "New path"+paths_xyz.length;	
	path_obj.values = [];

	var XYZ1 = {};
	var XYZ2 = {};
	var num_samples = 10;	
	var str = path_catagory.selectedOptions[0].innerHTML;
	path_obj.name = str+paths_xyz.length;	
	
	if (str=="Pick and Place") {
		parse_into_xyz_xyz   ( XYZ1, XYZ2 );
		create_pick_and_place( path_obj.values, XYZ1, XYZ2 );
	} else if (str=="Circular Path") {	
		var radius = parseInt(path_params.value);
		path_obj.name = str+"_r="+radius;	
		create_circular_path(path_obj.values, radius, -10, 50);
	} else if (str=="Line Segment") {
	
		parse_into_xyz_xyz(XYZ1,XYZ2)
		create_line_segment(path_obj.values, XYZ1, XYZ2, num_samples);
	} else if (str=="Line Path") {
	
		create_line_path(path_obj.values, XYZs, num_samples) 
	} else if (str=="Shake Hands") {
		var y = parseFloat( path_params.value );
		create_hand_shake_path( path_obj.values, y );
	} else if (str=="Shake") {
		var y = parseFloat(path_params.value);			
		var height = 10;
		create_shake_path(path_obj.values, y, height, which_hand.value, 3);
	} else if (str=="Put hands together") {
	
	} else if (str=="Line Segment") {
	
	}
	paths_xyz.push( path_obj );
	populate_path_names();
}

function create_shake_path(path_values, y, height, which_hand, num_samples)
{
	var center = {};
	var XYZ1 = {};	
	var XYZ2 = {};
	XYZ1.x =  height/2;
	XYZ2.x = -height/2;
	XYZ1.y = y;	
	XYZ2.y = y;
	
	if (which_hand=="Left") {
		XYZ1.z = -10;
		XYZ2.z = -10;
		center.z = 10;
	} else {
		XYZ1.z = 10;
		XYZ2.z = 10;	
		center.z = 10;
	}

	center.x = 0;	center.y = y;		
	path_values.push(center);
	
	create_line_segment( path_values, XYZ1,XYZ2, num_samples );	// go up
	create_line_segment( path_values, XYZ2,XYZ1, num_samples );	// go down
	
	create_line_segment( path_values, XYZ1,XYZ2, num_samples );	// go up
	create_line_segment( path_values, XYZ2,XYZ1, num_samples );	// go down
	
	create_line_segment( path_values, XYZ1,center, num_samples ); // go center
}
function create_hand_shake_path( path_values, y )
{
	var handshake_height = 10;
	create_shake_path(path_values, y, handshake_height, "Right", 3);
}
function create_line_segment(path, XYZ1, XYZ2, num_samples)
{
	// Linear Interpolate between pts:
	var d_x = (XYZ2.x - XYZ1.x)/num_samples;
	var d_y = (XYZ2.y - XYZ1.y)/num_samples;
	var d_z = (XYZ2.z - XYZ1.z)/num_samples;
	//path = [];
	for (i=0; i<num_samples; i++)
	{
		var newXYZ = {};
		newXYZ.x = i*d_x + XYZ1.x;
		newXYZ.y = i*d_y + XYZ1.y;
		newXYZ.z = i*d_z + XYZ1.z;
		path.push(newXYZ);
	}	
}
/* For multiple segmented line 
num_samples is per line_segment!
*/
function create_line_path(path, XYZs, num_samples)
{
	for (i=0; i<XYZs.length-1; i++) 
	{
		create_line_segment(path, XYZs[i], XYZs[i+1], num_samples);		
	};
	//paths_xyz.push(path_obj);
}
function create_pick_and_place(path_values,XYZ1,XYZ2)
{
	// Go to 1st Point:	
	//path_values.push(XYZ1);

	var maxX = Math.max(XYZ1.x,XYZ2.x);
	
	// Raise up an amount:	
	xyzR  = {};		xyzR.y=XYZ1.y;	xyzR.z=XYZ1.z;
	xyzR.x = maxX + 10;
	//path_values.push(xyz);	
	// 1st Pt to a raised position:
	create_line_segment(path_values, XYZ1, xyzR, 10);
	
	
	// Move to Pt above Destination:
	xyz2R  = {};		xyz2R.y=XYZ2.y;	xyz2R.z=XYZ2.z;
	xyz2R.x = maxX + 10;
	create_line_segment(path_values, xyzR, xyz2R, 10);	
	//path_values.push(xyz);		
	//create_line_segment(path_values, XYZ1, xyz, 5);

	// Go to last Point (lower):	
	create_line_segment(path_values, xyz2R, XYZ2, 10);
	path_values.push(XYZ2);
}
function create_circular_path(path_values, radius, normal_vector, num_samples)
{
	var angle = 0;
	var angle_step = Math.PI*2 / num_samples;
	//var path_obj = {};
	//path_obj.name = name+"_r="+radius;
	//path_obj.values = [];
	var xyz  = {};
	for (i=0; i<num_samples; i++)
	{
		xyz  = {};
		xyz.x = radius * Math.cos(angle);
		xyz.y = normal_vector;
		xyz.z = radius * Math.sin(angle);
		angle += angle_step;
		path_values.push(xyz);
	}	
	//paths_xyz.push(path_obj);
}
function populate_path_names()
{
	// REMOVE ALL TABLE ROWS:
	var rowCount = path_table.rows.length;
	for (var x=rowCount-1; x>=0; x--) {
	   path_table.deleteRow(x);
	}

	//paths_xyz.forEach(  (path,index) => {
	for (i=0; i<paths_xyz.length; i++)
	{
		var last_row = path_table.rows.length;
		var row = path_table.insertRow(-1);
		row.setAttribute("onclick","chosen_path_index = this.rowIndex;");
		var cell0 = row.insertCell(0);
		//var cell1 = row.insertCell(1);				
		//var cell2 = row.insertCell(2);		
		cell0.innerHTML = paths_xyz[i].name;
	}
}

var simTimer=null;
var chosen_path_index=0;
var path_point_index =0;
function arm_path_execution()
{
	var len = paths_xyz[chosen_path_index].values.length;
	if (path_point_index >= len) 
		if (repeat_path)
			path_point_index = 0;
		else
			stop_simulate_path();
			
	do_inverse_kinematics("left", paths_xyz[chosen_path_index].values[path_point_index] );
	do_inverse_kinematics("right", paths_xyz[chosen_path_index].values[path_point_index] );
	set_sliders(paths_xyz[chosen_path_index].values[path_point_index]);
	path_point_index++;
}
function stop_simulate_path()
{
	window.clearInterval( simTimer );
	simTimer = null;
}
function simulate_path()
{
	if (simTimer!=null)	return;		// Already running.
	
	var time_period_ms = path_speed.value;
	//chosen_path_index=0;
	path_point_index = 0;
//	selected_path = path_table.selectedOptions[0].
//	paths_xyz[chosen_path_index].values.forEach (  () => {
	//var natural_step_time = 50; //step_rate_ctrl.value;
	simTimer = window.setInterval( arm_path_execution, time_period_ms );	
		//setTimeout( move_arm_to, delay );
}

function add_path_to_sequencer()
{

}
function interpolate_between_presets( p_index1, p_index2, num_steps )
{

}
</script>

