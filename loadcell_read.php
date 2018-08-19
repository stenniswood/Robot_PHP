<?php

	//echo "GOT HERE 1<Br>";
	//var_dump($_REQUEST);

	function Send_read_request($dev_name, $command) 
	{
		global $_REQUEST,$forces;
		error_reporting(E_ERROR | E_WARNING | E_PARSE);
		$fd = fopen( $dev_name, "w+" );
		if ($fd==FALSE) {
			echo "Cannot open device: ".$dev_name;
			return "ERROR OPENING DEVICE";
		}

		$command .= "\r\n";
		$result = fwrite( $fd, $command );

		$i=0;
		$data=false;
		do {
			$data = fgets($fd);
			$i++;
		} while (($data==false) && ($i<1000));

		fclose($fd);

		$forces = $data;
		//$forces = explode(":", $data );		
		
		return "okay";
	};

	echo "Sending ".$_REQUEST["command"]."to ".$_REQUEST["dev_fname"]."<br>";
	
	Send_read_request( $_REQUEST["dev_fname"], $_REQUEST["command"]);
	
	echo $forces;
/*	echo "Force1:  ". $forces[0]. "<br>";
	echo "Force2:  ". $forces[1]. "<br>";
	echo "Force3:  ". $forces[2]. "<br>";
	echo "Force4:  ". $forces[3]. "<br>";
*/
	
?>