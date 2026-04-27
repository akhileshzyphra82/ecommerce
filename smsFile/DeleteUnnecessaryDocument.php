<?php
set_time_limit(0);
ini_set('display_errors',1);
date_default_timezone_set('Asia/Calcutta');//---------Set the default time zone to IST..Deba
include "connection.php";
// this code is user to delete unnecessary images in student-photo folder
$strDeleteQuery = "DELETE FROM tbl_unnecessary_invoice_detail";
$deleteResult = mysql_query($strDeleteQuery);

$dirDocument ='../admin/UI/Attachments/'; 
$document = glob($dirDocument."*");

echo"<pre>";print_r($document); die;

$strOrderIdQuery = "SELECT order_id FROM tbl_order WHERE order_id>0";
$arrOrderIdData = mysql_query($strOrderIdQuery);

$orderIdKeyArray=array();
while($arrOrderIdDataDetail = mysql_fetch_array($arrOrderIdData))
{ 
	$orderIdKeyArray[$arrOrderIdDataDetail[0]]=$arrOrderIdData[0];
}


foreach($document as $documentPath)
{
	$detailDocumentFolder=explode("/",$documentPath);
	echo"<pre>";print_r($detailDocumentFolder); die;
	foreach($detailDocumentFolder as $details)
	{
		if($details=='DocumentFile')
		{
			$documentManagerFile = glob("../UI/Document/$details/"."*");
			$documentFile=array();
			foreach($documentManagerFile as $fileDocument)
			{
				$DocFile=explode("/",$fileDocument);
				//echo "<pre>"; print_r($DocFile); die;
				if($DocFile[4]=='default.jpg' || $DocFile[4]=='index.html')
					continue;
					
				list($nameId,$ext)=explode(".",$DocFile[4]);
				list($mgtId,$docId)=explode("-",$nameId);
				if(array_key_exists($mgtId,$documentManagerKeyArray))
				{ 
					continue;
				}
				else
				{ 
					if(!in_array($mgtId,$documentManagerKeyArray))
					{
						$documentFile[]=$nameId;
						$strDocFile="INSERT INTO tbl_unnecessary_images_detail (`student_id`,`image_full_path`,`folder_name`) VALUES ('".$mgtId."','".$fileDocument."','".$DocFile[3]."')";
						 mysql_query($strDocFile);
					}
					
				}
			}
		}
	}
}

?>
