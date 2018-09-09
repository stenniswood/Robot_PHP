door_sizes = {
	width  : 36,
	height : 84,
	depth  : 0.5,
	
	frame_border : 1.5,
	knob_radius: 1.5,
};

//door_location 

var d_texture;
var door_geoms = [];
var door_meshes = [];
var door_material;


function make_door_materials()
{
	door_material = new THREE.MeshBasicMaterial( { map: d_texture } );
}
function construct_door(door_index)
{
	d_texture = new THREE.TextureLoader().load( "./textures/door_milano_luna.jpg" );
//	d_texture = new THREE.TextureLoader().load( "./textures/door_carmelle.jpg" );
	//d_texture.rotation = Math.PI/2;

	door_geom[door_index]	= new THREE.BoxGeometry( door_sizes.depth,  door_sizes.height, door_sizes.width );
	door_geom[door_index].translate(0, door_sizes.height/2, 0);
	door_mesh[door_index]     = new THREE.Mesh( door_geom, door_material );

	// Door Frame
	frame_geom_1[door_index]	= new THREE.BoxGeometry( door_sizes.depth,  door_sizes.height, 1 );
	frame_geom_top[door_index]	= new THREE.BoxGeometry( door_sizes.depth,  1, door_sizes.width+2 );
	frame_geom_2[door_index]	= new THREE.BoxGeometry( door_sizes.depth,  door_sizes.height, 1 );
	
	frame_mesh1[door_index]     = new THREE.Mesh( frame_geom_1[door_index], door_material );
	frame_mesh2[door_index]     = new THREE.Mesh( frame_geom_top[door_index], door_material );
	frame_mesh3[door_index]     = new THREE.Mesh( frame_geom_2[door_index], door_material );
	
	frame_geom_top[door_index].position.x = door_sizes.height;
	frame_geom_2[door_index].position.z   = door_sizes.width;

	// Door knob:
	var knob_offset = {
		from_bottom: 36,
		from_side  : 2,
		off_door   : 1.2,
		
	}

	var knob_geom[door_index]	 			= new THREE.SphereBufferGeometry( door_sizes.knob_radius,  20,  20 );
	var knob_connector_geoms[door_index]	= new THREE.CylinderGeometry( door_sizes.knob_radius/2, door_sizes.knob_radius/2, knob_offset );
	var knob_mesh[door_index]  				= new THREE.Mesh( knob_geom[door_index],   object_materials.obj[door_index%3] );
	var knob_connector_mesh[door_index]  	= new THREE.Mesh( knob_connector_geoms[door_index],   object_materials.obj[door_index%3] );
			
			
	knob_geom[door_index].translateq		  ( knob_offset.off_door, knob_offset.from_bottom, knob_offset.from_side );
	knob_connector_geoms[door_index].translate( knob_offset.off_door, knob_offset.from_bottom, knob_offset.from_side );
	
	door_mesh.add(knob_mesh[door_index]);
	door_mesh.add(knob_connector_mesh[door_index]);
	
		
	frame_mesh1.add(frame_mesh2);
	frame_mesh1.add(frame_mesh3);
	door_mesh.add(frame_mesh1);	
}

var MAX_ANGLE = Math.PI/2;

function open_door( angle )
{
	if (angle>MAX_ANGLE)
		door_mesh.rotation.x = MAX_ANGLE;
	else 
		door_mesh.rotation.x += angle;	
}

/* return is in door space */
function get_knob_location(door_index)
{
	return knob_mesh[door_index].position;
}

function convert_door_space_to_world(xyz, door_index)
{
	door_mesh[door_index].localToWorld( xyz );
}


function door_positioning()
{
	door_mesh.position.x = -10;
	door_mesh.scale.set(0.8, 0.8, 0.8);
	door_mesh.rotation.y= Math.PI;
	door_mesh.rotation.z= Math.PI/2;	

	scene.add(door_mesh);
}		
	
	


make_door_materials();
construct_door(0);

door_positioning();
