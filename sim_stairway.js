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
	stair_rise_material  = new THREE.MeshPhongMaterial( { color: 0xFF0000, emissive: 0xFF0000, side: THREE.DoubleSide, flatShading: false } );	
	stair_run_material   = new THREE.MeshPhongMaterial( { color: 0x00FF00, emissive: 0x00EF00, side: THREE.DoubleSide, flatShading: false } );	
	stair_side_material  = new THREE.MeshPhongMaterial( { color: 0x0000EF, emissive: 0x0000DF, side: THREE.DoubleSide, flatShading: false } );	
}

function construct_stair(num_steps)
{
	stairway = new THREE.Group();

	// Make stair pieces : 
	for (i=0; i<num_steps; i++)
	{
		rise_geom[i]  = new THREE.BoxGeometry( stair_sizes.rise, stair_sizes.thickness, stair_sizes.width );
		rise_mesh[i]  = new THREE.Mesh       ( rise_geom[i], stair_rise_material );

		rise_mesh[i].position.x = stair_sizes.rise/2 + stair_sizes.rise * i;
		rise_mesh[i].position.y = stair_sizes.run * i;
		rise_mesh[i].position.z = 0;

		run_geom[i]  = new THREE.BoxGeometry( stair_sizes.thickness, stair_sizes.run, stair_sizes.width );
		run_mesh[i]  = new THREE.Mesh       ( run_geom[i], stair_run_material );

		run_mesh[i].position.x = stair_sizes.rise * (i+1);
		run_mesh[i].position.y = stair_sizes.run * i + stair_sizes.run/2;
		run_mesh[i].position.z = 0;

		stairway.add( rise_mesh[i] );
		stairway.add( run_mesh[i]  );
	}

	// Make 2 Sides:
	scene.add(stairway);
}

function position_stairway()
{
	stairway.position.x = -10;
	stairway.position.y = -80 + stair_sizes.width/2 + wall_sizes.std_thickness;
	stairway.position.z = -165;
	stairway.rotation.x = Math.PI/2;
}

construct_stair(15);

