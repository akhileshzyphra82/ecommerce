<?php
ini_set('display_error','1');
require_once ('../../BL/HomeManager.php');
//require_once ('../../UI/CMS/ViewOrderDetailsModal.php');
 list($userId,$user_type_id,$customername)=explode("_",$_REQUEST["val"]);
 $objHomeManager= new HomeManager();
 $UserAddressDetails=$objHomeManager->getUserAddressByUserId($userId);
//echo "<pre>";print_r($UserAddressDetails);die;
?>

<div class="modal-dialog modal-lg" style="width:800px;height:800px">
<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Customer Address Details (Customer Name - <?php  echo $customername;?>)</h4>
		</div>
		<div class="modal-body">
			<table class="table table-bordered table-striped" >
			<thead>
				<tr>
					<th class="text_align_center">S.No</th>
					<th class="text_align_center">Address</th>
					<th class="text_align_center">Contact Person</th>
				</tr>
			
			<?php
			if(!empty($UserAddressDetails>0))
			{
				$i=1;
				foreach($UserAddressDetails as $value)
				{
			 ?></thead>
				<tr>
					<td><?php echo $i++; ?></td>
					<td><?php echo $value->ADDRESS ?><br />
					City-  <?php echo $value->CITY ?><br />
					State-  <?php echo  $value->STATE; ?><br />
					Zip-  <?php echo $value->ZIP;?>
					</td>
					<td><?php echo  $value->USER_NAME; ?></td>
				</tr>
				
			<tbody>
			<?php
				}
			}
			else 
			{
			?>
				<tr><td colspan="3" style="color:red;text-align:center">No address found</td></tr>
			<?php  
			}
			?>
			</tbody>
			</table>
		</div>
	</div>				
</div>

<div class="clear">
</div>
<div class="modal-footer">
</div>
