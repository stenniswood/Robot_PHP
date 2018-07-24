	var canvas = document.getElementById("eyeCanvas");
	var ctx = canvas.getContext("2d");
	var radius = 30;
	var pupil_radius = 10;
	var LeftPupil_LR_Offset  = 0;
	var RightPupil_LR_Offset = 0;
	var LeftPupil_UD_Offset = 0;
	var RightPupil_UD_Offset = 0;
	var track_mouse = true;
	
function draw_eyes()
{
	// Eye drawing Canvas:
	ctx.fillStyle   = "#FF0000";
	ctx.strokeStyle = '#00330';

	// Left Eye:
	// 200 - 30*2 - 30*2 = 80
	ctx.beginPath();
	ctx.arc(80/3+radius,50, radius, 0,2*Math.PI);
	ctx.fill();

	// Right Eye:
	ctx.arc(80/3+radius*4,50, radius, 0,2*Math.PI);
	ctx.fill();
	//ctx.stroke();

	// Left Pupil:
	ctx.beginPath();
	ctx.fillStyle = "#00F300";
	ctx.arc(80/3+radius-LeftPupil_LR_Offset,50+LeftPupil_UD_Offset, pupil_radius, 0,2*Math.PI);
	ctx.fill();

	// Right Pupil:
	ctx.beginPath();
	ctx.arc(80/3+radius*4-RightPupil_LR_Offset,50+RightPupil_UD_Offset, pupil_radius, 0,2*Math.PI);
	ctx.fill();
}


function toggle_mouse_track(event)
{
	track_mouse = !track_mouse;
}

var l_eye_msg;
var r_eye_msg;
var MAX = radius - pupil_radius;
	
function send_to_ani_eyes_left()
{
	var lr_deg = Math.round( (LeftPupil_LR_Offset / MAX) * 60 );
	var ud_deg = Math.round( (LeftPupil_UD_Offset / MAX) * 60 );

	l_eye_msg = "left look at ";
	l_eye_msg += lr_deg + " " + ud_deg + "\r\n";
	
	// From Javascript we need Ajax to send to server (PHP).	
}

function send_to_ani_eyes_right()
{
	r_eye_msg = "right look at ";
	var lr_deg = Math.round( (LeftPupil_LR_Offset / MAX) * 60 );
	var ud_deg = Math.round( (LeftPupil_UD_Offset / MAX) * 60 );	
	r_eye_msg +=  lr_deg + " " + ud_deg + "\r\n";
	
	// From Javascript we need Ajax to send to server (PHP).
}

function update_eye_position(event)
{
	if (!track_mouse) return;
	
	var rect = canvas.getBoundingClientRect();
    var x = event.clientX-rect.left;
    var y = event.clientY-rect.top;
    var coords = "X coords: " + x + ", Y coords: " + y;
	
	LeftPupil_LR_Offset  = -(x-rect.width/2.)/rect.width/2. * 1.5*radius;
	RightPupil_LR_Offset = -(x-rect.width/2.)/rect.width/2. * 1.5*radius;

	LeftPupil_UD_Offset  = (y-rect.height/2.)/rect.height/2 * 1.5*radius;
	RightPupil_UD_Offset = (y-rect.height/2.)/rect.height/2 * 1.5*radius;	
}


function send_to_usb_device() 
{
  if (track_mouse) {
	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
		  document.getElementById("result_text").innerHTML =
		  this.responseText;
		}
	  };
	  send_to_ani_eyes_left();
	  send_to_ani_eyes_right();
	  
	  var str = "eye_msg_left="+l_eye_msg+"&eye_msg_right="+r_eye_msg;
	  str += "&dev_fname="+"/dev/ttyACM0";

	  xhttp.open("GET", "eye_update.php?"+str, true);
	  xhttp.send("");
  }
}


