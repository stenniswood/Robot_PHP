<?php
function get_boottime() {
    //$tmp = explode(' ', file_get_contents('/proc/uptime'));
    //return time() - intval($tmp[0]);
    
    $uptime = trim( shell_exec( 'uptime' ) );
	// output is 04:47:32 up 187 days,  5:03,  1 user,  load average: 0.55, 0.55, 0.54
	$uptime = explode( ',', $uptime );
	$uptime = explode( ' ', $uptime[0] );

	$uptime = $uptime[2] . ' ' . $uptime[3]; // 187 days
	return $uptime;
}

?>

<div id="System" class="tabcontent" background-color="#2f3f9F">

 <fieldset>
  <legend>System:</legend>
<table>
<tr>
<td>Robot Type:</td><td>Wheeled</td>
</tr><tr>
<td>Model</td><td>TankPlus</td>
</tr><tr>
<td>HostName</td><td><?php echo gethostname(); ?></td>
</tr><tr>
<td>Email</td><td><input type="text"></td>
</tr><tr>
<td>Date of birth</td><td><input type="text"></td>
</tr><tr>
<td>Software Version</td><td>1.0</td>
</tr><tr>
<td>Vision Partner RPI </td><td>IP address:
<input id='vision_rpi_ipaddress' value='192.168.1.116'</td>
</tr><tr>
<td>Auxiliary Features:</td><td>(Audio, LaserPointDistances )</td> 
</tr><tr>
<td>Page Load Time</td><td><?php echo date('Y-m-d H:i:s'); ?></td>
</tr><tr>
<td>System Boot Time</td><td><?php echo get_boottime(); ?></td>
</tr></table>
 </fieldset>

<fieldset>
  <legend>Memory:</legend>
  <table>
  <tr><th width="100">Type</th><th>Capacity</th></tr>
  <tr><td>SDCard</td><td><?php 
  setlocale(LC_NUMERIC, "");
  echo sprintf("%1.0f MB / ", disk_free_space( "/" )/1000000);
  echo sprintf("%1.0f MB",    disk_total_space("/")/1000000 );  ?></td></tr>
  <tr><td>RAM</td><td>
  <?php
	function getSystemMemInfo()
	{
		$data = explode("\n", file_get_contents("/proc/meminfo"));
		$meminfo = array();
		foreach ($data as $line) {
			list($key, $val) = explode(":", $line);
			$meminfo[$key] = trim($val);
		}
		return $meminfo;
	}
	$memArray = getSystemMemInfo(); 
	echo sprintf("%1.0f / %1.0f MB", $memArray["MemAvailable"]/1000, $memArray["MemTotal"]/1000 );
  ?></td></tr>
  <tr><td>Harddrive</td><td>1TB onboard</td></tr>  
  <tr><td>Harddrive NFS</td><td>4TB</td></tr>    
  </table>
 </fieldset>


</div>
