var l_servo_limits_deg = {
	Base 	: {min:0, max:360},
	Shoulder: {min:0, max:360},
	Elbow   : {min:0, max:360},
	Wrist   : {min:0, max:360},
	WristRotate: {min:0, max:360},
	Gripper : {min:0, max:360},
	unit    : "Degrees",
};
var r_servo_limits_deg = {
	Base 	: {min:0, max:360},
	Shoulder: {min:0, max:360},
	Elbow   : {min:0, max:360},
	Wrist   : {min:0, max:360},
	WristRotate: {min:0, max:360},	
	Gripper : {min:0, max:360},
	unit    : "Degrees",
};
var l_servo_limits_rad = {};
var r_servo_limits_rad = {};

var l_deg_servo_angle_set = {
	Base    : 0.0,
	Shoulder: 0.0,
	Elbow   : 0.0,
	Wrist   : 0.0,
	WristRotate   : 0.0,
	Gripper : 0.0,
	hand	: "Left",
	unit    : "Degrees",
};
var r_deg_servo_angle_set = {
	Base    : 0.0,
	Shoulder: 0.0,
	Elbow   : 0.0,
	Wrist   : 0.0,
	WristRotate   : 0.0,	
	Gripper : 0.0,	
	hand	: "Right",
	unit    : "Degrees",
};

var l_rad_servo_angle_set={};
var r_rad_servo_angle_set={};


var vec_elbow  = new THREE.Vector3( 0,0, arm_sizes.upper_arm_length + arm_sizes.upper_arm_length/2 );


function convert_to_radians( angle_set, output_set )
{
	if (angle_set.unit != "Degrees") return;
	if (typeof output_set == "undefined")
		output_set = angle_set;
	
	output_set.Base     = angle_set.Base     * Math.PI / 180.;			
	output_set.Shoulder = angle_set.Shoulder * Math.PI / 180.;
	output_set.Elbow 	= angle_set.Elbow    * Math.PI / 180.;
	output_set.Wrist 	= angle_set.Wrist    * Math.PI / 180.;				
	output_set.WristRotate 	= angle_set.WristRotate    * Math.PI / 180.;				
	output_set.Gripper 	= angle_set.Gripper;
	output_set.unit 	= "Radians";
}
function convert_to_degrees( angle_set, output_set )
{
	if (angle_set.unit != "Radians") return;
	if (typeof output_set == "undefined")
		output_set = angle_set;
	
	output_set.Base      = angle_set.Base     * 180. / Math.PI ;			
	output_set.Shoulder  = angle_set.Shoulder * 180. / Math.PI ;
	output_set.Elbow 	 = angle_set.Elbow    * 180. / Math.PI ;
	output_set.Wrist 	 = angle_set.Wrist    * 180. / Math.PI ;				
	output_set.WristRotate 	= angle_set.WristRotate * 180. / Math.PI ;					
	output_set.Gripper 	 = angle_set.Gripper;						
	output_set.unit = "Degrees";
}

function set_servo_angles_rad( meshes, grip_mesh, arm_sizes, angle_set )
{
		if (angle_set.unit == "Degrees")	return;

		var Base = angle_set.Base - Math.PI/2.;
		meshes.upper_arm.rotation.x = Base; 
		meshes.upper_arm.rotation.y = angle_set.Shoulder;
		meshes.upper_arm.updateMatrixWorld();
		
		meshes.elbow.rotation.x     = Base;
		meshes.elbow.rotation.y     = angle_set.Shoulder;
		meshes.elbow.updateMatrixWorld();

		// Relocate the ForeArm to match the Elbow:
		vec_elbow     = new THREE.Vector3( 0,0, arm_sizes.upper_arm_length );
		var rot_point = meshes.elbow.localToWorld( vec_elbow );
		meshes.fore_arm.position.x = rot_point.x;
		meshes.fore_arm.position.y = rot_point.y;				
		meshes.fore_arm.position.z = rot_point.z;				
										
		meshes.fore_arm.rotation.x  = Base;
		meshes.fore_arm.rotation.y  = angle_set.Shoulder + angle_set.Elbow;
		meshes.fore_arm.updateMatrixWorld();
		
		// Relocate the Wrist to match the Forearm stub:	
		var fa_vec     = new THREE.Vector3( 0,0, arm_sizes.upper_arm_length );
		rot_point = meshes.fore_arm.localToWorld( fa_vec );
		meshes.wrist.position.x 		= rot_point.x;
		meshes.wrist.position.y 		= rot_point.y;				
		meshes.wrist.position.z 		= rot_point.z;
		meshes.wrist_mot.position.x 	= rot_point.x;
		meshes.wrist_mot.position.y 	= rot_point.y;				
		meshes.wrist_mot.position.z 	= rot_point.z;
		meshes.wrist_mot.rotation.x  	= Base;
		meshes.wrist.rotation.x  	   	= Base;
		
		if (isNaN(angle_set.Wrist))	return;
		meshes.wrist_mot.rotation.y   = angle_set.Shoulder + angle_set.Elbow + angle_set.Wrist;
		meshes.wrist.rotation.y  	  = angle_set.Shoulder + angle_set.Elbow + angle_set.Wrist;			

		// MOVE LEFT GRIPPER:	
		if (isNaN(angle_set.WristRotate))	return;
		meshes.wrist.updateMatrixWorld();		
		var fa_vec     = new THREE.Vector3( 0,0, arm_sizes.wrist_length );
		grip_mesh.wrist.rotation.z = angle_set.WristRotate;
		
		
		var collision = arms_collide();
		if (collision)
		{
			var collision_color = 0xff002f;
			color_code_arm( r_arm_meshes, collision_color );
		}
}

function within_boundary( angle, boundary )
{
	var retval = true;
	if (angle.a > boundary.max) {
		angle.a = boundary.max;
		retval=false;
	} else if (angle.a < boundary.min) {
		angle.a = boundary.min;
		retval=false;
	}
	return retval;
}
function convert_servo_limits_to_rad( servo_limits_deg, servo_limits_rad)
{
	servo_limits_rad.Base = {};
	servo_limits_rad.Base.max = variable.max;
	servo_limits_rad.Base.min = variable.min;

	servo_limits_rad.Shoulder = {};
	servo_limits_rad.Shoulder.max = variable.max;
	servo_limits_rad.Shoulder.min = variable.min;

	servo_limits_rad.Elbow = {};
	servo_limits_rad.Elbow.max = variable.max;
	servo_limits_rad.Elbow.min = variable.min;

	servo_limits_rad.Wrist = {};
	servo_limits_rad.Wrist.max = variable.max;
	servo_limits_rad.Wrist.min = variable.min;
	
	servo_limits_rad.WristRotate = {};
	servo_limits_rad.WristRotate.max = variable.max;
	servo_limits_rad.WristRotate.min = variable.min;
	
}

function check_servo_limits(angle_set, servo_limits)
{
	var angle_ref = {};
	angle_ref.a = angle_set.Base;
	if (within_boundary(angle_ref, servo_limits.Base )==false )		angle_set.Base = angle_ref.a;

	angle_ref.a = angle_set.Shoulder;
	if (within_boundary(angle_ref, servo_limits.Shoulder )==false )		angle_set.Shoulder = angle_ref.a;

	angle_ref.a = angle_set.Elbow;
	if (within_boundary(angle_ref, servo_limits.Elbow )==false )		angle_set.Elbow = angle_ref.a;

	angle_ref.a = angle_set.Wrist;
	if (within_boundary(angle_ref, servo_limits.Wrist )==false )		angle_set.Wrist = angle_ref.a;

	angle_ref.a = angle_set.WristRotate;
	if (within_boundary(angle_ref, servo_limits.WristRotate )==false )		angle_set.WristRotate = angle_ref.a;
}

function set_servo_angles_degrees( left_or_right, angle_set )
{
	var radians_set={};
	if (left_or_right=="left") {
		l_deg_servo_angle_set = angle_set;
		convert_to_radians( angle_set, radians_set );		
		set_servo_angles_rad( l_arm_meshes, l_grip_meshes, arm_sizes, radians_set );				
	} else {
		r_deg_servo_angle_set = angle_set;
		convert_to_radians( angle_set, radians_set );
		set_servo_angles_rad( r_arm_meshes, r_grip_meshes, arm_sizes, radians_set );	
	}
}

check_servo_limits( l_deg_servo_angle_set, l_servo_limits_deg );
check_servo_limits( r_deg_servo_angle_set, r_servo_limits_deg );

