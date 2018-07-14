<div id="Status" class="tabcontent">


<div class="tab">
  <button class="tablinks2" onclick="openStatusPage(event, 'StatusOverview')">Overview</button>
  <button class="tablinks2" onclick="openStatusPage(event, 'Controller')">Wireless Controller</button>
</div>



<div id="StatusOverview" class="tabcontent2">


 <fieldset>
  <legend>Devices:</legend>
	<p>
	<select id="Device">
	<option value="0" selected>(please select:)</option>
	<option value="1">DriveFive</option>
	<option value="2">Power Distributor</option>
	<option value="3">PiCamScan</option>
	<option value="4">Eyes</option>
	<option value="other">other</option>
	</select>
	Device Name: <input type="text" id="devpath" value="/dev/usbtty0" width="80" >
	<button  onclick="ListDevices()"> + Add board</button><br>
	</p>
  
	<div id="demo2" width="80"  >Hello Steve!</div>
	<form action="index.php">
	<input name="save_devs" type="hidden" value="save"></input>
	<button type="submit" method="post" value="submit">Save</button>
	</form>
 </fieldset>

<fieldset>
  <legend>Devices:</legend>
  <table><tr>
  <th width="40"></th><th width="150">Path</th><th width="150">Name</th><th width="300">Status</th>
  </tr>
  <?php
  	$deviceInfo["Dev"]    = "/dev/ttyUSB0";
  	$deviceInfo["Name"]   = "DriveFive";
  	$deviceInfo["Status"] = "Fully Operational";
  	
  	for ($i=0; $i<10; $i++)
  	{
  		echo "<td>$i</td><td>".$deviceInfo["Dev"]."</td><td>".$deviceInfo["Name"]."</td><td>".$deviceInfo["Status"]. "</td></tr>";

  	}
  ?>
  </table>
</fieldset>

</div>


<div id="Controller" class="tabcontent2">
Hello PS4 Controller:<br>
</div>

</div>

<script>

function InitializeStatusPage() {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent2");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

}

function openStatusPage(evt, cityName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent2");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks2" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks2");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}
</script>
