
<div id="Network" class="tabcontent">

<fieldset>
<legend>Summary:</legend>
<table>
<tr><td>IP Address</td><td><?php echo $_SERVER['SERVER_ADDR']; ?></td></tr>
<tr><td>Connecting from </td><td><?php echo $_SERVER['REMOTE_ADDR']; ?></td></tr>
<tr>
<td width="200px">Current Time</td><td><?php echo date('Y-m-d H:i:s'); ?></td>
</tr><tr>
<td>Battery Level    </td><td><?php echo "13.6v       88.5%"; ?></td>
</tr><tr>
<td>Wifi Strength</td><td><?php echo "78.5%"; ?></td>
</tr>
<tr>
<td>Wifi SSID</td><td>FastKinetics_5Ghz</td>
</tr>
</table>
</fieldset>


<fieldset>
<legend>Connections:</legend>

<table><tr>
<th width="200">Name</th><th>Status</th><th width="200">Elapsed Time</th><th>Task<th></tr>
<tr><td>Viki</td><td width="300">Registered</td><td><?php echo date('Y-m-d H:i:s'); ?></td><td></td></tr>
<tr><td>Ronnie</td><td width="300">Connected</td><td><?php echo date('Y-m-d H:i:s'); ?></td><td>awaiting alert</td></tr>
<tr><td>Drone 1</td><td><?php echo "surveillance_2"; ?></td><td><?php echo date('Y-m-d H:i:s'); ?></td><td>Scanning Area</td></tr>
<tr><td>Robot 2</td><td><?php echo "via Viki"; ?></td><td><?php echo date('Y-m-d H:i:s'); ?></td><td>Transporting package</td></tr>
<tr><td>Drone</td><td><?php echo "via Viki"; ?></td><td><?php echo date('Y-m-d H:i:s'); ?></td><td>Flying errand</td></tr>
</table>
</fieldset>


<button class="btn restart" onclick="/home/pi/bk_code/joystick_test/joys" >Restart Robot Service</button>
<button class="btn stop" >Stop</button>
</div>

