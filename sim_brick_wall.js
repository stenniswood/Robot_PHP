brick_sizes = {
	length : 8,
	width  : 4,
	height : 2,
};

var bricks = [];		// Holds all bricks in a wall.
var num_bricks = 0;		// tmp count for a wall.

function make_half_brick()
{
	num_bricks++;
	var brick = {};
	brick.geom	= new THREE.BoxGeometry( brick_sizes.height,  brick_sizes.width,  brick_sizes.length/2 );			
	brick.geom.translate ( brick_sizes.height/2, 0, brick_sizes.length/4 );
	brick.mesh  = new THREE.Mesh( brick.geom, object_materials.mat[1] );
	
	bricks.push(brick);
	scene.add(brick.mesh);
	return brick;
}

function make_brick()
{
	num_bricks++;
	var brick = {};
	brick.geom	= new THREE.BoxGeometry( brick_sizes.height,  brick_sizes.width,  brick_sizes.length );			
	brick.geom.translate ( brick_sizes.height/2, 0, brick_sizes.length/2 );
	brick.mesh  = new THREE.Mesh( brick.geom, object_materials.mat[1] );
	
	bricks.push(brick);
	scene.add(brick.mesh);
	return brick;
}

/* 
wall : { start, end, length }
	dir_vec is a vector in the YZ plane.
	brick gives width & length & height AND positioning <x,y,z>
			will be updated in place.
*/
function is_past_end( wall )
{
	var retval = false;

	var len_so_far = num_bricks * brick_sizes.length;
	if (len_so_far >= wall.length) 
		retval=true;
	
	return retval;
}

function next_brick_position( wall, dir_vec, num_bricks )
{
	var retval = new THREE.Vector3(0,0,0);
		
	retval.x = wall.current_height;
	retval.y = wall.start.y + brick_sizes.length * dir_vec.y * (num_bricks)*1.05;
	retval.z = wall.start.z + brick_sizes.length * dir_vec.z * (num_bricks)*1.05;
	if (wall.half_brick_start==true) {
		retval.y -= brick_sizes.length * dir_vec.y * 0.5;
		retval.z -= brick_sizes.length * dir_vec.z * 0.5;
	}
	return retval;
}

function start_next_layer( wall )
{
	num_bricks = 0;
	
	wall.half_brick_start = !wall.half_brick_start;
	wall.current_height  += brick_sizes.height;
}

function stack_blocks_wall(wall, brick, dir_vec)
{	
	next_brick_position( wall, brick, dir_vec, num_bricks );
	//pick_and_place( stockpile, brick );
}

function lay_next_brick( wall )
{
	var new_brick = make_brick();
	new_brick.mesh.rotation.x = wall.rotation_angle + Math.PI/2;
	var new_brick_pos = next_brick_position( wall, wall.direction, num_bricks );
	new_brick.mesh.position.x = new_brick_pos.x;
	new_brick.mesh.position.y = new_brick_pos.y;
	new_brick.mesh.position.z = new_brick_pos.z;
	
	var next_layer = is_past_end( wall );
	if (next_layer)
	{
		start_next_layer( wall );
		if (wall.half_brick_start==true) {
			new_brick = make_half_brick();
			new_brick.mesh.rotation.x = wall.rotation_angle + Math.PI/2;
			new_brick_pos = next_brick_position( wall, wall.direction, num_bricks );
			new_brick.mesh.position.x = new_brick_pos.x;
			new_brick.mesh.position.y = new_brick_pos.y;
			new_brick.mesh.position.z = new_brick_pos.z;
		}
	}
}

function build_brick_wall(wall)
{
	num_bricks = 0;
	//while (wall.current_height<=wall.height) 
	{
		lay_next_brick(wall);
	}
}

function init_brick_wall( mwall )
{
	mwall.half_brick_start=false;
	mwall.height = 10*12;
	mwall.length = 12*12;
	mwall.start = new THREE.Vector3( 0, 40, 40 );
	mwall.current_height=0;
	mwall.direction = new THREE.Vector3( 0,1.0, 1.0).normalize();	
	mwall.rotation_angle = Math.atan2(mwall.direction.y, mwall.direction.z);	
}

var BrickWall = {};
init_brick_wall( BrickWall )
build_brick_wall( BrickWall );
