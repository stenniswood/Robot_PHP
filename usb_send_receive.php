<?php
//include "device_list.php";

	$filedescriptor = USBOpenDevice( $_REQUEST['path'] );	
	$response = USBDeviceSend( $filedescriptor, $_REQUEST['data'] );
	USBCloseDevice( $filedescriptor );
	
	
	function USBOpenDevice( $path )
	{
		global $filedescriptor;
		error_reporting(E_ERROR | E_WARNING | E_PARSE);

		$filedescriptor = fopen( $path, "w+" );
		if ($filedescriptor==FALSE) {
			echo "Cannot open device: ".$dev;
			return;
		} 
		//else echo "OPENED DEVICE:".$path." fd=".$filedescriptor."<br>";
			
		stream_set_blocking($filedescriptor, true);
		return $filedescriptor;
	}
	function USBCloseDevice( $filedescriptor )
	{
		fclose( $filedescriptor );
	}

	function USBDeviceSend($fd, $data) 
	{		
			$result = fwrite( $fd, $data."\r\n" );
			//echo "fd=".$fd."    Bytes sent=".$result."    ".$data."<br>";

			// Some devices echo back our command, others don't!
			$timeout = 0;
			$found   = FALSE;
			$data    = false;
			$full_data = false;
			
				$i=0;
				do {		// while no data read in...
					$data = fgets($fd, 4096);					
					if ($data)
					{   
						//echo "   fgets:";   var_dump($data); echo "  ";
						$full_data .= $data;
						$i=0;		// restart
						$done = feof($fd);
						//var_dump($done);
						//echo "<br>";
						//$data = false;
					};
					$i++;
				} while (($data==false) && ($i<5000));

			//echo "Fulldata=";	var_dump($full_data);  echo "<br>";	
			echo $full_data;
			if ($full_data==false)	return "no response";

			return $full_data;  
	};

?>

