<?php
	echo "Eye UPDATE: ";
	var_dump($_REQUEST);


	function SendAniEyesUpdate($dev_name) 
	{
		global $_REQUEST;
		error_reporting(E_ERROR | E_WARNING | E_PARSE);
		$fd = fopen( $dev_name, "w+" );
		if ($fd==FALSE) {
			echo "Cannot open device: ".$dev_name;
			return "ERROR";
		}

		$_REQUEST["eye_msg_left"] .= "\r\n";
		$_REQUEST["eye_msg_right"] .= "\r\n";		
		

		$result = fwrite( $fd, $_REQUEST["eye_msg_left"] );
		var_dump($result);
		$result = fwrite( $fd, $_REQUEST["eye_msg_right"] );
		var_dump($result);			
		fclose($fd);
	
		return "okay";
	};

	
	SendAniEyesUpdate( $_REQUEST["dev_fname"] );


	/*	dio_fcntl($fd, F_SETFL, O_SYNC);
		dio_tcsetattr($fd, array(
		  'baud' => 38400,
		  'bits' => 8,
		  'stop'  => 1,
		  'parity' => 0
		)); */

//  echo "Device: ". $_REQUEST["dev_fname"];
//	echo "<br>Left:  ". $_REQUEST["eye_msg_left"] ."<br>";
//	echo "Right: ". $_REQUEST["eye_msg_right"]."<br>";	
?>