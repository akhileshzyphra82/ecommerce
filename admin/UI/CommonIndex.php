<?php
ini_set("display_errors",1);
ob_start();
//error_reporting(E_ALL & ~E_NOTICE);
session_start();
date_default_timezone_set('Asia/Kolkata');
require_once "../BL/HRManager.php";

$responseString = $_GET['responsestring'];
////change this for each index
$indexPage = $_POST['indexPage'];

// end change
if(isset($_GET['check']))
{
 	if(empty($_POST['txtUserName']) || empty($_POST['txtPassword']))
	{
	   header("location:".$indexPage."?blank");// Redirect to error page.   Fields can not be blank.
	   exit();
	}
	
	
	$txtUserName = trim($_POST['txtUserName']);
	
	$objHRManager = new HRManager();
	$objEmployee = $objHRManager->ValidateLogin($txtUserName, trim($_POST['txtPassword']));
	
	$EmployeeData=$objEmployee[0];
 	$resultMap = $objEmployee[1];				
	//echo"<pre>";print_r($objEmployee);die;
	
	$strMessage = "";
	$loginStatus = "";
		 
	if($resultMap == "LOGINFAILURE" )//Invalid Username/Password/User Type.
		$loginStatus = "1";
	elseif($resultMap == "NOTFOUND")//Login failed! Please try again.
		$loginStatus = "0";
	elseif($resultMap == "INACTIVE")//Your account has been Blocked/Inactive.
		$loginStatus = "3";
	else
		$strMessage = "SUCCESS";
	 
	$EmployeeType=$EmployeeData[0]->USER_TYPE_ID;
	$EmployeeId=$EmployeeData[0]->USER_ID;
	//echo"<pre>";print_r($EmployeeData);die;
	
//	echo"<pre>";print_r($objEmployee);die;
	if($strMessage == "SUCCESS")
	{
 		// on successfull login Track the IP -......... 
		$_SESSION['EMPLOYEEID'] =$EmployeeId;
		$_SESSION['EMPLOYEETYPEID'] = $EmployeeType;
		$_SESSION['BaseURL'] = $indexPage;
 	    $user_menu = dirname(__FILE__)."/UserMenu/".$EmployeeType.".html";
		if(!file_exists ($user_menu))
		{
			/* echo "<pre>"; print_r($EmployeeData); die; */
			$_SESSION['BasePrincipal'] = true;
			$_SESSION['USER_ID']=$EmployeeData[0]->USER_ID;
			$_SESSION['EMPLOYEE_NAME']=$EmployeeData[0]->NAME;
 			$_SESSION['EMAILID']=$EmployeeData[0]->COMMUNICATION_EMAIL_ID;
			$_SESSION['EMPLOYEE_CONTACT']=$EmployeeData[0]->COMMUNICATION_MOBILE_NUM;
			$_SESSION['USER_TYPE_ID']=$EmployeeData[0]->USER_TYPE_ID; 
			   
			   
		//echo"<pre>";print_r($responseString);die;   
			   
 			if(trim($responseString) != "") 
				$urlString = "location:http://'".$_SERVER['HTTP_HOST']."'/UI/'.$responseString";
			else
				header("location:User/Home.php");	
		}
	}
	else
	{
		header("location:".$indexPage."?error=".$loginStatus."");
	}

}
?>