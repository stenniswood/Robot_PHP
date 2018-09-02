

<script>
function construct_humanoid()
{
	var torso_geom = new THREE.BoxGeometry( arm_sizes.lower_arm_depth,  arm_sizes.lower_arm_width, arm_sizes.lower_arm_length );
	var torso_mesh = new THREE.Mesh( torso_geom,   bone_material );

	torso_mesh.add( l_leg_mesh );
	torso_mesh.add( r_leg_mesh );
		
	torso_mesh.add( l_arm_meshes );
	torso_mesh.add( r_arm_meshes );
}
function show_humanoid()
{

}

</script>