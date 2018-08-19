<?php

$stock_sound_1 = "/home/pi/python_games/match1.wav";
$stock_sound_2 = "/home/pi/python_games/match2.wav";
$stock_sound_3 = "/home/pi/python_games/match3.wav";
$stock_sound_4 = "/home/pi/python_games/match4.wav";


$fn = $_REQUEST['filename'];

if ($fn=="stock1")	$fn = $stock_sound_1;
if ($fn=="stock2")	$fn = $stock_sound_2;
if ($fn=="stock3")	$fn = $stock_sound_3;
if ($fn=="stock4")	$fn = $stock_sound_4;

$str = "omxplayer -o local ". $fn. " 2>&1";

$result = shell_exec($str);
//echo $result;


?>