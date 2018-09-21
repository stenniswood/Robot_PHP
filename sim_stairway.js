stair_sizes = {
	rise   : 7,
	run    : 11,
	thickness  : 1.0,
	width  : 32,
	
};

var stairway;

var s_texture;
var stair_rise_material;
var stair_run_material;

var rise_geom = [];
var rise_mesh = [];
var run_geom = [];
var run_mesh = [];

var left_side_geom = [];
var left_side_mesh = [];

var right_side_geom = [];
var right_side_mesh = [];

var frame_width   = 4;

function make_stair_materials()
{
//	d_texture = new THREE.TextureLoader().load( "./textures/stair_carmelle.jpg" );

//	t_texture       = new THREE.TextureLoader().load( "./textures/stair_milano_luna.jpg" );
	stair_rise_material  = new THREE.MeshPhongMaterial( { color: 0xFF0000, emissive: 0xFF0000, side: THREE.DoubleSide, flatShading: true } );	
	stair_run_material   = new THREE.MeshPhongMaterial( { color: 0x00FF00, emissive: 0x00EF00, side: THREE.DoubleSide, flatShading: false } );	
	stair_side_material  = new THREE.MeshPhongMaterial( { color: 0x0000EF, emissive: 0x0000DF, side: THREE.DoubleSide, flatShading: false } );	
	
	pstair_rise_material  = Physijs.createMaterial( stair_rise_material, .9 /* medium friction */, .6 /* low restitution */	);	
	pstair_run_material   = Physijs.createMaterial( stair_run_material,  .9 /* medium friction */, .6 /* low restitution */	);	
	pstair_side_material  = Physijs.createMaterial( stair_side_material, .9 /* medium friction */, .3 /* low restitution */	);	
}

var total_height = 0;
var total_run    = 0;

function construct_stair(num_steps)
{
	total_height = stair_sizes.rise * num_steps;
	total_run    = stair_sizes.run * num_steps;
	
	var side_geom  = new THREE.BoxGeometry( total_height, 1.0, total_run );	
	stairway       = new Physijs.BoxMesh  ( side_geom, pstair_rise_material, 200 );
	//stairway.position.x = total_height/2.0;

	var side_geom2  = new THREE.BoxGeometry( total_height, 1.0, total_run );	
	var side2       = new Physijs.BoxMesh  ( side_geom2, pstair_run_material, 200 );
	//side2.position.x = 0; // total_height/2.0;
	side2.position.y = + stair_sizes.width*3;
	side2.__dirtyPosition = true;
	stairway.add(side2);	

	// Make stair pieces : 
/*	for (i=0; i<num_steps; i++)
	{
		rise_geom[i]  = new THREE.BoxGeometry( stair_sizes.rise, stair_sizes.width, stair_sizes.thickness );
		rise_mesh[i]  = new Physijs.BoxMesh  ( rise_geom[i], pstair_rise_material, 20 );

		rise_mesh[i].position.x = -total_height/2 + stair_sizes.rise/2 + stair_sizes.rise * i;
		rise_mesh[i].position.y = -stair_sizes.width/2;
		rise_mesh[i].position.z = -total_run/2 + stair_sizes.run * i;
		rise_mesh[i].__dirtyPosition = true;

		run_geom[i]  = new THREE.BoxGeometry( stair_sizes.thickness, stair_sizes.width, stair_sizes.run );
		run_mesh[i]  = new Physijs.BoxMesh  ( run_geom[i], pstair_run_material, 20 );

		run_mesh[i].position.x = -total_height/2 + stair_sizes.rise * (i+1);
		run_mesh[i].position.y = -stair_sizes.width/2;
		run_mesh[i].position.z = -total_run/2 + stair_sizes.run * i + stair_sizes.run/2;
		run_mesh[i].__dirtyPosition = true;
		
		stairway.add( rise_mesh[i] );
		stairway.add( run_mesh[i]  );
	}
*/
	// Make 2 Sides:
	scene.add(stairway);
}

function position_stairway()
{
	stairway.position.x = total_height/2.0-1;
	stairway.position.y = -280 + stair_sizes.width/2 + wall_sizes.std_thickness;
	stairway.position.z = -100;
	//stairway.rotation.x = Math.PI/2;
	stairway.__dirtyPosition = true;
	stairway.__dirtyRotation = true;
}

make_stair_materials();
construct_stair(15);

// "sim_wall.js" positions this stairway.

