

<h1>Robot Signals:</h1>
	<table id="variables" border="1" >
	<thead><tr>
	<th width="150" >Signal Name</th>	
	<th width="150">Board Name</th>
	<th width="150">Eliciting Cmd</th>
	<th width="60">Response Index</th>
	<th width="200">Type</th>
	<th width="125">Buttohs</th>
	</tr></thead>
	<tr>
	<td><input id="NewSignalName"></input></td>
	<td><select id="SignalBoardSelection">
	<options></options>
	</select></td>
	<td><select id="Eliciting_cmd">
	</select></td>
	<td><input id="ResponseIndex"></input></td>
	<td><select>
	<option>Periodic</option>
	<option>Sequencer Gen</option>
	<option>Triggered </option>
	</select>
	<input id="SignalTypeParameter"></input>
	</td>
	</tr>
	</table>
	


<script>
	var var_table = document.getElementById( "variables");	
	var tableRows = var_table.getElementsByTagName('tr');
//	var InputVars = [];
	var InputVars = <?php echo json_encode( $InputVars);  	?>; 
	init_Inputvars_datasets();
	populate_var_table();
	
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
		for (var x=rowCount-1; x>0; x--) {
		   var_table.deleteRow(-1);
		}
		InputVars.forEach( (variable,index) => {
			var row = var_table.insertRow(-1);
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
			var index 		= var_table.rows.length-1-1;	// InputVars is zero indexed plus skip the header. so row 1 ==> InputVars[0]
			
			var graph_check = "<input type='checkbox' onclick='show_on_graph(this,"+index+")' value=\"Graph\">Graph</input>";
			var read_now_b  = "<button id='read_now' onclick='elicit_response("+index+")' >Read Now</button>";
			cell5.innerHTML = graph_check+read_now_b;
			
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

</script>