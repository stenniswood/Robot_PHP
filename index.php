<head>
      <script src="../js/style_minified.js"></script>
      <script src="../js/script.js"></script>
      <script src="../js/pipan.js"></script>


<style>
tr:hover {background-color:#f5f5f5;}


.btn {
    border: none;
    background-color: #efe;
    padding: 8px 18px;
    font-size: 16px;
    cursor: pointer;
    display: inline-block;
}
.btn_restart {
    border: none;
    background-color: lightblue;
    padding: 8px 18px;
    font-size: 16px;
    cursor: pointer;
    display: inline-block;

}
.btn_log {
    border: none;
    background-color: yellow;
    padding: 8px 18px;
    font-size: 16px;
    cursor: pointer;
    display: inline-block;
    width:300;
}

/* On mouse-over */
.btn_log:hover {background: #2f9f2f;}
.btn_restart:hover {background: #2f9f2f;}

.tab {
    overflow: hidden;
    border: 3px solid #ccc;
    color: #FFFFFF;
    background-color: #f14141;
}

/* Style the buttons that are used to open the tab content */
.tab button {
    color: #FFFFFF;
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 16px;
    transition: 0.3s;
}

/* Change background color of buttons on hover */
.tab button:hover {
    background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
    background-color: #F99;
}

/* Style the tab content */
.tabcontent {
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
    background-color: #4191C1;
}
.graphs{
    background-color: #4191C1;
}
</style>
<title>BK Robot</title>
<?php //include "sim_3d_head.php" ?>

</head>

<?php $_SESSION['active_tab'] = 'System'; 
//onload="webGLStart();"
?>
<body   >
<h2>Beyond Kinetics Tank Plus</h2>

<div class="tab">
  <button class="tablinks" onclick="openPage(event, 'System')"		 	>System</button>
  <button class="tablinks" onclick="openPage(event, 'Configuration')"	>Robot Configuration</button>
  <button class="tablinks" onclick="openPage(event, 'Status')"		 	>Robot Status</button>
  <button class="tablinks" onclick="openPage(event, 'ManualOverrides')"	>Manual Overrides</button>
  <button class="tablinks" onclick="openPage(event, 'Sequencer')"		>Sequencer</button>
  <button class="tablinks" onclick="openPage(event, 'Network')"			>Network</button>  
  <button class="tablinks"  onclick="openPage(event, 'Camera')"			>Camera</button>
  <button class="tablinks"  onclick="openPage(event, 'Map')"			>Map</button>
  <button class="tablinks"  onclick="openPage(event, 'Sim3D')"			>3D Sim</button>  
</div>

<?php include "arm_kinematics.php" 	?>
<?php include "System.php" 			?>
<?php include "configuration.php" 	?>
<?php include "manual_override.php" ?>
<?php include "status.php" 			?>
<?php include "sequencer.php" 		?>
<?php include "network.php" 		?>
<?php include "camera.php" 			?>
<?php include "map.php" 			?>
<?php include "sim_3d.php" 			?>

<script>
function InitializeStatusPage() {
	//openCity(event, "<?php echo $_SESSION['active_tab']; ?>" );
}
function openPage(evt, PageName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(PageName).style.display = "block";
    evt.currentTarget.className += " active";
};
//var tabs = document.getElementsByClassName("tablinks");
//var event.currentTarget = tabs[0];
</script>


</body>
</html>

