<div id="Sim3D" class="tabcontent">


<fieldset>
XYZ:<input id='xyz'></input>
<button id='move_left'  onclick="do_inverse_kinetmatics('left')" >Left</button>
<button id='move_right' onclick="do_inverse_kinetmatics('right')">Right</button>
<button id='store_position' onclick="store_xyz('right')">Store</button><br>
Approach Angle:<input id='approach'></input>
Left Gripper : <input id='l_gripper_position' type="range" min="0" max="100.00" value="0.5" class="slider" style="width:250" >
<span id="l_grip">Grip</span>
  Right Gripper: <input id='r_gripper_position' type="range" min="0" max="100.00" value="0.5" class="slider" style="width:250" >
<span id="r_grip">Grip</span>

</fieldset>

    <canvas id="arm_sim-canvas" style="border: none;" width="512" height="256"
    backgroundColor = 'transparent'></canvas>
    <br/>

<script>
	var positions = [{x:0,y:0,z:0}];
	var inp_xyz = document.getElementById( 'xyz' );
	var inp_approach = document.getElementById( 'approach' );	
	var l_grip_label  = document.getElementById( 'l_grip'   );
	var l_grip_slider = document.getElementById( 'l_gripper_position'   );	
	var r_grip_label  = document.getElementById( 'r_grip'   );
	var r_grip_slider = document.getElementById( 'r_gripper_position'   );	

l_grip_slider.oninput = function() { 
	open_gripper( this.value/100., l_grip_meshes );
  l_grip_label.innerHTML = this.value;
};
r_grip_slider.oninput = function() { 
	open_gripper( this.value/100., r_grip_meshes );
  r_grip_label.innerHTML = this.value;
};	
//	var store_xyz = document.getElementById( 'store_position' );
function store_xyz(l_or_r)
{
	var str = inp_xyz.value;
	var vals = str.split(" ");
	var xyz={};
	xyz.x = vals[0];		xyz.y = vals[1];		xyz.z = vals[2];
	positions.push(xyz);
}	
function do_inverse_kinetmatics(l_or_r)
{
	var str = inp_xyz.value;	
	var vals = str.split(" ");
	var xyz={};
	xyz.x = vals[0];		xyz.y = vals[1];		xyz.z = vals[2];
	xyz.Approach = inp_approach.value * Math.PI/180.;
	var servo_angles={};
	var out_of_range_color = 0xff002f;
	if (l_or_r=="left") {
		xyz.x -= l_arm_meshes.shoulder.position.x; // - xyz.x;		
		xyz.y -= l_arm_meshes.shoulder.position.y; //- xyz.y;		
		xyz.z -= l_arm_meshes.shoulder.position.z; //- xyz.z;
		INV_XYZ_To_Angles( xyz, servo_angles );
		if (typeof servo_angles.Elbow == 'undefined') {
			l_arm_meshes.upper_arm.material.color.setHex( out_of_range_color );
			l_arm_meshes.fore_arm.material.color.setHex	( out_of_range_color );
			l_arm_meshes.wrist.color.setHex				( out_of_range_color );		
		} else {
			l_arm_meshes.upper_arm.material.color.setHex( bone_color );
			l_arm_meshes.fore_arm.material.color.setHex	( bone_color );			
			l_arm_meshes.wrist.material.color.setHex	( bone_color );			
			l_rad_servo_angle_set = servo_angles;
			set_servo_angles_rad( l_arm_meshes, arm_sizes, servo_angles );				
		}
	} else {
		xyz.x -= r_arm_meshes.shoulder.position.x;		
		xyz.y -= r_arm_meshes.shoulder.position.y;		
		xyz.z -= r_arm_meshes.shoulder.position.z;
		INV_XYZ_To_Angles( xyz, servo_angles );

		if (typeof servo_angles.Elbow == 'undefined') {
			r_arm_meshes.upper_arm.material.color.setHex( out_of_range_color );
			r_arm_meshes.fore_arm.material.color.setHex	( out_of_range_color );			
			r_arm_meshes.wrist.material.color.setHex	( out_of_range_color );			
		} else {
			r_arm_meshes.upper_arm.material.color.setHex( bone_color );
			r_arm_meshes.fore_arm.material.color.setHex	( bone_color );			
			r_arm_meshes.wrist.material.color.setHex	( bone_color );			

			r_rad_servo_angle_set = servo_angles;	
			set_servo_angles_rad( r_arm_meshes, arm_sizes, servo_angles );
		}
	}
}
</script>

<script src="three.js/build/three.js"></script>
<script src="three.js/examples/js/controls/OrbitControls.js"></script>
<script src="three.js/examples/js/objects/ShadowMesh.js"></script>


<script>
	var canvas = document.getElementById("arm_sim-canvas");
	document.body.appendChild( canvas );
	
	var renderer = new THREE.WebGLRenderer();
	var w = canvas.width;
	renderer.setSize( canvas.width, canvas.height );
	renderer.shadowMap.enabled = true;
	document.body.appendChild( renderer.domElement );
	//canvas.appendChild( scene );	


	// Our Javascript will go here.
	var scene = new THREE.Scene();
	var camera = new //THREE.PerspectiveCamera( 120, window.innerWidth / window.innerHeight, 0.1, 5000 );
					THREE.OrthographicCamera(
						  canvas.width / -2,
						  canvas.width / 2,
						  canvas.height / 2,
						  canvas.height / -2, -500, 1000);

	camera.up.set(1, 0, 0); 
	camera.zoom = 7;
	camera.position.set(10, 0, 0);
	camera.lookAt(scene.position);
	camera.updateProjectionMatrix();
	scene.add(camera);
	scene.background = new THREE.Color( 0x0096ff );

	var controls = new THREE.OrbitControls( camera, renderer.domElement );
	controls.enableDamping = true; // an animation loop is required when either damping or auto-rotation are enabled
	controls.dampingFactor = 0.25;
	controls.screenSpacePanning = false;
	controls.minDistance = 100;
	controls.maxDistance = 500
	controls.maxPolarAngle = Math.PI / 2;
		
	
	  const size = 20
	  const step = 20
	  const gridHelper = new THREE.GridHelper(size, step);
	  gridHelper.rotation.x = 0;	gridHelper.rotation.y = 0;	gridHelper.rotation.z = 90/180.*Math.PI;
	  scene.add(gridHelper);

	  const axesHelper = new THREE.AxesHelper(5);
	  scene.add(axesHelper);

	

	
		var dirLight = new THREE.DirectionalLight( 0xFFFFFF, 1.0 );
		dirLight.position.set( 200, 0, -10 ).normalize();
		dirLight.castShadow = true; 
		dirLight.lookAt( scene.position );
		//dirLight.shadowDarkness = 0.5;
		
		//Set up shadow properties for the light
		dirLight.shadow.mapSize.width = 512;  // default
		dirLight.shadow.mapSize.height = 512; // default
		dirLight.shadow.camera.near = 0.5;    // default
		dirLight.shadow.camera.far = 1000;     // default
		
		var lightPosition4D = new THREE.Vector4();
		lightPosition4D.x = dirLight.position.x;
		lightPosition4D.y = dirLight.position.y;
		lightPosition4D.z = dirLight.position.z;
		// amount of light-ray divergence. Ranging from:
		// 0.001 = sunlight(min divergence) to 1.0 = pointlight(max divergence)
		lightPosition4D.w = 0.001; // must be slightly greater than 0, due to 0 causing matrixInverse errors



		//dirLight.shadowCameraVisible = true;
		scene.add( dirLight );
		
	/*	var pointLight = new THREE.PointLight( 0xffffff, 1.5 );
		pointLight.position.set( 90, 100, 0 );
		scene.add( pointLight );
*/
/*		var pointLight2 = new THREE.PointLight( 0xffffff, 1.5 );
		pointLight2.position.set( 90, -100, 0 );
		scene.add( pointLight2 );

		var pointLight3 = new THREE.PointLight( 0xffffff, 1.5 );
		pointLight3.position.set( 90, -100, 90 );
		scene.add( pointLight3 );
*/


</script>
<script src="arm_construction.js"></script>
<script src="arm_servos.js"></script>		
<script src="arm_gripper.js"></script>
<script>

	var texture = new THREE.TextureLoader().load( "textures/sponge_wall_texture.jpg" );
//	texture.wrapS = THREE.RepeatWrapping;
//	texture.wrapT = THREE.RepeatWrapping;
//	texture.repeat.set( 4, 4 );
	// Add Floor:	
//	var floor_geometry = new THREE.PlaneGeometry( 3*20, 3*20, 32 );

//	var material = new THREE.MeshBasicMaterial( { map: texture } );	
//	var groundMaterial = new THREE.MeshLambertMaterial( { color: 'rgb(0,130,0)' } );
//	var plane = new THREE.Mesh( floor_geometry, groundMaterial );


	var normalVector = new THREE.Vector3( 1, 0, 0 );
	var planeConstant = -9.9; // this value must be slightly higher than the groundMesh's y position of 0.0
	var shadowPlane = new THREE.Plane( normalVector, planeConstant );
	shadowPlane.receiveShadow = true;
	shadowPlane.castShadow = false;
	shadowPlane.renderOrder = - 1;
		
	var l = (get_total_arm_length() + 10)*2;
	var groundGeometry = new THREE.BoxBufferGeometry( l, 0.01, l );
//	var groundMaterial = new THREE.MeshLambertMaterial( { color: 'rgb(0,130,0)' } );
	var groundMaterial = new THREE.MeshBasicMaterial( { map: texture } );	
	groundMesh = new THREE.Mesh( groundGeometry, groundMaterial );
	groundMesh.position.x = -10.0; //this value must be slightly lower than the planeConstant (0.01) parameter above
	groundMesh.rotation.z = 90/180.*Math.PI;
	scene.add( groundMesh );

	function update_shadows(arm_meshes, grip_meshes)
	{
		arm_meshes.luaShadow.update( shadowPlane, lightPosition4D );
		arm_meshes.leaShadow.update( shadowPlane, lightPosition4D );
		arm_meshes.llaShadow.update( shadowPlane, lightPosition4D );				
		arm_meshes.lwmShadow.update( shadowPlane, lightPosition4D );
		arm_meshes.lwaShadow.update( shadowPlane, lightPosition4D );

		grip_meshes.g1Shadow.update( shadowPlane, lightPosition4D );
		grip_meshes.g2Shadow.update( shadowPlane, lightPosition4D );
		grip_meshes.jShadow.update( shadowPlane, lightPosition4D );
		grip_meshes.wShadow.update( shadowPlane, lightPosition4D );				
		
	}
		
	function animate() {
		requestAnimationFrame( animate );
		controls.update(); // only required if controls.enableDamping = true, or if controls.autoRotate = true
		update_shadows(l_arm_meshes, l_grip_meshes);
		update_shadows(r_arm_meshes, r_grip_meshes);

		

		//set_servo_angles( BaseDeg, ShoulderDeg, ElbowDeg );				
		renderer.render( scene, camera );
	}

	animate();						
	
	var l_grip_fraction = 0.5;
	var r_grip_fraction = 0.5;
	
	document.addEventListener("keydown", onDocumentKeyDown, false);
	function onDocumentKeyDown(event) {
	
		var key = event.key;
		if (key == 'd') 	   {			l_deg_servo_angle_set.Base += 2.0;
		} else if (key == 'D') {			l_deg_servo_angle_set.Base -= 2.0;
		} else if (key == 's') {			l_deg_servo_angle_set.Shoulder += 2.0;
		} else if (key == 'S') {			l_deg_servo_angle_set.Shoulder -= 2.0;
		} else if (key == 'e') {			l_deg_servo_angle_set.Elbow += 2.0;
		} else if (key == 'E') {			l_deg_servo_angle_set.Elbow -= 2.0;
		} else if (key == 'w') {			l_deg_servo_angle_set.Wrist += 2.0;
		} else if (key == 'W') {			l_deg_servo_angle_set.Wrist -= 2.0;

		} else if (key == 'g') {			r_deg_servo_angle_set.Base += 2.0;
		} else if (key == 'G') {			r_deg_servo_angle_set.Base -= 2.0;
		} else if (key == 'f') {			r_deg_servo_angle_set.Shoulder += 2.0;
		} else if (key == 'F') {			r_deg_servo_angle_set.Shoulder -= 2.0;
		} else if (key == 't') {			r_deg_servo_angle_set.Elbow += 2.0;
		} else if (key == 'T') {			r_deg_servo_angle_set.Elbow -= 2.0;
		} else if (key == 'r') {			r_deg_servo_angle_set.Wrist += 2.0;
		} else if (key == 'R') {			r_deg_servo_angle_set.Wrist -= 2.0;
		} else if (key == 'x') {	l_grip_fraction += 0.1;		l_grip_fraction = open_gripper( l_grip_fraction, l_grip_meshes );	
		} else if (key == 'X') {	l_grip_fraction -= 0.1;		l_grip_fraction = open_gripper( l_grip_fraction, l_grip_meshes );	
		} else if (key == 'v') {	r_grip_fraction += 0.1;		r_grip_fraction = open_gripper( r_grip_fraction, r_grip_meshes );	
		} else if (key == 'V') {	r_grip_fraction -= 0.1;		r_grip_fraction = open_gripper( r_grip_fraction, r_grip_meshes );	
		} else if (key == ' ') {
			l_deg_servo_angle_set.Base 	= 0.0;
			l_deg_servo_angle_set.Shoulder = 0.0;
			l_deg_servo_angle_set.Elbow = 0.0;
			l_deg_servo_angle_set.Wrist = 0.0;			

			r_deg_servo_angle_set.Base 	= 0.0;
			r_deg_servo_angle_set.Shoulder = 0.0;
			r_deg_servo_angle_set.Elbow = 0.0;
			r_deg_servo_angle_set.Wrist = 0.0;			
		}
		set_servo_angles_degrees( "left", l_deg_servo_angle_set );
		set_servo_angles_degrees( "right", r_deg_servo_angle_set );		
	};			
		
</script>



</div>


