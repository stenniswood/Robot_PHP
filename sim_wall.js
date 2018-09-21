
wall_sizes = {
	std_thickness  : 6,
	std_height     : 10*12,
};

var rooms = [];
var walls = [];

var s_texture;
var wall_rise_material;
var wall_run_material;

var rise_geom = [];
var rise_mesh = [];
var run_geom = [];
var run_mesh = [];

var left_side_geom = [];
var left_side_mesh = [];

var right_side_geom = [];
var right_side_mesh = [];
var frame_width   = 4;


function make_wall_materials()
{
//	d_texture = new THREE.TextureLoader().load( "./textures/wall_carmelle.jpg" );
//	t_texture       = new THREE.TextureLoader().load( "./textures/wall_milano_luna.jpg" );
	wall_material  = new THREE.MeshPhongMaterial ( { color: 0xFF0000, emissive: 0xFF0000, side: THREE.DoubleSide, wireframe:false, flatShading: false } );	
	wall_material2  = new THREE.MeshPhongMaterial( { color: 0x00FF00, emissive: 0x00FF00, side: THREE.DoubleSide, wireframe:true, flatShading: false } );	
	
	//wall_run_material   = new THREE.MeshPhongMaterial( { color: 0x00FF00, emissive: 0x00EF00, side: THREE.DoubleSide, flatShading: false } );	
	wall_side_material  = new THREE.MeshPhongMaterial( { color: 0x0000EF, emissive: 0x0000DF, side: THREE.DoubleSide, flatShading: false } );	
}

function construct_wall(length, material)
{
	wall = {};
	wall.length = length;
	wall.thickness = wall_sizes.std_thickness;	
	wall.geom  = new THREE.BoxGeometry( wall_sizes.std_height, wall.thickness, length );
	wall.mesh  = new THREE.Mesh       ( wall.geom, material );

	wall.geom.translate( wall_sizes.std_height/2, -wall.thickness/2, 0 );
	walls.push( wall );
	
	// Make 2 Sides:
	//scene.add( wall.mesh );
	return wall;
}
function position_walls()
{
	walls[0].mesh.position.x = -10;
	walls[0].mesh.position.y = -80;
	walls[0].mesh.position.z = 40;
}

function add_door( wall_index, pos_length )
{
	var len = frame_mesh1.length;
	construct_door( len );

	frame_mesh1[len].position.x = 0;
	frame_mesh1[len].position.y = wall.thickness/2;
	frame_mesh1[len].position.z = -walls[wall_index].length/2 + pos_length ;
	frame_mesh1[len].updateMatrixWorld();
	frame_mesh1[len].rotation.x = Math.PI/2;
	
	walls[wall_index].mesh.add(frame_mesh1[len]);
}

function construct_room(length,width)
{
	construct_wall( length*12,  wall_material  );
	construct_wall( length*12,  wall_material  );
	construct_wall( width*12,   wall_material );
	construct_wall( width*12,   wall_material );
	var len = walls.length;
	
	walls[len-1].mesh.rotation.x = Math.PI/2;
	walls[len-2].mesh.rotation.x = Math.PI/2;
	
	walls[len-4].mesh.position.x = -10;
	walls[len-4].mesh.position.y = -width*12/2+walls[len-4].thickness;
	walls[len-4].mesh.position.z = 0;

	walls[len-3].mesh.position.x = -10;
	walls[len-3].mesh.position.y = +width*12/2;
	walls[len-3].mesh.position.z = 0;

	walls[len-2].mesh.position.x = -10;
	walls[len-2].mesh.position.z = -length*12/2 + 1*walls[len-2].thickness;
	walls[len-2].mesh.position.y = 0;

	walls[len-1].mesh.position.x = -10;
	walls[len-1].mesh.position.z = +length*12/2;// - walls[len-2].thickness;
	walls[len-1].mesh.position.y = 0; 
	
	var room = new THREE.Group( );
	room.add( walls[len-4].mesh );
	room.add( walls[len-3].mesh );
	room.add( walls[len-2].mesh );
	room.add( walls[len-1].mesh );
	rooms.push(room);	
	scene.add( room );	
	return rooms.length-1;
}

make_wall_materials();

/*
//construct_wall(20*12, walls[0] );
var r_index = construct_room(12,14);
rooms[r_index].position.x = -10;
rooms[r_index].position.y = 100;
rooms[r_index].position.z = -200;
add_door( 1, 7*12 );
generate_door_knob_path(0,100);

r_index = construct_room(8,15);
rooms[r_index].position.x = -10;
rooms[r_index].position.y = 100;
rooms[r_index].position.z = 200;
//add_door( 4, pos_length );

r_index = construct_room(6,4);
rooms[r_index].position.x = -10;
rooms[r_index].position.y = 00;
rooms[r_index].position.z = 200;
//add_door( 5, pos_length );
*/
//position_walls();

// Here b/c stairway needs to know thickness of wall for proper positioning:
position_stairway();

