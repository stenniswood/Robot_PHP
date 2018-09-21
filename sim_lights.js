var lightPosition4D = new THREE.Vector4();		// Used by updateShadows

		
function init_directional_lights()
{
	// Light
	var light = new THREE.DirectionalLight( 0xFFFFFF );
	light.position.set( 20, 40, -15 );
	light.target.position.copy( scene.position );
	light.castShadow = true;
	light.shadowCameraLeft = -60;
	light.shadowCameraTop = -60;
	light.shadowCameraRight = 60;
	light.shadowCameraBottom = 60;
	light.shadowCameraNear = 20;
	light.shadowCameraFar = 200;
	light.shadowBias = -.0001
	light.shadowMapWidth = light.shadowMapHeight = 2048;
	light.shadowDarkness = .7;
	scene.add( light );

	// Light 2
	var light2 = new THREE.DirectionalLight( 0xFFFFFF );
	light2.position.set( 20, -40, +15 );
	light2.target.position.copy( scene.position );
	light2.castShadow = true;
	light2.shadowCameraLeft = -60;
	light2.shadowCameraTop = -60;
	light2.shadowCameraRight = 60;
	light2.shadowCameraBottom = 60;
	light2.shadowCameraNear = 20;
	light2.shadowCameraFar = 200;
	light2.shadowBias = -.0001
	light2.shadowMapWidth = light.shadowMapHeight = 2048;
	light2.shadowDarkness = .7;
	scene.add( light2 );
	


	
	var dirLight = new THREE.DirectionalLight( 0xFFFFFF, 1.0 );
	dirLight.position.set( ground_size, 10, 40 ).normalize();
	dirLight.castShadow = true; 
	dirLight.lookAt( scene.position );
	dirLight.shadowDarkness = 0.5;
	//Set up shadow properties for the light
	dirLight.shadow.mapSize.width = 512;  // default
	dirLight.shadow.mapSize.height = 512; // default
	dirLight.shadow.camera.near = 0.5;    // default
	dirLight.shadow.camera.far = 1000;     // default
	//dirLight.shadowCameraVisible = true;
//	scene.add( dirLight );

	lightPosition4D.x = dirLight.position.x;
	lightPosition4D.y = dirLight.position.y;
	lightPosition4D.z = dirLight.position.z;
	// amount of light-ray divergence. Ranging from:
	// 0.001 = sunlight(min divergence) to 1.0 = pointlight(max divergence)
	lightPosition4D.w = 0.001; // must be slightly greater than 0, due to 0 causing matrixInverse errors
}

function init_point_lights()
{
		var pointLight = new THREE.PointLight( 0xffffff, 1.5 );
		pointLight.position.set( 90, ground_size, -ground_size );
		scene.add( pointLight );
		
		var pointLight1 = new THREE.PointLight( 0xffffff, 1.5 );
		pointLight1.position.set( 90, ground_size, ground_size );
		//scene.add( pointLight1 );

		var pointLight2 = new THREE.PointLight( 0xffffff, 1.5 );
		pointLight2.position.set( 90, -ground_size, -ground_size );
		//scene.add( pointLight2 );

		var pointLight3 = new THREE.PointLight( 0xffffff, 1.5 );
		pointLight3.position.set( 90, -ground_size, ground_size );
		//scene.add( pointLight3 );
}

