<?php
require_once ('../../BL/HomeManager.php');
//echo "<pre>";print_r($_REQUEST["val"]);die;
$NewsAndEventobj=new HomeManager();
$NewsEventdetail=$NewsAndEventobj->GetAndDisplayAllInsertedImages($_REQUEST["val"]);
?>

<div class="modal-dialog modal-lg" style="width:800px;height:800px">
<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">News Event Images</h4>
		</div>
		<div class="modal-body">
		<?php 
		if($NewsEventdetail)
		{
			foreach($NewsEventdetail as $imageVal)
			{
				$NewsEventImagePath="../Images/NewsAndEventPic/".$imageVal->NEWS_EVENT_IMG_ID.".".$imageVal->IMAGE_EXT;
		?>
				<img src="<?php echo $NewsEventImagePath;?>" width="250" height="250"  />
		<?php
			}
		}
		else
		{
		echo "No Preview";
		}
		?>
		</div>				
		</div>
		<div class="clear"></div>
		<div class="modal-footer">
	</div>
</div>

