

var leg_sizes = {
		upper_leg_length :16,
		lower_leg_length :16,
		foot_length      : 8,

		upper_leg_width: 3,
		upper_leg_depth: 2,
		lower_leg_width: 3,
		lower_leg_depth: 2,

		foot_width    :  4,
		foot_depth    :  1,

		hip_radius    :  2,		
		knee_radius   :  2,
		ankle_radius  :0.75,
};

var l_rad_leg_angle_set = {
	isStanceLeg: true,
	BodyPart : "Leg",
	Leg      : "Left",
	Hip      : 0.0,
	HipSwing : 0.0,	
	HipRotate: 0.0,
	Knee   : 0.0,
	Ankle   : Math.PI/2,
	unit    : "Radians",
};
var r_rad_leg_angle_set = {
	isStanceLeg: false,
	BodyPart : "Leg",
	Leg      : "Right",
	Hip      : 0.0,
	HipSwing : 0.0,		
	HipRotate: 0.0,
	Knee     : 0.0,
	Ankle    : Math.PI/2,
	unit     : "Radians",
};

var leg_angles_limits = {
	Hip      : { max: 360.0, min: 0.0 },
	HipSwing : { max: 360.0, min: 0.0 },
	HipRotate: { max: 360.0, min: 0.0 },
	Knee     : { max: 360.0, min: 0.0 },
	Ankle    : { max: 360.0, min: 0.0 },
	unit     : "Radians",
};


function check_leglimits_okay( leg_limits, leg_angles )
{
	var retval = true;
	if ( leg_angles.Hip > leg_limits.Hip.max ) retval = false;
	if ( leg_angles.Hip < leg_limits.Hip.min ) retval = false;

	if ( leg_angles.HipSwing > leg_limits.HipSwing.max ) retval = false;
	if ( leg_angles.HipSwing < leg_limits.HipSwing.min ) retval = false;

	if ( leg_angles.HipRotate > leg_limits.HipRotate.max ) retval = false;
	if ( leg_angles.HipRotate < leg_limits.HipRotate.min ) retval = false;

	if ( leg_angles.Knee > leg_limits.Knee.max ) retval = false;
	if ( leg_angles.Knee < leg_limits.Knee.min ) retval = false;
	
	if ( leg_angles.Ankle > leg_limits.Ankle.max ) retval = false;
	if ( leg_angles.Ankle < leg_limits.Ankle.min ) retval = false;		
	return retval;
}

function clamp_legservos_at_limits( angle_set, servo_limits )
{
	var retval = true;
	if ( arm_angles.Hip > arm_limits.Hip.max ) 		arm_angles.Hip = arm_limits.Hip.max;
	if ( arm_angles.Hip < arm_limits.Hip.min ) 		arm_angles.Hip = arm_limits.Hip.min;

	if ( arm_angles.HipSwing > arm_limits.HipSwing.max ) arm_angles.HipSwing = arm_limits.HipSwing.max;
	if ( arm_angles.HipSwing < arm_limits.HipSwing.min ) arm_angles.HipSwing = arm_limits.HipSwing.min;

	if ( arm_angles.HipRotate > arm_limits.HipRotate.max ) 		arm_angles.HipRotate = arm_limits.HipRotate.max;
	if ( arm_angles.HipRotate < arm_limits.HipRotate.min ) 		arm_angles.HipRotate = arm_limits.HipRotate.min;

	if ( arm_angles.Knee > arm_limits.Knee.max ) 		arm_angles.Knee = arm_limits.Knee.max;
	if ( arm_angles.Knee < arm_limits.Knee.min ) 		arm_angles.Knee = arm_limits.Knee.min;

	if ( arm_angles.Ankle > arm_limits.Ankle.max ) arm_angles.Ankle = arm_limits.Ankle.max;
	if ( arm_angles.Ankle < arm_limits.Ankle.min ) arm_angles.Ankle = arm_limits.Ankle.min;

	return retva;
}


let leg_material   = {};
let l_leg_geom     = {};
let r_leg_geom     = {};
let l_leg_meshes   = {};
let r_leg_meshes   = {};

function leg_materials()
{
	leg_material.rmeshMaterial  = new THREE.MeshPhongMaterial  ( { color: 0x754249, emissive: 0x772534, side: THREE.DoubleSide, flatShading: true } );
	leg_material.rmeshMaterial2 = new THREE.MeshPhongMaterial  ( { color: 0xC56219, emissive: 0x876514, side: THREE.DoubleSide, flatShading: true } );
	leg_material.bone_material  = new THREE.MeshLambertMaterial( { color: 0xE36C3C, wireframe: false } );

	leg_material.meshMaterial  = new THREE.MeshPhongMaterial  ( { color: 0x456259, emissive: 0x072534, side: THREE.DoubleSide, flatShading: true } );
	leg_material.meshMaterial2 = new THREE.MeshPhongMaterial  ( { color: 0xB56239, emissive: 0x974514, side: THREE.DoubleSide, flatShading: true } );
	leg_material.material3     = new THREE.MeshLambertMaterial( { color: 0x43CC4C, wireframe: false } );
}

function construct_leg(leg_geoms,leg_meshes, joint_material, bone_material )
{
	// UPPER LEG :
	leg_geoms.hip			= new THREE.SphereGeometry		( leg_sizes.hip_radius,  	  leg_sizes.hip_radius,  20, 20 );
	leg_geoms.upper_leg		= new THREE.BoxGeometry			( leg_sizes.upper_leg_depth,  leg_sizes.upper_leg_width,  leg_sizes.upper_leg_length );
	leg_geoms.knee 	 		= new THREE.CylinderGeometry	( leg_sizes.knee_radius, 	  leg_sizes.knee_radius, 	  3, 32 );
	leg_geoms.lower_leg 	= new THREE.BoxGeometry			( leg_sizes.lower_leg_depth,  leg_sizes.lower_leg_width,  leg_sizes.lower_leg_length );
	leg_geoms.ankle			= new THREE.CylinderGeometry	( leg_sizes.ankle_radius, 	  leg_sizes.ankle_radius, 3, 32 );
	leg_geoms.foot    		= new THREE.BoxGeometry			( leg_sizes.foot_depth,	      leg_sizes.foot_width,  	  leg_sizes.foot_length );

	// Where to 'grab' the part : 
	leg_geoms.upper_leg.translate ( 0, 0, leg_sizes.upper_leg_length/2 );
	leg_geoms.lower_leg.translate ( 0, 0, leg_sizes.lower_leg_length/2 );
	leg_geoms.foot.translate	  ( 0, 0, leg_sizes.foot_length/2 	   );
/*	
	leg_geoms.knee.translate	  ( 0, 0, leg_sizes.upper_leg_length   );
	leg_geoms.ankle.translate 	  ( 0, 0, 0 );
*/
	// 
	leg_meshes.hip  	 = new THREE.Mesh( leg_geoms.hip,   	joint_material );
	leg_meshes.upper_leg = new THREE.Mesh( leg_geoms.upper_leg, leg_material.bone_material  );						
	leg_meshes.knee      = new THREE.Mesh( leg_geoms.knee,      joint_material );
	leg_meshes.lower_leg = new THREE.Mesh( leg_geoms.lower_leg, leg_material.bone_material  );
	leg_meshes.ankle     = new THREE.Mesh( leg_geoms.ankle,  	joint_material );			
	leg_meshes.foot      = new THREE.Mesh( leg_geoms.foot,      leg_material.bone_material  );

//			upper_leg_mesh.position.x = 0;		upper_leg_mesh.position.y = 0;
//			elbow_mesh.position.x 	  = 0;
//			fore_leg_mesh.position.y  = 15;		//fore_leg_mesh.position.z  = upper_leg_length;
}

function init_leg_relative_locations(leg_meshes)
{
	leg_meshes.upper_leg.position.x 	= 0;
	leg_meshes.knee.position.x 			= 0;
	leg_meshes.lower_leg.position.x 	= 0;
	leg_meshes.ankle.position.x 		= 0;
	leg_meshes.foot.position.x 			= 0;//+leg_sizes.foot_length/2;

	leg_meshes.upper_leg.position.y 	= 0;
	leg_meshes.knee.position.y 			= 0;
	leg_meshes.lower_leg.position.y 	= 0;
	leg_meshes.ankle.position.y 		= 0;
	leg_meshes.foot.position.y 			= 0;//-leg_sizes.foot_depth/2;

	leg_meshes.upper_leg.position.z 	= 0;
	leg_meshes.knee.position.z 			= leg_sizes.upper_leg_length;
	leg_meshes.lower_leg.position.z 	= 0;
	leg_meshes.ankle.position.z 		= leg_sizes.lower_leg_length;  // leg_sizes.upper_leg_length + 
	leg_meshes.foot.position.z 			= 0; //leg_sizes.lower_leg_length+3;

	leg_meshes.foot.rotation.y = 90*Math.PI/180.;
}

function assemble_legs(meshes)
{
	meshes.ankle.add     ( meshes.foot      );	
	meshes.lower_leg.add ( meshes.ankle     );
	meshes.knee.add      ( meshes.lower_leg );

	meshes.hip.add		( meshes.upper_leg );
	meshes.upper_leg.add( meshes.knee 	 );
}
function create_leg_shadow_meshes(leg_meshes)
{
	leg_meshes.hip.castShadow 		= true;
	leg_meshes.upper_leg.castShadow = true;
	leg_meshes.knee.castShadow 		= true;
	leg_meshes.lower_leg.castShadow = true;
	leg_meshes.ankle.castShadow 	= true;
	leg_meshes.foot.castShadow 		= true;

	leg_meshes.hip.receiveShadow 		= false;
	leg_meshes.upper_leg.receiveShadow 	= false;
	leg_meshes.knee.receiveShadow 		= false;
	leg_meshes.lower_leg.receiveShadow 	= false;
	leg_meshes.ankle.receiveShadow 		= false;
	leg_meshes.foot.receiveShadow 		= false;

	leg_meshes.luaShadow = new THREE.ShadowMesh( leg_meshes.upper_leg );
	leg_meshes.leaShadow = new THREE.ShadowMesh( leg_meshes.knee 	  );
	leg_meshes.llaShadow = new THREE.ShadowMesh( leg_meshes.lower_leg );
	leg_meshes.lwmShadow = new THREE.ShadowMesh( leg_meshes.ankle 	  );
	leg_meshes.lwaShadow = new THREE.ShadowMesh( leg_meshes.foot      );

	scene.add( leg_meshes.luaShadow );
	scene.add( leg_meshes.leaShadow );
	scene.add( leg_meshes.llaShadow );
	scene.add( leg_meshes.lwmShadow );
	scene.add( leg_meshes.lwaShadow );
}

function convert_legs_to_radians( angle_set, output_set )
{
	if (angle_set.unit != "Degrees") return;
	if (typeof output_set == "undefined")
		output_set = angle_set;				// do inplace
	
	output_set.Hip       = angle_set.Hip       * Math.PI / 180.;			
	output_set.HipRotate = angle_set.HipRotate * Math.PI / 180.;
	output_set.HipSwing  = angle_set.HipSwing  * Math.PI / 180.;				
	output_set.Knee 	 = angle_set.Knee      * Math.PI / 180.;
	output_set.Ankle 	 = angle_set.Ankle     * Math.PI / 180.;				
	output_set.unit 	 = "Radians";
}

function convert_legs_to_degrees( angle_set, output_set )
{
	if (angle_set.unit != "Radians") return;
	if (typeof output_set == "undefined")
		output_set = angle_set;
	
	output_set.Hip       = angle_set.Hip       * 180. / Math.PI ;			
	output_set.HipRotate = angle_set.HipRotate * 180. / Math.PI ;
	output_set.HipSwing  = angle_set.HipSwing  * 180. / Math.PI ;
	output_set.Knee 	 = angle_set.Elbow     * 180. / Math.PI ;
	output_set.Ankle 	 = angle_set.Wrist     * 180. / Math.PI ;				
	output_set.unit = "Degrees";
}

function set_leg_angles_rad( angle_set, meshes )
{
	meshes.hip.rotation.y = angle_set.Hip;
	meshes.hip.rotation.x = angle_set.HipSwing;
	meshes.hip.rotation.z = angle_set.HipRotate;
		
	meshes.lower_leg.rotation.y = angle_set.Knee;
	meshes.foot.rotation.y = angle_set.Ankle;	
}

function set_leg_angles( angle_set )
{
	if (typeof angle_set.Leg=="Undefined") return;

	var rad_set = angle_set;
	if (angle_set.Unit=="Degrees")
		convert_legs_to_radians( angle_set, rad_set );
		
	if (angle_set.Leg=="Left")
		set_leg_angles_rad( rad_set, l_leg_meshes );
	else 
		set_leg_angles_rad( rad_set, r_leg_meshes );
}

function convert_to_common_angle_set( rad_angle_set )
{
	rad_angle_set.Knee  =  -rad_angle_set.Knee;
	rad_angle_set.Ankle = rad_angle_set.Ankle - Math.PI/2;
}

/* This maps the angles from "my way" of thinking of them into the needed graphical representation. */
function set_common_leg_angles( which_leg, common_angles )
{
	var rad_leg_angle_set = {};
	rad_leg_angle_set.Leg = which_leg;
	if (common_angles.isStanceLeg==true)
	{	stance_leg = which_leg;	swing_leg = opposite_leg(stance_leg);	}
	
	if (common_angles.unit == "Degrees") {
		rad_leg_angle_set.Hip      = Math.radians( common_angles.Hip       );
		rad_leg_angle_set.HipSwing = Math.radians( common_angles.HipSwing  );	
		rad_leg_angle_set.HipRotate= Math.radians( common_angles.HipRotate );
		rad_leg_angle_set.Knee     = Math.radians( -common_angles.Knee     );
		rad_leg_angle_set.Ankle    = Math.radians( common_angles.Ankle+90  );
		rad_leg_angle_set.unit = "Radians";
	} else {
		rad_leg_angle_set.Hip      =  common_angles.Hip;
		rad_leg_angle_set.HipSwing =  common_angles.HipSwing;	
		rad_leg_angle_set.HipRotate=  common_angles.HipRotate;
		rad_leg_angle_set.Knee     =  -common_angles.Knee;
		rad_leg_angle_set.Ankle    =  -(common_angles.Ankle+Math.PI/2);
		rad_leg_angle_set.unit = "Radians";
	}
	
	if (which_leg=="Left") {
		l_rad_leg_angle_set = rad_leg_angle_set;
		set_leg_angles( l_rad_leg_angle_set );	
	} else {
		r_rad_leg_angle_set = rad_leg_angle_set;
		set_leg_angles( r_rad_leg_angle_set );
	}
	
//	set_leg_angles( rad_leg_angle_set );	
}

// Requires Common Angle Convention:
function get_height_robot_space( rad_angle_set, torso_angle_y )
{
	if (rad_angle_set.Leg=="Left")
		return left_foot_height_robot_space ( rad_angle_set, torso_angle_y );
	else 
		return right_foot_height_robot_space( rad_angle_set, torso_angle_y );
}
// Requires Common Angle Convention:
function left_foot_height_robot_space( l_rad_servo_angle_set, torso_angle_y )
{
	var angle_wrt_gravity 		   = torso_angle_y + l_rad_servo_angle_set.Hip;
	var vertical_distance_to_knee  = leg_sizes.upper_leg_length * Math.cos( angle_wrt_gravity );
	var knee_angle_wrt_gravity     = angle_wrt_gravity - l_rad_servo_angle_set.Knee;
	
	var vertical_distance_to_ankle =  leg_sizes.lower_leg_length * Math.cos( knee_angle_wrt_gravity );
	return (vertical_distance_to_knee + vertical_distance_to_ankle);
}

function right_foot_height_robot_space( r_rad_servo_angle_set, torso_angle_y )
{
	var angle_wrt_gravity 		   = torso_angle_y + l_rad_servo_angle_set.Hip;
	var vertical_distance_to_knee  = leg_sizes.upper_leg_length * Math.cos( angle_wrt_gravity );
	var knee_angle_wrt_gravity     = torso_angle_y + r_rad_servo_angle_set.Knee;
	var vertical_distance_to_ankle = leg_sizes.lower_leg_length * Math.cos(knee_angle_wrt_gravity);
	return (vertical_distance_to_knee + vertical_distance_to_ankle);
}
function opposite_leg(which_leg)
{
	var leg = which_leg.toUpperCase();
	var opposite = (leg=="LEFT")?"Right":"Left";
	return opposite;
}

/************************************** 
Keeping lower leg aligned with gravity 
	Note:  Call  set_common_leg_angles( "Left",  leg_angle_set ) after this to actuate in simulator.
Return: 	The angle set.
***************************************/
function leg_lift_distance( height_to_raise )
{
	var vertical_distance_to_knee = leg_sizes.upper_leg_length - height_to_raise;
	var ratio     = vertical_distance_to_knee / leg_sizes.upper_leg_length;	// cos is (adjacent / hypotenus)
	var angle_rad = Math.acos( ratio );

	var leg_angle_set = {};		// Common Angle convention.
	leg_angle_set.unit    		= "Radians";
	leg_angle_set.Hip   		= angle_rad ;
	leg_angle_set.HipSwing      = 0;	
	leg_angle_set.HipRotate     = 0;		
	leg_angle_set.Knee  		= angle_rad;
	leg_angle_set.Ankle 		= 0;
	return leg_angle_set;
}

/* Keeping lower leg aligned with gravity */
function leg_lift( hip_angle_deg )
{
	var angle_rad = Math.radians( hip_angle_deg);
	var knee_rad  = Math.radians( 180-(2*hip_angle_deg) - (90-hip_angle_deg) );

	var leg_angle_set 			= {};		// Common Angle convention.
	leg_angle_set.unit    		= "Radians";
	leg_angle_set.Hip   		= angle_rad ;
	leg_angle_set.HipSwing      = 0;	
	leg_angle_set.HipRotate     = 0;		
	leg_angle_set.Knee  		= angle_rad;
	leg_angle_set.Ankle 		= 0;
	return leg_angle_set;
}


leg_materials();
construct_leg(l_leg_geom, l_leg_meshes, leg_material.meshMaterial2, leg_material.meshMaterial );
construct_leg(r_leg_geom, r_leg_meshes, leg_material.rmeshMaterial2, leg_material.rmeshMaterial );
init_leg_relative_locations(l_leg_meshes);
init_leg_relative_locations(r_leg_meshes);

assemble_legs( l_leg_meshes );
assemble_legs( r_leg_meshes );

create_leg_shadow_meshes( l_leg_meshes );
create_leg_shadow_meshes( r_leg_meshes );
