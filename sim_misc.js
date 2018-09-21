
table_sizes = {
	width  : 38,
	length : 56,
	depth  : 1.0,
	height : 30,

	leg_width: 2,
};
round_table_sizes = {
	radius  : 38,
	pole_radius : 2,
	height : 30,
	
	foot_width  : 0.5,
	foot_height : 1.5,
};


var table = [];
var table_material;
var table_leg_material;
var t_texture;

var table_geom = [];
var table_mesh = [];

var leg1_geom  =[];
var leg2_geom  =[];
var leg3_geom  =[];
var leg4_geom  =[];

var leg1_mesh  =[];
var leg2_mesh  =[];
var leg3_mesh  =[];
var leg4_mesh  =[];

var frame_width   = 4;

var knob_geom			=[]; 
var knob_connector_geoms=[]; 
var knob_mesh			=[];
var knob_connector_mesh =[];

function make_misc_materials()
{
//	d_texture = new THREE.TextureLoader().load( "./textures/table_carmelle.jpg" );
	d_texture = texture_loader.load( "../physics/examples/images/wood.jpg" );

//	t_texture       = new THREE.TextureLoader().load( "./textures/table_milano_luna.jpg" );
	table_material     = new THREE.MeshBasicMaterial( { map: d_texture } );
	//table_leg_material = new THREE.MeshPhongMaterial( { color: 0xEFEFEF, emissive: 0xFFEFDF, side: THREE.DoubleSide, wireframe:true, flatShading: false } );	
	table_leg_material = new THREE.MeshPhongMaterial( { map: d_texture, side: THREE.DoubleSide, flatShading: false } );	
	
	ptable_material     = Physijs.createMaterial( table_material, .9 /* medium friction */, .3 /* low restitution */	);	
	ptable_leg_material = Physijs.createMaterial( table_material, .9 /* medium friction */, .6 /* low restitution */	);
}

function construct_table(table_index)
{
	// Make Table TOP : 
	table_geom[table_index]  = new THREE.BoxGeometry( table_sizes.depth, table_sizes.width, table_sizes.length );
	table_mesh[table_index]  = new Physijs.BoxMesh  ( table_geom[table_index], ptable_material, 30 );

	//table_geom[table_index].translate( table_sizes.depth/2, table_sizes.width/2, table_sizes.length/2 );
	//table_mesh[table_index].rotation.y = Math.PI/2;

	table_mesh[table_index].position.x = table_sizes.height/2;		// zero is middle height of the legs.
	table_mesh[table_index].position.y = table_sizes.width/2;
	table_mesh[table_index].position.z = table_sizes.length/2-table_sizes.leg_width/2;
	table_mesh[table_index].__dirtyPosition = true;

	// Make 4 Legs:
	leg1_geom[table_index]  = new THREE.BoxGeometry( table_sizes.height, table_sizes.leg_width, table_sizes.leg_width );
	leg1_mesh[table_index]  = new Physijs.BoxMesh  ( leg1_geom[table_index], ptable_leg_material, 5 );

	leg2_geom[table_index]  = new THREE.BoxGeometry( table_sizes.height, table_sizes.leg_width, table_sizes.leg_width );
	leg2_mesh[table_index]  = new Physijs.BoxMesh  ( leg2_geom[table_index], ptable_leg_material, 5 );

	leg3_geom[table_index]  = new THREE.BoxGeometry( table_sizes.height, table_sizes.leg_width, table_sizes.leg_width );
	leg3_mesh[table_index]  = new Physijs.BoxMesh  ( leg3_geom[table_index], ptable_leg_material, 5 );

	leg4_geom[table_index]  = new THREE.BoxGeometry( table_sizes.height, table_sizes.leg_width, table_sizes.leg_width );
	leg4_mesh[table_index]  = new Physijs.BoxMesh  ( leg4_geom[table_index], ptable_leg_material, 5 );

	//leg1_geom[table_index].translate( +table_sizes.height/2, table_sizes.leg_width/2, table_sizes.leg_width/2 );
	//leg2_geom[table_index].translate( +table_sizes.height/2, table_sizes.leg_width/2, table_sizes.leg_width/2 );
	//leg3_geom[table_index].translate( +table_sizes.height/2, table_sizes.leg_width/2, table_sizes.leg_width/2 );
	//leg4_geom[table_index].translate( +table_sizes.height/2, table_sizes.leg_width/2, table_sizes.leg_width/2 );

	leg1_mesh[table_index].position.x = 0;
	leg2_mesh[table_index].position.x = 0;
	leg3_mesh[table_index].position.x = 0;
	leg4_mesh[table_index].position.x = 0;

	leg1_mesh[table_index].position.y = 0;
	leg2_mesh[table_index].position.y = 0;
	leg3_mesh[table_index].position.y = table_sizes.width - table_sizes.leg_width;
	leg4_mesh[table_index].position.y = table_sizes.width - table_sizes.leg_width;			
	
	leg1_mesh[table_index].position.z = 0;
	leg2_mesh[table_index].position.z = table_sizes.length-table_sizes.leg_width;
	leg3_mesh[table_index].position.z = 0;
	leg4_mesh[table_index].position.z = table_sizes.length-table_sizes.leg_width;

	leg1_mesh[table_index].__dirtyPosition = true;
	leg2_mesh[table_index].__dirtyPosition = true;
	leg3_mesh[table_index].__dirtyPosition = true;
	leg4_mesh[table_index].__dirtyPosition = true;

/* Non-Physics way:
	table[table_index] = new THREE.Group();
	table[table_index].position.x = -10;
	table[table_index].add( leg1_mesh[table_index]  );
	table[table_index].add( leg2_mesh[table_index]  );
	table[table_index].add( leg3_mesh[table_index]  );
	table[table_index].add( leg4_mesh[table_index]  );
	table[table_index].add( table_mesh[table_index] );		*/

	table[table_index] = leg1_mesh[table_index];
	table[table_index].position.x = -8.0+table_sizes.height/2;
	table[table_index].__dirtyPosition = true;

	table[table_index].add( table_mesh[table_index]  );
	table[table_index].add( leg2_mesh[table_index]  );
	table[table_index].add( leg3_mesh[table_index]  );
	table[table_index].add( leg4_mesh[table_index]  );	
}

/* For now we assume all children are meshes */
function get_group_bounding_box(group)
{
	var max = new THREE.Vector3(0,0,0);
	var min = new THREE.Vector3(0,0,0);
//	var max_x=0;		var max_y=0;		var max_z=0;
//	var min_x=0;		var min_y=0;		var min_z=0;	
	var num_children = group.children.length;
	
	for (i=0; i<num_children; i++)
	{
		group.children[i].geometry.computeBoundingBox();
		var x = group.children[i].geometry.boundingBox.max.x + group.children[i].position.x;	// any relative position within the group.
		var y = group.children[i].geometry.boundingBox.max.y + group.children[i].position.y;
		var z = group.children[i].geometry.boundingBox.max.z + group.children[i].position.z;
		if (x>max.x)		max.x = x;
		if (y>max.y)		max.y = y;
		if (z>max.z)		max.z = z;		

		x = group.children[i].geometry.boundingBox.min.x + group.children[i].position.x;
		y = group.children[i].geometry.boundingBox.min.y + group.children[i].position.y;
		z = group.children[i].geometry.boundingBox.min.z + group.children[i].position.z;
		if (x<min.x)		min.x = x;
		if (y<min.y)		min.y = y;
		if (z<min.z)		min.z = z;		
	}
	var box = new THREE.Box3( min, max );
	return box;
}

function place_tables()
{
	table[0].position.y = -80;
	table[0].position.z = 80;

	table[1].position.y = 0;
	table[1].position.z = 0;

	table[0].__dirtyPosition = true;	
	table[1].__dirtyPosition = true;		
}

function construct_round_table(table_index)
{
	table = new THREE.Group();

	var table_top   = new THREE.CylinderGeometry( round_table_sizes.radius, round_table_sizes.radius, round_table_sizes.height );
	var table_pole  = new THREE.CylinderGeometry( round_table_sizes.pole_radius, round_table_sizes.pole_radius, round_table_sizes.height );	

	var table_foot1 = new THREE.BoxGeometry( round_table_sizes.foot_height, round_table_sizes.radius*0.75, round_table_sizes.foot_width  );
	var table_foot2 = new THREE.BoxGeometry( round_table_sizes.foot_height, round_table_sizes.radius*0.75, round_table_sizes.foot_width  );
}

make_misc_materials();
construct_table(0);
construct_table(1);
place_tables();
scene.add(table[0]);
scene.add(table[1]);

