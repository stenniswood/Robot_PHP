var simulation = {
	sequence : [],
	stance_leg : "Left",
	// blah blah to hold internal states during simulation.
};

var humanoid_sequence = [];			// Array of Angle_sets.
var seq_index = 0;

var stance_leg = "Left";
var swing_leg  = opposite_leg(stance_leg);

function sim_execution()
{
	// This will operate on any angle set - left arm, right arm, left leg, right leg, eyes, etc.
	sim_actuate_angle_set( humanoid_sequence[seq_index++] );	// Located in sim_bipedal.js

	if (seq_index>=humanoid_sequence.length)
		seq_index = 0;
}

function switch_stance_leg()
{
	swing_leg  = stance_leg;
	stance_leg = opposite_leg(stance_leg);
}
function get_stance_leg_angle_set()
{
	if (stance_leg=="Left")
		return Object.assign( {}, l_rad_leg_angle_set );
	else
		return Object.assign( {}, r_rad_leg_angle_set );
}

// Common Angle Convetion input.
function adjust_torso_to_stance_leg( prev_height, stance_angle_set )
{
	var foot_vertical = 0;
	var delta = 0;
	if (stance_angle_set.Leg=="Left")
	{
		foot_vertical = left_foot_height_robot_space ( stance_angle_set );
		delta = prev_height - foot_vertical;
	} else {
		foot_vertical = right_foot_height_robot_space( stance_angle_set );
		delta = prev_height - foot_vertical;		
	}
	// Want to move it in World Coords : 
	humanoid.x = 0;
	humanoid.y = 0;
	//humanoid.z += delta;
	
	// work from the bottom up :
	// Get vertical for Lower_leg.
	var angle_wrt_up     = stance_angle_set.Ankle;
	var lower_horizontal = leg_sizes.lower_leg_length * Math.sin( angle_wrt_up );
	var lower_vertical   = leg_sizes.lower_leg_length * Math.cos( angle_wrt_up );

	var upper_leg_angle_wrt_up = (-stance_angle_set.Knee + stance_angle_set.Ankle);
	var upper_vertical   = leg_sizes.upper_leg_length * Math.cos( upper_leg_angle_wrt_up );
	var upper_horizontal = leg_sizes.upper_leg_length * Math.sin( upper_leg_angle_wrt_up );

	// Upper_leg : 
	torso_mesh.position.x = lower_horizontal + upper_horizontal;	// x is forward
	torso_mesh.position.y = 0;										// y is width
	torso_mesh.position.z = lower_vertical + upper_vertical;		// z is height
	torso_mesh.position.z = -get_height_robot_space( stance_angle_set, torso_mesh.rotation.y );
	torso_mesh.rotation.y = -(upper_leg_angle_wrt_up+stance_angle_set.Hip);
}

/* Assumption the leg is straight and foot flat on the ground */
function compute_horiz_distance_for_ankle( ankle_angle )
{
	var length = leg_sizes.upper_leg_length + leg_sizes.lower_leg_length;
	var angle  = Math.Pi/2 + ankle_angle;  // ankle_angle is defined (-) for raising the toes. (common angle convention)
	var horiz  = length * Math.cos(angle);
	return horiz;
}
// To lean the robot forward:
function find_angle_for_horiz_distance( horiz_distance )
{
	var length = leg_sizes.upper_leg_length + leg_sizes.lower_leg_length;
	var ratio  = horiz_distance / length;
	return  -(Math.asin(ratio)); // (Math.PI/2) 
}

function sim_play_sequence( humanoid_sequence )
{

}

function walk_up_step()
{
	// Lift Swing leg first:
	var height_to_raise = stair_sizes.rise + 2;	
	var swing_angle_set = leg_lift_distance( height_to_raise );	// Common Angle Convention
	swing_angle_set.BodyPart 	= "Leg";
	swing_angle_set.Leg 		= swing_leg;
	swing_angle_set.isStanceLeg = false;
	humanoid_sequence.push( swing_angle_set );	

	// Bend ankle of the stance leg to come forward (by the run amount):
	var stance_angle_set         = get_stance_leg_angle_set();
	stance_angle_set.Ankle 		 = find_angle_for_horiz_distance( stair_sizes.run );
	stance_angle_set.isStanceLeg = true;
	humanoid_sequence.push( stance_angle_set );

	// Lower Swing leg to Step height:
	var height_to_raise = stair_sizes.rise + 2;	
	var swing_angle_set = leg_lift_distance( height_to_raise-2 );
	swing_angle_set.BodyPart = "Leg";
	swing_angle_set.Leg = swing_leg;	
	swing_angle_set.isStanceLeg = false;	
	humanoid_sequence.push( swing_angle_set );

	// Now straighten the previous swing leg:
	switch_stance_leg();
	stance_angle_set = leg_lift( 0.0 );		// 0 degrees.
	stance_angle_set.BodyPart    = "Leg";
	stance_angle_set.Leg         = stance_leg;
	stance_angle_set.isStanceLeg = true;

	// AND bend prev stance leg knee:
	//swing_leg
	humanoid_sequence.push( stance_angle_set );	
}

function walk_up_stairs()
{
	for (i=0; i<15; i++)
	{
		walk_up_step();
	}
}


walk_up_stairs();
