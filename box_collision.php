
<div id="collision_results">No Collision</div>

<script>
var collision_color = 0xFF002F;
var bone_color    = 0x754249;
var bone_emissive = 0x072534;
var box_sizes = [
	{	length:2, width:10, depth:6 },
	{	length:8, width:4,  depth:5 },
];
var box_locations = [
	{	x:0, y:-3, z:-10 },
	{	x:0, y:-7, z:10  },
];

let object_material1;
let object_material2;
let line_material;

let object_geoms  = [];
let object_meshes = [];

function object_materials()
{
	object_material1  = new THREE.MeshPhongMaterial( { color: bone_color, emissive: bone_emissive, side: THREE.DoubleSide, flatShading: true } );
	object_material2  = new THREE.MeshPhongMaterial( { color: 0xC56219, emissive: 0x876514, side: THREE.DoubleSide, flatShading: true } );
	line_material = new THREE.LineBasicMaterial( {color: 0x0000ff} );
}


function color_code_boxes( hexColor )
{
	object_meshes[0].material.color.setHex( hexColor );
	object_meshes[1].material.color.setHex( hexColor );

	object_meshes[0].material.emissive.setHex( hexColor );
	object_meshes[1].material.emissive.setHex( hexColor );

	//object_meshes[2].material.color.setHex( hexColor );
}

function colorize_boxes( status )
{
	if (status=="Collision")
		color_code_boxes( collision_color );
	else
		color_code_boxes( bone_color );
}

function construct_boxes()
{
	object_geoms[0] = new THREE.BoxGeometry( box_sizes[0].depth,  box_sizes[0].width,  box_sizes[0].length );
	object_geoms[1] = new THREE.BoxGeometry( box_sizes[1].depth,  box_sizes[1].width,  box_sizes[1].length );
	
	object_geoms[0].translate ( box_sizes[0].depth/2, 0, 0 );
	object_geoms[1].translate ( box_sizes[1].depth/2, 0, 0 );	

	object_meshes[0]  = new THREE.Mesh( object_geoms[0],  object_material1 );			
	object_meshes[1]  = new THREE.Mesh( object_geoms[1],  object_material2 );
	
	var geometry = new THREE.Geometry();
	geometry.vertices.push(
		new THREE.Vector3( 6, 0, 0 ),
		new THREE.Vector3( 6, 10, 0 )
	);
	var line = new THREE.Line( geometry, material );
	scene.add( line );
}

function locate_boxes_for_no_collision()
{
	object_meshes[0].position.x 	= box_locations[0].x;
	object_meshes[0].position.y 	= box_locations[0].y;
	object_meshes[0].position.z 	= box_locations[0].z;

	object_meshes[1].position.x 	= box_locations[1].x;
	object_meshes[1].position.y 	= box_locations[1].y;
	object_meshes[1].position.z 	= box_locations[1].z;
}

function line_intersects_const_y( point, vec, y_const )
{
	var y_units = (point.y - y_const) / vec.y;  // how many unit do we have to go?	
	
	var intersect = new THREE.Vector3(0,0,0);
	intersect.x = point.x - y_units * vec.x;
	intersect.y = point.y - y_units * vec.y;
	intersect.z = point.z - y_units * vec.z;	
	return intersect;
}

function line_intersects_const_z( point, vec, z_const )
{
	var dz = (point.z - z_const) / vec.z;  // how many unit do we have to go?	
	var dx = dz  * vec.x;
	
	var intersect = new THREE.Vector3(0,0,0);
	intersect.x = point.x + dx;
	intersect.y = point.y - dz * vec.y;
	intersect.z = point.z - dz * vec.z;	
	return intersect;
}

function test_xz_in_square(point, MaxZ,MinZ, height)
{
	if ((point.x>=0) && (point.x <= height))
	{
		if ((point.z>=MinZ) && (point.z <= MaxZ))
			return true;
	}
	return false;
}
function test_xy_in_square(point, MaxY,MinY, height)
{
	if ((point.x>=0) && (point.x <= height))
	{
		if ((point.y>=MinY) && (point.y <= MaxY))
			return true;
	}
	return false;
}

function test_length_within_obj1(point, vec, intersection, length)
{
	//var distance_to_inter = point.distanceTo( intersection );
	// Dot product of vec & intersection will tell how many units of that vector are needed (ie distance along the vector)
	
	var tmp_vec = intersection.clone();
	tmp_vec.sub(point);
	var distance = vec.dot( tmp_vec );
	if (distance>0)	return false;
	
	// The point will be the center of the object, so length is only /2:
	var within_length     = ((-distance) < length);
	return within_length;
}

function test_intersects_const_y( point, obj0_unitVec, y_const, 
							box1_maxX, 
							box1_maxZ, box1_minZ, obj0_length )
{
	var retval=false;
	var intersection    = line_intersects_const_y( point, obj0_unitVec, y_const     );
	var inside_y_plane  = test_xz_in_square(intersection, box1_maxZ, box1_minZ, box1_maxX );
	var length_okay     = test_length_within_obj1( point, obj0_unitVec, intersection,  obj0_length );

/*	if (point.x != 6) {
		var pt_str = " point <"+Number(point.x).toFixed(2)+","+Number(point.y).toFixed(2)+","+Number(point.z).toFixed(2)+">\t\t";;
		var unit_str = " unit_v <"+Number(obj0_unitVec.x).toFixed(6)+", "+Number(obj0_unitVec.y).toFixed(6)+", "+Number(obj0_unitVec.z).toFixed(6)+">";
		var inter_str = " Intersection <"+Number(intersection.x).toFixed(6)+","+Number(intersection.y).toFixed(6)+","+Number(intersection.z).toFixed(6)+">";
		info.innerHTML += "<br>Y plane test: "+inside_y_plane+"\t, length_okay="+length_okay+"\t"+pt_str;
		info.innerHTML += unit_str;
		info.innerHTML += " Object_length="+obj0_length;
		info.innerHTML += inter_str;
	}*/
	if (inside_y_plane && length_okay)	{
		
		retval = true;
	}
	return retval;
}
function test_intersects_const_z( point, obj0_unitVec, z_const, 
							box1_maxX, box1_maxY,box1_minY, obj0_width )
{
	var retval=false;
	var intersection    = line_intersects_const_z( point, obj0_unitVec, z_const );
	var inside_z_plane  = test_xy_in_square		 ( intersection, box1_maxY, box1_minY, box1_maxX );
	var length_okay     = test_length_within_obj1( point, obj0_unitVec, intersection, obj0_width );
	if (inside_z_plane && length_okay)	retval = true;

	if (point.x != 6) {
		var pt_str = " point <"+Number(point.x).toFixed(2)+","+Number(point.y).toFixed(2)+","+Number(point.z).toFixed(2)+">\t\t";;
		var unit_str = " unit_v <"+Number(obj0_unitVec.x).toFixed(6)+", "+Number(obj0_unitVec.y).toFixed(6)+", "+Number(obj0_unitVec.z).toFixed(6)+">";
		var inter_str = " Intersection <"+Number(intersection.x).toFixed(6)+","+Number(intersection.y).toFixed(6)+","+Number(intersection.z).toFixed(6)+">";
		info.innerHTML += "<br>Z plane test: "+inside_z_plane+"\t, length_okay="+length_okay+"\t"+pt_str;
		info.innerHTML += unit_str;
		info.innerHTML += " Object_length="+obj0_width;
		info.innerHTML += inter_str;
	}
	return retval;
}
function rotation_matrix_only(obj_index, vector)
{
	object_meshes[obj_index].matrixAutoUpdate = false;
	object_meshes[obj_index].updateMatrix();
	object_meshes[obj_index].localToWorld( vector );
	vector.sub(object_meshes[obj_index].position);		// Don't want to add the translate to unit vectors!
	//return vector;
}
/* One box will be axis aligned.  This is because we'll work within it's local coordinate
   space.  Then convert the other box, first to world space, then to this local space.
   
*/
function do_boxes_collide() 
{
	var retval = false;
	object_meshes[0].updateMatrixWorld();		object_meshes[0].updateMatrix();
	object_meshes[1].updateMatrixWorld();		object_meshes[1].updateMatrix();
	object_meshes[0].geometry.computeBoundingBox();
	object_meshes[1].geometry.computeBoundingBox();
	
	// BOX 1 will be axis aligned:
	var maxV0 = object_meshes[0].geometry.boundingBox.max;
	var minV0 = object_meshes[0].geometry.boundingBox.min;
	var maxV1 = object_meshes[1].geometry.boundingBox.max;
	var minV1 = object_meshes[1].geometry.boundingBox.min;	

	var box0_maxX = object_meshes[0].geometry.boundingBox.max.x; 
	var box0_maxY = object_meshes[0].geometry.boundingBox.max.y; 
	var box0_maxZ = object_meshes[0].geometry.boundingBox.max.z; 	
	var box0_minX = object_meshes[0].geometry.boundingBox.min.x; 
	var box0_minY = object_meshes[0].geometry.boundingBox.min.y; 
	var box0_minZ = object_meshes[0].geometry.boundingBox.min.z; 	

	// Axis aligned:
	var box1_maxX = object_meshes[1].geometry.boundingBox.max.x; 
	var box1_maxY = object_meshes[1].geometry.boundingBox.max.y; 
	var box1_maxZ = object_meshes[1].geometry.boundingBox.max.z; 	
	var box1_minX = object_meshes[1].geometry.boundingBox.min.x; 
	var box1_minY = object_meshes[1].geometry.boundingBox.min.y; 
	var box1_minZ = object_meshes[1].geometry.boundingBox.min.z; 	


	var point1 = maxV0.clone();   //point1.y -= minV0.y;
	var point2 = maxV0.clone();	  point2.z = minV0.z;
	var point3 = maxV0.clone();	  point3.x = minV0.x;
	var point4 = maxV0.clone();	  point4.x = minV0.x; point4.z = minV0.z;
	var point1_w = point1.clone();	object_meshes[0].localToWorld( point1_w );
	var point2_w = point2.clone();  object_meshes[0].localToWorld( point2_w );
	var point3_w = point3.clone();  object_meshes[0].localToWorld( point3_w );
	var point4_w = point4.clone();  object_meshes[0].localToWorld( point4_w );

	var point1_l1 = point1_w.clone();  object_meshes[1].worldToLocal( point1_l1 );
	var point2_l1 = point2_w.clone();  object_meshes[1].worldToLocal( point2_l1 );
	var point3_l1 = point3_w.clone();  object_meshes[1].worldToLocal( point3_l1 );
	var point4_l1 = point4_w.clone();  object_meshes[1].worldToLocal( point4_l1 );


	// Non-axis aligned:
	var zero_vec = new THREE.Vector3( 0,0,0 );	
	var unitX = new THREE.Vector3( 1,0,0 );
	var unitY = new THREE.Vector3( 0,1,0 );
	var unitZ = new THREE.Vector3( 0,0,1 );	
	var zero_vec_w = zero_vec.clone();  object_meshes[0].localToWorld( zero_vec_w );
	var unitX_w = unitX.clone();  object_meshes[0].localToWorld( unitX_w );
	var unitY_w = unitY.clone();  object_meshes[0].localToWorld( unitY_w );
	var unitZ_w = unitZ.clone();  object_meshes[0].localToWorld( unitZ_w );
	var zero_vec_l1 = zero_vec_w.clone();  object_meshes[1].worldToLocal( zero_vec_l1 );
	var unitX_l1 = unitX_w.clone();  object_meshes[1].worldToLocal( unitX_l1 );
	var unitY_l1 = unitY_w.clone();  object_meshes[1].worldToLocal( unitY_l1 );
	var unitZ_l1 = unitZ_w.clone();  object_meshes[1].worldToLocal( unitZ_l1 );
	unitX_l1.sub(zero_vec_l1);
	unitY_l1.sub(zero_vec_l1);	
	unitZ_l1.sub(zero_vec_l1);	

	var obj0_length_x  =  maxV0.x-minV0.x;
	var obj0_length_y  =  maxV0.y-minV0.y;
	var obj0_length_z  =  maxV0.z-minV0.z;
	
	// Y PLANE - INTERSECTIONS:
/*	var  result0 = test_intersects_const_y( point1_l1, unitX_l1, minV1.y, 
							box1_maxX, box1_maxZ, box1_minZ, obj0_length_x );
	var  result1 = test_intersects_const_y( point1_l1, unitY_l1, minV1.y, 
							box1_maxX, box1_maxZ, box1_minZ, obj0_length_y );
	var  result2 = test_intersects_const_y( point1_l1, unitZ_l1, minV1.y,
							box1_maxX, box1_maxZ, box1_minZ, obj0_length_z );
	if (result0 || result1 || result2) return true;

	var  result3 = test_intersects_const_y( point2_l1, unitX_l1, minV1.y, 
							box1_maxX, box1_maxZ, box1_minZ, maxV0.x-minV0.x );
	var  result4 = test_intersects_const_y( point2_l1, unitY_l1, minV1.y, 
							box1_maxX, box1_maxZ, box1_minZ, maxV0.y-minV0.y );
	var  result5 = test_intersects_const_y( point2_l1, unitZ_l1, minV1.y,
							box1_maxX, box1_maxZ, box1_minZ, maxV0.z-minV0.z );
	if (result3 || result4 || result5) return true;

	var  result6 = test_intersects_const_y( point3_l1, unitX_l1, minV1.y, 
							box1_maxX, box1_maxZ, box1_minZ, maxV0.x-minV0.x );
	var  result7 = test_intersects_const_y( point3_l1, unitY_l1, minV1.y, 
							box1_maxX, box1_maxZ, box1_minZ, maxV0.y-minV0.y );
	var  result8 = test_intersects_const_y( point3_l1, unitZ_l1, minV1.y,
							box1_maxX, box1_maxZ, box1_minZ, maxV0.z-minV0.z );
	if (result6 || result7 || result8) return true;

	var  result9 = test_intersects_const_y( point4_l1, unitX_l1, minV1.y, 
							box1_maxX, box1_maxZ, box1_minZ, maxV0.x-minV0.x );
	var  result10 = test_intersects_const_y( point4_l1, unitY_l1, minV1.y, 
							box1_maxX, box1_maxZ, box1_minZ, maxV0.y-minV0.y );
	var  result11 = test_intersects_const_y( point4_l1, unitZ_l1, minV1.y,
							box1_maxX, box1_maxZ, box1_minZ, maxV0.z-minV0.z );
	if (result9 || result10 || result11) return true;

	// Now Y Plane at Max Y:
	var  result0 = test_intersects_const_y( point1_l1, unitX_l1, maxV1.y, 
							box1_maxX, box1_maxZ, box1_minZ, maxV0.x-minV0.x );
	var  result1 = test_intersects_const_y( point1_l1, unitY_l1, maxV1.y, 
							box1_maxX, box1_maxZ, box1_minZ, maxV0.y-minV0.y );
	var  result2 = test_intersects_const_y( point1_l1, unitZ_l1, maxV1.y,
							box1_maxX, box1_maxZ, box1_minZ, maxV0.z-minV0.z );
	if (result0 || result1 || result2) return true;

	var  result3 = test_intersects_const_y( point2_l1, unitX_l1, maxV1.y, 
							box1_maxX, box1_maxZ, box1_minZ, maxV0.x-minV0.x );
	var  result4 = test_intersects_const_y( point2_l1, unitY_l1, maxV1.y, 
							box1_maxX, box1_maxZ, box1_minZ, maxV0.y-minV0.y );
	var  result5 = test_intersects_const_y( point2_l1, unitZ_l1, maxV1.y,
							box1_maxX, box1_maxZ, box1_minZ, maxV0.z-minV0.z );
	if (result3 || result4 || result5) return true;

	var  result6 = test_intersects_const_y( point3_l1, unitX_l1, maxV1.y, 
							box1_maxX, box1_maxZ, box1_minZ, maxV0.x-minV0.x );
	var  result7 = test_intersects_const_y( point3_l1, unitY_l1, maxV1.y, 
							box1_maxX, box1_maxZ, box1_minZ, maxV0.y-minV0.y );
	var  result8 = test_intersects_const_y( point3_l1, unitZ_l1, maxV1.y,
							box1_maxX, box1_maxZ, box1_minZ, maxV0.z-minV0.z );
	if (result6 || result7 || result8) return true;

	var  result9 = test_intersects_const_y( point4_l1, unitX_l1, maxV1.y, 
							box1_maxX, box1_maxZ, box1_minZ, maxV0.x-minV0.x );
	var  result10 = test_intersects_const_y( point4_l1, unitY_l1, maxV1.y, 
							box1_maxX, box1_maxZ, box1_minZ, maxV0.y-minV0.y );
	var  result11 = test_intersects_const_y( point4_l1, unitZ_l1, maxV1.y,
							box1_maxX, box1_maxZ, box1_minZ, maxV0.z-minV0.z );
	if (result9 || result10 || result11) return true;
*/	



	// Z PLANE - INTERSECTIONS:
	var  result0 = test_intersects_const_z( point1_l1, unitX_l1, minV1.z, 
							box1_maxX, box1_maxY, box1_minY, maxV0.x-minV0.x );
	var  result1 = test_intersects_const_z( point1_l1, unitY_l1, minV1.z, 
							box1_maxX, box1_maxY, box1_minY, maxV0.y-minV0.y );
	var  result2 = test_intersects_const_z( point1_l1, unitZ_l1, minV1.z,
							box1_maxX, box1_maxY, box1_minY, maxV0.z-minV0.z );
	if (result0 || result1 || result2) return true;

	var  result3 = test_intersects_const_z( point2_l1, unitX_l1, minV1.z, 
							box1_maxX, box1_maxY, box1_minY, maxV0.x-minV0.x );
	var  result4 = test_intersects_const_z( point2_l1, unitY_l1, minV1.z, 
							box1_maxX, box1_maxY, box1_minY, maxV0.y-minV0.y );
	var  result5 = test_intersects_const_z( point2_l1, unitZ_l1, minV1.z,
							box1_maxX, box1_maxY, box1_minY, maxV0.z-minV0.z );
	if (result3 || result4 || result5) return true;

	var  result6 = test_intersects_const_z( point3_l1, unitX_l1, minV1.z, 
							box1_maxX, box1_maxY, box1_minY, maxV0.x-minV0.x );
	var  result7 = test_intersects_const_z( point3_l1, unitY_l1, minV1.z, 
							box1_maxX, box1_maxY, box1_minY, maxV0.y-minV0.y );
	var  result8 = test_intersects_const_z( point3_l1, unitZ_l1, minV1.z,
							box1_maxX, box1_maxY, box1_minY, maxV0.z-minV0.z );
	if (result6 || result7 || result8) return true;

	var  result9 = test_intersects_const_z( point4_l1, unitX_l1, minV1.z, 
							box1_maxX, box1_maxY, box1_minY, maxV0.x-minV0.x );
	var  result10 = test_intersects_const_z( point4_l1, unitY_l1, minV1.z, 
							box1_maxX, box1_maxY, box1_minY, maxV0.y-minV0.y );
	var  result11 = test_intersects_const_z( point4_l1, unitZ_l1, minV1.z,
							box1_maxX, box1_maxY, box1_minY, maxV0.z-minV0.z );
	if (result9 || result10 || result11) return true;

	// Now Z Plane at Max Z:
	var  result0 = test_intersects_const_z( point1_l1, unitX_l1, maxV1.z, 
							box1_maxX, box1_maxY, box1_minY, maxV0.x-minV0.x );
	var  result1 = test_intersects_const_z( point1_l1, unitY_l1, maxV1.z, 
							box1_maxX, box1_maxY, box1_minY, maxV0.y-minV0.y );
	var  result2 = test_intersects_const_z( point1_l1, unitZ_l1, maxV1.z,
							box1_maxX, box1_maxY, box1_minY, maxV0.z-minV0.z );
	if (result0 || result1 || result2) return true;

	var  result3 = test_intersects_const_z( point2_l1, unitX_l1, maxV1.z, 
							box1_maxX, box1_maxY, box1_minY, maxV0.x-minV0.x );
	var  result4 = test_intersects_const_z( point2_l1, unitY_l1, maxV1.z, 
							box1_maxX, box1_maxY, box1_minY, maxV0.y-minV0.y );
	var  result5 = test_intersects_const_z( point2_l1, unitZ_l1, maxV1.z,
							box1_maxX, box1_maxY, box1_minY, maxV0.z-minV0.z );
	if (result3 || result4 || result5) return true;

	var  result6 = test_intersects_const_z( point3_l1, unitX_l1, maxV1.z, 
							box1_maxX, box1_maxY, box1_minY, maxV0.x-minV0.x );
	var  result7 = test_intersects_const_z( point3_l1, unitY_l1, maxV1.z, 
							box1_maxX, box1_maxY, box1_minY, maxV0.y-minV0.y );
	var  result8 = test_intersects_const_z( point3_l1, unitZ_l1, maxV1.z,
							box1_maxX, box1_maxY, box1_minY, maxV0.z-minV0.z );
	if (result6 || result7 || result8) return true;

	var  result9 = test_intersects_const_z( point4_l1, unitX_l1, maxV1.z, 
							box1_maxX, box1_maxY, box1_minY, maxV0.x-minV0.x );
	var  result10 = test_intersects_const_z( point4_l1, unitY_l1, maxV1.z, 
							box1_maxX, box1_maxY, box1_minY, maxV0.y-minV0.y );
	var  result11 = test_intersects_const_z( point4_l1, unitZ_l1, maxV1.z,
							box1_maxX, box1_maxY, box1_minY, maxV0.z-minV0.z );
	if (result9 || result10 || result11) return true;

/*	var obj1_width = object_meshes[1].geometry.boundingBox.max.y - 
					 object_meshes[1].geometry.boundingBox.min.y;

*/

//	var point4 = point3.addScaledVector( unitY , obj1_width  );	// vector add 
		
	
	return false;
		
/*	var box0 = new THREE.Box3( minV0, maxV0 );
	var box1 = new THREE.Box3( minV1, maxV1 );
	var collides =  box0.intersectsBox( box1 );	
	return collides; */
}

object_materials			 ( );
construct_boxes  			 ( );
locate_boxes_for_no_collision( );


const axesHelper2 = new THREE.AxesHelper(5);
object_meshes[0].add(axesHelper2);

scene.add( object_meshes[0] );
scene.add( object_meshes[1] );

</script>
