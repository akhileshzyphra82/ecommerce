<?php
session_start();
set_time_limit(0);

$intEmployeeTypeId = $_SESSION['EMPLOYEETYPEID'];
$intEmployeeId= $_SESSION['EMPLOYEEID'];

//echo '<pre>'; print_r($_SESSION);die;
if($intEmployeeTypeId!='3' &&  $intEmployeeTypeId!='1')
{
	session_destroy();
	header("location:https://sinelec-tech.com"); die;
}


if($intEmployeeId=='' || count($_SESSION)==0)
{
	session_destroy();
	header("location:https://sinelec-tech.com/admin/UI/index.php"); die;
}

?>