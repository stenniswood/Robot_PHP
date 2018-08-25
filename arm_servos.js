
var l_deg_servo_angle_set = {
	Base    : 0.0,
	Shoulder: 0.0,
	Elbow   : 0.0,
	Wrist   : 0.0,
	unit    : "Degrees",
};
var r_deg_servo_angle_set = {
	Base    : 0.0,
	Shoulder: 0.0,
	Elbow   : 0.0,
	Wrist   : 0.0,
	unit    : "Degrees",
};

var l_rad_servo_angle_set={};
var r_rad_servo_angle_set={};


var vec_elbow  = new THREE.Vector3( 0,0, arm_sizes.upper_arm_length + arm_sizes.upper_arm_length/2 );


function convert_to_radians( angle_set, output_set )
{
	if (angle_set.unit != "Degrees") return;
	
	output_set.Base     = angle_set.Base     * Math.PI / 180.;			
	output_set.Shoulder = angle_set.Shoulder * Math.PI / 180.;
	output_set.Elbow 	= angle_set.Elbow    * Math.PI / 180.;
	output_set.Wrist 	= angle_set.Wrist    * Math.PI / 180.;				
	output_set.unit 	= "Radians";
}
function convert_to_degrees( angle_set, output_set )
{
	if (angle_set.unit != "Radians") return;
	
	output_set.Base      = angle_set.Base     * 180. / Math.PI ;			
	output_set.Shoulder  = angle_set.Shoulder * 180. / Math.PI ;
	output_set.Elbow 	= angle_set.Elbow    * 180. / Math.PI ;
	output_set.Wrist 	= angle_set.Wrist    * 180. / Math.PI ;				
	output_set.unit = "Degrees";
}

function set_servo_angles_rad( meshes, arm_sizes, angle_set )
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
}


function set_servo_angles_degrees( left_or_right, angle_set )
{
	var radians_set={};
	if (left_or_right=="left") {
		l_deg_servo_angle_set = angle_set;
		convert_to_radians( angle_set, radians_set );		
		set_servo_angles_rad( l_arm_meshes, arm_sizes, radians_set );				
	} else {
		r_deg_servo_angle_set = angle_set;
		convert_to_radians( angle_set, radians_set );
		set_servo_angles_rad( r_arm_meshes, arm_sizes, radians_set );	
	}
	
}


set_servo_angles_degrees( "left", l_deg_servo_angle_set );
set_servo_angles_degrees( "right", r_deg_servo_angle_set );

