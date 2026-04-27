<?php 
ob_start();
ini_set('display_errors',0);
////error_reporting(E_ALL | E_STRICT);



include('../admin/UI/Includes/Functions.php');
$paramsArray = GetQueryStringParameters();
(isset($paramsArray['fileName']))? $fileName=$paramsArray['fileName'] : $fileName="";
 $filePath='../admin/UI/Attachments/';
if(file_exists($filePath.$fileName )){
header("Content-type: application/pdf");
header("Content-Disposition: inline; filename=".$fileName);
@readfile($filePath.$fileName);
}else{
	echo "Not Found"; 
}




?>