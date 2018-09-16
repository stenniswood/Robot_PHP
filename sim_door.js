
door_sizes = {
	width  : 36,
	height : 84,
	depth  : 1.5,
	
	frame_border : 1.5,
	knob_radius: 2,
};

var d_texture;
var door_geoms  = [];
var door_meshes = [];
var door_material;
var frame_material;

var frame_geom_1  =[];
var frame_geom_2  =[];
var frame_geom_3  =[];
var frame_mesh1   =[];
var frame_mesh2   =[];
var frame_mesh3   =[];
var frame_width   = 4;


var knob_geom			=[]; 
var knob_connector_geoms=[]; 
var knob_mesh			=[];
var knob_connector_mesh =[];


function make_door_materials()
{
//	d_texture = new THREE.TextureLoader().load( "./textures/door_carmelle.jpg" );
	//
	d_texture      = new THREE.TextureLoader().load( "./textures/door_milano_luna.jpg" );
	//d_texture.rotation = Math.PI;
	door_material  = new THREE.MeshBasicMaterial( { map: d_texture } );
	door_material  = new THREE.MeshBasicMaterial( { map: d_texture } );
	frame_material = new THREE.MeshPhongMaterial( { color: 0xEFEFEF, emissive: 0xFFEFDF, side: THREE.DoubleSide, flatShading: true } );	
}

function construct_door(door_index)
{
	// THE DOOR :
	// These parameters are intentionally switched in order to fit the Texture!*/
	door_geoms[door_index]	  = new THREE.BoxGeometry(  door_sizes.width, door_sizes.depth, door_sizes.height );
	door_meshes[door_index]   = new THREE.Mesh( door_geoms[door_index], door_material );
	door_geoms[door_index].translate(+door_sizes.width/2, 0, door_sizes.height/2);
	door_meshes[door_index].rotation.y = Math.PI/2;
	door_meshes[door_index].position.y = frame_width;
	door_meshes[door_index].position.y = frame_width;	
	
	// Door Frame : 
	frame_geom_1[door_index]	= new THREE.BoxGeometry( door_sizes.height,  frame_width, door_sizes.depth );
	frame_geom_2[door_index]	= new THREE.BoxGeometry( frame_width, 		 door_sizes.width+2*frame_width, door_sizes.depth );
	frame_geom_3[door_index]	= new THREE.BoxGeometry( door_sizes.height,  frame_width, door_sizes.depth );	

	frame_geom_1[door_index].translate( door_sizes.height/2, frame_width/2, 0 );
	frame_geom_2[door_index].translate( frame_width/2, 		 (door_sizes.width+2*frame_width)/2, 0 );
	frame_geom_3[door_index].translate( door_sizes.height/2, frame_width/2, 0 );

	frame_mesh1[door_index]     = new THREE.Mesh( frame_geom_1[door_index], frame_material );
	frame_mesh2[door_index]     = new THREE.Mesh( frame_geom_2[door_index], frame_material );
	frame_mesh3[door_index]     = new THREE.Mesh( frame_geom_3[door_index], frame_material );

	frame_mesh1[door_index].position.x = 0;			// Put back to -10 
	frame_mesh1[door_index].position.y = 0;			// was 31 
	frame_mesh1[door_index].position.z = 0;			// was 31 
	
	frame_mesh2[door_index].position.x = door_sizes.height;			// Put back to -10
	frame_mesh2[door_index].position.y = 0;			// was 31
	frame_mesh2[door_index].position.z = 0;		// was 31 

	frame_mesh3[door_index].position.y = frame_width+door_sizes.width;		// top piece

	//frame_mesh1[door_index].rotation.z = Math.PI/2;
	
	// Door knob:
	var knob_offset = {
		from_bottom: 45,
		from_side  : 4,
		off_door   : -2.0,
	}
	
	knob_geom[door_index]	 			= new THREE.SphereBufferGeometry( door_sizes.knob_radius,  20,  20 );	
	knob_connector_geoms[door_index]	= new THREE.CylinderGeometry	( door_sizes.knob_radius/2, door_sizes.knob_radius/2, knob_offset );
	knob_mesh[door_index]  				= new THREE.Mesh				( knob_geom[door_index],   object_materials.obj[door_index%3] );
	knob_connector_mesh[door_index]  	= new THREE.Mesh				( knob_connector_geoms[door_index],   object_materials.obj[door_index%3] );

	knob_mesh[door_index].position.x =  (door_sizes.width - knob_offset.from_side);
	knob_mesh[door_index].position.y =  knob_offset.off_door;
	knob_mesh[door_index].position.z =  knob_offset.from_bottom;

/*	knob_geom[door_index].translate			  ( knob_offset.off_door, knob_offset.from_bottom, knob_offset.from_side );
	knob_connector_geoms[door_index].translate( knob_offset.off_door, knob_offset.from_bottom, knob_offset.from_side );
	*/
	door_meshes[door_index].add( knob_mesh[door_index]          );
	door_meshes[door_index].add( knob_connector_mesh[door_index]);
	
	frame_mesh1[door_index].add( frame_mesh2[door_index] );
	frame_mesh1[door_index].add( frame_mesh3[door_index] ); 
	frame_mesh1[door_index].add( door_meshes[door_index] );
	
	//scene.add( door_meshes[door_index]);
}

var MAX_ANGLE = Math.PI/2;

function open_door_fraction( fraction, door_index )
{
	var angle = MAX_ANGLE * fraction;
	open_door( angle, door_index );
}

function open_door( angle, door_index )
{	
	if (angle>MAX_ANGLE)	angle = MAX_ANGLE;
	if (angle<0)			angle = 0.0;
	door_meshes[door_index].rotation.z = (angle);
	door_meshes[door_index].updateMatrixWorld( );
}

/* return is in door space */
function get_knob_location(door_index)
{
	//var v = new THREE.Vector3( Math.cos( j ), Math.sin( j ), 0 );	
	return knob_mesh[door_index].position;
}

function convert_door_space_to_world(xyz, door_index)
{
	var frame_coord = door_meshes[door_index].localToWorld( xyz ).clone();	
	return frame_coord; //frame_mesh1[door_index].localToWorld( frame_coord );
}

function door_positioning(door_index)
{
	frame_mesh1[door_index].position.x = -10;
	frame_mesh1[door_index].position.y = 80;
	frame_mesh1[door_index].position.z = 80;
	frame_mesh1[door_index].updateMatrixWorld();	
	//frame_mesh1[door_index].scale.set(0.8, 0.8, 0.8);
	//door_meshes[door_index].rotation.y= Math.PI;
	//door_meshes[door_index].rotation.z= Math.PI/2;	
}		

//var knob_path = [];
var knob_path_geometry = new THREE.Geometry();
		
function generate_door_knob_path( door_index, samples )
{
	var delta = 1.0 / samples;
	var frac  = 0;
	var angle = 0;
		
	for (i=0; i<samples; i++)
	{
		open_door_fraction( frac, door_index );
		door_meshes[door_index].updateMatrix();
		door_meshes[door_index].updateMatrixWorld();

		var knob_loc_l = get_knob_location(door_index).clone();
		var knob_loc_w = convert_door_space_to_world( knob_loc_l, door_index );

		knob_path_geometry.vertices.push( knob_loc_w );
		frac += delta;
	}

	var resolution = new THREE.Vector2( window.innerWidth, window.innerHeight );
	var line       = new MeshLine();
	var material   = new MeshLineMaterial({
		useMap : false,
		color  : new THREE.Color( 0xFF161f ),
		opacity   : 1,
		resolution: resolution,
		sizeAttenuation: !false,
		lineWidth: 2,
		near     : camera.near,
		far      : camera.far
	});
	var meshline = new THREE.Mesh( line.geometry, material );

	line.setGeometry( knob_path_geometry, function( p ) { return 4; }  );
	scene.add       ( meshline );
//	return path;
}



make_door_materials(   );
//construct_door     ( 0 );
//scene.add		   ( frame_mesh1[0] );

//open_door_fraction ( 0.0, 0 );
//door_positioning(0);

//generate_door_knob_path(0,100);


