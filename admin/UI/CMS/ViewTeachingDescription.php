<?php
require_once ('../../BL/HomeManager.php');
require_once ('../../BL/TeachingWorldManager.php');
list($teachingId,$ApplyFor,$ActivityType)= explode("_",$_REQUEST["val"]);
$objTeachingWorldManager = new TeachingWorldManager();
$TeachingDeetails=$objTeachingWorldManager->GetSchoolTeachingByTeachingId($teachingId);
   if($ApplyFor=="English")
   {
     $Title="Teaching Description";
	 $TeachingDescription=$TeachingDeetails[0]->ENG_REMARK;
   }
   if($ApplyFor=="Chinese")
   {
		$Title="&#27963;&#21160;&#25551;&#36848;";
		$TeachingDescription=$TeachingDeetails[0]->CHN_REMARK;
		
		if($ActivityType=="TeachingWorld")
		{
			$ActivityType="&#25945;&#23398;&#19990;&#30028;";
		}
		if($ActivityType=="WeeklyEducation")
		{
			$ActivityType="&#27599;&#21608;&#25945;&#32946;";
		}
  
   }
   ?>
<div class="modal-dialog modal-lg" style="width:800px;height:800px">
<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?php echo $Title;   ?> (<?php  echo $ActivityType; ?>)</h4>
		</div>
	<div class="modal-body">
	<?php echo $TeachingDescription; ?>
	</div>				
</div>

<div class="clear"></div>
	<div class="modal-footer">
	</div>
</div>
											
