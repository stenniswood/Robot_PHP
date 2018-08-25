

<h1>Robot Signals:</h1>
	<table id="variables" border="1" >
	<thead><tr>
	<th width="150" >Signal Name</th>	
	<th width="150">Board Name</th>
	<th width="150">Eliciting Cmd</th>
	<th width="60">Response Index</th>
	<th width="200">Type</th>
	<th width="125">Buttons</th>
	</tr></thead>

	<tr>
	<td><input  id="NewSignalName"></input></td>
	<td><select id="SignalBoardSelection"><options></options></select></td>
	<td><select id="Eliciting_cmd"></select></td>
	<td><input  id="ResponseIndex"></input></td>
	<td><select id='signal_type'>
	<option>Periodic</option>
	<option>Sequencer Gen</option>
	<option>Triggered </option>
	<option>Internal Variable</option>
	</select>
	<input id="SignalTypeParameter"></input>
	</td>
	<td>
	<button name="Add_input" onclick="handle_add_input();">
	<img src="icons/plus.png"  style="width:25px;height:25px;">
	</button>

	</td>
	</tr>
	</table>
	


<script>
	var var_table = document.getElementById( "variables");	
	var tableRows = var_table.getElementsByTagName('tr');
//	var InputVars = [];
	var InputVars = <?php echo json_encode( $InputVars);  	?>; 
	
	var board_sel = document.getElementById( "SignalBoardSelection" );
	//var inputs 		= document.getElementById("input_controls");
	var signal_sel 	= document.getElementById("signal_type");
	var var_nam      = document.getElementById( "NewSignalName" );
	var response_idx = document.getElementById( "ResponseIndex"   );
	var period_inp   = document.getElementById( "SignalTypeParameter"  );
	
	init_Inputvars_datasets();
	populate_var_table();
	populate_all_devices_select();
	
	function find_input_variable(name)
	{
		var retval = -1;
		InputVars.forEach( (variable,index) => {
			if (variable['name']==name)
				retval = index;
		});
		return retval;
	}
	function populate_all_devices_select()
	{
		var opt;
		all_devices.forEach( (variable,index) => {
			opt = document.createElement('option');
			opt.text = variable['name'] +","+ variable['type'] +","+ variable['path'];
			board_sel.add(opt);
		});
	}
	function extract_vartable_row(rowIndex)		// for saving
	{
		var text = {};
		var num_rows = var_table.rows.length;

		text['name']    	= var_table.rows[rowIndex].cells[0].innerHTML;
		var model_name_device    	= var_table.rows[rowIndex].cells[1].innerHTML;
		var splitup 		= model_name_device.split(",");
		text['board_name']  = splitup[0];
		text['model']  		= splitup[1];		
		text['device']  	= splitup[2];

		text['eliciting_cmd']  = var_table.rows[rowIndex].cells[2].innerHTML;
		text['response_index'] = var_table.rows[rowIndex].cells[3].innerHTML;
		text['signal_type']    = var_table.rows[rowIndex].cells[4].innerHTML;			
		return text;
	}

	function add_user_variable( info )
	{
		InputVars.push(info);
		add_dataset( InputVars.length-1 );
		populate_var_table();
	}
	function populate_var_table()
	{
		// REMOVE ALL TABLE ROWS:
		var rowCount = tableRows.length;
		for (var x=rowCount-2; x>0; x--) {
		   var_table.deleteRow(x);
		}
		/* if length==2, then want insert at 1
		if length==3, then want insert at 2
		if length==4, then want insert at 3*/
		InputVars.forEach( (variable,index) => {
			var last_row = var_table.rows.length;
			var row = var_table.insertRow(last_row-1);
			var cell0 = row.insertCell(0);
			var cell1 = row.insertCell(1);				
			var cell2 = row.insertCell(2);
			var cell3 = row.insertCell(3);			
			var cell4 = row.insertCell(4);
			var cell5 = row.insertCell(5);						
			//var cell6 = row.insertCell(6);	
		
			cell0.innerHTML = variable["name"];
			cell1.innerHTML = variable["board_name"]+", "+variable["model"]+", "+variable["device"];
			cell2.innerHTML = variable["eliciting_cmd"];
			cell3.innerHTML = variable["response_index"];
			cell4.innerHTML = variable["signal_type"];			
			
			// Want the InputVars index; which is zero indexed;
			//   so subract the header and button rows. so row 1 ==> InputVars[0]	
			
			var graph_check = "<input type='checkbox' onclick='show_on_graph(this,"+index+")' value=\"Graph\">Graph</input>";
			var read_now_b  = "<button id='read_now' onclick='elicit_response("+index+")' >Read Now</button>";
			var delete_b = "<img src='icons/redcross.png' alt='X' onclick='handle_delete_input()' style='width:20px;height:20px;'>";
			cell5.innerHTML = graph_check+read_now_b+delete_b;
			
		});
	}
	
	function usb_request_input_ajax(device_path, data, InputVarIndex) 
	{	
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
			  var results = this.responseText.split("\t");
			  var pindex  = InputVars[InputVarIndex]['response_index'];
			  InputVars[InputVarIndex]['latest_value'] = results[pindex];
			  var str = results[pindex].trim();
			  // Add to Dataset : 
			 // newDataset.data.push(  );
			  add_data( InputVarIndex, str );  
			}
		};  
	
		var payload;
		payload = "path="+device_path;
		payload += "&data="+data;

		xhttp.open("POST", "usb_send_receive.php", true);		 
		xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhttp.send(payload);	
	}
	
	function elicit_response(index)
	{
		var devs = InputVars[index]["device"];
		var cmd  = InputVars[index]["eliciting_cmd"];
		usb_request_input_ajax( devs, cmd, index );		
	}
	//var Input_timers={};
	function start_periodic_reads()
	{
		InputVars.forEach( (variable,index) => {
			var splitted = variable["signal_type"].split(" ");
			if ( splitted[0]=="Periodic" )
			{			
				InputVars[index]['interval_timer'] = setInterval( 
					function() { elicit_response( index );  }, splitted[1] );
			}
		});
	}

	function stop_periodic_reads()
	{
		InputVars.forEach( (variable,index) => {
			var splitted = variable["signal_type"].split(" ");
			if ( splitted[0]=="Periodic" )
			{
				clearInterval( InputVars[index]['interval_timer'] );
			}
		});
	}

	function handle_var_row_click()
	{	
			table.rows[selected_row].childNodes[7].innerHTML = 
			"<img src='explanation_mark.png' alt='X' onclick='perform_now()' style='width:25px;height:25px;'><img src='redcross.png' alt='X' onclick='handle_delete()' style='width:25px;height:25px;'>";
	}	

	function handle_add_input()
	{
		var new_entry = {};

			var str = board_sel.selectedOptions[0].innerHTML.trim();
			var split = str.split(",");
			new_entry["board_name"] = split[0];
			new_entry["model"]  	= bm_sel.selectedOptions[0].innerHTML;		
			new_entry["name"]   	= var_nam.value;
			new_entry["response_index"]  = response_idx.value;
			new_entry["eliciting_cmd"]   = extract_full_command_from_controls( bm_sel.selectedOptions[0].innerHTML );;
			new_entry["signal_type"]     = signal_sel.selectedOptions[0].innerHTML +" "+ period_inp.value;			
			if (bs_sel.selectedOptions.length)
			{
				var devIndex=-1;
				var i;
				for (i=0; i<all_devices.length; i++)
				{
					if (all_devices[i]["name"]==new_entry["board_name"])
						devIndex = i;
				} 
				if (i>=0)
					new_entry["device"]  = all_devices[devIndex]['path'];
				else 
					new_entry["device"] = "NotFound!";
			}

			add_user_variable( new_entry );	
	}
	
	function handle_delete_input()
	{
		
	}
	
</script>