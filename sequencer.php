<div id="Sequencer" class="tabcontent">

<fieldset>
  <legend id="result_text" >Master Schedule:</legend>
	<table id="schedule" border="1" ><tr>
	<th width="150" >Date</th>	
	<th width="150">Time</th>
	<th width="200">Operation</th>
	<th width="200">Status</th></tr>
	<tr><td>Today</td><td>3:35pm</td>
	<td>Start sequence #3</td>
	<td>Awaiting</td></tr>
	</table>
</fieldset>


<script src="sequencer_help.js" >

</script>

<?php include "sequencer_load_save.php";

	//init_junk_sequence();
	//save_sequence("MY_SEQEUENCES.SEQ");	
	load_sequence("MY_SEQUENCES.SEQ");	
?>

<script>
	var selected_row = 1;
	var mmicro_seq = <?php echo json_encode($MicroSeq);  ?>
	
	function move_indicator( old_row, new_row )
	{
		var table = document.getElementById('sequence');
		// Remove the icons from previous placement:
		if (old_row) {
			table.rows[old_row].childNodes[0].innerHTML = "";
			//table.rows[old_row].childNodes[7].innerHTML = "";
		}
		// Determine new active row:
		//selected_row = event.target.parentElement.rowIndex;
		
		if (new_row) {
			// Add icons: ->, ! icon and a X for "perform now" and "delete"
			table.rows[new_row].childNodes[0].innerHTML = "<img src='icons/RightRedArrow.png' alt='->' style='width:20px;height:15px;' />";
			//table.rows[new_row].childNodes[7].innerHTML = 
			//"<img src='explanation_mark.png' alt='X' onclick='perform_now()' style='width:25px;height:25px;'><img src='redcross.png' alt='X' onclick='handle_delete()' style='width:25px;height:25px;'>";
		}		
	}

	function handle_table_row_click(event) 
	{
		var table = document.getElementById('sequence');
		// Remove the icons from previous placement:
		if (selected_row) {
			table.rows[selected_row].childNodes[0].innerHTML = "";
			table.rows[selected_row].childNodes[7].innerHTML = "";
		}
		// Determine new active row:
		selected_row = event.target.parentElement.rowIndex;
		
		if (selected_row) {
			// Add icons: ->, ! icon and a X for "perform now" and "delete"
			table.rows[selected_row].childNodes[0].innerHTML = "<img src='icons/RightRedArrow.png' alt='->' style='width:20px;height:15px;' />";
			table.rows[selected_row].childNodes[7].innerHTML = 
			"<img src='explanation_mark.png' alt='X' onclick='perform_now()' style='width:25px;height:25px;'><img src='redcross.png' alt='X' onclick='handle_delete()' style='width:25px;height:25px;'>";
		}
	}
</script>

<fieldset background="icons/real_sequencing.png"  >
  <legend>Micro Sequence:</legend>
	<table id="sequence" onclick="handle_table_row_click(event)" border="1" opacity="0.4" ><tr>
	<th width="10"  align="left"></th>
	<th width="10"  align="left">Step</th>
	<th width="50"  align="left">Label</th>	
	<th width="100" align="left">Type</th>
	<th width="100" align="left">Model</th>	
	<th width="300" align="left">Action</th>
	<th width="200" align="left">Device</th>
	<th width="200" align="left">Buttons</th>	</tr>	
	<?php
		$i=0;
		//var_dump($MicroSeq);		echo "<br>";
		foreach ($MicroSeq as $row)
		{
			//var_dump($row);
			echo "<tr ondblclick=\"handle_table_row_click(event)\">";
			if ($selected_row==$i)
				echo "<td><img src=\"icons/RightRedArrow.png\"  style=\"width:20px;height:15px;\"> "."</td>";
			else
				echo "<td></td>";
			echo "<td onclick=\"handle_table_row_click(event)\">".$i."</td>";
			echo "<td ondblclick='handle_on_change_label(event)'>".$row["label"]."</td>";
			echo "<td>".$row["type"]."</td>";
			echo "<td>".$row["model"]."</td>";
			echo "<td ondblclick='handle_on_change_action(event)'>".$row["action"]."</td>";
			echo "<td>".$row["device"]."</td>";
			echo "<td></td>";
			echo "</tr>";
			$i++;
		}
	?>
<tr><td></td><td><?php $i+1; ?></td><td></td>
<td><select id="select_type" onchange="configure_type(this.value)" >
	<option value="1" selected>Command</option>
	<option value="2" selected>Input</option>		
	<option value="3" selected>Directive</option>	
	<option value="4" selected>System</option>		
</select>
<button id="delay_button" >
<img src='icons/delay.png' alt='dt' onclick='handle_add_delay()' style='width:25px;height:25px;'>
</button>
</td>
<td><select id="board_model" selected="1" onchange="configure_model(this.value)" >
	<option value="1" selected>Drive Five</option>
	<option value="2" >Ani-Eyes</option>	
	<option value="3" >Load-cell</option>		
	<option value="4" >IO Expander</option>	
</select></td>

<td>
<span>
<select id="drive_five_cmds" onchange="cmd_change('Drive Five')" selected="1" >
	<option value="1" selected>PWM</option>
	<option value="2" >PID</option>	
	<option value="3" >read status</option>		
	<option value="4" >read current</option>	
	<option value="5" >read speed </option>
	<option value="6" >read position</option>
	<option value="9" >stop</option>	
	<option value="7" >forward</option>		
	<option value="8" >backward</option>	
	<option value="10" >spin</option>	
	<option value="11" >set wheel diameter</option>	
	<option value="12" >set wheel separation</option>	
	<option value="13" >set counts_per_rev</option>
</select>

<select id="eye_cmds" onchange="cmd_change('Ani-Eyes')" selected="1">
	<option value="0" >(ani-eyes select:)</option>
	<option value="1" >look left</option>
	<option value="2" >look right</option>
	<option value="3" >look up</option>
	<option value="4" >look down</option>
	<option value="5" >straight</option>		
	<option value="6" selected>look at</option>
	<option value="7" >left look at</option>	
	<option value="8" >right look at</option>		
	<option value="9" >close eyes</option>			
	<option value="10" >open eyes</option>			
	<option value="11" >blink</option>			
	<option value="12" >roll eyes</option>			
	<option value="13" >scan horizon</option>				
	<option value="14" >scan up down</option>					
</select>

<select id="load_cell_cmds" onchange="cmd_change('Load-cell')" selected="1">
	<option value="0" >(load cell select:)</option>
	<option value="1" selected>send</option>
	<option value="2" >stream</option>	
	<option value="3" >stop</option>	
	<option value="4" >tare</option>		
	<option value="5" >status</option>
</select>	

<select id="io_expander_cmds" onchange="cmd_change('IO Expander')" selected="1">
	<option value="0" >(IO expander select:)</option>
	<option value="1" selected>send</option>
	<option value="2" >stream</option>	
	<option value="3" >mask_ad</option>		
	<option value="4" >mask_di</option>		
	<option value="5" >servo</option>		
	<option value="6" >dwrite</option>			
	<option value="7" >dsend</option>				
	<option value="8" >scan</option>
</select>	

<select id="system_cmds" onchange="cmd_change('System')" selected="1">
	<option value="0" selected>play</option>
	<option value="1" >exec</option>	
	<option value="2" >tts</option>	
	<option value="3" >arm xyz</option>	
	<option value="4" >arm gripper</option>		
	<option value="5" >arm wrist</option>
	<option value="6" >arm raise</option>	
	<option value="7" >arm lower</option>		
	<option value="8" >arm rotate</option>			
</select>	

<select id="new_directive" onchange="cmd_change('Directive')" selected="1">
	<option value="0" >(please select:)</option>
	<option value="1" selected>delay</option>
	<option value="2" >wait for</option>	
	<option value="3" >goto</option>		
	<option value="4" >wait until (n>X)</option>	
	<option value="5" >wait until (n\< X)</option>
	<option value="5" >if (x==true)</option>
	<option value="6" >if (x==false)</option>		
</select>

<p id="parameter">
Parameters:
<input id="parameter_text"></input><br>
Help:<p id="context_help"></p><br>
<p id="parameter_help"></p>
</p>

<p  id="input_controls" >
	Variable Name: 
	<input id="variable_name" ></input><br>
	Reply Index:
	<input id="reply_index" ></input><br>
</p>

</span></td>


<td><select id="board_select" onchange="" selected="1">
	<option value="0" >(board select:)</option>

</select></td>



<td>
<button name="Now" onclick="perform_now();">
<img src='icons/explanation_mark.png' alt='X' onclick='perform_now()' style='width:25px;height:25px;'>
</button>
<button name="Add" onclick="handle_add();">
<img src="icons/plus.png"  style="width:25px;height:25px;">
</button>
<button name="Delete">
<img src="icons/redcross.png" onclick="handle_delete()" style="width:25px;height:25px;">
</button>
</td></tr>

</table>

<button name="Play" onclick="play_sequence(1)">
<img src="icons/play.png"  style="width:25px;height:25px;">
</button>

<button name="Stop">
<img src="icons/stop.png" onclick="stop_sequence()" style="width:25px;height:25px;">
</button>
<button name="Save" onclick="save_ajax('MY_SEQUENCES.SEQ')">
<img src="icons/save.png"  style="width:25px;height:25px;">
</button>
Step Rate:<input id="step_rate" width="50" value="200" >ms</input>

</fieldset>



<script>
	var step_rate_ctrl = document.getElementById( "step_rate" );		

	var seq_table = document.getElementById  ( "sequence"    );
	var type_sel  = document.getElementById  ( "select_type" );
	var bm_sel    = document.getElementById  ( "board_model" );	

	var df_sel 		= document.getElementById("drive_five_cmds");		
	var e_sel  		= document.getElementById("eye_cmds");		
	var lc_sel		= document.getElementById("load_cell_cmds");
	var io_sel 		= document.getElementById("io_expander_cmds");		
	var system_sel 	= document.getElementById("system_cmds");	
	
	var nd_sel 		= document.getElementById("new_directive");
	var bs_sel 		= document.getElementById("board_select" );

	var param_p 	= document.getElementById("parameter" );
	var param 		= document.getElementById("parameter_text" );
	var micro_seq   = [];
	
	var inputs 		= document.getElementById("input_controls");
	var help_ctrls  = document.getElementById("context_help");	
	var help_param_ctrl  = document.getElementById("parameter_help");		
	
	
	inputs.style.display = "none";	
	bm_sel.style.display = "none";
	hide_all_cmds();
	init_help();

	
	function populate_board_select(model)
	{
		// ERASE ALL:
		bs_sel.innerHTML = "";
		var option;
		var board_list;
		switch (model)
		{
		case "Drive Five"	: board_list = drive_five_boards;		break;
		case "Ani-Eyes"		: board_list = ani_eyes_boards;			break;
		case "Load-cell"	: board_list = load_cell_boards;		break;
		case "IO Expander"	: board_list = io_expander_boards;		break;
		default: board_list=[]; 
					option = document.createElement("option");
					option.text = "board select";			// .$opt['name']		
				break;
		}
		var num = board_list.length;
		var i;
		for (i=0; i<num; i++)
		{
			option = document.createElement("option");
			option.text = board_list[i]['path'];			// .$opt['name']
			bs_sel.add(option);
		}
	}
	
	function extract_full_command_from_controls(Model)
	{
		var opt_index=0;
		var cmd_text="";

		//	Which board is selected?
		switch (Model)
		{
		case "Drive Five":		// Drive Five			
			cmd_text  = df_sel.selectedOptions[0].innerHTML;
			break;
		case "Ani-Eyes": 	// Ani-Eyes
			cmd_text  = e_sel.selectedOptions[0].innerHTML;		
			break;
		case "Load-cell":		// Load Cell
			cmd_text  = lc_sel.selectedOptions[0].innerHTML;		
			break;
		case "IO Expander":		// IO Expander
			cmd_text  = io_sel.selectedOptions[0].innerHTML;
			break;
		default:	
			break;
		}
		// Append any text input parameters :
		cmd_text += " ";
		cmd_text += param.value;
		return cmd_text;
	}
	function perform_now()
	{
		
	}
	function handle_delete() 
	{		
		//micro_seq.remove();				
		seq_table.deleteRow(selected_row);
	}
	function handle_add_delay()
	{
		var num_rows        = seq_table.rows.length;
		var new_entry 		= {};
		new_entry["step"]   = num_rows;
		new_entry["label"]  = "";
		new_entry["type"]   = "Directive";
		new_entry["model"]  = "";
		new_entry["action"] = "delay "+ step_rate_ctrl.value;
		new_entry["device"] = "";
		micro_seq.push( new_entry );

		var row = seq_table.insertRow(num_rows-1);
		var cell0 = row.insertCell(0);
		var cell1 = row.insertCell(1);				
		var cell2 = row.insertCell(2);
		var cell3 = row.insertCell(3);
		var cell4 = row.insertCell(4);
		var cell5 = row.insertCell(5);						
		var cell6 = row.insertCell(6);			
		cell1.innerHTML = new_entry["step"];
		cell2.innerHTML = new_entry["label"];		
		cell3.innerHTML = new_entry["type"];
		cell4.innerHTML = new_entry["model"];
		cell5.innerHTML = new_entry["action"];
		cell6.innerHTML = new_entry["device"];	
		play_sound_ajax("junk");	
	}
	
	function handle_add() 
	{		
		var str;		
		var num_rows      = seq_table.rows.length;

		// Extract Text from each SELECT box.
		var type_id       = type_sel.selectedIndex;
		var selected_text =  type_sel.selectedOptions[0].innerHTML;

		var new_entry = {};
		new_entry["step"]   = num_rows;
		new_entry["label"]  = "";
		new_entry["type"]   = type_sel.selectedOptions[0].innerHTML;
		new_entry["model"]  = "";
		
		switch ( selected_text )
		{
		case "Command":	// Command
			new_entry["model"]  = bm_sel.selectedOptions[0].innerHTML;
			new_entry["action"] = extract_full_command_from_controls( bm_sel.selectedOptions[0].innerHTML );
			if (bs_sel.selectedOptions.length)
				new_entry["device"] = bs_sel.selectedOptions[0].innerHTML;
			break;
		case "Input": 	// Input		
			new_entry["model"]  = bm_sel.selectedOptions[0].innerHTML;		
			new_entry["action"] = "VARIABLE = 100.00";
			if (bs_sel.selectedOptions.length)
				new_entry["device"] = bs_sel.selectedOptions[0].innerHTML;
			break;
		case "Directive":		// Directive
			
			new_entry["action"] = nd_sel.selectedOptions[0].innerHTML;
			new_entry["action"] += " " + param.value;
			new_entry["device"] = "";
			break;
		case "System":		// System
			new_entry["action"] = system_sel.selectedOptions[0].innerHTML;
			new_entry["action"] += " " + param.value;
			new_entry["device"] = "";
			break;
			
		default:
			break;
		}
		micro_seq.push( new_entry );
		
		var row = seq_table.insertRow(num_rows-1);
		var cell0 = row.insertCell(0);
		var cell1 = row.insertCell(1);				
		var cell2 = row.insertCell(2);
		var cell3 = row.insertCell(3);
		var cell4 = row.insertCell(4);
		var cell5 = row.insertCell(5);						
		var cell6 = row.insertCell(6);			
		cell1.innerHTML = new_entry["step"];
		cell2.innerHTML = new_entry["label"];		
		cell3.innerHTML = new_entry["type"];
		cell4.innerHTML = new_entry["model"];
		cell5.innerHTML = new_entry["action"];
		cell6.innerHTML = new_entry["device"];		
		
		play_sound_ajax("junk");
	}
	function clear_parameter()
	{
		 param.value = "";
	}	
	function cmd_change($model)
	{
		var cmd;
		clear_parameter();
		switch($model)
		{
		case "Drive Five" : cmd = df_sel.selectedOptions[0].innerHTML;
				break;
		case "Ani-Eyes"   : cmd = e_sel.selectedOptions[0].innerHTML;
				break;
		case "Load-cell"  : cmd = lc_sel.selectedOptions[0].innerHTML;
				break;
		case "IO Expander": cmd = io_sel.selectedOptions[0].innerHTML;
				break;
		}

		var type = type_sel.selectedOptions[0].innerHTML;
		if (type=="System")
			cmd = system_sel.selectedOptions[0].innerHTML;

		
		help_ctrls.innerHTML = cmd_help[$model][cmd];		
		help_param_ctrl.innerHTML = param_help[$model][cmd];
	}
	function configure_type(value)
	{
		switch(value)
		{
		case "1": 	// Command
				hide_all_cmds();
				bm_sel.style.display = "inline";
				bs_sel.style.display = "inline";
				inputs.style.display = "none";
				param_p.style.display = "inline";
				val = bm_sel[bm_sel.selectedIndex].value;
				configure_model(val);								
			break;
		case "2":	// Input
				hide_all_cmds();
				bm_sel.style.display = "inline";
				bs_sel.style.display = "inline";
				inputs.style.display = "inline";						
				param_p.style.display = "none";				
			break;
		case "3":	// Directive
				hide_all_cmds();
				inputs.style.display = "none";
				bs_sel.style.display = "none";
				bm_sel.style.display = "none";
				nd_sel.style.display = "block";
				param_p.style.display = "inline";				
			break;
		case "4":	// System
				hide_all_cmds();
				inputs.style.display = "none";
				bs_sel.style.display = "none";
				bm_sel.style.display = "none";
				param_p.style.display = "inline";				
				system_sel.style.display = "inline";		
			break;

		default:
			break;
		}
	}
	function hide_all_cmds()
	{
		nd_sel.style.display = "none";
		df_sel.style.display = "none";		
		e_sel.style.display  = "none";
		lc_sel.style.display = "none";	
		io_sel.style.display = "none";	
		system_sel.style.display = "none";			
	}

	function configure_model(value)
	{
		hide_all_cmds();
		switch(value)
		{
		case "1": 	// Drive Five
			df_sel.style.display = "block";
			//help_ctrls.innerHTML = cmd_help["Drive Five"]["PWM"];
			// Now configure the Device choices select:
			populate_board_select("Drive Five");
			break;
		case "2":	// Ani-Eyes
			e_sel.style.display = "block";
			help_ctrls.innerHTML = cmd_help["Ani-Eyes"]["straight"];
			// Now configure the Device choices select:
			populate_board_select("Ani-Eyes");

			break;
		case "3":	// Load-cell
			lc_sel.style.display = "block";
			help_ctrls.innerHTML = cmd_help["Load-cell"]["send"];
			// Now configure the Device choices select:
			populate_board_select("Load-cell");
			break;
		case "4":	// IO Expander
			help_ctrls.innerHTML = cmd_help["IO Expander"]["send"];		
			io_sel.style.display = "inline";
			// Now configure the Device choices select:
			populate_board_select("IO Expander");
			break;

		default:
			break;
		}
	}

	/*********************
	PLAY SEQUENCE ROUTINES	
	**********************/
	var active_row = 0;
	var NaturalStepSpeed = 100;		// 100ms

	function perform_command(model, action)
	{
		// Maybe don't need this switch.  Just pass the text to the specified board filedescriptor.
		switch(model)
		{
		case "Drive Five":		break;		
		case "Ani-Eyes"	:		break;		
		case "Load-cell":		break;		
		case "IO Expander":		break;	
		default: break;							
		}
	}
	/* Returns a row index */
	function find_label(text)
	{
		var i=0;
		var row_text;
		var num_rows = seq_table.rows.length;
		for (i=0; i<num_rows; i++)
		{
			row_text = seq_table.rows[i].childNodes[2].innerHTML;
			if (row_text==text)
				return i;
		}
		return "not found";
	}
	function extract_table_row(rowIndex)		// for saving
	{
		var text = {};
		var num_rows = seq_table.rows.length;

			text['label']  = seq_table.rows[rowIndex].cells[2].innerHTML;
			text['type']   = seq_table.rows[rowIndex].cells[3].innerHTML;
			text['model']  = seq_table.rows[rowIndex].cells[4].innerHTML;
			text['action'] = seq_table.rows[rowIndex].cells[5].innerHTML;
			text['device'] = seq_table.rows[rowIndex].cells[6].innerHTML;			

		return text;
	}
	

	function raw_text_again(event)
	{
		if (event.key === 'Enter') {
			var editing_row = event.target.parentElement.parentElement.rowIndex;
			var label_input = document.getElementById("new_label");		
			var input_text = label_input.value;
			seq_table.rows[editing_row].childNodes[2].innerHTML = input_text;
		}
	}
	function raw_text_again_action(event)
	{
		if (event.key === 'Enter') {
			var editing_row = event.target.parentElement.parentElement.rowIndex;
			var label_input = document.getElementById("new_action");		
			var input_text = label_input.value;
			seq_table.rows[editing_row].childNodes[5].innerHTML = input_text;
		}
	}

	/* Responder for double click on the label column */
	function handle_on_change_label(event)
	{
		var editing_row = event.target.parentElement.rowIndex;
		
		if (editing_row) {
			// Add icons: ->, ! icon and a X for "perform now" and "delete"
			seq_table.rows[editing_row].childNodes[2].innerHTML = 
			"<input id='new_label' onKeyPress='raw_text_again(event);' />";
		}			
	}
	/* Responder for double click on the label column */
	function handle_on_change_action(event)
	{
		var editing_row = event.target.parentElement.rowIndex;
		
		if (editing_row) {
			// Add icons: ->, ! icon and a X for "perform now" and "delete"
			var text = seq_table.rows[editing_row].childNodes[5].innerHTML;
			seq_table.rows[editing_row].childNodes[5].innerHTML = 
			"<input id='new_action' onKeyPress='raw_text_again_action(event);' value='"+text+"' />";
		}			
	}

	function perform_command(model,action,device)
	{
			//var words = action.split( " ");	
			
			// Ajax send :
			// Find the deviceInfo index.
			usb_send_ajax( device, action );
	}
	function perform_input(action)
	{
			var words = action.split( " ");			
			switch(words[0])
			{
			case "goto":	active_row = words[1];
					break;
			case "delay":	sleep(words[1]);
					break;
			default:
					break;				
			}
	}
	function perform_directive(action)
	{
		var str;
		var line_num=-1;
		var words = action.split(" ");
		var delay;
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
		
		default:
				break;				
		}
	}
	function resume_playback() 
	{
		play_back_paused = false;
	}
	
	function perform_system(action)
	{
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
		case "arm xyz"		:	
				break;
		case "arm gripper"	:				break;
		case "arm wrist"	:				break;
		case "arm raise"	:				break;
		case "arm lower"	:				break;		
		case "arm rotate"	:				break;			
		case "leg xyz"		:	
				break;
		case "bend foot"	:	
				break;

		default:
				break;				
		}
	}

	function perform_row_action(row)
	{
			var Type = row.childNodes[3].innerHTML;
			var action = row.childNodes[5].innerHTML;
			var model  = row.childNodes[4].innerHTML;			
			var device = row.childNodes[6].innerHTML;						
			switch(Type)
			{
			case "Command"	:	perform_command(model,action,device);
					break;
			case "Input"	:	perform_input();
					break;
			case "Directive":	perform_directive(action);
					break;
			case "System"   :	perform_system(action);
					break;
			default:
					break;				
			}
	}
	
	var prev_row = 0;
	var active_row = 1;
	var myVar ;
	var play_back_paused=false;
	var natural_step_time = 500;
	
	function play_sequence(starting_from)
	{
		natural_step_time = step_rate_ctrl.value;
		myVar        = window.setInterval( continueExecution, natural_step_time);
		
	}
	function stop_sequence()
	{
		clearInterval( myVar );	
	}
	
	function continueExecution()
	{
		if (play_back_paused)
			return;

		move_indicator( prev_row, active_row );
		if (active_row >= seq_table.rows.length-1) {
			clearInterval(myVar);
			move_indicator( active_row, 1 );			
			active_row = 1;
			return;
		} else {			
			prev_row = active_row;
			perform_row_action( seq_table.rows[active_row] );
			active_row++;			
		}

	}
	

function save_ajax(filename) 
{
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
		  //document.getElementById("result_text").innerHTML = this.responseText;
		  alert("Sequence Saved!"+this.responseText);
		}
	};  
	
	var payload;
	payload = "Filename="+filename;
	payload += "&operation=Save";

	// Extract the entire Table : 	
	var sl;
	var num_rows = seq_table.rows.length;	
	micro_seq = [];
	var i;
	for (i=1; i<(num_rows-1); i++)
	{
		sl = extract_table_row(i);
		micro_seq.push(sl);
	}	
				
	var j_str = JSON.stringify(micro_seq);
	payload += "&MicroSeqTable="+j_str;

	xhttp.open("POST", "sequencer_load_save.php", true);		 
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	//xhttp.setRequestHeader("Connection", "close");  
	//xhttp.setRequestHeader("Content-length", payload.length);
	xhttp.send(payload);	
}

function usb_send_ajax(device_path, data) 
{	
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
		  //document.getElementById("result_text").innerHTML = this.responseText;
		  //alert("Sequence Saved!"+this.responseText);
		}
	};  
	
	var payload;
	payload = "path="+device_path;
	payload += "&data="+data;

	xhttp.open("POST", "usb_send_receive.php", true);		 
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	//xhttp.setRequestHeader("Connection", "close");  
	//xhttp.setRequestHeader("Content-length", payload.length);
	xhttp.send(payload);	
}

function play_sound_ajax(filename) 
{	
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
		  //document.getElementById("result_text").innerHTML = this.responseText;
		  //alert("Sequence Saved!"+this.responseText);
		}
	};  
	
	var payload;
	payload = "filename="+filename;
	xhttp.open("GET", "php_test.php?"+payload, true);		 
	xhttp.send();	
}

</script>

<?php include "graph_inputs.php"; ?>


</div>





