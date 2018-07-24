<?php

	function SendAniEyesUpdate($dev_name) 
	{
		global $_REQUEST;
		error_reporting(E_ERROR | E_WARNING | E_PARSE);
		$fd = fopen( $dev_name, "w+" );
		if ($fd==FALSE) {
			echo "Cannot open device: ".$dev_name;
			return "ERROR";
		}
	/*	dio_fcntl($fd, F_SETFL, O_SYNC);
		dio_tcsetattr($fd, array(
		  'baud' => 38400,
		  'bits' => 8,
		  'stop'  => 1,
		  'parity' => 0
		)); */
		

	
		$result = fwrite( $fd, $_REQUEST["eye_msg_left"] );
		$result = fwrite( $fd, $_REQUEST["eye_msg_right"] );
				
		fclose($fd);
		
		return "okay";
	};

	echo "Left:  ". $_REQUEST["eye_msg_left"] ."<br>";
	echo "Right: ". $_REQUEST["eye_msg_right"];
	
	SendAniEyesUpdate(_$REQUEST["dev_fname"]);
	
?>