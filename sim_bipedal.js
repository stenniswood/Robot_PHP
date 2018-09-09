var torso_size = {
	length : 20,
	width : 10,
	depth : 5,
}
var leg_sizes = {
		upper_leg_length :12,
		lower_leg_length :12,
		foot_length      : 5,

		upper_leg_width:   2,
		upper_leg_depth: 1.5,
		lower_leg_width:   2,
		lower_leg_depth: 1.5,

		foot_width    :  4,
		foot_depth    :  1,

		hip_radius    :  2,		
		knee_radius   :  2,
		ankle_radius  :0.75,
};

var l_rad_leg_angle_set = {
	Hip      : 0.0,
	HipSwing : 0.0,	
	HipRotate: 0.0,
	Knee   : 0.0,
	Ankle   : 0.0,
	unit    : "Radians",
};
var r_rad_leg_angle_set = {
	Hip    : 0.0,
	HipSwing : 0.0,		
	HipRotate: 0.0,
	Knee   : 0.0,
	Ankle   : 0.0,
	unit    : "Radians",
};


let leg_material   = {};
let l_leg_geom     = {};
let r_leg_geom     = {};
let l_leg_meshes   = {};
let r_leg_meshes   = {};

function leg_materials()
{
	leg_material.rmeshMaterial  = new THREE.MeshPhongMaterial( { color: 0x754249, emissive: 0x772534, side: THREE.DoubleSide, flatShading: true } );
	leg_material.rmeshMaterial2 = new THREE.MeshPhongMaterial( { color: 0xC56219, emissive: 0x876514, side: THREE.DoubleSide, flatShading: true } );
	leg_material.bone_material  = new THREE.MeshLambertMaterial( { color: 0x738C3C, wireframe: false } );

	leg_material.meshMaterial  = new THREE.MeshPhongMaterial( { color: 0x456259, emissive: 0x072534, side: THREE.DoubleSide, flatShading: true } );
	leg_material.meshMaterial2 = new THREE.MeshPhongMaterial( { color: 0xB56239, emissive: 0x974514, side: THREE.DoubleSide, flatShading: true } );
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
			//fore_leg_mesh.position.y  = 15;		//fore_leg_mesh.position.z  = upper_leg_length;
}
var head_geoms;
var head_mesh;
var head_radius = 3.5;

function construct_head()
{		
	head_geoms = new THREE.SphereBufferGeometry( head_radius,  head_radius,  20, 20 );
	head_mesh     = new THREE.Mesh( head_geoms,   leg_material.bone_material );
}

var torso_mesh;
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
	
	torso_mesh.position.y = 20;
	scene.add( torso_mesh       );
}

function show_humanoid()
{

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
	l_arm_meshes.shoulder.position.y 		= torso_size.width/2;
	l_arm_meshes.shoulder.position.z 		= arm_sizes.shoulder_radius-torso_size.length;
	l_arm_meshes.shoulder.rotation.x = 90* Math.PI/180;

	r_arm_meshes.shoulder.position.x 		= 0;
	r_arm_meshes.shoulder.position.y 		= -torso_size.width/2;
	r_arm_meshes.shoulder.position.z 		= arm_sizes.shoulder_radius-torso_size.length;
	r_arm_meshes.shoulder.rotation.x = 90* Math.PI/180;	
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
function set_leg_angles( angle_set, meshes )
{
	meshes.hip.rotation.y = angle_set.Hip;
	meshes.hip.rotation.x = angle_set.HipSwing;
	meshes.hip.rotation.z = angle_set.HipRotate;
		
	meshes.lower_leg.rotation.y = angle_set.Knee;
	meshes.foot.rotation.y = angle_set.Ankle;	
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
/* This maps the angles from "my way" of thinking of them into the needed graphical representation. */
function set_common_leg_angles( which_leg, common_angles_degs )
{
	var rad_leg_angle_set = {};
	if (common_angles_degs.unit == "Degrees") {
		rad_leg_angle_set.Hip      = Math.radians( common_angles_degs.Hip   );
		rad_leg_angle_set.HipSwing = Math.radians( common_angles_degs.HipSwing );	
		rad_leg_angle_set.HipRotate= Math.radians( common_angles_degs.HipRotate );
		rad_leg_angle_set.Knee     = Math.radians( -common_angles_degs.Knee  );
		rad_leg_angle_set.Ankle    = Math.radians( common_angles_degs.Ankle+90 );
		rad_leg_angle_set.unit = "Radians";
	}
	
	if (which_leg=="Left") {
		l_rad_leg_angle_set = rad_leg_angle_set;
	} else {
		r_rad_leg_angle_set = rad_leg_angle_set;
	}
	
//	set_leg_angles( rad_leg_angle_set, leg_meshes );	
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
	var knee_rad = Math.radians( 180-(2*angle_deg) - (90-angle_deg) );
	
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
function leg_lift( which_leg, angle_deg )
{
	var angle_rad = Math.radians( angle_deg);
	var knee_rad = Math.radians( 180-(2*angle_deg) - (90-angle_deg) );

	var leg_angle_set = {};
	leg_angle_set.unit    = "Degrees";
	leg_angle_set.Hip   		= angle_deg ;
	leg_angle_set.HipSwing      = 0;	
	leg_angle_set.HipRotate     = 0;		
	leg_angle_set.Knee  		= angle_deg;
	leg_angle_set.Ankle 		= 0;

	if (which_leg=="Left")
	{
		set_common_leg_angles( "Left",  leg_angle_set );
	} else {
		set_common_leg_angles( "Right", leg_angle_set );
	}	
}

leg_materials();
construct_leg(l_leg_geom, l_leg_meshes, leg_material.meshMaterial2, leg_material.meshMaterial );
construct_leg(r_leg_geom, r_leg_meshes, leg_material.rmeshMaterial2, leg_material.rmeshMaterial );
init_leg_relative_locations(l_leg_meshes);
init_leg_relative_locations(r_leg_meshes);

assemble_legs( l_leg_meshes );
assemble_legs( r_leg_meshes );
place_legs();
place_arms();
create_leg_shadow_meshes( l_leg_meshes );
create_leg_shadow_meshes( r_leg_meshes );

construct_humanoid();
//scene.add( );
//scene.add( torso_mesh );
	