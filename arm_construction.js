
var arm_sizes = {
		upper_arm_length:10,
		lower_arm_length:10,
		wrist_length    :4,				

		upper_arm_width:2,
		upper_arm_depth:1.5,
		lower_arm_width:2,
		lower_arm_depth:1.5,

		elbow_radius    : 2,
		shoulder_radius : 3,
		wrist_mot_radius:1.5,
};

let arm_material   = {};
let l_arm_geom     = {};
let r_arm_geom     = {};
let l_arm_meshes   = {};
let r_arm_meshes   = {};

function arm_materials()
{
//	arm_material.material      = new THREE.MeshBasicMaterial( { color: 0x00ff00 } );
//	arm_material.lineMaterial  = new THREE.LineBasicMaterial( { color: 0xffffff, transparent: false, opacity: 0.5 } );
	arm_material.rmeshMaterial  = new THREE.MeshPhongMaterial( { color: 0x754249, emissive: 0x772534, side: THREE.DoubleSide, flatShading: true } );
	arm_material.rmeshMaterial2 = new THREE.MeshPhongMaterial( { color: 0xC56219, emissive: 0x876514, side: THREE.DoubleSide, flatShading: true } );
	arm_material.rmaterial3     = new THREE.MeshLambertMaterial( { color: 0x738C3C, wireframe: false } );

	arm_material.meshMaterial  = new THREE.MeshPhongMaterial( { color: 0x456259, emissive: 0x072534, side: THREE.DoubleSide, flatShading: true } );
	arm_material.meshMaterial2 = new THREE.MeshPhongMaterial( { color: 0xB56239, emissive: 0x974514, side: THREE.DoubleSide, flatShading: true } );
	arm_material.material3     = new THREE.MeshLambertMaterial( { color: 0x43CC4C, wireframe: false } );
}

function construct_arm(arm_geoms,arm_meshes, joint_material, bone_material )
{
			// UPPER ARM :
			arm_geoms.shoulder		= new THREE.SphereBufferGeometry( arm_sizes.shoulder_radius,  arm_sizes.shoulder_radius,  20, 20 );
			arm_geoms.upper_arm		= new THREE.BoxBufferGeometry	( arm_sizes.upper_arm_depth,  arm_sizes.upper_arm_width,  arm_sizes.upper_arm_length );
			arm_geoms.elbow 	 	= new THREE.CylinderGeometry	( arm_sizes.elbow_radius, 	  arm_sizes.elbow_radius, 	  3, 32 );
			arm_geoms.fore_arm 		= new THREE.BoxGeometry			( arm_sizes.lower_arm_depth,  arm_sizes.lower_arm_width,  arm_sizes.lower_arm_length );
			arm_geoms.wrist    		= new THREE.BoxGeometry			( arm_sizes.lower_arm_depth,  arm_sizes.lower_arm_width,  arm_sizes.wrist_length );
			arm_geoms.wrist_mot		= new THREE.CylinderGeometry	( arm_sizes.wrist_mot_radius, arm_sizes.wrist_mot_radius, 3, 32 );
			
			// Where to 'grab' the part : 
			arm_geoms.upper_arm.translate ( 0, 0, arm_sizes.upper_arm_length/2 );
			arm_geoms.fore_arm.translate  ( 0, 0, arm_sizes.upper_arm_length/2 );
			arm_geoms.elbow.translate	  ( 0, 0, arm_sizes.upper_arm_length   );
			arm_geoms.wrist.translate	  ( 0, 0, arm_sizes.wrist_length/2 	   );
			arm_geoms.wrist_mot.translate ( 0, 0, 0 );

			// 
			arm_meshes.shoulder  = new THREE.Mesh( arm_geoms.shoulder,   joint_material );
			arm_meshes.upper_arm = new THREE.Mesh( arm_geoms.upper_arm,  bone_material  );						
			arm_meshes.elbow     = new THREE.Mesh( arm_geoms.elbow,      joint_material );
			arm_meshes.fore_arm  = new THREE.Mesh( arm_geoms.fore_arm,   bone_material  );
			arm_meshes.wrist_mot = new THREE.Mesh( arm_geoms.wrist_mot,  joint_material );			
			arm_meshes.wrist     = new THREE.Mesh( arm_geoms.wrist,      bone_material  );

//			upper_arm_mesh.position.x = 0;		upper_arm_mesh.position.y = 0;
//			elbow_mesh.position.x 	  = 0;
			//fore_arm_mesh.position.y  = 15;		//fore_arm_mesh.position.z  = upper_arm_length;
}
function init_arm_locations()
{
	l_arm_meshes.shoulder.position.y 	= 10;
	l_arm_meshes.upper_arm.position.y 	= 10;
	l_arm_meshes.elbow.position.y 		= 10;
	l_arm_meshes.fore_arm.position.y 	= 10;
	l_arm_meshes.wrist_mot.position.y 	= 10;
	l_arm_meshes.wrist.position.y 		= 10;
	
	l_arm_meshes.shoulder.position.x 	= 0;
	l_arm_meshes.upper_arm.position.x 	= 0;
	l_arm_meshes.elbow.position.x 		= 0;
	l_arm_meshes.fore_arm.position.x 	= 0;
	l_arm_meshes.wrist_mot.position.x 	= 0;
	l_arm_meshes.wrist.position.x 		= 0;
	
	r_arm_meshes.shoulder.position.y 	= -10;
	r_arm_meshes.upper_arm.position.y 	= -10;
	r_arm_meshes.elbow.position.y 		= -10;
	r_arm_meshes.fore_arm.position.y 	= -10;
	r_arm_meshes.wrist_mot.position.y 	= -10;
	r_arm_meshes.wrist.position.y 		= -10;

	r_arm_meshes.shoulder.position.x 	= 0;
	r_arm_meshes.upper_arm.position.x 	= 0;
	r_arm_meshes.elbow.position.x 		= 0;
	r_arm_meshes.fore_arm.position.x 	= 0;
	r_arm_meshes.wrist_mot.position.x 	= 0;
	r_arm_meshes.wrist.position.x 		= 0;
	
}
function add_to_scene(meshes)
{
	scene.add( meshes.shoulder  );
	scene.add( meshes.upper_arm );
	scene.add( meshes.elbow	 );
	scene.add( meshes.fore_arm  );
	scene.add( meshes.wrist_mot );
	scene.add( meshes.wrist     );			
}

arm_materials();
construct_arm(l_arm_geom, l_arm_meshes, arm_material.meshMaterial2, arm_material.meshMaterial );
construct_arm(r_arm_geom, r_arm_meshes, arm_material.rmeshMaterial2, arm_material.rmeshMaterial );
init_arm_locations();

add_to_scene( l_arm_meshes );
add_to_scene( r_arm_meshes );

