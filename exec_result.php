
<?php
echo exec('pwd');
echo "<br>";

	$status = exec('/home/pi/bk_code/joystick_test/joys > /dev/null & ', $out);
	echo "<br>";

	$size = count($out);	
	for ($i=0; $i<$size; $i++)
	{
		echo $i."\t";
		echo $out[$i];
		echo "<br>";
	}

?>

