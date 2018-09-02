
var leg_sizes = {
		upper_leg_length :10,
		lower_leg_length :10,
		foot_length      : 5,

		upper_leg_width:   2,
		upper_leg_depth: 1.5,
		lower_leg_width:   2,
		lower_leg_depth: 1.5,

		foot_width    :  4,
		foot_depth    :  1,

		hip_radius    :  3,		
		knee_radius   :  2,
		ankle_radius  :1.5,
};

var l_rad_leg_angle_set = {
	Hip    : 0.0,
	HipRotate: 0.0,
	Knee   : 0.0,
	Ankle   : 0.0,
	unit    : "Radians",
};
var r_rad_leg_angle_set = {
	Hip    : 0.0,
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
	leg_material.rmaterial3     = new THREE.MeshLambertMaterial( { color: 0x738C3C, wireframe: false } );

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
	leg_meshes.upper_leg = new THREE.Mesh( leg_geoms.upper_leg, bone_material  );						
	leg_meshes.knee      = new THREE.Mesh( leg_geoms.knee,      joint_material );
	leg_meshes.lower_leg = new THREE.Mesh( leg_geoms.lower_leg, bone_material  );
	leg_meshes.ankle     = new THREE.Mesh( leg_geoms.ankle,  	joint_material );			
	leg_meshes.foot      = new THREE.Mesh( leg_geoms.foot,      bone_material  );

//			upper_leg_mesh.position.x = 0;		upper_leg_mesh.position.y = 0;
//			elbow_mesh.position.x 	  = 0;
			//fore_leg_mesh.position.y  = 15;		//fore_leg_mesh.position.z  = upper_leg_length;
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
	l_leg_meshes.hip.position.y 		= 0;
	l_leg_meshes.hip.position.z 		= 0;

	r_leg_meshes.hip.position.x 		= 0;
	r_leg_meshes.hip.position.y 		= 00;
	r_leg_meshes.hip.position.z 		= 30;
}

function add_leg_to_scene(meshes)
{
	meshes.ankle.add     ( meshes.foot      );	
	meshes.lower_leg.add ( meshes.ankle     );
	meshes.knee.add      ( meshes.lower_leg );

	meshes.hip.add( meshes.upper_leg );
	meshes.hip.add( meshes.knee 	 );
	
	scene.add( meshes.hip       );
}
function set_leg_angles( angle_set, meshes )
{
	meshes.hip.rotation.y = angle_set.Hip;
	meshes.hip.rotation.x = angle_set.HipRotate;
	
	meshes.lower_leg.rotation.y = angle_set.Knee;
	meshes.foot.rotation.y = angle_set.Ankle;	
}

leg_materials();
construct_leg(l_leg_geom, l_leg_meshes, leg_material.meshMaterial2, leg_material.meshMaterial );
construct_leg(r_leg_geom, r_leg_meshes, leg_material.rmeshMaterial2, leg_material.rmeshMaterial );
init_leg_relative_locations(l_leg_meshes);
init_leg_relative_locations(r_leg_meshes);

place_legs();

add_leg_to_scene( l_leg_meshes );
add_leg_to_scene( r_leg_meshes );

