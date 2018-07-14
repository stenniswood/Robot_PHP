<script>
var device = [];

function addDevice() {
    var dp = document.getElementById("devpath");
    var e  = document.getElementById("Device");
    var dev = {pathname:"none", deviceName:"n.a."};
    dev["pathname"] = dp.value;
    dev["deviceName"] = e.options[e.selectedIndex].text;
    device.push( dev );
    //device.push( {pathname: dp.value, deviceName:"dev/"} );
}

function ListDevices() {
	var i=0;
	var text="<table>";
	text += "<tr><th width=\"40\">#</th><th width=\"150\">Device Path</th><th width=\"150\">Board Type</th></tr>";
	for (i=0; i<device.length; i++)
	{
		text += "<tr><td>"+i+"</td><td>"+device[i]["pathname"] +"</td><td>"+ device[i]["deviceName"] + "</td></tr>";
	}
	text+= "</table>"
	document.getElementById("demo2").innerHTML = text;
}
function writetoFile() { // Should be PHP - server side.
	var i=0;
	var text="<table>";
	text += "<tr><th width=\"40\">#</th><th width=\"150\">Device Path</th><th width=\"150\">Board Type</th></tr>";
	for (i=0; i<device.length; i++)
	{
		text += "<tr><td>"+i+"</td><td>"+device[i]["pathname"] +"</td><td>"+ device[i]["deviceName"] + "</td></tr>";
	}
	text+= "</table>"
	document.getElementById("demo2").innerHTML = text;
}
function myFunction() {
	document.getElementById("demo2").innerHTML = "Hello World";
}
</script>

<?PHP
    $_SESSION['active_tab'] = 'configuration';
    
	function read_ini( $fname )
	{
		$myfile = fopen( $fname, "r" ) or die("Unable to open file!");		
		$txt    = fread($myfile, filesize($fname) );
		//document.getElementById("demo2").innerHTML = text;
		fclose($myfile  );
	}

	/*function save_ini( $fname )
	{
		$myfile = fopen( $fname, "w" ) or die("Unable to open file!");
		var i=0;
		for (i=0; i<device.length; i++)
		{
			$txt += i+" "+device[i]["pathname"] +" "+ device[i]["deviceName"] + "\n";
			fwrite($myfile, $txt );
		}

		fclose($myfile  );
	}*/

	$_SESSION["mechatronic_status"]     = "Not started";
	$_SESSION["vision_sense_status"]    = "Not started";
	$_SESSION["battery_monitor_status"] = "Not started";
	$_SESSION["remote_video_status"]    = "Not started";

	function echo_array($out) {
			for ($i=0; $i<count($out); $i++)
			{
				echo $out[$i]."<br>";
			}	
	}
	function read_status() {
			$retval = exec('ps -u www-data', $out);
			$_SESSION["all_status"] = $out;
			return $out;
	}
	function read_mechatronic_status() {
			$retval = exec('ps -u www-data | grep joys', $out);
			$_SESSION["all_status"] = $out;
			return $out;
	}
	
	function read_vision_status() {
			$retval = exec('ps -au | grep xeyes', $out);
			for ($i=0; $i<count($out); $i++)
			{
				echo $out[$i]."<br>";
			}
			$_SESSION["all_status"] = $out;
	}

	function start_mechatronics() {
			$retval = exec('/home/pi/bk_code/joystick_test/joys &> /dev/null &', $out);
			$_SESSION["mechatronic_status"] = $retval;
	}
	function start_vision_sense() {
			exec('/home/pi/bk_code/xeyes/xeyes > visionsense.log', $out);
			//echo "catting color_test.c...";
			//$status = exec('/home/pi/bk_code/cat color_test.c > vision.txt', $out);
			//echo $status;
			//echo "<br>";
			
			$_SESSION["vision_sense_status"] = $status;
	}
	function start_batterymonitor() {
			//exec('/home/pi/bk_code/joystick_test/joys > battery.log', $out);
			$_SESSION["battery_monitor_status"] = "Running";
	}
	function start_remotevideo() {
			//exec('/home/pi/bk_code/joystick_test/joys > remote_video.log', $out);
			$_SESSION["remote_video_status"] = "Running";			
	}
	function start_ds4() {
			exec('ds4drv > /dev/null &', $out);
	}
	function stop_all() {
			$_SESSION["mechatronic_status"]     = "Stopped";
			$_SESSION["vision_sense_status"]    = "Stopped";
			$_SESSION["battery_monitor_status"] = "Stopped";
			$_SESSION["remote_video_status"]    = "Stopped";						
			
	}
	// VIEW LOGS FUNCTIONS:
	function view_log_mechatronics() {
			exec('cat mechatronic.log', $out);
			$_SESSION["mechatronic_status"] = "Running";
	}
	function view_log_vision_sense() {
			exec('cat visionsense.log', $out);
			$_SESSION["vision_sense_status"] = "Running";			
	}
	function view_log_batterymonitor() {
			exec('cat battery.log', $out);
			$_SESSION["battery_monitor_status"] = "Running";
	}
	function view_log_remotevideo() {
			exec('cat remote_video.log', $out);
			$_SESSION["remote_video_status"] = "Running";			
	}
	function view_log_ds4() {
			exec('ds4drv', $out);
	}

	if (isset($_POST["save_devs"]))
	{
		echo "Data is saved!";
	}
	if (isset($_POST["mechatronic"]))
	{
		start_mechatronics();
	}	
	if (isset($_POST["vision_sense"]))
	{
		start_vision_sense();
	}	
	if (isset($_POST["battery_monitor"]))
	{
		start_batterymonitor();
	}	
	if (isset($_POST["remote_video"]))
	{
		start_remotevideo();
	}

	$result = read_mechatronic_status();
	//$result = read_status();
	echo_array($result);
?>

<div id="Configuration" class="tabcontent">

 <fieldset>
  <legend>Services:</legend>
	<form action="index.php" method="post" >
	<button class="checkbox" >Autonomous Mode</button><br>
	<button class="checkbox" >Manual PS4 Mode</button><br>
	<table>
	<tr><th width="120">Service</th><th>Status</th><th width="150">Log</th></tr>

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


 <fieldset>
  <legend>Devices:</legend>
	<p>
	<select id="Device">
	<option value="0" selected>(please select:)</option>
	<option value="1">DriveFive</option>
	<option value="2">Power Distributor</option>
	<option value="3">PiCamScan</option>
	<option value="4">Eyes</option>
	<option value="5">Load cells</option>	
	<option value="other">other</option>
	</select>
	Device Name: <input type="text" id="devpath" value="/dev/usbtty0" width="80" >
	<button  onclick="addDevice(); ListDevices()"> + Add board</button><br>
	</p>
  
	<div id="demo2" width="80"  >Hello Steve!</div>
	<form action="index.php">
	<input name="save_devs" type="hidden" value="save"></input>
	<button type="submit" method="post" value="submit">Save</button>
	</form>
 </fieldset>
  

</div>
