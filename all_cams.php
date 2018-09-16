

<!DOCTYPE html>
<html>
  <head>
    <title>Steve's Cams</title>
  </head>
  <body onload="setTimeout('init();', 100);">

      <div style="float:left"><img id="mjpeg_dest" /></div>
      <div><img id="mjpeg_dest2" /></div>      

 </body>
</html>


<script>
	var mjpeg_img;

	function reload_img () {
	  mjpeg_img.src = "../cam_pic.php?time=" + new Date().getTime();
	}
	function reload_img2 () {
	  mjpeg_img2.src = "http://192.168.1.144/html/cam_pic.php?time=" + new Date().getTime();
	}

	function error_img () {
	  setTimeout("mjpeg_img.src = '../cam_pic.php?time=' + new Date().getTime();", 100);
	}
	function init() {
	  mjpeg_img = document.getElementById("mjpeg_dest");
	  mjpeg_img.onload = reload_img;
	  mjpeg_img.onerror = error_img;
	  reload_img();

	  mjpeg_img2 = document.getElementById("mjpeg_dest2");
	  mjpeg_img2.onload = reload_img2;
	  mjpeg_img2.onerror = error_img;
	  reload_img2();

	}

</script>