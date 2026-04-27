<?php
include('../Common.php');
include('../Includes/Functions.php');
require_once ('../../BL/HomeManager.php');
$paramsArray = GetQueryStringParameters();
(isset($paramsArray['action']))? $action=$paramsArray['action'] : $action=$_GET["action"];
//echo "<pre>";print_r($_REQUEST["val"]);die;
list($bannerId,$ApplyFor,$ActivityType)= explode("_",$_REQUEST["val"]);

if($ApplyFor=="English")
{
	$Title="Teaching Image";
	$msg="No Pdf File Found";
}
if($ApplyFor=="Chinese")
{
	$Title="&#25945;&#23398;&#24418;&#35937;";
	$msg="&#27809;&#26377;&#25214;&#21040;PDF&#25991;&#20214;";

	if($ActivityType=="TeachingWorld")
	{
		$ActivityType="&#25945;&#23398;&#19990;&#30028;";
	}
	if($ActivityType=="WeeklyEducation")
	{
		$ActivityType="&#27599;&#21608;&#25945;&#32946;";
	}

}
	$Flag="Teaching";
	$ext="pdf";
	$objHomeManager = new HomeManager();
	$ImageDetailArray=$objHomeManager->getPdfDetailByActivityId($bannerId,$ApplyFor,$Flag,$ext);
?>
									
<div class="modal-dialog modal-lg" style="width:680px;">
<!-- Modal content-->
	<form action="TeachingWorld.php?urlstring=<?php echo EncryptURL('action=UpdatePdf'); ?>" name="module" method="post" enctype="multipart/form-data"   >
		<input  type="hidden" name="teachingId" id="teachingId" value='<?php echo $bannerId; ?>'>
		<input  type="hidden" name="activityFor" id="activityFor" value='<?php echo $ApplyFor; ?>'>
		<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?php  echo $Title; ?> (<?php  echo $ActivityType; ?>)</h4>
		</div>
		<div class="modal-body">
		<?php 
		if(count($ImageDetailArray)>0)
		{
		?>
			<table id="" class="table table-bordered table-striped">
			<thead>
				<tr >
					<th class="text_align_center">S.No</th>
					<th class="text_align_center">Pdf file</th>
					<th class="text_align_center">File Name</th>
					<th class="text_align_center">Change Pdf</th>
					<th class="text_align_center">Delete Pdf</th>
				</tr>
			</thead>
			<?php 
			$i=1;
			foreach($ImageDetailArray as $value)
			{ 
				$imgPath="../Images/".$value->IMAGES_ID."_".$ApplyFor.".".$value->IMAGE_EXT;
			?>
		
			<tr>
				<td><?php  echo $i++; ?></td>
				<td><a href="<?php echo $imgPath; ?>"><?php echo $value->IMAGE_NAME; ?></a></td>
				<td id="PdfName"><input  type="text" name="pdf_Name[]" id="pdf_Name" value='<?php echo $value->IMAGE_NAME; ?>'></td>
				<td><input type="file" name="pdf_file[]" id="pdf_file"  class="form-control"  ></td>
				<td><a href="TeachingWorld.php?urlstring=<?php echo EncryptURL('action=DeletePdf&intimagesId='.$value->IMAGES_ID.'&imgPath='.$imgPath); ?>" 
				class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to Delete this record ?\n Click OK to Continue, Cancel to Stop')" >
				<span class="glyphicon glyphicon-remove"  ></span> Delete</a></td>
			</tr>	  
				<input  type="hidden" name="pdfId[]" id="pdfId" value='<?php echo $value->IMAGES_ID; ?>'>
			<?php 
			}
			?>
			</table>
		
		<?php 
		}
		else
		{
		?>								        	
		<div class="alert alert-danger alert-dismissible" style="height:50px;">
		<h4><?php echo $msg; ?></h4>
		</div>
		<?php 
		}
		?>
				<label class="control-label" for="last-name"> Add more pdf</label><br/>
				<input type="file" name="new_pdf_file[]" id="new_pdf_file" multiple class="form-control"  >
				<div id="imageName"></div>
				<button type="submit" class="btn btn-success" onclick="return confirm('Are You Sure you want to Save it?\n Click OK to Continue, Cancel to Stop')" 
				id="newButton">Submit</button>
				</div>				
		</div>
		<div class="clear"></div>
		<div class="modal-footer">
		</div>
	</form>
</div>
											
<script>
$(document).ready(function() {


  $("#pdf_file").change(function(){
  var inp = document.getElementById('pdf_file');
  $("#pdf_Name").empty();

  var name = inp.files.item(0).name;
  
    name = name.replace(/\\/g, '/');
	var fname = name.substring(name.lastIndexOf('/')+1, name.lastIndexOf('.'));
	 
	$("#pdf_Name").val(fname);

 
  });
  
 $("#new_pdf_file").change(function(){
  var inp = document.getElementById('new_pdf_file');
for (var i = 0; i < inp.files.length; ++i) {
  var name = inp.files.item(i).name;
  
    name = name.replace(/\\/g, '/');
	var fname = name.substring(name.lastIndexOf('/')+1, name.lastIndexOf('.'));
    
	
	var j=i+1;
	
	 $("#imageName").append('<label class="control-label" for="last-name">'+j+' pdf name</label><input  type="text" name="new_pdf_name[]" id="new_pdf_name" value='+fname+'><br>'); 
}
  });
  
  });

</script>