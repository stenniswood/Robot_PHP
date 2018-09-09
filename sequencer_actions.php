<?php  ?>

<script>
	function perform_row_action(row)
	{
			var Type = row.childNodes[3].innerHTML;
			var action = row.childNodes[5].innerHTML;
			var model  = row.childNodes[4].innerHTML;			
			var device = row.childNodes[7].innerHTML;						
			switch(Type)
			{
			case "Command"	:	perform_command(model,action,device);
					break;
			case "Directive":	perform_directive(action);
					break;
			case "System"     :	perform_system(action);
					break;
			case "Left_arm"   :	perform_system(action);
					break;
			case "Right_arm"  :	perform_system(action);
					break;
			case "Left_leg"   :	perform_system(action);
					break;
			case "Right_leg"  :	perform_system(action);
					break;
			default:
					break;				
			}
	}

	function perform_now()
	{
		var Model = bm_sel.selectedOptions[0].innerHTML;
		var cmd   = extract_full_command_from_controls( Model );
		
		var board = bs_sel.selectedOptions[0].innerHTML;
		var dev    = board.split(",");
		
		perform_command(Model,cmd,dev[1]);
	}

	function perform_command(model,action,device)
	{
			// We have to decompose the string so parse any variable names.  ie. $Speed 
			var words = action.split( " ");	
			var cmd = words[0].toUpperCase();
			var respun_str = action;
			
			if (cmd=="PWM")
			{
				words.forEach( (word,index) => {
					if (word[1]=="$")
					{
						var substr = word.substring(1);
						var value = get_value( substr );
						words[index] = words[index][0] + value;
					}
				});

				// Then recompose string.
				respun_str = "";
				words.forEach( (word,index) => {
					respun_str += words[index] + " ";
				});
			}			
			// Ajax send :
			// Find the deviceInfo index.
			usb_send_ajax( device, respun_str );
	}
	function perform_input(action)
	{
	}

	function perform_system(action)
	{
		var servo_angles={};
		var filename;
		var words = action.split( " " );
		switch(words[0])
		{
		case "play":  filename = words[1];
				play_sound_ajax( filename ); 
				break;
		case "exec"			:	
				break;
		case "tts"			:
				break;

		case "l_arm_xyz"		:	var xyz = {};		
				xyz['x']=words[1]; xyz['y']=words[2]; xyz['z']=words[3];				
				INV_XYZ_To_Angles( xyz, servo_angles );
				set_servo_angles_rad( l_arm_meshes, l_grip_meshes, arm_sizes, angle_set );				
				update_object_position(xyz);
				break;
		case "l_arm_gripper":				break;
		case "l_arm_wrist"	:				break;
		case "l_arm_raise"	:				break;
		case "l_arm_lower"	:				break;		
		case "l_arm_rotate"	:				break;			

		case "r_arm_xyz"	:	var xyz = {};		
				xyz['x']=words[1]; xyz['y']=words[2]; xyz['z']=words[3];
				INV_XYZ_To_Angles( xyz, servo_angles );
				set_servo_angles_rad( r_arm_meshes, r_grip_meshes, arm_sizes, angle_set );
				update_object_position(xyz);
				break;
		case "r_arm_gripper":				break;
		case "r_arm_wrist"	:				break;
		case "r_arm_raise"	:				break;
		case "r_arm_lower"	:				break;		
		case "r_arm_rotate"	:				break;			
		
		case "leg_xyz"		:	
				break;
		case "bend_foot"	:	
				break;

		default:
				break;				
		}
	}

	// Variable to be updated if required.
	function perform_directive(action,VarIndex)
	{
		var str;
		var line_num=-1;
		var words = action.split(" ");
		var delay;
		var src_min=0; var src_max=100;
		var dest_min=0; var dest_max=100;
		words.forEach( (word, index) => {
			words[index] = word.trim();
		});
		
		switch(words[0])
		{
		case "goto":	search_label = words[1];
				line_num = find_label(search_label);
				str      = "Goto "+search_label+". line="+line_num+".";
				
				if (line_num!="not found")
					active_row = line_num-1;			// Put 1 above line, because of pos increment operator.
				else
					alert(str);				
				break;
		case "delay":	delay = words[1];
				play_back_paused = true;
				setTimeout( resume_playback, delay );
				break;
		case "range":	// Maps input value to new range.  Can be inverse proportion.

				value = get_value( words[1] );
				src_min  = get_value( words[2] );	src_max  = get_value( words[3] );
				dest_min = get_value( words[4] );	dest_max = get_value( words[5] );
				var range_src  = src_max - src_min;
				var range_dest = dest_max- dest_min;
				// x is y as z is to w
				//(value - src_min)/range_src = (dest_val-dest_min)/range_dest;
				dest_val = (range_dest*(value - src_min)/range_src) + dest_min;	
				InputVars[VarIndex].latest_value = dest_val;
				add_data( VarIndex, dest_val );  			
				break;
		case "control":	// Maps input value to new range.  Can be inverse proportion.
				value    = get_value( words[1] );						
				src_min  = get_value(words[2]);	
				src_max  = get_value(words[3]);
				
				if (value > src_max)	value = src_max;
				if (value < src_min)	value = src_min;
	
				InputVars[VarIndex].latest_value = value;
				add_data(VarIndex, value);
				break;

		case "if_less_than":	// Maps input value to new range.  Can be inverse proportion.
				var value0 = get_value( words[1] );
				var value1 = get_value( words[2] );
				if (value0 < value1)
				{
					line_num = find_label(search_label);
					active_row = line_num-1;			// Put 1 above line, because of pos increment operator.
				}
				break;
		case "if_greater_than":	// Maps input value to new range.  Can be inverse proportion.
				var value0 = get_value( words[1] );
				var value1 = get_value( words[2] );

				if (value0 > value1)
				{
					line_num = find_label(search_label);
					active_row = line_num-1;			// Put 1 above line, because of pos increment operator.
				}
				break;
		case "if_equal":		// Maps input value to new range.  Can be inverse proportion.
				var value0 = get_value( words[0] );
				var value1 = get_value( words[1] );

				if (value0 == value1)
				{
					line_num = find_label(search_label);
					active_row = line_num-1;			// Put 1 above line, because of pos increment operator.
				}
				break;		
		default:
				break;				
		}
	}
	
	
</script>
