/*
	Objects are here for manipulation by the robotic arms.	
*/
var box_sizes = [
	{	length:2, width:10, depth:6 },
	{	length:8, width:4,  depth:5 },
	{	length:2, width:3,  depth:3 },
	{	length:2, width:4,  depth:2 },	
];
var box_locations = [
	{	x:-10, y:-10, z:20 },
	{	x:-10, y:-7, z:10 },
	{	x:-10, y:+7, z:+15 },
	{	x:-10, y:-17, z:10 },	
];
var sphere_sizes = [
	{	radius:2.2,  widthsegs:10,  heightsegs:10 },
	{	radius:2,    widthsegs:20,  heightsegs:10 },
	{	radius:1,    widthsegs:30,  heightsegs:30 },
	{	radius:0.5,  widthsegs:5,   heightsegs:5 },	
];
var sphere_locations = [
	{	x:-10, y:10, z:-15 },
	{	x:-10, y:17, z:-10 },
	{	x:-10, y:15,  z:0 },
	{	x:-10, y:15, z:15 },	
];

var cylinder_sizes = [
	{	b_radius:0.75, t_radius:1, height:5  },
	{	b_radius:2,    t_radius:4, height:10  },
	{	b_radius:0.5,  t_radius:0.5,  height:7  },
	{	b_radius:1.5,  t_radius:2,  height:5   },	
];
var cylinder_locations = [
	{	x:-10, y:15, z:-23 },
	{	x:-10, y:-12,  z:-10 },
	{	x:-10, y:5,  z:-5 },
	{	x:-10, y:15,  z:-5 },	
];


let object_materials = {};
let object_geoms     = {};
let object_meshes    = [];
var object_centers   = {};

var obj_1_color = 0x954249;
var obj_2_color = 0x3522A9;	//0xC56219
var obj_3_color = 0x45A239;

var obj_1_emissive = 0x954249;
var obj_2_emissive = 0x3522A9;// 0x876514
var obj_3_emissive = 0x459239;


function make_object_materials()
{
	object_materials.obj = [];
	object_materials.obj[1] = new THREE.MeshPhongMaterial( { color: obj_1_color, emissive: obj_1_emissive, side: THREE.DoubleSide, flatShading: true } );
	object_materials.obj[2] = new THREE.MeshPhongMaterial( { color: obj_2_color, emissive: obj_2_emissive, side: THREE.DoubleSide, flatShading: true } );
	object_materials.obj[3] = new THREE.MeshLambertMaterial( { color: obj_3_color, emissive: obj_3_emissive, wireframe: false } );

	object_materials.meshMaterial  = new THREE.MeshPhongMaterial( { color: obj_1_color, emissive: obj_1_emissive, side: THREE.DoubleSide, flatShading: true } );
	object_materials.meshMaterialJ = new THREE.MeshPhongMaterial( { color: obj_2_color, emissive: obj_2_emissive, side: THREE.DoubleSide, flatShading: true } );
	object_materials.joint_material = new THREE.MeshLambertMaterial( { color: obj_3_color, wireframe: false } );

}
function construct_objects(geoms, meshes )
{
	var mindex=0;
	box_sizes.forEach( (variable, index) => {
			geoms[index]	= new THREE.BoxGeometry( variable.depth,  variable.width,  variable.length );			
			geoms[index].translate ( variable.depth/2, 0, 0 );
			meshes[index]  = new THREE.Mesh( geoms[index],   object_materials.obj[index%3] );			
			meshes[index].position.x 	= box_locations[index].x;
			meshes[index].position.y 	= box_locations[index].y;
			meshes[index].position.z 	= box_locations[index].z;			
	});
	sphere_sizes.forEach( (variable,index) => {
			mindex = box_sizes.length + index;
			geoms[mindex]	= new THREE.SphereBufferGeometry( variable.radius,  variable.widthsegs,  variable.heightsegs );
			geoms[mindex].translate ( variable.radius, 0, 0);
			meshes[mindex]  = new THREE.Mesh( geoms[mindex],   object_materials.obj[index%3] );
			meshes[mindex].position.x 	= sphere_locations[index].x;
			meshes[mindex].position.y 	= sphere_locations[index].y;
			meshes[mindex].position.z 	= sphere_locations[index].z;
	});
	cylinder_sizes.forEach( (variable,index) => {
			mindex = box_sizes.length + sphere_sizes.length + index;	
			geoms[mindex]	= new THREE.CylinderGeometry( variable.b_radius, variable.b_radius, variable.height );
			geoms[mindex].translate ( variable.b_radius, 0, 0 );			
			meshes[mindex]  = new THREE.Mesh( geoms[mindex],   object_materials.obj[index%3] );
			meshes[mindex].position.x 	= cylinder_locations[index].x;
			meshes[mindex].position.y 	= cylinder_locations[index].y;
			meshes[mindex].position.z 	= cylinder_locations[index].z;
	});
}

var smeshes = [];
function create_obj_shadow_meshes(meshes)
{
	meshes.forEach(  (variable,index) => {
		variable.castShadow 	= true;
		variable.receiveShadow 	= false;
		smeshes[index] = new THREE.ShadowMesh( variable );
		scene.add( smeshes[index] );		
	});
}

function add_objs_to_scene(meshes)
{
	meshes.forEach(  (variable,index) => {
		scene.add( variable  );
	});
}

var object_index = 0;	// 
function prev_object()
{
	object_index--;
	if (object_index < 0)
		object_index = 0;
	move_to_object();
}
function next_object()
{
	object_index++;
	if (object_index >= object_meshes.length)
		object_index = object_meshes.length-1;
	move_to_object();
}

function determine_smallest_axis(box3)
{
	var dx = box3.max.x - box3.min.x;
	var dy = box3.max.y - box3.min.y;
	var dz = box3.max.z - box3.min.z;
	var smallest_axis = 'x';
	if ((dx<dy) && (dx<dz))		smallest_axis = 'x';
	if ((dy<dx) && (dy<dz))		smallest_axis = 'y';
	if ((dz<dx) && (dz<dy))		smallest_axis = 'z';
	return smallest_axis;	
}
function determine_wrist_rotate(small_axis,base)
{
	// For -90 Approach!
	
	var retval=0;
	if (small_axis=='x')
		retval = base;
	else if (small_axis=='y')
		retval = base - Math.PI/2;
	else if (small_axis=='z')	
		retval = base; 
	
	// For 0 approach we'd always want wrist_rotate = 0. horizontal.
	
	return retval;
}
function move_to_object()
{
	object_meshes[object_index].geometry.computeBoundingBox();
	
	var xyz={};
	var zero_vec;
	var box3 			 = object_meshes[object_index].geometry.boundingBox;
	var smallest_axis 	 = determine_smallest_axis(box3);	
	var grip_distance    = box3.max[smallest_axis] - box3.min[smallest_axis];
	zero_vec = box3.getCenter();
	object_meshes[object_index].localToWorld( zero_vec );

	// TEXT FEEDBACK TO USER:
	var type 			= object_meshes[object_index].geometry.type;	
	var zero_world_xyz 	= "<" +zero_vec.x+ ", " +zero_vec.y+ ", "+zero_vec.z+"> ";
	var info 			= type+ "; smallest axis="+smallest_axis+"; grip="+grip_distance+" at "+zero_world_xyz;

	// FORM THE XYZ POSITOIN:
	xyz.Approach = inp_approach.value * Math.PI/180.;
	xyz.grip_position = grip_position;

	xyz.x = zero_vec.x;
	xyz.y = zero_vec.y;
	xyz.z = zero_vec.z;
	xyz.hand = "Left";
		
	var result = do_inverse_kinematics(xyz);
	if (result==false) {
		r_object_grab_feedback.innerHTML = "Using right arm.";
		xyz.x = zero_vec.x;
		xyz.y = zero_vec.y;
		xyz.z = zero_vec.z;
		xyz.hand = "Right";
		result = do_inverse_kinematics(xyz);
		if (result) {
			r_rad_servo_angle_set.WristRotate = xyz.WristRotate = determine_wrist_rotate(smallest_axis, r_rad_servo_angle_set.base);		
			r_object_grab_feedback.innerHTML = "Grabbed with right arm. "+info;
			set_sliders(xyz);
		} else {
			r_object_grab_feedback.innerHTML = "Out of reach!"+type + str_xyz;			
			open_gripper_distance( grip_distance, r_grip_meshes );
		}
	} else {
			l_rad_servo_angle_set.WristRotate = xyz.WristRotate = determine_wrist_rotate(smallest_axis, l_rad_servo_angle_set.base);
			r_object_grab_feedback.innerHTML = "Grabbed with Left arm"+info;
			open_gripper_distance( grip_distance, l_grip_meshes );
			set_sliders(xyz);			
	}
}



make_object_materials();
construct_objects( object_geoms, object_meshes );

add_objs_to_scene( object_meshes );

create_obj_shadow_meshes(object_meshes);




