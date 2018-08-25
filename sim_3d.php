<div id="Sim3D" class="tabcontent">

    <canvas id="arm_sim-canvas" style="border: none;" width="500" height="250"
    backgroundColor = 'transparent'></canvas>
    <br/>

<script src="js/three.js"></script>
<script src="three.js/examples/js/controls/OrbitControls.js"></script>
		
<script>
	var canvas = document.getElementById("arm_sim-canvas");
	document.body.appendChild( canvas );
	
	var renderer = new THREE.WebGLRenderer();
	var w = canvas.width;
	renderer.setSize( canvas.width, canvas.height );
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

	
	
		var dirLight = new THREE.DirectionalLight( 0xffffff, 0.125 );
		dirLight.position.set( 0, 0, 40 ).normalize();
		scene.add( dirLight );
		
		var pointLight = new THREE.PointLight( 0xffffff, 1.5 );
		pointLight.position.set( 90, 100, 0 );
		scene.add( pointLight );

		var pointLight2 = new THREE.PointLight( 0xffffff, 1.5 );
		pointLight2.position.set( 90, -100, 0 );
		scene.add( pointLight2 );

		var pointLight3 = new THREE.PointLight( 0xffffff, 1.5 );
		pointLight3.position.set( 90, -100, 90 );
		scene.add( pointLight3 );

</script>
<script src="arm_construction.js"></script>
<script src="arm_servos.js"></script>						
<script>
		
	function animate() {
		requestAnimationFrame( animate );
		controls.update(); // only required if controls.enableDamping = true, or if controls.autoRotate = true
		
		//set_servo_angles( BaseDeg, ShoulderDeg, ElbowDeg );				
		renderer.render( scene, camera );
	}

	animate();						
				
	document.addEventListener("keydown", onDocumentKeyDown, false);
	function onDocumentKeyDown(event) {
		var key = event.key;
		if (key == 'b') {
			l_deg_servo_angle_set.Base += 2.0;
		} else if (key == 'B') {
			l_deg_servo_angle_set.Base -= 2.0;
		} else if (key == 's') {
			l_deg_servo_angle_set.Shoulder += 2.0;
		} else if (key == 'S') {
			l_deg_servo_angle_set.Shoulder -= 2.0;
		} else if (key == 'e') {
			l_deg_servo_angle_set.Elbow += 2.0;
		} else if (key == 'E') {
			l_deg_servo_angle_set.Elbow -= 2.0;
		} else if (key == 'w') {
			l_deg_servo_angle_set.Wrist += 2.0;
		} else if (key == 'W') {
			l_deg_servo_angle_set.Wrist -= 2.0;
		} else if (key == ' ') {
			l_deg_servo_angle_set.Base 	= 0.0;
			l_deg_servo_angle_set.Shoulder = 0.0;
			l_deg_servo_angle_set.Elbow = 0.0;
		}
		set_servo_angles_degrees( "left", l_deg_servo_angle_set );
	};			
		
</script>



</div>


