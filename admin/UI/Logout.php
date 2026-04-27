<?php
session_start();
if(isset($_SESSION) && array_key_exists('BasePrincipal', $_SESSION))
{
	session_destroy();
	//header("location:".$_SESSION['BaseURL']);
	header("location:index.php");
}
else
{
	session_destroy();
	header("location:index.php");
}
?>