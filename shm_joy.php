

<?php

	// get the q parameter from URL
	$q = $_REQUEST["vduty"];
	var_dump( $_REQUEST );

	$IPC_KEY_SEQ 		   = 0x04D5;
	$sizeofData            = 888;
	$MotorArrayOffset      = 580;
	$DiagnosticArrayOffset = 748;


	function read_counts()
	{
		// get id for name of cache
		$shm_id  = shmop_open( $IPC_KEY_SEQ, "w", 0644,  $sizeofData );
		if (!$shm_id) {
			echo "Couldn't open BK robot shared memory segment\n";
		}
		shmop_write($shm_id, "read_counts", 80 );
		$count_string = shmop_read ($shm_id, 80+128, 80 );
		return $count_string;
	}


	// get id for name of cache
    $shm_id  = shmop_open($IPC_KEY_SEQ, "w", 0644,  $sizeofData );
	if (!$shm_id) {
		echo "Couldn't open BK robot shared memory segment\n";
	}

    shmop_write($shm_id, $_REQUEST["text"], 80 );

?>