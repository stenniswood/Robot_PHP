/* yellow robot arm - measured dimensions are :
var LENGTH_SHOULDER =	4.75;
var LENGTH_ELBOW	=	4.75;
var LENGTH_WRIST	=	6.0;
var BASE_HEIGHT		= 	2.5;
*/
var arm_sizes = {
		upper_arm_length:8,
		lower_arm_length:8,
		wrist_length    :4,				

		upper_arm_width:2,
		upper_arm_depth:1.5,
		lower_arm_width:2,
		lower_arm_depth:1.5,

		elbow_radius    : 2,
		shoulder_radius : 3,
		wrist_mot_radius:1.5,
};

var out_of_range_color = 0xffF02f;
var collision_color    = 0xff001f;
	
Math.radians = function(degrees) {
  return degrees * Math.PI / 180;
};
 
// Converts from radians to degrees.
Math.degrees = function(radians) {
  return radians * 180 / Math.PI;
};
	
function get_total_arm_length()
{
	return arm_sizes.upper_arm_length + arm_sizes.lower_arm_length + arm_sizes.wrist_length;
}

let arm_material   = {};
let l_arm_geom     = [];
let r_arm_geom     = [];
let l_arm_meshes   = {};
let r_arm_meshes   = {};

var bone_color = 0x754249;
var bone_emissive = 0x072534;
function arm_materials()
{
	arm_material.rmeshMaterial  = new THREE.MeshPhongMaterial( { color: bone_color, emissive: bone_emissive, side: THREE.DoubleSide, flatShading: true } );
	arm_material.rmeshMaterialJ = new THREE.MeshPhongMaterial( { color: 0xC56219, emissive: 0x876514, side: THREE.DoubleSide, flatShading: true } );
	arm_material.rmaterial3     = new THREE.MeshLambertMaterial( { color: 0x738C3C, wireframe: false } );

	arm_material.meshMaterial  = new THREE.MeshPhongMaterial( { color: bone_color, emissive: bone_emissive, side: THREE.DoubleSide, flatShading: true } );
	arm_material.meshMaterialJ = new THREE.MeshPhongMaterial( { color: 0xB56239, emissive: 0x974514, side: THREE.DoubleSide, flatShading: true } );
	arm_material.material3     = new THREE.MeshLambertMaterial( { color: 0x43CC4C, wireframe: false } );
}


function construct_arm(arm_geoms,arm_meshes, joint_material, bone_material )
{
	// UPPER ARM :
	arm_geoms[0] /*shoulder	 */		= new THREE.SphereBufferGeometry( arm_sizes.shoulder_radius,  arm_sizes.shoulder_radius,  20, 20 );
	arm_geoms[1] /*upper_arm */		= new THREE.BoxBufferGeometry	( arm_sizes.upper_arm_depth,  arm_sizes.upper_arm_width,  arm_sizes.upper_arm_length );
	arm_geoms[2] /*elbow 	 */		= new THREE.CylinderGeometry	( arm_sizes.elbow_radius, 	  arm_sizes.elbow_radius, 	  3, 32 );
	arm_geoms[3] /*fore_arm  */		= new THREE.BoxGeometry			( arm_sizes.lower_arm_depth,  arm_sizes.lower_arm_width,  arm_sizes.lower_arm_length );
	arm_geoms[4] /*wrist     */		= new THREE.BoxGeometry			( arm_sizes.lower_arm_depth,  arm_sizes.lower_arm_width,  arm_sizes.wrist_length );
	arm_geoms[5] /*wrist_mot */		= new THREE.CylinderGeometry	( arm_sizes.wrist_mot_radius, arm_sizes.wrist_mot_radius, 3, 32 );

	// Where to 'grab' the part : 
	arm_geoms[1].translate ( 0, 0, arm_sizes.upper_arm_length/2 );		// .upper_arm
	arm_geoms[3].translate ( 0, 0, arm_sizes.lower_arm_length/2 );		// .fore_arm
	arm_geoms[4].translate ( 0, 0,  arm_sizes.wrist_length/2);			// .wrist
	arm_geoms[5].translate ( 0, 0, 0 );									// .wrist_mot

	// CREATE MESHES:
	arm_meshes.shoulder  = new THREE.Mesh( arm_geoms[0],  joint_material );
	arm_meshes.upper_arm = new THREE.Mesh( arm_geoms[1],  bone_material  );						
	arm_meshes.elbow     = new THREE.Mesh( arm_geoms[2],  joint_material );
	arm_meshes.fore_arm  = new THREE.Mesh( arm_geoms[3],  bone_material  );
	arm_meshes.wrist_mot = new THREE.Mesh( arm_geoms[4],  joint_material );			
	arm_meshes.wrist     = new THREE.Mesh( arm_geoms[5],  bone_material  );
}

function init_arm_locations()
{
	l_arm_meshes.shoulder.position.z 	= 10;
	l_arm_meshes.upper_arm.position.z 	= 0;
	l_arm_meshes.elbow.position.z 		= arm_sizes.upper_arm_length;
	l_arm_meshes.fore_arm.position.z 	= 0;
	l_arm_meshes.wrist_mot.position.z 	= arm_sizes.lower_arm_length;
	l_arm_meshes.wrist.position.z 		= 0;
	
	l_arm_meshes.shoulder.position.x 	= 0;
	l_arm_meshes.upper_arm.position.x 	= 0;
	l_arm_meshes.elbow.position.x 		= 0;
	l_arm_meshes.fore_arm.position.x 	= 0;
	l_arm_meshes.wrist_mot.position.x 	= 0;
	l_arm_meshes.wrist.position.x 		= 0;
	
	r_arm_meshes.shoulder.position.z 	= -10;
	r_arm_meshes.upper_arm.position.z 	= 0;
	r_arm_meshes.elbow.position.z 		= arm_sizes.upper_arm_length;
	r_arm_meshes.fore_arm.position.z 	= 0;
	r_arm_meshes.wrist_mot.position.z 	= arm_sizes.lower_arm_length;
	r_arm_meshes.wrist.position.z 		= 0;

	r_arm_meshes.shoulder.position.x 	= 0;
	r_arm_meshes.upper_arm.position.x 	= 0;
	r_arm_meshes.elbow.position.x 		= 0;
	r_arm_meshes.fore_arm.position.x 	= 0;
	r_arm_meshes.wrist_mot.position.x 	= 0;
	r_arm_meshes.wrist.position.x 		= 0;
	
}
function create_shadow_meshes(arm_meshes)
{
	arm_meshes.shoulder.castShadow 	= true;
	arm_meshes.upper_arm.castShadow = true;
	arm_meshes.elbow.castShadow 	= true;
	arm_meshes.fore_arm.castShadow 	= true;
	arm_meshes.wrist_mot.castShadow = true;
	arm_meshes.wrist.castShadow 	= true;

	arm_meshes.shoulder.receiveShadow 	= false;
	arm_meshes.upper_arm.receiveShadow 	= false;
	arm_meshes.elbow.receiveShadow 		= false;
	arm_meshes.fore_arm.receiveShadow 	= false;
	arm_meshes.wrist_mot.receiveShadow 	= false;
	arm_meshes.wrist.receiveShadow 		= false;

	arm_meshes.luaShadow = new THREE.ShadowMesh( arm_meshes.upper_arm );
	arm_meshes.leaShadow = new THREE.ShadowMesh( arm_meshes.elbow 	  );
	arm_meshes.llaShadow = new THREE.ShadowMesh( arm_meshes.fore_arm  );
	arm_meshes.lwmShadow = new THREE.ShadowMesh( arm_meshes.wrist_mot );
	arm_meshes.lwaShadow = new THREE.ShadowMesh( arm_meshes.wrist     );

	scene.add( arm_meshes.luaShadow );
	scene.add( arm_meshes.leaShadow );
	scene.add( arm_meshes.llaShadow );
	scene.add( arm_meshes.lwmShadow );
	scene.add( arm_meshes.lwaShadow );
}
function add_to_scene(meshes)
{
	meshes.shoulder.add( meshes.upper_arm );
	meshes.upper_arm.add( meshes.elbow     );	
	meshes.elbow.add( meshes.fore_arm  );
	meshes.fore_arm.add( meshes.wrist_mot );	
	meshes.wrist_mot.add( meshes.wrist  );

//	scene.add( meshes.shoulder  );
}

function arms_collide()
{
	var retval = false;
	// Get Bounding Box for each segment of the Left Arm:
	var l_boxes = [];
	var vec_min=[];
	var vec_max=[];
	l_arm_geom.forEach ( (variable,index) => {
		l_arm_geom[index].computeBoundingBox();
		vec_min[index] = variable.boundingBox.min;
		vec_max[index] = variable.boundingBox.max;
	});
	
	l_arm_meshes.shoulder.updateMatrixWorld();
	l_arm_meshes.shoulder.localToWorld ( vec_min[0] );	l_arm_meshes.shoulder.localToWorld	( vec_max[0] );
	l_boxes[0] = new THREE.Box3( vec_min[0], vec_max[0] );
	
	l_arm_meshes.upper_arm.updateMatrixWorld();	
	l_arm_meshes.upper_arm.localToWorld( vec_min[1] );	l_arm_meshes.upper_arm.localToWorld	( vec_max[1] );		
	l_boxes[1] = new THREE.Box3( vec_min[1], vec_max[1] );
		
	l_arm_meshes.elbow.updateMatrixWorld();			
	l_arm_meshes.elbow.localToWorld	   ( vec_min[2] );	l_arm_meshes.elbow.localToWorld		( vec_max[2] );		
	l_boxes[2] = new THREE.Box3( vec_min[2], vec_max[2] );
	
	l_arm_meshes.fore_arm.updateMatrixWorld();		
	l_arm_meshes.fore_arm.localToWorld ( vec_min[3] );	l_arm_meshes.fore_arm.localToWorld	( vec_max[3] );		
	l_boxes[3] = new THREE.Box3( vec_min[3], vec_max[3] );			



	vec_min = [];
	vec_max = [];
	r_arm_geom.forEach ( (variable,index) => {
		r_arm_geom[index].computeBoundingBox();
		vec_min[index] = variable.boundingBox.min;
		vec_max[index] = variable.boundingBox.max;
	});

	var r_boxes = [];	
	r_arm_meshes.shoulder.updateMatrixWorld();
	r_arm_meshes.shoulder.localToWorld ( vec_min[0] );	r_arm_meshes.shoulder.localToWorld	( vec_max[0] );
	r_boxes[0] = new THREE.Box3( vec_min[0], vec_max[0] );

	r_arm_meshes.upper_arm.updateMatrixWorld();	
	r_arm_meshes.upper_arm.localToWorld( vec_min[1] );	r_arm_meshes.upper_arm.localToWorld	( vec_max[1] );		
	r_boxes[1] = new THREE.Box3( vec_min[1], vec_max[1] );
		
	r_arm_meshes.elbow.updateMatrixWorld();	
	r_arm_meshes.elbow.localToWorld	   ( vec_min[2] );	r_arm_meshes.elbow.localToWorld		( vec_max[2] );		
	r_boxes[2] = new THREE.Box3( vec_min[2], vec_max[2] );
	
	r_arm_meshes.fore_arm.updateMatrixWorld();	
	r_arm_meshes.fore_arm.localToWorld ( vec_min[3] );	r_arm_meshes.fore_arm.localToWorld	( vec_max[3] );		
	r_boxes[3] = new THREE.Box3( vec_min[3], vec_max[3] );			


	// Get Bounding Box for each segment of the Right Arm:
/*	var r_boxes = [];
	r_arm_geom.forEach ( (variable,index) => {
		variable.computeBoundingBox();
		vec1 = variable.boundingBox.min;
		vec2 = variable.boundingBox.max;
		r_arm_meshes[index].localToWorld( vec1 );
		r_arm_meshes[index].localToWorld( vec2 );		
		r_boxes[index] = new THREE.Box3( vec1, vec2 );
	}); */
	
	// Check for intersection amoung any 2 boxes:	
	var collision;
	l_boxes.forEach ( (l_box,l_index) => {		
		r_boxes.forEach( (r_box,r_index) => {			
			collision = r_box.intersectsBox( l_box );
			if (collision)
				retval = true;
		})
	});
	return retval;
}

arm_materials();
construct_arm(l_arm_geom, l_arm_meshes, arm_material.meshMaterialJ,  arm_material.meshMaterial  );
construct_arm(r_arm_geom, r_arm_meshes, arm_material.rmeshMaterialJ,  arm_material.rmeshMaterial );
init_arm_locations();


add_to_scene( l_arm_meshes );
add_to_scene( r_arm_meshes );

create_shadow_meshes(l_arm_meshes);
create_shadow_meshes(r_arm_meshes);



