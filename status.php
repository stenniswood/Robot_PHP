<div id="Status" class="tabcontent">

<div class="tab">
  <button class="tablinks2" onclick="openStatusPage(event, 'StatusOverview')">Overview</button>
  <button class="tablinks2" onclick="openStatusPage(event, 'Controller')">Wireless Controller</button>
</div>

<div id="StatusOverview" class="tabcontent2">
 <fieldset>
  <legend>Devices:</legend>
  <?php FormDeviceTable(); ?>  
  <?php //print_device_table();  ?>
 </fieldset>
</div>


<div id="Controller" class="tabcontent2" style="display:none" > 
 <fieldset>
	<legend>Devices:</legend>
	<?php include "ps4_controller_status.php"; ?>
 </fieldset>
</div>

</div>

<script>
function InitializeStatusPage() 
{
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
