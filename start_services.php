
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
			if (count($out)==0)
				$_SESSION["mechatronic_status"] = "Not running";
			else 
				$_SESSION["mechatronic_status"] = "Running";
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
			echo_array($result);
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

	//$result = read_mechatronic_status();
	//echo_array($result);
?>
