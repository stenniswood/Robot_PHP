<?php
   define('BASE_DIR', "/var/www/html/");
   
   require_once(BASE_DIR.'/config.php');
?>


<div id="Camera" class="tabcontent">
  <h3>Cam View</h3>
  
  <div class="container-fluid text-center liveimage">
  #<iframe src='http://192.168.1.114:8081' style='border:none; width:640; height:480;' frameborder='0'></iframe>
  <img id="mjpeg_dest" 		   src="./loading.jpg">
  </div>
  
</div>

