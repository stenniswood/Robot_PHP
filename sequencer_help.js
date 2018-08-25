

var cmd_help={};
var param_help={};
	
function init_drivefive_help()
{
	var help_context = { };
	var help_params  = { };		
	help_context["PWM"] 		= "Pulse Width Modulate motors; motors are labelled: v,w,x,y,z";
	help_params["PWM"] 			= "v[-1.0..1.0] w[-1.0..1.0] x[-1.0..1.0] y[-1.0..1.0] z[-1.0..1.0]";
	
	help_context["PID"] 		= "Propotional Integral Derivative control. Move a motor(s) to a position; this requires a potentiometer or encoder for feedback control";
	help_params["PID"]  		= "v[-1.0..1.0] w[-1.0..1.0] x[-1.0..1.0] y[-1.0..1.0] z[-1.0..1.0]";

	help_context["read status"]  = "Read overall status of the DriveFive board.";
	help_params["PID"]  		 = "";

	help_context["read current"] = "Read electrical currents in each motor";
	help_params["read current"]  = "units will be in Amps";

	help_context["read speed"]   = "Read speed of each motor";
	help_params["read speed"]    = "";

	help_context["stop"] = "Stop all motors";
	help_params["stop"]  = "none";

	help_context["spin"] = "Spin the robot in place. One wheel forward, one backward";
	help_params["spin"]  = "speed [-1.0..1.0]";

	help_context["read position"] = "Read position of each motor";
	help_params["read position"]  = "v[-1.0..1.0] w[-1.0..1.0] x[-1.0..1.0] y[-1.0..1.0] z[-1.0..1.0]";

	help_context["forward"] 	= "Move robot forward at a speed";
	help_params["forward"]  	= "[-1.0..1.0]";

	help_context["backward"] 	= "Move robot backward at a speed";
	help_params["backward"]  	= "[-1.0..1.0]";


	help_context["set wheel diameter"] 	= "The diameter of the wheel for linear speed calculations";
	help_params["set wheel diameter"]  	= "[0.0..10000.0]";

	help_context["set wheel separation"] 	= "The distance between the 2 wheels for accurate curve radius";
	help_params["set wheel separation"]  	= "[0.0..10000.0]";

	help_context["set counts_per_rev"] 	= "The number of encoder counts per rev of the wheel.";
	help_params["set counts_per_rev"]  	= "[0.0..2000.0]";

	help_context["backward"] 	= "Move robot backward at a speed";
	help_params["backward"]  	= "[-1.0..1.0]";

	cmd_help["Drive Five"]	 = help_context;
	param_help["Drive Five"] = help_params;
}
function init_aniEyes_help()
{
	var help_context = { };
	var help_params  = { };		
	help_context["straight"] 	= "Make both eyes look straight ahead";
	help_params["straight"] 	= "";

	help_context["look left"] 	= "Look left";
	help_params["look left"] 	= "none";

	help_context["look right"] 	= "Look right";
	help_params["look right"] 	= "none";

	help_context["look up"] 	= "Look up (and center)";
	help_params["look up"] 		= "none";

	help_context["look down"] 	= "Look down (centered)";
	help_params["look down"] 	= "none";

	help_context["look at"] 	= "Move eyes to a specified left-right and up-down angles.";
	help_params["look at"] 		= "[Left-Right angle (degrees); 0=straight ahead] [Up-Down angle (degrees)]";

	help_context["left look at"] 	= "Move left eye to a specified left-right and up-down angles.";
	help_params["left look at"] 	= "[Left-Right angle (degrees); 0=straight ahead] [Up-Down angle (degrees)]";

	help_context["right look at"] 	= "Move right eye to a specified left-right and up-down angles.";
	help_params["right look at"] 	= "[Left-Right angle (degrees); 0=straight ahead] [Up-Down angle (degrees)]";

	help_context["blink"] 		= "Blink both eyes";
	help_params["blink"]	 	= "none";

	help_context["close left eyelid"] 		= "Close left eye lid to a fraction";
	help_params["close left eyelid"]	 	= "[0.0..1.0] fraction closed";

	help_context["close right eyelid"] 		= "Close right eye lid to a fraction";
	help_params["close right eyelid"]	 	= "[0.0..1.0] fraction closed";

	help_context["blink"] 		= "Blink both eyes";
	help_params["blink"]	 	= "none";

	help_context["roll eyes"] 		= "Roll both eyes";
	help_params["roll eyes"]	 	= "none";

	help_context["scan horizon"] 	= "Scan left to right";
	help_params["scan horizon"]	 	= "none";

	help_context["scan up down"] 	= "Scan up to down";
	help_params["scan up down"]	 	= "none";

	cmd_help["Ani-Eyes"]	= help_context;
	param_help["Ani-Eyes"] 	= help_params;
}

function init_loadcell_help()
{
	var help_context = { };
	var help_params  = { };		
	help_context["send"] 	= "Send the measurements (4 corners)";
	help_params["send"] 	= "v[-1.0..1.0] w[-1.0..1.0] x[-1.0..1.0] y[-1.0..1.0] z[-1.0..1.0]";

	cmd_help["Load-cell"]	 = help_context;
	param_help["Load-cell"] = help_params;
}

function init_IOexpander_help()
{
	var help_context = { };
	var help_params  = { };		
	help_context["send"] 		= "Send the analog values over; as per mask_ad ";
	help_params["send"] 		= "call \"mask_ad 1 2 3\" first.";

	help_context["stream"] 		= "Send the analog values over; as per mask_ad ";
	help_params["stream"] 		= "call \"mask_ad 1 2 3\" first.";

	help_context["mask_ad"] 	= "Send the analog values; ";
	help_params["mask_ad"] 		= "first_pin  second_pin... (pins are 0..6).";

	help_context["mask_di"] 	= "Send the digital input values over";
	help_params["mask_di"] 		= "first_di second_di..(0..13)\" ";

	help_context["servo"] 	= "Adjust RC servo to new position";
	help_params["servo"] 	= "[servo number 0..7] [angle: 0..180].";

	help_context["dwrite"] 	= "Send the analog values over; as per mask_ad ";
	help_params["dwrite"] 	= "call \"mask_ad 1 2 3\" first";

	help_context["dsend"] 	= "Send the analog values over; as per mask_ad ";
	help_params["dsend"] 	= "call \"mask_ad 1 2 3\" first.";

	help_context["scan"] 	= "Send the analog values over; as per mask_ad ";
	help_params["scan"] 	= "call \"mask_ad 1 2 3\" first.";

	cmd_help["IO Expander"]	  = help_context;
	param_help["IO Expander"] = help_params;
}

function init_System_help()
{
	var help_context = { };
	var help_params  = { };		
	help_context["play"] 	= "Play an audio file on the server robot.";
	help_params["play"] 	= "Filename";

	help_context["exec"] 	= "Execute a shell command on the server robot.";
	help_params["exec"] 	= "command line";

	help_context["tts"] 	= "Text To Speech.";
	help_params["tts"]	 	= "Text to be spoken";

	help_context["arm xyz"] 	= "Move 6DOF arm to an XYZ coordinate.";
	help_params["arm xyz"]	 	= "x y z in mm";
	help_context["arm gripper"] = "Open the gripper to X (1.0=full open; 0.0=full closed).";
	help_params["arm gripper"]	= "[0.0..1.0] fraction open";
	help_context["arm wrist"] 	= "Bend wrist to given angle.";
	help_params["arm wrist"]	= "x y z in mm";
	help_context["arm rotate"] 	= "Rotate the base keep other servos fixed (except wrist rotate which follows the base).";
	help_params["arm rotate"]	= "[0.0..180.0] angle in degrees";

	help_context["leg xyz"] 	= "Move the ankle to an XYZ coordinate.";
	help_params["leg xyz"]	 	= "x y z in mm";
	help_context["bend foot"] 	= "Bend wrist to given angle.";
	help_params["bend foot"]	= "x y z in mm";

	cmd_help["System"]	  = help_context;
	param_help["System"]  = help_params;
}
function init_Directive_help()
{
	var help_context = { };
	var help_params  = { };		
	help_context["delay"] 		= "Pause sequence for a while";
	help_params ["delay"] 			= "number of milliseconds";

	help_context["goto"] 		= "Pause sequence for a while";
	help_params ["goto"] 			= "number of milliseconds";

	help_context["range"] 		= "Pause sequence for a while";
	help_params ["range"] 			= "number of milliseconds";

	help_context["if_less_than"] 	= "Pause sequence for a while";
	help_params ["if_less_than"] 	= "number of milliseconds";

	help_context["if_greater_than"] = "Pause sequence for a while";
	help_params ["if_greater_than"] = "number of milliseconds";


	cmd_help["Directive"]	= help_context;
	param_help["Directive"] = help_params;
}

function init_help()
{
	init_drivefive_help();

	init_aniEyes_help();
	init_loadcell_help();
	init_IOexpander_help();
	
	init_System_help();
	init_Directive_help();
}

