<head>
      <script src="../js/style_minified.js"></script>
      <script src="../js/script.js"></script>
      <script src="../js/pipan.js"></script>


<style>
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
    background-color: #3191C1;
}

</style>
<title>BK Robot</title>
</head>

<?php $_SESSION['active_tab'] = 'System'; ?>
<body >
<h2>Beyond Kinetics Tank Plus</h2>

<div class="tab">
  <button class="tablinks" onclick="openCity(event, 'System')"		 >System</button>
  <button class="tablinks" onclick="openCity(event, 'Configuration')">Robot Configuration</button>
  <button class="tablinks" onclick="openCity(event, 'Status')"		 >Robot Status</button>
  <button class="tablinks" onclick="openCity(event, 'ManualOverrides')">Manual Overrides</button>
  <button class="tablinks" onclick="openCity(event, 'Network')"		>Network</button>
  <button class="tablinks"  onclick="openCity(event, 'Camera')"		>Camera</button>
</div>

<?php include "System.php" ?>
<?php include "configuration.php" ?>
<?php include "manual_override.php" ?>
<?php include "status.php" ?>
<?php include "network.php" ?>
<?php include "camera.php" ?>



<script>
function InitializeStatusPage() {
	//openCity(event, "<?php echo $_SESSION['active_tab']; ?>" );
};
function openCity(evt, cityName) {
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
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
};
//var tabs = document.getElementsByClassName("tablinks");
//var event.currentTarget = tabs[0];
</script>

</body>
</html>
