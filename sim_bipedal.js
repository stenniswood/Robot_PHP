var torso_size = {
	length : 20,
	width : 10,
	depth : 5,
}

var head_geoms;
var head_mesh;
var head_radius = 3.5;
var torso_mesh;
var humanoid;

function construct_head()
{		
	head_geoms = new THREE.SphereBufferGeometry( head_radius,  head_radius,  20, 20 );
	head_mesh     = new THREE.Mesh( head_geoms,   leg_material.bone_material );
}

function construct_humanoid()
{
	construct_head();
	
	var torso_geom = new THREE.BoxGeometry( torso_size.depth, torso_size.width, torso_size.length  );
	torso_mesh     = new THREE.Mesh( torso_geom,   leg_material.bone_material );
	torso_geom.translate( 0, 0, -torso_size.length/2 );
	
	//l_leg_meshes.upper_leg.rotation.x = Math.PI;
	//r_leg_meshes.upper_leg.rotation.x = Math.PI;
	head_mesh.position.x = 0;
	head_mesh.position.y = 0;
	head_mesh.position.z = -torso_size.length-head_radius;
	
	torso_mesh.add( head_mesh );
	torso_mesh.add( l_arm_meshes.shoulder );
	torso_mesh.add( r_arm_meshes.shoulder );	
	
	torso_mesh.add( l_leg_meshes.hip );
	torso_mesh.add( r_leg_meshes.hip );	
	torso_mesh.position.y = 0;
	torso_mesh.position.z = -get_height_robot_space( l_rad_leg_angle_set, torso_mesh.rotation.y );
		
	humanoid = new THREE.Group();		// humanoid is a shell which will hold it's relations to world coordinates.
	humanoid.add(torso_mesh);
	humanoid.rotation.y = -Math.PI/2;
	humanoid.position.x = -10;
	scene.add( humanoid );
}


function place_legs()
{
	l_leg_meshes.hip.position.x 		= 0;
	l_leg_meshes.hip.position.y 		= torso_size.width/2-leg_sizes.hip_radius;
	l_leg_meshes.hip.position.z 		= 0;

	r_leg_meshes.hip.position.x 		= 0;
	r_leg_meshes.hip.position.y 		= -torso_size.width/2+leg_sizes.hip_radius;
	r_leg_meshes.hip.position.z 		= 0;
}
function place_arms()
{
	l_arm_meshes.shoulder.position.x 		= 0;
	l_arm_meshes.shoulder.position.y 		= torso_size.width/2+arm_sizes.upper_arm_width;
	l_arm_meshes.shoulder.position.z 		= arm_sizes.shoulder_radius-torso_size.length;
	l_arm_meshes.shoulder.rotation.x = 90* Math.PI/180;

	r_arm_meshes.shoulder.position.x 		= 0;
	r_arm_meshes.shoulder.position.y 		= -torso_size.width/2-arm_sizes.upper_arm_width;
	r_arm_meshes.shoulder.position.z 		= arm_sizes.shoulder_radius-torso_size.length;
	r_arm_meshes.shoulder.rotation.x = 90* Math.PI/180;	
}


function sit_pose(  )
{
	torso_mesh.position.x = -10 + leg_sizes.lower_leg_length + 0;
	torso_mesh.rotation.y = Math.radians( -90 );
		
	l_deg_servo_angle_set.Elbow = 90;
	r_deg_servo_angle_set.Elbow = 90;
	set_servo_angles_degrees( "left",  l_deg_servo_angle_set );
	set_servo_angles_degrees( "right", r_deg_servo_angle_set );		
	populate_angle_table(l_deg_servo_angle_set, 1);
	populate_angle_table(r_deg_servo_angle_set, 2);
	
	
	var leg_angle_set = {};
	leg_angle_set.unit = "Degrees";
	leg_angle_set.Hip   		= 90 ;
	leg_angle_set.HipSwing      = 0;	
	leg_angle_set.HipRotate     = 0;		
	leg_angle_set.Knee  		= 90 ;
	leg_angle_set.Ankle 		= 0;
	
	set_common_leg_angles( "Left",  leg_angle_set  );
	set_common_leg_angles( "Right", leg_angle_set );	
}

function stand_pose(  )
{
	torso_mesh.position.x = -10 + leg_sizes.upper_leg_length + leg_sizes.lower_leg_length + 0;
	torso_mesh.rotation.y = Math.radians( -90 );
		
	l_deg_servo_angle_set.Elbow = 30;
	r_deg_servo_angle_set.Elbow = 30;
	set_servo_angles_degrees( "left",  l_deg_servo_angle_set );
	set_servo_angles_degrees( "right", r_deg_servo_angle_set );		
	populate_angle_table(l_deg_servo_angle_set, 1);
	populate_angle_table(r_deg_servo_angle_set, 2);
	
	var leg_angle_set = {};
	leg_angle_set.unit    = "Degrees";
	leg_angle_set.Hip   		= 0 ;
	leg_angle_set.HipSwing      = 0;	
	leg_angle_set.HipRotate     = 0;		
	leg_angle_set.Knee  		= 0 ;
	leg_angle_set.Ankle 		= 0;
	
	set_common_leg_angles( "Left",  leg_angle_set );
	set_common_leg_angles( "Right", leg_angle_set );
}
function squat( angle_deg )
{
	var angle_rad = Math.radians( angle_deg);
	var knee_rad  = Math.radians( 180-(2*angle_deg) - (90-angle_deg) );
	
	torso_mesh.position.x = -10 + Math.cos( angle_rad ) * leg_sizes.upper_leg_length +
							Math.sin( knee_rad ) * leg_sizes.lower_leg_length + 0;
	torso_mesh.rotation.y = Math.radians( -90 );
		
	l_deg_servo_angle_set.Elbow = 30;
	r_deg_servo_angle_set.Elbow = 30;
	set_servo_angles_degrees( "left",  l_deg_servo_angle_set );
	set_servo_angles_degrees( "right", r_deg_servo_angle_set );		
	
	var leg_angle_set = {};
	leg_angle_set.unit    = "Degrees";
	leg_angle_set.Hip   		= angle_deg ;
	leg_angle_set.HipSwing      = 0;	
	leg_angle_set.HipRotate     = 0;		
	leg_angle_set.Knee  		= angle_deg*2 ;
	leg_angle_set.Ankle 		= angle_deg;

	set_common_leg_angles( "Left",  leg_angle_set );
	set_common_leg_angles( "Right", leg_angle_set );
}

function sim_actuate_angle_set( angle_set )
{
	var rad_set = {};
	
	if (angle_set.BodyPart=="Arm")
	{
		if (angle_set.unit=="Degrees")
			convert_to_radians( angle_set );
		else
			rad_set = angle_set;
			
		if (angle_set.Arm=="Left")
		{
			set_servo_angles_rad( l_arm_meshes, l_grip_meshes, arm_sizes, angle_set );
			
		} else {
			set_servo_angles_rad( r_arm_meshes, r_grip_meshes, arm_sizes, angle_set );	
		}
	} 
	else if (angle_set.BodyPart=="Leg")
	{
		if (angle_set.unit=="Degrees")
			convert_legs_to_radians( angle_set, rad_set )
		else
			rad_set = Object.assign( {}, angle_set);

		if (rad_set.Leg=="Left")
		{
			//set_leg_angles_rad( rad_set, l_leg_meshes );						
			if (rad_set.isStanceLeg) {
				convert_to_common_angle_set( l_rad_leg_angle_set );
				var prev_height = get_height_robot_space( l_rad_leg_angle_set );	// Common
				adjust_torso_to_stance_leg( prev_height, rad_set );
			}
			set_common_leg_angles( rad_set.Leg, rad_set );
		} else {
			//set_leg_angles_rad( rad_set, r_leg_meshes );
			if (rad_set.isStanceLeg) {
				//var prev = get_stance_leg_angle_set();
				convert_to_common_angle_set( r_rad_leg_angle_set );				
				var prev_height = get_height_robot_space( r_rad_leg_angle_set );	// Common		
				adjust_torso_to_stance_leg( prev_height, rad_set );
			}
			set_common_leg_angles( rad_set.Leg, rad_set );
		}
	}
}

function convert_world_to_robot_space(world_xyz)
{
	var robot_xyz = world_xyz.clone();
	torso_mesh.localToWorld( robot_xyz );	
}

function convert_robot_to_left_arm(robot_xyz)
{
	var l_arm_xyz = robot_xyz.clone();
	l_arm_meshes.localToWorld( l_arm_xyz );	
	return l_arm_xyz;
}
function convert_robot_to_right_arm(robot_xyz)
{
	var r_arm_xyz = robot_xyz.clone();
	r_arm_meshes.localToWorld( r_arm_xyz );
	return r_arm_xyz;
}
function convert_robot_to_left_leg(robot_xyz)
{
	var l_leg_xyz = robot_xyz.clone();
	l_leg_meshes.localToWorld( l_leg_xyz );	
	return l_leg_xyz;
}
function convert_robot_to_right_leg(robot_xyz)
{
	var r_leg_xyz = robot_xyz.clone();
	r_leg_meshes.localToWorld( r_leg_xyz );	
	return r_leg_xyz;
}


var left_hand_path_geometry  = new THREE.Geometry();
var right_hand_path_geometry = new THREE.Geometry();
var left_hand_meshline ;

function generate_hand_path(  )
{
	var frac  = 0;
	var angle = 0;
		
	for (i=0; i<samples; i++)
	{

		knob_path_geometry.vertices.push( hand_loc_w );
		frac += delta;
	}

	var resolution = new THREE.Vector2( window.innerWidth, window.innerHeight );
	var line       = new MeshLine();
	var material   = new MeshLineMaterial({
		useMap : false,
		color  : new THREE.Color( 0xFF161f ),
		opacity   : 1,
		resolution: resolution,
		sizeAttenuation: !false,
		lineWidth: 2,
		near     : camera.near,
		far      : camera.far
	});
	left_hand_meshline = new THREE.Mesh( line.geometry, material );
//	left_hand_meshline = new THREE.Mesh( line.geometry, material );

	line.setGeometry( knob_path_geometry, function( p ) { return 4; }  );
	scene.add       ( meshline );
//	return path;
}

place_legs();
place_arms();

construct_humanoid();

