var grip_sizes = {
		grip1_length:3,
		grip2_length:3,
		joiner_length:4,
		wrist_length:2,				

		grip1_width:0.5,
		grip1_depth:0.5,
		grip2_width:0.5,
		grip2_depth:0.5,

		joiner_width:1,
		joiner_depth:1,		
		wrist_width:1,
		wrist_depth:1,		
};
let l_grip_geom     = {};
let r_grip_geom     = {};
let l_grip_meshes   = {};
let r_grip_meshes   = {};
let l_gripper = new THREE.Group();
let r_gripper = new THREE.Group();
let MAX_GRIP_SIZE = grip_sizes.joiner_length;

function construct_gripper(grip_geoms,grip_meshes, joint_material, bone_material )
{
	// SIMPLE GRIPPER :
	grip_geoms.grip1	= new THREE.BoxGeometry	( grip_sizes.grip1_depth,  grip_sizes.grip1_width,  grip_sizes.grip1_length );
	grip_geoms.grip2 	= new THREE.BoxGeometry	( grip_sizes.grip2_depth,  grip_sizes.grip2_width, 	grip_sizes.grip2_length );
	grip_geoms.joiner 	= new THREE.BoxGeometry	( grip_sizes.joiner_depth, grip_sizes.joiner_width, grip_sizes.joiner_length );
	grip_geoms.wrist    = new THREE.BoxGeometry	( grip_sizes.wrist_depth,  grip_sizes.wrist_width, 	grip_sizes.wrist_length );

	// Where to 'grab' the part : 
	grip_geoms.grip1.translate  ( 0, -grip_sizes.grip1_width/2 , grip_sizes.grip1_length/2- grip_sizes.wrist_length );
	grip_geoms.grip2.translate  ( 0, +grip_sizes.grip2_width/2,  grip_sizes.grip2_length/2- grip_sizes.wrist_length );
	
	grip_geoms.joiner.translate	( 0, +grip_sizes.joiner_width/2, 0  );
	grip_geoms.wrist.translate	( 0, 0, +grip_sizes.wrist_length/2 	 );

	// 
	grip_meshes.grip1  = new THREE.Mesh( grip_geoms.grip1,   bone_material );
	grip_meshes.grip2  = new THREE.Mesh( grip_geoms.grip2,   bone_material  );						
	grip_meshes.joiner = new THREE.Mesh( grip_geoms.joiner,  joint_material );
	grip_meshes.wrist  = new THREE.Mesh( grip_geoms.wrist,   bone_material  );
	grip_meshes.joiner.rotation.x = 90 *Math.PI/180.;
	
	// Nest the pieces inside Wrist.
	grip_meshes.wrist.add(grip_meshes.grip1 );	
	grip_meshes.wrist.add(grip_meshes.grip2 );
	grip_meshes.wrist.add(grip_meshes.joiner);
}

function create_grip_shadow_meshes(grip_meshes)
{
	grip_meshes.grip1.castShadow 	= true;
	grip_meshes.grip2.castShadow 	= true;
	grip_meshes.joiner.castShadow 	= true;
	grip_meshes.wrist.castShadow 	= true;

	grip_meshes.grip1.receiveShadow 	= false;
	grip_meshes.grip2.receiveShadow 	= false;
	grip_meshes.joiner.receiveShadow 	= false;
	grip_meshes.wrist.receiveShadow 	= false;

	grip_meshes.g1Shadow = new THREE.ShadowMesh( grip_meshes.grip1 	);
	grip_meshes.g2Shadow = new THREE.ShadowMesh( grip_meshes.grip2     );
	grip_meshes.jShadow = new THREE.ShadowMesh( grip_meshes.joiner    );
	grip_meshes.wShadow = new THREE.ShadowMesh( grip_meshes.wrist     );

	scene.add( grip_meshes.g1Shadow );
	scene.add( grip_meshes.g2Shadow );
	scene.add( grip_meshes.jShadow );
	scene.add( grip_meshes.wShadow );
}


function init_grip_locations()
{
	// HEIGHT :
	l_grip_meshes.grip1.position.x 	= 0;
	l_grip_meshes.grip2.position.x 	= 0;
	l_grip_meshes.joiner.position.x = 0;

	l_grip_meshes.grip1.position.y 	= -2;
	l_grip_meshes.grip2.position.y 	= +2;

	l_grip_meshes.grip1.position.z 	= 4+grip_sizes.joiner_width/2;
	l_grip_meshes.grip2.position.z 	= 4+grip_sizes.joiner_width/2;
	l_grip_meshes.joiner.position.z = grip_sizes.wrist_length;	

	// RIGHT:
	r_grip_meshes.grip1.position.x 	= 0;
	r_grip_meshes.grip2.position.x 	= 0;
	r_grip_meshes.joiner.position.x = 0;
	
	r_grip_meshes.grip1.position.y 	= -2;
	r_grip_meshes.grip2.position.y 	= +2;
	
	r_grip_meshes.grip1.position.z 	= 4+grip_sizes.joiner_width/2;
	r_grip_meshes.grip2.position.z 	= 4+grip_sizes.joiner_width/2;
	r_grip_meshes.joiner.position.z = grip_sizes.wrist_length;

	//l_grip_meshes.wrist.position.x 	= 10;
	//r_grip_meshes.wrist.position.x 	= 10;

//	l_grip_meshes.wrist.position.z = 4;
//	r_grip_meshes.wrist.position.z = 4;
	
}
function add_to_scene(meshes)
{

//	scene.add( l_grip_meshes.wrist  );
//	scene.add( r_grip_meshes.wrist  );
}
function open_gripper_distance( distance_between, grip_meshes )
{
	if (distance_between > grip_sizes.joiner_length)
		return "TooFar";
		
	// center the distance:
	var from_center = distance_between/2;	
	grip_meshes.grip1.position.y = -from_center;
	grip_meshes.grip2.position.y = +from_center;
	return "done";
}

function open_gripper( fraction, hand, grip_meshes )
{
	if (fraction>1.0)  fraction = 1.0;
	if (fraction<=0.0) fraction = 0.0;
	
	var HAND = hand.toUpperCase();
	if (HAND=="LEFT")
		l_rad_servo_angle_set.Gripper = fraction;
	else 
		r_rad_servo_angle_set.Gripper = fraction;
	
	var distance_between = (grip_sizes.joiner_length-grip_sizes.grip1_width-grip_sizes.grip2_width) * fraction;
	
	// center the distance:
	var from_center = distance_between/2;	
	grip_meshes.grip1.position.y 	= -from_center;
	grip_meshes.grip2.position.y 	= +from_center;
	
	return fraction;
}



function init_grippers() 
{
	construct_gripper(l_grip_geom, l_grip_meshes, arm_material.meshMaterialJ,  arm_material.meshMaterial  );
	construct_gripper(r_grip_geom, r_grip_meshes, arm_material.rmeshMaterialJ,  arm_material.rmeshMaterial );
	init_grip_locations();

	l_grip_meshes.wrist.position.z = arm_sizes.wrist_length/2;
	r_grip_meshes.wrist.position.z = arm_sizes.wrist_length/2;
	l_arm_meshes.wrist.add( l_grip_meshes.wrist );
	r_arm_meshes.wrist.add( r_grip_meshes.wrist );
	
	//add_to_scene( l_grip_meshes );
	//add_to_scene( r_grip_meshes );

	create_grip_shadow_meshes(l_grip_meshes);
	create_grip_shadow_meshes(r_grip_meshes);
}

init_grippers();
