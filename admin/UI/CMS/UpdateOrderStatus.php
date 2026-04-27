<?php
include('../Includes/Functions.php');

list($orderNo,$orderCurStatus)=explode("@_@",$_REQUEST["val"]);
?>

<div class="modal-dialog modal-lg" style="width:800px;height:800px">
<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Update Order Status (Order no - <?php  echo $orderNo;?>)</h4>
		</div>
		<div class="modal-body">
		<div class="row">
		<form action="ViewOrderDetailsNew.php?urlstring=<?php echo EncryptURL('action=UpdateStatus'); ?>" method="post">
			<table id="" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th class="text_align_center">Current Status</th>
						<th class="text_align_center" colspan="2">Change Order Status</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						 <td><?php echo $orderCurStatus; ?></td>
						 <td colspan="2">
						<select  class="form-control pull-right" name="table_search" required>
                        	<option value="">Select Status</option>
                        <?php 
						if($orderCurStatus=='Payment Successful')
						{
						?>
							<option value="Dispatched">Dispatched</option>
							<option value="Delivered">Delivered</option>
							<option value="Cancel Order">Cancel Order</option>
                        <?php
						}
						elseif($orderCurStatus=='Invoice Payment Successful')
						{
						?>
							<option value="Dispatched Invoice Payment Pending">Dispatched Invoice Payment Pending</option>
							<option value="Delivered">Delivered</option>
							<option value="Invoice Payment Pending">Invoice Payment Pending</option>
							<option value="Payment Failed">Payment Failed</option>
							<option value="Cancel Order">Cancel Order</option>
                        <?php
						}
						elseif($orderCurStatus=='Invoice Payment Pending')
						{
						?>
							<option value="Invoice Payment Successful">Invoice Payment Successful</option>
							<option value="Payment Failed">Payment Failed</option>
							<option value="Dispatched Invoice Payment Pending">Dispatched Invoice Payment Pending</option>
							<option value="Delivered">Delivered</option>
							<option value="Cancel Order">Cancel Order</option>
                            
                        <?php
						}
						elseif($orderCurStatus=='Dispatched Invoice Payment Pending')
						{
						?>
							<option value="Invoice Payment Successful">Invoice Payment Successful</option>
							<option value="Payment Failed">Payment Failed</option>
							<option value="Dispatched">Dispatched</option>
							<option value="Delivered">Delivered</option>
							<option value="Cancel Order">Cancel Order</option>
                        <?php
						}

						elseif($orderCurStatus=='Bank Transfer Payment Successful')
						{
						?>
							<option value="Dispatched">Dispatched</option>
							<option value="Delivered">Delivered</option>
							<option value="Bank Transfer Payment Pending">Bank Transfer Payment Pending</option>
							<option value="Payment Failed">Payment Failed</option>
							<option value="Cancel Order">Cancel Order</option>
                        <?php
						}
						elseif($orderCurStatus=='Bank Transfer Payment Pending')
						{
						?>
							<option value="Bank Transfer Payment Successful">Bank Transfer Payment Successful</option>
							<option value="Payment Failed">Payment Failed</option>
							<option value="Cancel Order">Cancel Order</option>
							<option value="Delete Order">Delete Order</option>

                        <?php
						}

						elseif($orderCurStatus=='Dispatched' || $orderCurStatus=='Delivered' ||  $orderCurStatus=='Dispatched Invoice Payment Pending')
						{
						?>
							<option value="Delivered">Delivered</option>
                        <?
						}
						
						elseif($orderCurStatus=='Payment Failed')
						{
						?>
							<option value="Cancel Order">Cancel Order</option>
                        <?
						}

						
						elseif($orderCurStatus=='Cart' || $orderCurStatus=='Checkout')
						{
						?>
							<option value="Payment Successful">Payment Successful</option>
							<option value="Payment Failed">Payment Failed</option>
							<option value="Delete Order">Delete Order</option>
                        <?php
						}
						?>    
						</select>
						<input type="hidden" name="order_id" value="<?php  echo $orderNo;?>" />
						<input type="hidden" name="referenceStatus"  value="<?php echo $orderCurStatus ?>" />
						</td>
					</tr>
                    <tr>
						<th class="text_align_center">Courier Company</th>
						<th class="text_align_center">Tracking ID</th>
						<th class="text_align_center">Tracking URL</th>
                    </tr>
                    <tr>
                        <td><input type="text" name="dispatch_courier_company"/></td>
						<td><input type="text" name="dispatch_courier_tracking_id"  /></td>
						<td><input type="text" name="dispatch_courier_tracking_url" /></td>
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
