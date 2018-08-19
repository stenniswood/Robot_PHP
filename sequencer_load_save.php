<?php

	function init_junk_sequnece()	
	{
		global $MicroSeq;
		
		$MicroSeq[0]["device"]  = "/dev/ttUSB0";
		$MicroSeq[0]["type"]    = "Command";
		$MicroSeq[0]["model"]   = "DriveFive";
		$MicroSeq[0]["action"]  = "pwm v0.1 w0.2";
	
		$MicroSeq[1]["device"]  = "/dev/ttUSB0";
		$MicroSeq[1]["type"]    = "Command";
		$MicroSeq[1]["model"]   = "DriveFive";	
		$MicroSeq[1]["action"]     = "pwm v0.1 w0.2";
	
		$MicroSeq[2]["device"]  = "/dev/ttACM0";
		$MicroSeq[2]["type"]    = "Command";
		$MicroSeq[2]["model"]   = "Ani-eyes";	
		$MicroSeq[2]["action"]     = "straight";

		$MicroSeq[3]["device"]  = "n.a.";
		$MicroSeq[3]["type"]    = "Directive";
		$MicroSeq[3]["action"]     = "delay 500";

		$MicroSeq[4]["device"]  = "n.a.";
		$MicroSeq[4]["type"]    = "Directive";
		$MicroSeq[4]["action"]  = "goto 5";  
	}

	function save_sequence($fname, $MicroSeqTable ) 
	{
		//global $MicroSeq;
		//echo "SAVING SEQUENCER DATA!";
		
		$fd = fopen($fname, "w+");
		if ($fd==FALSE) {
			echo "ERROR - CANNOT OPEN SEQUENCE FILE<br>";			
		}
		foreach ($MicroSeqTable as $step)
		{
			$line  = $step["label"] .",";
			$line .= $step["type"] .",";
			$line .= $step["model"] .",";
			$line .= $step["action"] .",";
			$line .= $step["device"].",";
			$line .= $step["params"]."\n";	
			fwrite($fd, $line );
		}
		fclose($fd);
	}
	
	function load_sequence($fname) 
	{
		global $MicroSeq;
		//echo "OPENING FILENAME: ";		
		//var_dump($fname);
		
		$fd = fopen($fname, "r");		
		if ($fd==FALSE)
		{
			$error_string = "ERROR CANNOT OPEN file for reading...".$fname."<br>";
			echo $error_string;
			//alert($error_string);
			return;
		}

		$i = 0;
		do
		{
			$line = fgets( $fd );
			//var_dump($line);		
			//echo "<br>";
			if ($line) {
				$tmpAry = explode(",", $line );
				$MicroSeq[$i]["label"]  = $tmpAry[0];
				$MicroSeq[$i]["type"]   = $tmpAry[1];
				$MicroSeq[$i]["model"]  = $tmpAry[2];			
				$MicroSeq[$i]["action"] = $tmpAry[3];				
				$MicroSeq[$i]["device"] = $tmpAry[4];
				$MicroSeq[$i]["params"] = $tmpAry[5];
				$i++;
			}
		} while (!feof($fd));		
		fclose($fd);
		return;
	}
	
	if (isset($_POST['MicroSeqTable']))
		$Micro = json_decode($_POST['MicroSeqTable'], TRUE);

	if ($_REQUEST["operation"]=="Load")
		load_sequence( $_REQUEST["Filename"] );
	else if ($_REQUEST["operation"]=="Save")
		save_sequence( $_REQUEST["Filename"], $Micro ); 

	
?>
