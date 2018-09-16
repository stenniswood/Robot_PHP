<?php
   define('BASE_DIR', "/var/www/html/");
   
   require_once(BASE_DIR.'/config.php');
   
   //$soundfile = "/var/www/html/robot/Summer_snow.mp3";
   //echo "<embed src =\"$soundfile\" hidden=\"true\" autostart=\"true\"></embed>";
   
?>


<div id="Camera" class="tabcontent">
  <h3>Cam View</h3>
  <img id="mjpeg_dest" />
</div>


<script>
var mjpeg_img;

function reload_img () {
  mjpeg_img.src = "../cam_pic.php?time=" + new Date().getTime();
}
function error_img () {
  setTimeout("mjpeg_img.src = '../cam_pic.php?time=' + new Date().getTime();", 100);
}
function init() {
  mjpeg_img = document.getElementById("mjpeg_dest");
  mjpeg_img.onload = reload_img;
  mjpeg_img.onerror = error_img;
  reload_img();
}
</script>
