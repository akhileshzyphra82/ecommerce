<?php
session_start();
set_time_limit(0);

$CUSTOMER_EMAIL = $_SESSION['CUSTOMER_EMAIL'];
$CUSTOMER = $_SESSION['CUSTOMER'];
$CUSTOMER_ID= $_SESSION['CUSTOMER_ID'];
$USER_TYPE_ID_WEBSITE= $_SESSION['USER_TYPE_ID_WEBSITE'];

//echo '<pre>'; print_r($_SESSION);die;
if($CUSTOMER_ID=='')
{
	
	session_destroy();
	//echo 'akki<pre>'; print_r($_SESSION);die;
	header("location:https://sinelec-tech.com/website/login.php"); die;
}
?>