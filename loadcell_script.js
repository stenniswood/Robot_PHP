var lc_canvas = document.getElementById("loadcellCanvas");
var lc_ctx = lc_canvas.getContext("2d");

var force1 = 0;
var force2 = 0;
var force3 = 0;
var force4 = 0;
var colorStr;
var ReturnedForcesStr;
var counter = 0;

function draw_one_cell(left, bottom, force ) 
{
	var width  = 20;
	var height = 30;
	
	var colorR = Math.round( force/40. * 255 );
	var colorB = 100;//( 255-force );
	colorStr = "rgba( " + colorR + ", 0, "+colorB+", 1.0 )"; 
	lc_ctx.fillStyle   = colorStr;
	lc_ctx.strokeStyle = '#00330';

	lc_ctx.fillRect(left, bottom, width, height);
}

function draw_cells()
{
	draw_one_cell(20, 20, force4 );	
	draw_one_cell(20, 60, force3 );
	draw_one_cell(50, 60, force1 );
	draw_one_cell(50, 20, force2 );
	document.getElementById("result_text").innerHTML = colorStr + ReturnedForcesStr +"; Counts"+ counter;
}

function update_cell_forces( $event )
{
//	force2 = 50;
//	force3 = 150;
//	force4 = 255;
}


function read_from_device()
{
   //if (track_mouse) 
   {
   		counter++;
	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
		
		  ReturnedForcesStr = this.responseText;
		  //document.getElementById("result_text").innerHTML = str;
		  var forces = ReturnedForcesStr.split( ":" );
		  force1 = parseFloat( forces[0] );
		  force2 = parseFloat( forces[1] );
		  force3 = parseFloat( forces[2] );
		  force4 = parseFloat( forces[3] );
		  draw_cells();
		}
	  };
	  
	  var str = "dev_fname=" + "/dev/ttyACM1";
	  str += "&command=Send";
	  xhttp.open("GET", "loadcell_read.php?"+str, true);	  
	  xhttp.send("");
  }
}

//var x = setInterval(read_from_device, 1000);

draw_cells();
