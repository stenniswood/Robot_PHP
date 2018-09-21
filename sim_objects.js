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
	{	x:-10+75, y:+10, z:20 },
	{	x:-10+75, y:+7,  z:10 },
	{	x:-10+75, y:+7,  z:+15 },
	{	x:-10+75, y:+17, z:25 },	
];
var sphere_sizes = [
	{	radius:2.2,  widthsegs:10,  heightsegs:10 },
	{	radius:2,    widthsegs:20,  heightsegs:10 },
	{	radius:1,    widthsegs:30,  heightsegs:30 },
	{	radius:0.5,  widthsegs:5,   heightsegs:5 },	
];
var sphere_locations = [
	{	x:-10+100, y:-54, z:-100 },
	{	x:-10+100, y:-64, z:-100 },
	{	x:-10+75 , y:15, z: 0 },
	{	x:-10+75 , y:15, z:15 },	
]; 

var cylinder_sizes = [
	{	b_radius:0.75,  t_radius:1, height:5    },
	{	b_radius:2   ,  t_radius:4, height:10   },
	{	b_radius:0.5 ,  t_radius:0.5,  height:7 },
	{	b_radius:1.5 ,  t_radius:2,  height:5   },	
];
var cylinder_locations = [
	{	x:-10+75, y:15, z:-23 },
	{	x:-10+75, y:-12,  z:-10 },
	{	x:-10+75, y:5,  z:-5 },
	{	x:-10+75, y:15,  z:-5 },	
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
	object_materials.mat = [];		// Raw Coloring material
	object_materials.mat[0] = new THREE.MeshPhongMaterial  ( { color: obj_1_color, emissive: obj_1_emissive, side: THREE.DoubleSide, flatShading: true } );
	object_materials.mat[1] = new THREE.MeshPhongMaterial  ( { color: obj_2_color, emissive: obj_2_emissive, side: THREE.DoubleSide, flatShading: true } );
	object_materials.mat[2] = new THREE.MeshLambertMaterial( { color: obj_3_color, emissive: obj_3_emissive, wireframe: false } );
	object_materials.mat[3] = new THREE.MeshLambertMaterial( { map: texture_loader.load( '../physics/examples/images/plywood.jpg' ) });
	object_materials.mat[4] = new THREE.MeshLambertMaterial( { map: texture_loader.load( './textures/cinder-block-texture.jpg' ) });

	object_materials.pmat = [];		// Physical material
	object_materials.pmat[0] = Physijs.createMaterial( object_materials.mat[0], .9 /* medium friction */, .4 /* low restitution */	);
	object_materials.pmat[1] = Physijs.createMaterial( object_materials.mat[1], .8 /* medium friction */, .2 /* low restitution */	);
	object_materials.pmat[2] = Physijs.createMaterial( object_materials.mat[2], .6 /* medium friction */, .6 /* low restitution */	);
	object_materials.pmat[3] = Physijs.createMaterial( object_materials.mat[3], .6 /* medium friction */, .3 /* low restitution */	);
	object_materials.pmat[4] = Physijs.createMaterial( object_materials.mat[4], .6 /* medium friction */, .3 /* low restitution */	);		

	object_materials.pmat[3].map.wrapS = object_materials.pmat[3].map.wrapT = THREE.RepeatWrapping;
	object_materials.pmat[3].map.repeat.set( 1.0, 1.0 );	
	object_materials.pmat[4].map.wrapS = object_materials.pmat[3].map.wrapT = THREE.RepeatWrapping;
	object_materials.pmat[4].map.repeat.set( 1.0, 1.0 );
}

function construct_objects(geoms, meshes )
{
	var mindex=0;
	box_sizes.forEach( (variable, index) => {
			geoms[index]   = new THREE.BoxGeometry( variable.depth,  variable.width,  variable.length );
			//geoms[index].translate ( variable.depth/2, 0, 0 );

			meshes[index] = new Physijs.BoxMesh( geoms[index], object_materials.pmat[index%5], 10 ); // mass
			//meshes[index]  = new THREE.Mesh( geoms[index],   object_materials.obj[index%3] );
			meshes[index].position.x 	= box_locations[index].x;
			meshes[index].position.y 	= box_locations[index].y;
			meshes[index].position.z 	= box_locations[index].z;	
			meshes[index].__dirtyPosition = true;		
	});

	sphere_sizes.forEach( (variable,index) => {
			mindex = box_sizes.length + index;
			geoms[mindex]	= new THREE.SphereGeometry( variable.radius,  variable.widthsegs,  variable.heightsegs );
			//geoms[mindex].translate ( variable.radius, 0, 0);

			meshes[mindex] = new Physijs.SphereMesh( geoms[mindex], object_materials.pmat[index%5], 20 );
			//meshes[index] = new Physijs.BoxMesh( geoms[index], object_materials.pmat[index%5], 0 ); // mass			
			//meshes[mindex]  = new THREE.Mesh( geoms[mindex],   object_materials.mat[index%3] );
			meshes[mindex].position.x 	= sphere_locations[index].x;
			meshes[mindex].position.y 	= sphere_locations[index].y;
			meshes[mindex].position.z 	= sphere_locations[index].z;
			meshes[mindex].__dirtyPosition = true;					
	});
	cylinder_sizes.forEach( (variable,index) => {
			mindex = box_sizes.length + sphere_sizes.length + index;	
			geoms[mindex]	= new THREE.CylinderGeometry( variable.b_radius, variable.b_radius, variable.height );
			//geoms[mindex].translate ( variable.b_radius, 0, 0 );			
			meshes[mindex]  = new Physijs.CylinderMesh( geoms[mindex],   object_materials.pmat[index%3], 20 );
			
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

function which_hand_is_best(xyz)
{
	// INVERSE KINEMATICS:
	var result = do_inverse_kinematics(xyz);
	if (result!=true) {		// Unreachable Position!  Try Right arm.		
		
		result = do_inverse_kinematics(xyz);
		if (result==true) {		
			r_object_grab_feedback.innerHTML = "Grabbed with right arm. "+info;
			return "Right";

		} else {				// Unreachable with Right!
			return "out_of_reach";
		}
	} else {
			return "Left";
	}
}

function move_to_object()
{
	release_object(xyz);
	
	object_meshes[object_index].geometry.computeBoundingBox();
	
	// FIND CENTER, SMALLEST AXIS, & GRIP WIDTH FOR THE OBJECT:
	var xyz={};
	var zero_vec;
	var box3 			 = object_meshes[object_index].geometry.boundingBox;
	var smallest_axis 	 = determine_smallest_axis(box3);	
	var grip_distance    = box3.max[smallest_axis] - box3.min[smallest_axis];
	zero_vec             = box3.getCenter();
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
	
	// INVERSE KINEMATICS:
	var best_hand = which_hand_is_best(xyz);
	var result = do_inverse_kinematics(xyz);
	if (result!=true) {		// Unreachable Position!  Try Right arm.		
		r_object_grab_feedback.innerHTML = "Using right arm.";
		xyz.x = zero_vec.x;
		xyz.y = zero_vec.y;
		xyz.z = zero_vec.z;
		xyz.hand = "Right";
		result = do_inverse_kinematics(xyz);
		if (result==true) {		
			r_rad_servo_angle_set.WristRotate = xyz.WristRotate = determine_wrist_rotate(smallest_axis, r_rad_servo_angle_set.Base);		
			set_servo_angles_rad( r_arm_meshes, r_grip_meshes, arm_sizes, r_rad_servo_angle_set );
						update_object_position(xyz);
			r_object_grab_feedback.innerHTML = "Grabbed with right arm. "+info;
			open_gripper_distance( grip_distance, "Right", r_grip_meshes );			
			set_sliders(xyz);
		} else {				// Unreachable with Right!
			r_object_grab_feedback.innerHTML = "Out of reach!"+type + str_xyz;			
		}
	} else {
			l_rad_servo_angle_set.WristRotate = xyz.WristRotate = determine_wrist_rotate(smallest_axis, l_rad_servo_angle_set.Base);
			set_servo_angles_rad( l_arm_meshes, l_grip_meshes, arm_sizes, l_rad_servo_angle_set );
						update_object_position(xyz);			
			r_object_grab_feedback.innerHTML = "Grabbed with Left arm"+info;
			open_gripper_distance( grip_distance, "Left", l_grip_meshes );
			set_sliders(xyz);			
	}

	grab_object   (xyz, xyz.hand);
		
	var closest_obj_index = find_closest_object( xyz );
	console.log("Closest obj=");
	console.log(closest_obj_index);
}

// Front/Back is +0 Y direction.  
// Returns XYZ coordinate.
function place_on_top_of( mesh, ref_group )
{
	var dest_xyz = {};
	mesh.geometry.computeBoundingBox();
	var ref_box = get_group_bounding_box( ref_group );	

	var ref_width  = (ref_box.max.z - ref_box.min.z);
	var ref_length = (ref_box.max.y - ref_box.min.y);	

	var new_x = ref_box.max.x + ref_group.position.x;
	var new_z = Math.random() * ref_width  + ref_group.position.z;
	var new_y = Math.random() * ref_length + ref_group.position.y;

	var Padding = 2;
	var obj_width  = (mesh.geometry.boundingBox.max.z - mesh.geometry.boundingBox.min.z);
	var obj_length = (mesh.geometry.boundingBox.max.y - mesh.geometry.boundingBox.min.y);	

	dest_xyz.x = new_x;
	dest_xyz.y = new_y;
	dest_xyz.z = new_z;	

	mesh.position.x = new_x;
	mesh.position.y = new_y;
	mesh.position.z = new_z;

/*	var alignment_edge = 0;
	if (align=="front")			// toward robot
	{
		alignment_edge  = (ref_mesh.position.y-ref_length/2);	// front edge.
		dest_xyz.y = alignment_edge + obj_length/2;		
	} else if (align=="back")	
	{
		alignment_edge  = (ref_mesh.position.y+ref_length/2);	// back edge.
		dest_xyz.y = alignment_edge - obj_length/2;
	} else if (align=="centers")
	{
		dest_xyz.y = ref_mesh.position.y;
	} */
	return dest_xyz;
}

// Front/Back is +0 Y direction.  
// Returns XYZ coordinate.
function place_in_front_of( mesh, ref_mesh )
{
	var dest_xyz = {};
	mesh.computeBoundingBox();
	ref_mesh.computeBoundingBox();	

	var ref_length = (ref_mesh.boundingBox.max.y - ref_mesh.boundingBox.min.y);	
	var Padding = 2;
	var obj_width  = (mesh.boundingBox.max.z - mesh.boundingBox.min.z);
	var obj_length = (mesh.boundingBox.max.y - mesh.boundingBox.min.y);	
	var new_y_position = ref_left_edge - Padding - obj_width/2;

	dest_xyz.x = ref_mesh.position.x;
	dest_xyz.z = ref_mesh.position.z;	
	dest_xyz.y = new_y_position;
	
	var alignment_edge = 0;
	if (align=="front")			// toward robot
	{
		alignment_edge  = (ref_mesh.position.y-ref_length/2);	// front edge.
		dest_xyz.y = alignment_edge + obj_length/2;		
	} else if (align=="back")	
	{
		alignment_edge  = (ref_mesh.position.y+ref_length/2);	// back edge.
		dest_xyz.y = alignment_edge - obj_length/2;
	} else if (align=="centers")
	{
		dest_xyz.y = ref_mesh.position.y;
	}
	return dest_xyz;
}

function place_behind( mesh, ref_mesh )
{
	var dest_xyz = {};
	mesh.computeBoundingBox();
	ref_mesh.computeBoundingBox();	

	var ref_width  = (ref_mesh.boundingBox.max.z - ref_mesh.boundingBox.min.z);	
	var ref_length = (ref_mesh.boundingBox.max.y - ref_mesh.boundingBox.min.y);	
	var Padding = 2;
	var obj_width  = (mesh.boundingBox.max.z - mesh.boundingBox.min.z);
	var obj_length = (mesh.boundingBox.max.y - mesh.boundingBox.min.y);	
	var ref_back_edge = ref_mesh.boundingBox.max.y + ref_length;	
	var new_y_position = ref_back_edge + Padding + obj_width/2;

	dest_xyz.x = ref_mesh.position.x;
	dest_xyz.z = ref_mesh.position.z;	
	dest_xyz.y = new_y_position;
	
	var alignment_edge = 0;
	if (align=="left")			// toward robot
	{
		alignment_edge  = (ref_mesh.position.z-ref_width/2);	// front edge.
		dest_xyz.z  = alignment_edge + obj_width/2;		
	} else if (align=="right")	
	{
		alignment_edge  = (ref_mesh.position.z+ref_width/2);	// back edge.
		dest_xyz.z  = alignment_edge - obj_width/2;
	} else if (align=="centers")
	{
		dest_xyz.z = ref_mesh.position.z;
	}
	return dest_xyz;
}

/* Sideways is +0 Z direction.  
align : 	"front"
		 	"back"
		 	"centers"
*/
function place_left_of( mesh, ref_mesh, align )
{
	var dest_xyz = {};
	mesh.computeBoundingBox();
	ref_mesh.computeBoundingBox();	
	
	var ref_width  = (ref_mesh.boundingBox.max.z - ref_mesh.boundingBox.min.z);
	var ref_length = (ref_mesh.boundingBox.max.y - ref_mesh.boundingBox.min.y);	
	var ref_left_edge = ref_mesh.position.z - ref_width/2;
	var Padding = 2;
	
	var obj_width  = (mesh.boundingBox.max.z - mesh.boundingBox.min.z);
	var obj_length = (mesh.boundingBox.max.y - mesh.boundingBox.min.y);	
	var new_z_position = ref_left_edge - Padding - obj_width/2;

	dest_xyz.x = ref_mesh.position.x;
	dest_xyz.z = new_position;
	
	var alignment_edge = 0;
	if (align=="front")			// toward robot
	{
		alignment_edge  = (ref_mesh.position.y-ref_length/2);	// front edge.
		dest_xyz.y = alignment_edge + obj_length/2;		
	} else if (align=="back")	
	{
		alignment_edge  = (ref_mesh.position.y+ref_length/2);	// back edge.
		dest_xyz.y = alignment_edge - obj_length/2;
	} else if (align=="centers")
	{
		dest_xyz.y = ref_mesh.position.y;
	}
	return dest_xyz;
}

function place_right_of( mesh, ref_mesh )
{
	var dest_xyz = {};
	mesh.computeBoundingBox();
	ref_mesh.computeBoundingBox();	
	var ref_width  		= (ref_mesh.boundingBox.max.z - ref_mesh.boundingBox.min.z);
	var ref_length 		= (ref_mesh.boundingBox.max.y - ref_mesh.boundingBox.min.y);	
	var ref_right_edge 	= ref_mesh.position.z + ref_width/2;
	var Padding 		= 2;
	
	var obj_width  		= (mesh.boundingBox.max.z - mesh.boundingBox.min.z);
	var obj_length 		= (mesh.boundingBox.max.y - mesh.boundingBox.min.y);	
	var new_z_position 	= ref_right_edge + Padding + obj_width/2;

	dest_xyz.x = ref_mesh.position.x;
	dest_xyz.z = new_position;
	
	var alignment_edge = 0;
	if (align=="front")			// toward robot
	{
		alignment_edge  = (ref_mesh.position.y-ref_length/2);	// front edge.
		dest_xyz.y = alignment_edge + obj_length/2;		
	} else if (align=="back")	
	{
		alignment_edge  = (ref_mesh.position.y+ref_length/2);	// back edge.
		dest_xyz.y = alignment_edge - obj_length/2;
	} else if (align=="centers")
	{
		dest_xyz.y = ref_mesh.position.y;
	}
	return dest_xyz;
}

function find_closest_object(xyz)
{
	var min_index=-1;
	var min_distance = 100000;
	var txyz = new THREE.Vector3(xyz.x, xyz.y, xyz.z);
	
	object_meshes.forEach( (mesh,index) => {
		var distance = mesh.position.distanceTo ( txyz );
		if (distance < min_distance) {
 			min_distance = distance;
 			min_index = index;
 		}
	});
	return min_index;
}

// Left or Right hand holding the object?
var Lefthand_holding_object = false;
var grasped_object = -1;
function update_object_position(newPos)
{
	if (grasped_object==-1)	return;
	object_meshes[grasped_object].position.x = newPos.x;
	object_meshes[grasped_object].position.y = newPos.y;
	object_meshes[grasped_object].position.z = newPos.z;
}

function grab_object(xyz, hand)
{
	grasped_object = find_closest_object(xyz);
	var HAND = hand.toUpperCase();
	Lefthand_holding_object = HAND;
}
function release_object(xyz)
{
	grasped_object = -1;
}

function find_largest_area(xyz)
{
	var len;
	var wid;
	var area;
	var max_area=0;
	var max_index=-1;	
	
	object_meshes.forEach(  (m,index) =>{
		if (m.geometry.type=="BoxGeometry")
		{
			m.geometry.computeBoundingBox();
			len = m.geometry.boundingBox.max.y-m.geometry.boundingBox.min.y;
			wid = m.geometry.boundingBox.max.z-m.geometry.boundingBox.min.z;
			area = len * wid;
			if (area > max_area) {
				max_area = area;
				max_index = index;
			}			
		}		
	});
	return max_index;
}


make_object_materials();
construct_objects( object_geoms, object_meshes );
add_objs_to_scene( object_meshes );
create_obj_shadow_meshes(object_meshes);

var omi;
var obj;
for (omi=0; omi<6; omi++)
	obj = place_on_top_of( object_meshes[omi], table[1] );

for (omi=6; omi<12; omi++)
	place_on_top_of( object_meshes[omi], table[0] );

object_meshes[5].setLinearVelocity( new THREE.Vector3(0, -200, 0) );



