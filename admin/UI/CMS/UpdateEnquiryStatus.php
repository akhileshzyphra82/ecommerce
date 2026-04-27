<?php
//require_once ('../../BL/ProductManager.php');
include('../Includes/Functions.php');

list($enquiryNo,$enquiryStatus)=explode("_",$_REQUEST["val"]);
?>

<div class="modal-dialog modal-lg" style="width:800px;height:800px">
<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Update Enquiry Status (Enquiry no - <?php  echo $enquiryNo;?>)</h4>
		</div>
		<div class="modal-body">
		<div class="row">
		<form action="Enquiry.php?urlstring=<?php echo EncryptURL('action=UpdateStatus'); ?>" method="post">
			<table id="" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th class="text_align_center">Current Enquiry Status</th>
						<th class="text_align_center">Change Enquiry Status</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						 <td><?php echo $enquiryStatus; ?></td>
						 <td>
						<select  class="form-control pull-right" name="enquiryChangedStatus" required>
                        	<option value="">Select Status</option>
                        <?php 
						if($enquiryStatus=='Quotation Pending')
						{
						?>
							<option value="Quotation Sent">Quotation Sent</option>
							<option value="Order Generated">Order Generated</option>
							<option value="Order Completed">Order Completed</option>
                        <?php
						}
						elseif($enquiryStatus=='Quotation Sent')
						{
						?>
							<option value="Quotation Pending">Quotation Pending</option>
							<option value="Order Generated">Order Generated</option>
							<option value="Order Completed">Order Completed</option>
                        <?php
						}
						elseif($enquiryStatus=='Order Generated')
						{
						?>
							<option value="Quotation Pending">Quotation Pending</option>
							<option value="Quotation Sent">Quotation Sent</option>
							<option value="Order Completed">Order Completed</option>
                        <?php
						}
						?>    
						</select>
						<input type="hidden" name="enquiryNo" value="<?php  echo $enquiryNo;?>" />
						<input type="hidden" name="enquiryCurrentStatus"  value="<?php echo $enquiryStatus ?>" />
						</td>
					</tr>
				</tbody>
			</table>	
			<div class="form-group text-center">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<button type="submit" class="btn btn-success">Submit</button>
			</div>
			</div>
		</form>
	 </div>
</div>				
	</div>
	<div class="clear"></div>
	<div class="modal-footer">
	</div>
	</div>
