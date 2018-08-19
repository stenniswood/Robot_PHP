
<?php
	// Scans /dev/ directory for all ttyACM* and ttyUSB* devices.
	function ScanDevices() 
	{
		global $ttyUSBOnly;	
		$dir    = '/dev/';
		$Devicefiles = scandir($dir);
		$ttyUSBOnly = array_filter($Devicefiles, function($file) { 	
				if ((strpos($file, 'ttyUSB') !== false) ||
					(strpos($file, 'ttyACM') !== false))
					return true;
				else return false;
		});	
		//var_dump($ttyUSBOnly);	
	}

	// ttyUSBOnly --> deviceInfo
	function OpenDevices()
	{
		global $deviceInfo, $ttyUSBOnly;
		error_reporting(E_ERROR | E_WARNING | E_PARSE);
		$i=0;
		foreach ($ttyUSBOnly as $dev)
		{
			$deviceInfo[$i]['status'] = "Available";
			$deviceInfo[$i]['path'] = "/dev/".$dev;
			$deviceInfo[$i]['filedescriptor'] = fopen( $deviceInfo[$i]['path'], "w+" );
			if ($deviceInfo[$i]['filedescriptor'] == FALSE) {
				echo error_get_last()['message']."<br>";
				$deviceInfo[$i]['status'] = "Not available";
			}
			//stream_set_timeout ($deviceInfo[$i]['filedescriptor'], 1);
			stream_set_blocking($deviceInfo[$i]['filedescriptor'], false);
			$i++;
		}
	}
	function CloseDevices()
	{
		global $deviceInfo;
		$i=0;
		foreach ($deviceInfo as $dev)		
		{
			if ($deviceInfo[$i]['filedescriptor'] != FALSE)
			{
				fclose(deviceInfo[$i]['filedescriptor']);
				$deviceInfo[$i]['filedescriptor'] = "";
			}
			$i++;
		}		
	}	
	
	function QueryDeviceType($fd) 
	{
			if ($fd==FALSE)	{
				echo "NO FileDescriptor!!<br>";
				return ;
			}
			usleep(100000);
			$result = fwrite( $fd, "device type\r\n" );
			fflush( $fd );

			// Some device echo back our command, others don't!
			$timeout = 0;
			$found=FALSE;
			$new_data=false;
			$data=false;
			$full_data = false;

			$i=0;
			do {
				$new_data=false;
				$data = fgets($fd, 4096);
				
				if ($data)
				{  
					$full_data .= $data ;
					$data = " ";
					$new_data=true;
				}
				$i++;
				usleep(10000);
			} while ($new_data ||  ($i<50) );

			$full_data = trim($full_data, ">");
			//echo "<br>FullData=";
			//var_dump($full_data);
			
			$found = strpos( $full_data, "ype:");
			if ($found==FALSE)
				return "no response";
			else
			{
				$result = explode("ype:", $full_data );
				if ($result[1]==NULL)
					return "invalid response";
				else
					return trim($result[1], " \t\n\r\0\x0B");  
			}
			return "na.";
	};
	

	function GetDevicesTypeAndStatus() 
	{
		global $deviceInfo;
		$i=0;
		echo "<br>";
		foreach ($deviceInfo as $dev) 
		{
			//var_dump( $dev );
			//echo "<br>". $deviceInfo[$i]['path'] ."   ";
			
			$deviceInfo[$i]['type']       =  QueryDeviceType( $deviceInfo[$i]['filedescriptor'] );
			$deviceInfo[$i]['name']       =  GetDeviceName( $deviceInfo[$i]['filedescriptor'] );			
			if ($deviceInfo[$i]['type']   == "Unknown")
				$deviceInfo[$i]['status'] =  "No response";
			$i++;
		};
	}

	function GetDeviceName($fd) 
	{
			if ($fd==FALSE)	{
				echo "NO FileDescriptor!!<br>";
				return ;
			}
			usleep(100000);
			$result = fwrite( $fd, "getname\r\n" );
			fflush( $fd );

			// Some device echo back our command, others don't!
			$timeout = 0;
			$found=FALSE;
			$new_data=false;
			$data=false;
			$full_data = false;

			$i=0;
			do {
				$new_data=false;
				$data = fgets($fd, 4096);
				
				if ($data)
				{  
					$full_data .= $data ;
					$data = " ";
					$new_data=true;
				}
				$i++;
				usleep(10000);
			} while ($new_data ||  ($i<100) );

			$full_data = trim($full_data, ">");
			//echo "<br>FullData=";
			//var_dump($full_data);
			
			$found = strpos( $full_data, "AME:");
			if ($found==FALSE)
				return "no response";
			else
			{
				$result = explode("AME:", $full_data );
				if ($result[1]==NULL)
					return "invalid response";
				else
					return $result[1];  
			}
			return "na.";
	};


	function FormDeviceTable()
	{
		global $deviceInfo;
		$numDevice = count( $deviceInfo );
		echo <<< EOT
		<table border="1"><tr>
		<th width="40" align="left">ID</th>
		<th width="150" align="left">Path</th>
		<th width="125" align="left">Type</th>
		<th width="150" align="left">Name</th>
		<th width="300" align="left">Status</th></tr>
EOT;
		echo "Auto detected ".$numDevice." devices.<br>";
		ListDevices();
		echo "</table>";
	}

	function ListDevices() {
		global $deviceInfo;
		$numDevice = count( $deviceInfo );
		$i=0;
		foreach ($deviceInfo as $di)
		{
			echo "<tr><td>". $i;
			echo "</td><td>".$di['path']."</td>";
			echo "<td>". $di['type']. "</td>";
			echo "<td>". $di['name']. "</td>";			
			echo "<td>". $di['status']. "</td>";	
			echo "</tr>";		
			$i++;
		}
	};

	function GetAllofAKind( $model ) {
		global $deviceInfo;
		$numDevice = count( $deviceInfo );
		$i=0;
		$DevSubset = [];
		foreach ($deviceInfo as $di)
		{
			//echo "<br>";  var_dump($di);
			if ($di["type"] == $model)
			{
				//echo "Match!<br>";
				$DevSubset[$i] = $di;
				$i++;
			}
		}
		return $DevSubset;
	};

	
	ScanDevices();
	OpenDevices();
	GetDevicesTypeAndStatus();
	CloseDevices();

	$drive_fives = GetAllofAKind( "DriveFive");
	$load_cells  = GetAllofAKind( "Load cell");
	$ani_eyes    = GetAllofAKind( "Ani-eyes");
	$io_expanders  = GetAllofAKind( "IO Expander");
	
	

	
?>
