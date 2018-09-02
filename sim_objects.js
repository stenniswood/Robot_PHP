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
	{	radius:2.2,  widthsegs:10, heightsegs:10 },
	{	radius:2,  widthsegs:20,  heightsegs:10 },
	{	radius:1,  widthsegs:30,  heightsegs:30 },
	{	radius:0.5,   widthsegs:5,  heightsegs:5 },	
];
var sphere_locations = [
	{	x:-10, y:10, z:-20 },
	{	x:-10, y:7, z:-10 },
	{	x:-10, y:5, z:0 },
	{	x:-10, y:5, z:15 },	
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


let object_materials   = {};
let object_geoms     = {};
let object_meshes   = [];

var object_color_1    = 0x754249;
var object_emissive_1 = 0x072534;

var object_color_2    = 0x754249;
var object_emissive_2 = 0x072534;

var object_color_3    = 0x754249;
var object_emissive_3 = 0x072534;



var object_centers = {};


function construct_objects(geoms, meshes )
{
	var mindex=0;
	box_sizes.forEach( (variable, index) => {
			geoms[index]	= new THREE.BoxGeometry( variable.depth,  variable.width,  variable.length );			
			geoms[index].translate ( variable.depth/2, 0, 0 );
			meshes[index]  = new THREE.Mesh( geoms[index],   arm_material.joint_material );			
			meshes[index].position.x 	= box_locations[index].x;
			meshes[index].position.y 	= box_locations[index].y;
			meshes[index].position.z 	= box_locations[index].z;			
	});
	sphere_sizes.forEach( (variable,index) => {
			mindex = box_sizes.length + index;
			geoms[mindex]	= new THREE.SphereBufferGeometry( variable.radius,  variable.widthsegs,  variable.heightsegs );
			geoms[mindex].translate ( variable.radius, 0, 0);
			meshes[mindex]  = new THREE.Mesh( geoms[mindex],   arm_material.joint_material );
			meshes[mindex].position.x 	= sphere_locations[index].x;
			meshes[mindex].position.y 	= sphere_locations[index].y;
			meshes[mindex].position.z 	= sphere_locations[index].z;
	});
	cylinder_sizes.forEach( (variable,index) => {
			mindex = box_sizes.length + sphere_sizes.length + index;	
			geoms[mindex]	= new THREE.CylinderGeometry( variable.b_radius, variable.b_radius, variable.height );
			geoms[mindex].translate ( variable.b_radius, 0, 0 );			
			meshes[mindex]  = new THREE.Mesh( geoms[mindex],   arm_material.joint_material );
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


construct_objects( object_geoms, object_meshes );

add_objs_to_scene( object_meshes );

create_obj_shadow_meshes(object_meshes);




