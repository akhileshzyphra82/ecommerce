<?php 

	$connectionArray = array('162.241.224.212', 'sinelect_db', 'password@12345', 'sinelect_panel_productdb');
	//$connectionArray = array('localhost', 'root', '', 'easy_2_01_17');// local connection
	$mysql_link = mysql_connect($connectionArray[0],$connectionArray[1],$connectionArray[2]);
	mysql_select_db($connectionArray[3], $mysql_link) or die("Error in openning database");
?>