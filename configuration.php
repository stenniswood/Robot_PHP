<?php include "start_services.php";  ?>

<div id="Configuration" class="tabcontent">

 <fieldset>
  <legend>Services:</legend>
	<form action="index.php" method="post" >
	<button class="checkbox" >Autonomous Mode</button><br>
	<button class="checkbox" >Manual PS4 Mode</button><br>
	<table>
	<tr><th width="120">Service</th><th>Status</th><th width="50">Log</th></tr>

	<tr><td><input  class="btn_restart" type="checkbox" width="50" id="mechatronic" name="mechatronic" value="start" >Start Mechatronic Module</td>
	<td><?php echo $_SESSION["mechatronic_status"]?></td>
	<td>	<button class="btn_log" id="viewmechatroniclog" >View Mechatronic Log</button></td></tr>
	<tr><td><input class="btn_restart" width="10" type="checkbox" id="vision_sense" name="vision_sense">Start Vision Sense</input></td>
	<td><?php echo $_SESSION["vision_sense_status"]?></td>
	<td>    <button class="btn_log" id="viewvisionsenselog" >View VisionSense Log</button></td></tr>
	<tr><td><input class="btn_restart" width="110" type="checkbox" id="battery_monitor" name="battery_monitor" >Start Battery Monitor</input></td>
	<td><?php echo $_SESSION["battery_monitor_status"]?></td>
	<td>    <button class="btn_log" id="viewbatterymonitorlog" >View BatteryMonitor Log</button></td></tr>	
	<tr><td><input class="btn_restart" width="110" type="checkbox" id="remote_video" name="remote_video" >Start Remote Video </input></td>
	<td><?php echo $_SESSION["remote_video_status"]?></td>
	<td>    <button class="btn_log" id="viewremotevideolog" >View RemoteVideo Log</button></td></tr>
	<tr><td><button class="btn stop" >Stop All</button></td>
	<td>	<input type="submit" class="btn_restart" value="Activate Now"></td></tr>
	</table>
	</form>
 </fieldset>


<?php include "device_list.php";  ?>

<script>
	var all_devices        = <?php echo json_encode( $deviceInfo );  	?>;
	var drive_five_boards  = <?php echo json_encode( $drive_fives );  	?>;
	var load_cell_boards   = <?php echo json_encode( $load_cells  );	?>;
	var ani_eyes_boards	   = <?php echo json_encode( $ani_eyes);  		?>;
	var io_expander_boards = <?php echo json_encode( $io_expanders);  	?>; 
	var InputVars = <?php echo json_encode( $InputVars);  	?>; 

</script>


 <fieldset>
  <legend>Devices:</legend>
	<?php FormDeviceTable(); ?>
	<br>
 </fieldset>  

</div>
