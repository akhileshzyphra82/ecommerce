<?php
////error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
set_time_limit(0);
include "connection.php";
// this code is user to delete unnecessary images in student-photo folder
$strQuery = "SELECT `unnecessary_images_detail_id`,`image_full_path` FROM tbl_unnecessary_images_detail WHERE unnecessary_images_detail_id>0";
$fetchResult = mysql_query($strQuery);
while($imageDetail = mysql_fetch_array($fetchResult))
{ 
	$deleteImageId=$imageDetail[0];
	$folderPath=$imageDetail[1];
	if(file_exists($folderPath))
	{
		if(@unlink($folderPath))
		{
			$strDeleteQuery = "DELETE FROM tbl_unnecessary_images_detail WHERE unnecessary_images_detail_id=$deleteImageId";
			$deleteResult = mysql_query($strDeleteQuery);
			echo "File deleted sucessfully<br>";
		}
	}
	else
	{
		echo "File don't exist.<br>";
	}
}
?>
