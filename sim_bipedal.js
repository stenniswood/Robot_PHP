
var leg_sizes = {
		upper_leg_length:10,
		lower_leg_length:10,
		foot_length    :4,				

		upper_leg_width:2,
		upper_leg_depth:1.5,
		lower_leg_width:2,
		lower_leg_depth:1.5,

		foot_width    :4,
		foot_depth    :4,
		
		knee_radius    : 2,
		hip_radius : 3,
		ankle_radius:1.5,
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
			leg_geoms.hip			= new THREE.SphereGeometry		( leg_sizes.shoulder_radius,  leg_sizes.shoulder_radius,  20, 20 );
			leg_geoms.upper_leg		= new THREE.BoxGeometry			( leg_sizes.upper_leg_depth,  leg_sizes.upper_leg_width,  leg_sizes.upper_leg_length );
			leg_geoms.knee 	 		= new THREE.CylinderGeometry	( leg_sizes.elbow_radius, 	  leg_sizes.elbow_radius, 	  3, 32 );
			leg_geoms.lower_leg 	= new THREE.BoxGeometry			( leg_sizes.lower_leg_depth,  leg_sizes.lower_leg_width,  leg_sizes.lower_leg_length );
			leg_geoms.ankle			= new THREE.CylinderGeometry	( leg_sizes.wrist_mot_radius, leg_sizes.wrist_mot_radius, 3, 32 );
			leg_geoms.foot    		= new THREE.BoxGeometry			( leg_sizes.lower_leg_depth,  leg_sizes.lower_leg_width,  leg_sizes.wrist_length );

			// Where to 'grab' the part : 
			leg_geoms.upper_leg.translate ( 0, 0, leg_sizes.upper_leg_length/2 );
			leg_geoms.lower_leg.translate ( 0, 0, leg_sizes.upper_leg_length/2 );
			leg_geoms.knee.translate	  ( 0, 0, leg_sizes.upper_leg_length   );
			leg_geoms.foot.translate	  ( 0, 0, leg_sizes.wrist_length/2 	   );
			leg_geoms.ankle_mot.translate ( 0, 0, 0 );

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
function init_leg_locations()
{
	l_leg_meshes.shoulder.position.y 	= 10;
	l_leg_meshes.upper_leg.position.y 	= 10;
	l_leg_meshes.elbow.position.y 		= 10;
	l_leg_meshes.fore_leg.position.y 	= 10;
	l_leg_meshes.wrist_mot.position.y 	= 10;
	l_leg_meshes.wrist.position.y 		= 10;
	
	l_leg_meshes.shoulder.position.x 	= 0;
	l_leg_meshes.upper_leg.position.x 	= 0;
	l_leg_meshes.elbow.position.x 		= 0;
	l_leg_meshes.fore_leg.position.x 	= 0;
	l_leg_meshes.wrist_mot.position.x 	= 0;
	l_leg_meshes.wrist.position.x 		= 0;
	
	r_leg_meshes.shoulder.position.y 	= -10;
	r_leg_meshes.upper_leg.position.y 	= -10;
	r_leg_meshes.elbow.position.y 		= -10;
	r_leg_meshes.fore_leg.position.y 	= -10;
	r_leg_meshes.wrist_mot.position.y 	= -10;
	r_leg_meshes.wrist.position.y 		= -10;

	r_leg_meshes.shoulder.position.x 	= 0;
	r_leg_meshes.upper_leg.position.x 	= 0;
	r_leg_meshes.elbow.position.x 		= 0;
	r_leg_meshes.fore_leg.position.x 	= 0;
	r_leg_meshes.wrist_mot.position.x 	= 0;
	r_leg_meshes.wrist.position.x 		= 0;
	
}
function add_leg_to_scene(meshes)
{
	scene.add( meshes.hip       );
	scene.add( meshes.upper_leg );
	scene.add( meshes.knee	    );
	scene.add( meshes.lower_leg );
	scene.add( meshes.ankle    );
	scene.add( meshes.foot     );			
}

leg_materials();
construct_leg(l_leg_geom, l_leg_meshes, leg_material.meshMaterial2, leg_material.meshMaterial );
construct_leg(r_leg_geom, r_leg_meshes, leg_material.rmeshMaterial2, leg_material.rmeshMaterial );
init_leg_locations();

add_leg_to_scene( l_leg_meshes );
add_leg_to_scene( r_leg_meshes );

