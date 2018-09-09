<?php

	function init_junk_sequnece()	
	{
/*		global $MicroSeq;
		
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
		$MicroSeq[4]["action"]  = "goto 5";  */
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
			$line = json_encode( $step );
			$line .= "\r\n";
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
			if ($line) {
				$MSeq[$i] = json_decode( $line, TRUE );				
				$i++;
			}
		} while (!feof($fd));		

		fclose($fd);
		return $MSeq;
	}
	
	function save_variables($fname, $InputVars ) 
	{		
		echo "SAVING VARIABLES<br>";
		$fd = fopen($fname, "w+");
		if ($fd==FALSE) {
			echo "ERROR - CANNOT OPEN SEQUENCE FILE<br>";			
		}
		foreach ($InputVars as $step)
		{
				
			$line  = $step["name"]  		.",";			
			$line .= $step["board_name"] 	.",";
			$line .= $step["model"] 		.",";
			$line .= $step["device"]		.",";			
			$line .= $step["eliciting_cmd" ].",";
			$line .= $step["response_index"].",";
			$line .= $step["signal_type"   ]."\n";	
			fwrite($fd, $line );
		}
		fclose($fd);
	}
	function load_variables($fname, $InputVars ) 
	{				
		global $InputVars;
		$fd = fopen($fname, "r");		
		if ($fd==FALSE)
		{
			$error_string = "ERROR CANNOT OPEN file for reading...".$fname."<br>";
			echo $error_string;
			return;
		}

		$i = 0;
		do
		{
			$line = fgets( $fd );
			//var_dump($line); echo "<br>";
			if ($line) {
				$tmpAry = explode(",", $line );
				$InputVars[$i]["name"]  		 = trim($tmpAry[0]);
				$InputVars[$i]["board_name"]   	 = trim($tmpAry[1]);
				$InputVars[$i]["model"]  		 = trim($tmpAry[2]);			
				$InputVars[$i]["device"] 		 = trim($tmpAry[3]);				
				$InputVars[$i]["eliciting_cmd"]  = trim($tmpAry[4]);
				$InputVars[$i]["response_index"] = trim($tmpAry[5]);
				$InputVars[$i]["signal_type"] 	 = trim($tmpAry[6]);				
				$i++;
			}
		} while (!feof($fd));		
		fclose($fd);
		return;
	}
	
	
	if (isset($_POST['MicroSeqTable']))
		$Micro = json_decode($_POST['MicroSeqTable'], TRUE);
	if (isset($_POST['InputVars']))
		$InputVars = json_decode($_POST['InputVars'], TRUE);

	if ($_REQUEST["operation"]=="Load")
		load_sequence( $_REQUEST["Filename"] );
	else if ($_REQUEST["operation"]=="Save")
		save_sequence( $_REQUEST["Filename"], $Micro ); 
	else if ($_REQUEST["operation"]=="SaveInputs")
		save_variables( $_REQUEST["Filename"], $InputVars ); 
	else if ($_REQUEST["operation"]=="LoadInputs")
		load_variables( $_REQUEST["Filename"], $InputVars ); 

	
?>
