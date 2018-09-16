<!DOCTYPE html>
<html>
	<head>
		<meta charset=utf-8>
		<title>My first three.js app</title>
		<style>
			body { margin: 0; }
			canvas { width: 100%; height: 100% }

		</style>
	</head>
	<body>

		<script src="js/three.js"></script>
		<script src="three.js/examples/js/controls/OrbitControls.js"></script>
		<script src="three.js/examples/js/objects/ShadowMesh.js"></script>
		
		<script>
			// Our Javascript will go here.
			var scene = new THREE.Scene();
			var camera = new THREE.PerspectiveCamera( 60, window.innerWidth / window.innerHeight, 0.1, 5000 );

			var renderer = new THREE.WebGLRenderer();
			renderer.setSize( window.innerWidth, window.innerHeight );
			document.body.appendChild( renderer.domElement );			

	var controls = new THREE.OrbitControls( camera, renderer.domElement );
	controls.enableDamping = true; // an animation loop is required when either damping or auto-rotation are enabled
	controls.dampingFactor = 0.25;
	controls.screenSpacePanning = false;
	controls.minDistance = 100;
	controls.maxDistance = 500
	controls.maxPolarAngle = Math.PI;

			// CUBE:
//			var geometry = new THREE.BoxGeometry( 1, 1, 1 );
			var geometry  = new THREE.CylinderGeometry( 1, 5, 20, 32 );
			var geometry2 = new THREE.CylinderGeometry( 5, 5, 20, 32 );			
			var material = new THREE.MeshBasicMaterial( { color: 0x00ff00 } );
			var cube  = new THREE.Mesh( geometry, material );
			var cube2 = new THREE.Mesh( geometry, material );			
			cube.position.x=5;
			scene.add( cube );
			//scene.add( cube2 );
			var textMesh1;

				var dirLight = new THREE.DirectionalLight( 0xffffff, 0.125 );
				dirLight.position.set( 0, 0, 40 ).normalize();
				scene.add( dirLight );
				
				var pointLight = new THREE.PointLight( 0xffffff, 1.5 );
				pointLight.position.set( 0, 100, 90 );
				scene.add( pointLight );
				
			camera.position.z = 40;
			

		var texture       = new THREE.TextureLoader().load( "textures/sponge_wall_texture.jpg" );
		var normalVector  = new THREE.Vector3( 1, 0, 0 );
		var planeConstant = -9.9; // this value must be slightly higher than the groundMesh's y position of 0.0
		var shadowPlane   = new THREE.Plane( normalVector, planeConstant );
		shadowPlane.receiveShadow = true;
		shadowPlane.castShadow = false;
		shadowPlane.renderOrder = - 1;

		var l = 60;
		var groundGeometry = new THREE.BoxBufferGeometry( l, 0.01, l );
		var groundMaterial = new THREE.MeshBasicMaterial( { map: texture } );	
		groundMesh = new THREE.Mesh( groundGeometry, groundMaterial );
		groundMesh.position.x = 0; //this value must be slightly lower than the planeConstant (0.01) parameter above
		groundMesh.rotation.z = 90/180.*Math.PI;
		scene.add( groundMesh );			
			
			function animate() {
				requestAnimationFrame( animate );
				//cube.rotation.x += 0.01;
				//cube.rotation.y += 0.01;	
				textMesh1.rotation.x += 0.01;
				//textMesh1.rotation.y += 0.01;
				renderer.render( scene, camera );
			}

	var loader = new THREE.FontLoader();
	loader.load( 'js/fonts/helvetiker_bold.typeface.json', function ( font ) {
		var textGeo = new THREE.TextGeometry( 'Beyond Kinetics.js!', {
			font: font,
			size: 10,
			height: 5,
			curveSegments: 12,
			bevelEnabled: true,
			bevelThickness: 2,
			bevelSize: 1,
			bevelSegments: 5
		} );
		var materials = [
						new THREE.MeshPhongMaterial( { color: 0x6fffff, flatShading: true } ), // front
						new THREE.MeshPhongMaterial( { color: 0xffff3f } ) // side
					];
				
		var group = new THREE.Group();
		group.position.y = 4;

		textGeo.computeBoundingBox();
		textGeo.computeVertexNormals();	
		var centerOffset = -0.5 * ( textGeo.boundingBox.max.x - textGeo.boundingBox.min.x );
		
		textGeo   = new THREE.BufferGeometry().fromGeometry( textGeo );
		textMesh1 = new THREE.Mesh( textGeo, materials );
		textMesh1.position.x = centerOffset;
		textMesh1.position.y = 0;
		textMesh1.position.z = 0;
		textMesh1.rotation.x = 0;
		textMesh1.rotation.y = Math.PI * 2;
		group.add( textMesh1 );
//		scene.add( group );
		animate();						
	} );
	//	<script src="sim_objects.js"> <script>
	
	
		document.addEventListener("keydown", onDocumentKeyDown, false);
	function onDocumentKeyDown(event) {
	
		var delta = 2.0 *Math.PI/180.;
		var key = event.key;
		if (key == 'y') 	   {			l_rad_leg_angle_set.Hip   += delta;
		} else if (key == 'Y') {			l_rad_leg_angle_set.Hip   -= delta;
		} else if (key == 'h') {			l_rad_leg_angle_set.Knee  += delta;
		} else if (key == 'H') {			l_rad_leg_angle_set.Knee  -= delta;
		} else if (key == 'n') {			l_rad_leg_angle_set.Ankle += delta;
		} else if (key == 'N') {			l_rad_leg_angle_set.Ankle -= delta;

		} else if (key == 'u') {			r_rad_leg_angle_set.Hip   += delta;
		} else if (key == 'U') {			r_rad_leg_angle_set.Hip   -= delta;
		} else if (key == 'j') {			r_rad_leg_angle_set.Knee  += delta;
		} else if (key == 'J') {			r_rad_leg_angle_set.Knee  -= delta;
		} else if (key == 'm') {			r_rad_leg_angle_set.Ankle += delta;
		} else if (key == 'M') {			r_rad_leg_angle_set.Ankle -= delta;
		} else if (key == 'k') {		object_meshes[0].position.y += 0.25;
		} else if (key == 'K') {		object_meshes[0].position.y -= 0.25;
		} else if (key == 'l') {		object_meshes[1].position.y += 0.25;
		} else if (key == 'L') {		object_meshes[1].position.y -= 0.25;

		} else if (key == 'i') {		object_meshes[0].position.z += 0.25;
		} else if (key == 'I') {		object_meshes[0].position.z -= 0.25;
		} else if (key == 'o') {		object_meshes[1].position.z += 0.25;
		} else if (key == 'O') {		object_meshes[1].position.z -= 0.25;

		} else if (key == ' ') {
			l_rad_leg_angle_set.Hip 	= 0.0;
			l_rad_leg_angle_set.Knee    = 0.0;
			l_rad_leg_angle_set.Ankle   = 0.0;
			l_rad_leg_angle_set.HipRotate = 0.0;			

			r_rad_servo_angle_set.Hip 	= 0.0;
			r_rad_servo_angle_set.Knee  = 0.0;
			r_rad_servo_angle_set.Ankle = 0.0;
			r_rad_servo_angle_set.HipRotate = 0.0;			
		}
		//set_leg_angles( l_rad_leg_angle_set, l_leg_meshes );
		//set_leg_angles( r_rad_leg_angle_set, r_leg_meshes );

		if (do_boxes_collide() )
			status = "Collision";
		else
			status = "Okay";
		colorize_boxes( status );
	};
		</script>


<?php
include "box_collision.php";
/*
	<script src="arm_construction.js"> 
		var bone_color    = 0x754249;
		var bone_emissive = 0x072534;
	</script>
	<script src="sim_bipedal.js"> </script>

	<?php include "humanoid.php"; ?>
*/ ?>
		
	</body>
</html>
