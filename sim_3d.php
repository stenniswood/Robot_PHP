<div id="Sim3D" class="tabcontent">
<script src="three.js/build/three.js"></script>
<script src="three.js/examples/js/controls/OrbitControls.js"></script>
<script src="three.js/examples/js/objects/ShadowMesh.js"></script>

<style>
.xyzslider {
    -webkit-appearance: none;
    width: 100%;
    height: 15px;
    background: #E3c3c3;
    outline: none;
    opacity: 0.7;
    -webkit-transition: .2s;
    transition: opacity .2s;
}
.xyzslider:hover {
    opacity: 1;
}
.xyzslider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 25px;
    height: 15px;
    background: #4CAF50;
    cursor: pointer;
}
</style>

<fieldset>

XYZ:<input id='xyz'></input>
<button id='move_left'  onclick="activate('left')" >Left</button>
<button id='move_right' onclick="activate('right')">Right</button>

Approach Angle:<input id='approach' value='-90'></input><br>
</fieldset>

<fieldset>
<legend>Arm Moves</legend>
<select id='path_catagory'></input>
<option>Pick and Place</option>
<option>Pick and Place Object</option>
<option>Put hands together</option>
<option>Move left hand to right</option>
<option>Move right hand to left</option>
<option>Open door</option>
<option>Open drawer</option>
<option>Close door</option>
<option>Close drawer</option>
<option>Shake</option>
<option>Shake Hands</option>
<option>Circular Path</option>
<option>Line Segment</option>
<option>Line Path</option>
<option>Bull Dozer lift</option>
</select>
<input id="path_parameters" > </input>
<input id="path_speed" width="50" value="100"> </input>
<select id="which_hand">
<option>Left</option>
<option>Right</option>
<option>Both</option>
</select>
<button id='store_position' onclick="store_xyz('right'); store_xyz('left');">Store</button>
<button id='create_path'    onclick="create_path()">Create</button>
<button id='do_path'        onclick="simulate_path()">Do Path</button>
<button id='stop_path'      onclick="stop_simulate_path()">Stop Path</button>
<input  id='repeat' type='checkbox' onclick='repeat_path=this.checked;'>Repeat</input>
<button id='add_path_seq'  class='addseq' onclick="add_path_to_sequencer()">Add to Sequencer</button><br>

<table id="paths" >
</tr>
</table>



<table class="nohover"><tr>
<td><button id="prev_position" onclick="prev_preset();  ">Prev position</button></td>
<td><button id="next_position" onclick="next_preset();  ">Next position</button></td>
</tr><tr>
<td><button id="prev_object" onclick="prev_object();  ">Prev object</button></td>
<td><button id="next_object" onclick="next_object();  ">Next object</button></td>
<td><button id="pick_place"  onclick="next_object();  ">Pick & Place</button></td>
<td><button id="stack_objects" onclick="next_object();  ">Stack object</button></td>
</tr>
</table>

<input id='show_full_humanoid' type="checkbox" onclick="show_humanoid()" >Humanoid</input>
<div id="object_grab_feedback">Text for objects positioning.</div>
</fieldset>

<fieldset>
<legend>Linear Positioning:</legend>
XYZ is to : 
<input name="xyz_is_" id='xyz_is_wrist' 		 onclick="grip_position = GRIP_NONE;" type="radio"  >wrist</input>
<input name="xyz_is_" id='xyz_is_gripper_tip' 	 onclick="grip_position = GRIP_TIP;" type="radio"  >tip of gripper</input>
<input name="xyz_is_" id='xyz_is_gripper_center' onclick="grip_position = GRIP_CENTER;" type="radio"  >center of gripper</input>
<input name="xyz_is_" id='xyz_is_gripper_back'   onclick="grip_position = GRIP_CENTER;" type="radio"  >back of gripper</input>

    <canvas id="arm_sim-canvas" style="border: none;" width="512" height="256"
    backgroundColor = 'transparent'></canvas>
    <br/>

<?php include "xyz_sliders.php" ?>
</fieldset>

<script src="./three.js/examples/js/loaders/ColladaLoader.js" ></script>

<?php include "arm_presets.php" ?>


<script>

	var grip_position=GRIP_TIP;
	var preset_position_index = 0;		// index into the array below:
	//var l_positions = [{x:0,y:0,z:0}];
	//var r_positions = [{x:0,y:0,z:0}];

	var r_object_grab_feedback = document.getElementById( 'object_grab_feedback'   );
	
	var path_table  = document.getElementById( "paths");	
	var path_params = document.getElementById( "path_parameters");	
	var path_catagory = document.getElementById( "path_catagory");
	var path_speed    = document.getElementById( "path_speed");		
	var which_hand    = document.getElementById( "which_hand");
	var repeat_ctrl   = document.getElementById( "repeat");	
	var repeat_path = repeat_ctrl.checked;

function activate(l_or_r)
{
	var str = inp_xyz.value;	
	var vals = str.split(" ");
	var xyz={};
	xyz.x = vals[0];		xyz.y = vals[1];		xyz.z = vals[2];
	xyz.approach = inp_approach.value;
	xyz.hand = l_or_r;
	do_inverse_kinematics( xyz );
}
function update_input() 
{
  var str;
  str =  l_arm_slider_x.value/10. + " ";
  str += l_arm_slider_y.value/10. + " ";
  str += l_arm_slider_z.value/10.;
  inp_xyz.value = str;
}
function update_input_r() 
{
  var str;
  str =  r_arm_slider_x.value/10. + " ";
  str += r_arm_slider_y.value/10. + " ";
  str += r_arm_slider_z.value/10.;
  inp_xyz.value = str;
}

/* For indicating an out of reach position (red=error) or normal op (bone color) */
function color_code_arm( arm_meshes, hexColor )
{
	arm_meshes.upper_arm.material.color.setHex( hexColor );
	arm_meshes.fore_arm.material.color.setHex ( hexColor );
	arm_meshes.wrist.material.color.setHex	  ( hexColor );
}
function colorize_arm( status, arm_meshes )
{
	if (status=="Out_of_Reach")
		color_code_arm( arm_meshes, out_of_range_color );
	else if (status=="Collision")
		color_code_arm( arm_meshes, collision_color );
	else 
		color_code_arm( arm_meshes, bone_color );
}

function robot_space_to_arm_space(arm_meshes, xyz)
{
	var xyz_as = {};
	xyz_as.x 		= xyz.x - arm_meshes.shoulder.position.x; 	
	xyz_as.y 		= xyz.y - arm_meshes.shoulder.position.y; 
	xyz_as.z 		= xyz.z - arm_meshes.shoulder.position.z; 
	xyz_as.hand 	= xyz.hand;
	xyz_as.WristRotate = xyz.WristRotate;
	xyz_as.Approach    = xyz.Approach;
	return xyz_as;
}

function do_inverse_kinematics( xyz, angle_set )
{
	var status;
	var servo_angles={};
	var tmp_xyz  = {};
	var HAND =  xyz.hand.toUpperCase();

	if (HAND=="LEFT") {
		tmp_xyz = robot_space_to_arm_space( l_arm_meshes, xyz );
		status  = INV_Grip_XYZ_To_Angles  ( tmp_xyz, servo_angles, xyz.grip_position );
		colorize_arm( status, l_arm_meshes );
		if (status=="success") {
			l_rad_servo_angle_set = servo_angles;
			l_rad_servo_angle_set.WristRotate = xyz.wrist_rotate - servo_angles.Base;
			set_servo_angles_rad( l_arm_meshes, l_grip_meshes, arm_sizes, servo_angles );				
			update_object_position(xyz);
			convert_to_degrees( servo_angles, l_deg_servo_angle_set );
			populate_angle_table(l_deg_servo_angle_set,1);
		} else return status;
	} else if (HAND=="RIGHT") {
		tmp_xyz = robot_space_to_arm_space( r_arm_meshes, xyz );
		status  = INV_Grip_XYZ_To_Angles( tmp_xyz, servo_angles, xyz.grip_position );		
		colorize_arm( status, r_arm_meshes );
		if (status=="success") {
			r_rad_servo_angle_set = servo_angles;
			r_rad_servo_angle_set.WristRotate = xyz.wrist_rotate - servo_angles.Base;
			set_servo_angles_rad( r_arm_meshes, r_grip_meshes, arm_sizes, servo_angles );
			update_object_position(xyz);
			convert_to_degrees( servo_angles, r_deg_servo_angle_set );			
			populate_angle_table(r_deg_servo_angle_set,2);
		} else return status;
	}
	return true;
}
</script>



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
	var camera = new THREE.PerspectiveCamera( 70, window.innerWidth / window.innerHeight, 0.1, 5000 );
//	var camera = new THREE.PerspectiveCamera( 120, window.innerWidth / window.innerHeight, 0.1, 5000 );
/*					THREE.OrthographicCamera(
						  canvas.width / -2,
						  canvas.width / 2,
						  canvas.height / 2,
						  canvas.height / -2, -500, 1000);*/

	camera.up.set(1, 0, 0); 
	camera.zoom = 30;
	camera.position.set(10, 30, 0);
	camera.lookAt(scene.position);
	camera.updateProjectionMatrix();
	scene.add(camera);
	scene.background = new THREE.Color( 0x0096ff );

	var controls = new THREE.OrbitControls( camera, renderer.domElement );
	controls.enableDamping = true; // an animation loop is required when either damping or auto-rotation are enabled
	controls.dampingFactor = 0.25;
	controls.screenSpacePanning = false;
	controls.minDistance = 1;
	controls.maxDistance = 4000
	controls.maxPolarAngle = Math.PI ;/// 2;
		
	
	  const size = 20
	  const step = 20
	  const gridHelper = new THREE.GridHelper(size, step);
	  gridHelper.rotation.x = 0;	gridHelper.rotation.y = 0;	gridHelper.rotation.z = 90/180.*Math.PI;
	  scene.add(gridHelper);

	  const axesHelper = new THREE.AxesHelper(5);
	  scene.add(axesHelper);

	

	
		var dirLight = new THREE.DirectionalLight( 0xFFFFFF, 1.0 );
		dirLight.position.set( 200, 10, -40 ).normalize();
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
		
		var pointLight = new THREE.PointLight( 0xffffff, 1.5 );
		pointLight.position.set( 90, 100, 0 );
		scene.add( pointLight );

/*		var pointLight2 = new THREE.PointLight( 0xffffff, 1.5 );
		pointLight2.position.set( 90, -100, 0 );
		scene.add( pointLight2 );

		var pointLight3 = new THREE.PointLight( 0xffffff, 1.5 );
		pointLight3.position.set( 90, -100, 90 );
		scene.add( pointLight3 );
*/
</script>
<script src="sim_objects.js"></script>
<script src="arm_construction.js"></script>
<script src="arm_gripper.js"></script>
<script src="arm_servos.js"></script>	
	
<script src="sim_bipedal.js"> </script>

<script>


	function init_grippers() {
		construct_gripper(l_grip_geom, l_grip_meshes, arm_material.meshMaterialJ,  arm_material.meshMaterial  );
		construct_gripper(r_grip_geom, r_grip_meshes, arm_material.rmeshMaterialJ,  arm_material.rmeshMaterial );
		init_grip_locations();

		l_grip_meshes.wrist.position.z = arm_sizes.wrist_length;
		r_grip_meshes.wrist.position.z = arm_sizes.wrist_length;
		l_arm_meshes.wrist.add( l_grip_meshes.wrist );
		r_arm_meshes.wrist.add( r_grip_meshes.wrist );
		
		//add_to_scene( l_grip_meshes );
		//add_to_scene( r_grip_meshes );

		create_grip_shadow_meshes(l_grip_meshes);
		create_grip_shadow_meshes(r_grip_meshes);
	}
	init_grippers();

	set_servo_angles_degrees( "left",  l_deg_servo_angle_set );
	set_servo_angles_degrees( "right", r_deg_servo_angle_set );
		
	// FOLDING CHAIR & IMPORTED OBJECTS:
	var loader = new THREE.ColladaLoader();
    loader.load('FOLDING CHAIR.dae',function colladaReady( collada ){
        var chair = collada.scene;
        chair.children[0].visible = false;
        chair.children[1].visible = false;        
        chair.children[2].visible = false;        
        chair.children[4].visible = false;                        
        chair.position.x = -10;
        chair.position.y = 0;
        chair.position.z = 0;   
        chair.rotation.z = -Math.PI /2;     
        chair.scale.set( 7, 7, 7 );
	//	player.children[2].geometry.computeBoundingBox();
		//chair.children[3].geometry.computeBoundingBox();
        //skin = collada.skins [ 0 ];
        scene.add( chair );
        });

/*    loader.load('./Senza/model.dae',function colladaReady( collada ){
        var door = collada.scene;
*        door.children[0].visible = false;
        door.children[1].visible = false;        
        door.children[2].visible = false;        
        door.children[4].visible = false;                        
        door.position.x = -10;
        door.position.y = 20;
        door.position.z = -100;   
        door.rotation.x = +Math.PI;     
        door.rotation.y = +Math.PI /2;             
        door.scale.set( 1.5, 1.5, 1.5 );
	//	player.children[2].geometry.computeBoundingBox();
		//door.children[3].geometry.computeBoundingBox();
        //skin = collada.skins [ 0 ];
        //scene.add( door );
        });*/

//	var d_texture = new THREE.TextureLoader().load( "./textures/door_carmelle.jpg" );
	var d_texture = new THREE.TextureLoader().load( "./textures/door_milano_luna.jpg" );
		
	//d_texture.rotation = Math.PI/2;
	
	//d_texture.wrapS = THREE.RepeatWrapping;
	//d_texture.wrapT = THREE.RepeatWrapping;
	//d_texture.repeat.set( 4, 4 );
	var door_depth = 0.5;
	var door_width = 36;
	var door_height = 84;
	door_geom	= new THREE.BoxGeometry( door_depth,  door_height, door_width );
	door_geom.translate(0, door_height/2, 0);
	var door_material = new THREE.MeshBasicMaterial( { map: d_texture } );
	var door_mesh     = new THREE.Mesh( door_geom, door_material );
	door_mesh.position.x = -10;
	door_mesh.scale.set(0.8, 0.8, 0.8);
	door_mesh.rotation.y= Math.PI;
	door_mesh.rotation.z= Math.PI/2;	
	scene.add(door_mesh);
			

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
		
	var l = (get_total_arm_length() + 15)*10;
	var groundGeometry = new THREE.BoxBufferGeometry( l, 0.01, l );
//	var groundMaterial = new THREE.MeshLambertMaterial( { color: 'rgb(0,130,0)' } );
	var groundMaterial = new THREE.MeshBasicMaterial( { map: texture } );	
	groundMesh = new THREE.Mesh( groundGeometry, groundMaterial );
	groundMesh.position.x = -10.0; //this value must be slightly lower than the planeConstant (0.01) parameter above
	groundMesh.rotation.z = 90/180.*Math.PI;
	scene.add( groundMesh );

	function update_shadows(arm_meshes, grip_meshes, leg_meshes)
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
		
		leg_meshes.luaShadow.update( shadowPlane, lightPosition4D );
		leg_meshes.leaShadow.update( shadowPlane, lightPosition4D );
		leg_meshes.llaShadow.update( shadowPlane, lightPosition4D );
		leg_meshes.lwmShadow.update( shadowPlane, lightPosition4D );
		leg_meshes.lwaShadow.update( shadowPlane, lightPosition4D );

	}
		
	function animate() {
		requestAnimationFrame( animate );
		controls.update(); // only required if controls.enableDamping = true, or if controls.autoRotate = true
		update_shadows(l_arm_meshes, l_grip_meshes, l_leg_meshes );
		update_shadows(r_arm_meshes, r_grip_meshes, r_leg_meshes );
		smeshes.forEach( (variable,index) => {
			variable.update( shadowPlane, lightPosition4D);
		});
		 

		//set_servo_angles( BaseDeg, ShoulderDeg, ElbowDeg );				
		renderer.render( scene, camera );
	}

	animate();						
	
	var l_grip_fraction = 0.5;
	var r_grip_fraction = 0.5;
		var squat_angle = 0;
			
	document.addEventListener("keydown", onDocumentKeyDown, false);
	function onDocumentKeyDown(event) {
		var delta = 2.0 *Math.PI/180.;

		
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
		} else if (key == 'x') {	l_grip_fraction += 0.1;		l_grip_fraction = open_gripper( l_grip_fraction, "Left", l_grip_meshes );	
		} else if (key == 'X') {	l_grip_fraction -= 0.1;		l_grip_fraction = open_gripper( l_grip_fraction, "Left", l_grip_meshes );	
		} else if (key == 'v') {	r_grip_fraction += 0.1;		r_grip_fraction = open_gripper( r_grip_fraction, "Left", r_grip_meshes );	
		} else if (key == 'V') {	r_grip_fraction -= 0.1;		r_grip_fraction = open_gripper( r_grip_fraction, "Left", r_grip_meshes );	
		} else if (key == 'c') {	l_deg_servo_angle_set.WristRotate -= 2.0;
		} else if (key == 'C') {	l_deg_servo_angle_set.WristRotate += 2.0;	
		} else if (key == 'b') {	r_deg_servo_angle_set.WristRotate -= 2.0;	
		} else if (key == 'B') {	r_deg_servo_angle_set.WristRotate += 2.0;

		} else if (key == 'y') {			l_rad_leg_angle_set.Hip   += delta;
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

		} else if (key == 'o') {	sit_pose();//		torso_mesh.rotation.y   += delta;
		} else if (key == 'O') {	stand_pose();//		torso_mesh.rotation.y   -= delta;
		} else if (key == 'l') {	squat_angle += delta; squat( Math.degrees(squat_angle) );//		torso_mesh.rotation.y   += delta;
		} else if (key == 'L') {	squat_angle -= delta; squat( Math.degrees(squat_angle) );//		torso_mesh.rotation.y   -= delta;

		} else if (key == '1') {	torso_mesh.position.y += 2; //leg_lift( "Left", Math.degrees(squat_angle) );//		torso_mesh.rotation.y   += delta;
		} else if (key == '!') {	torso_mesh.position.y -= 2; //leg_lift( "Left", Math.degrees(squat_angle) );//		torso_mesh.rotation.y   -= delta;
		} else if (key == '2') {	torso_mesh.position.z += 2; //leg_lift( "Left", Math.degrees(squat_angle) );//		torso_mesh.rotation.y   += delta;
		} else if (key == '@') {	torso_mesh.position.z -= 2; //leg_lift( "Left", Math.degrees(squat_angle) );//		torso_mesh.rotation.y   -= delta;


		} else if (key == '.') {	squat_angle += delta; leg_lift( "Left", Math.degrees(squat_angle) );//		torso_mesh.rotation.y   += delta;
		} else if (key == '>') {	squat_angle -= delta; leg_lift( "Left", Math.degrees(squat_angle) );//		torso_mesh.rotation.y   -= delta;

		} else if (key == 'p') {			torso_mesh.rotation.z  += delta;
		} else if (key == 'P') {			torso_mesh.rotation.z  -= delta;

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
		set_servo_angles_degrees( "left",  l_deg_servo_angle_set );
		set_servo_angles_degrees( "right", r_deg_servo_angle_set );		
		populate_angle_table(l_deg_servo_angle_set, 1);
		populate_angle_table(r_deg_servo_angle_set, 2);
		
		set_leg_angles( l_rad_leg_angle_set, l_leg_meshes );
		set_leg_angles( r_rad_leg_angle_set, r_leg_meshes );		
		
	};			
		
</script>



</div>


