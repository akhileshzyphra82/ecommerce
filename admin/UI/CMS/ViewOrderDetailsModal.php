<?php
ini_set('display_error','1');
require_once ('../../BL/ProductManager.php');
require_once ('../Common.php');
include('../Includes/Functions.php');

list($order_no,$user_id)=explode("@_@",$_REQUEST["val"]);

$paramsArray = GetQueryStringParameters();
$ProductsDetails1=array();
$OrderAddress=array();
$OrderAddressDetails=array();
$objProductManager = new ProductManager(); 
$ProductsDetails=$objProductManager->GetOrderDetailsByOrderId($order_no);
//print_r($ProductsDetails);
foreach($ProductsDetails as $value)
{
	if($value->IMAGE_EXT!="" && $value->IMAGE_FOR=='Product')
		$ProductsDetails1[$value->PRODUCT_ID] = $value;
	
	$OrderAddress = $value;
	$OrderHistory[$value->ORDER_HISTORY_ID] = array($value->ORDER_STATUS,$value->ORDER_STATUS_DATE);
}
$OrderAddressDetails = $OrderAddress ;
$action="DetailsProduct";

?>

<div class="modal-dialog modal-lg" style="width:800px;height:800px">
<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		</div>
		<div class="modal-body">
		<div class="row">
		<!--Order Details-->
		<div class="col-sm-12  p-bottom10 "  style="border:2px solid #f1f1f1">
		<?php 
		if(count($ProductsDetails)>0)
		{ 
			$str = date('m-d-Y', strtotime($ProductsDetails[0]->ORDER_DATE));
			$dateObj = DateTime::createFromFormat('m-d-Y', $str);?>
			<div class="col-sm-6 col-md-6 text-left" style="border-right:2px solid #f1f1f1">
				<div class="sectionTitle p-bottom10">
					<h3 align="left">Order Details</h3>
				</div>
				<div class="col-sm-12 col-md-12 p-20" >
					Order ID :&nbsp;<label><?php echo $ProductsDetails[0]->ORDER_ID;?></label><br>
					Order Date :&nbsp; <label><?php echo $dateObj->format('M d Y');?></label><br>
					Total Amount :&nbsp;<label><span class="glyphicon glyphicon-euro"></span>
					<?php echo number_format($ProductsDetails[0]->ORDER_TOTAL_AMT+$ProductsDetails[0]->SHIPING_AMT,2);?></label><br>
					Transaction ID :&nbsp;<label><?php echo $ProductsDetails[0]->TRANSACTION_ID;?></label><br />
					EU VAT Number :&nbsp;<label><?php echo $OrderAddressDetails->EU_VAT;?></label>
				</div>
			</div>	
			<div class="col-sm-6 col-md-6" >
				<div class="sectionTitle p-bottom10">
					<h3 align="left">Address</h3>
				</div>
				<div class="col-sm-12 col-md-12"  >
					<label>
					<?php echo $OrderAddressDetails->USER_NAME;?>
					</label><br>
					Delivery Address :&nbsp;
					<label> <?php echo $OrderAddressDetails->ADDRESS.','.$OrderAddressDetails->CITY.' '.$OrderAddressDetails->STATE.' , '.$OrderAddressDetails->COUNTRY_NAME.'-'.$OrderAddressDetails->ZIP;?></label><br>
					Phone :&nbsp;<label><?php echo $OrderAddressDetails->DELIVERY_PHONE_NO;?></label>
				</div>
				</div>
			</div>	
			<div class="col-sm-12  p-bottom10"  style="border:2px solid #f1f1f1">
				<div class="col-sm-6 col-md-6" >
					<table class="table cart ">
					</tbody>
					</table>
				</div>
				<div class="col-sm-12 col-md-12" >
					<table class="table cart ">
					<tbody>
						<tr>
							<th>Order status history</th>
							<th> Date</th>
						</tr>
					<?php 
					if(!empty($OrderHistory))
					{	
						foreach($OrderHistory as $value)
						{ 
							$str = date('m-d-Y', strtotime($value[1]));
							$dateObj = DateTime::createFromFormat('m-d-Y', $str);
					?>
						<tr>
							<td><?php echo $value[0];?></td><td> <?php echo $dateObj->format('M d Y');?></td>
						</tr>
					<?php 
						}
					}
					?>
					</tbody>
					</table>
				</div>
		<?php
		}
		?>
	</div>
</div>
	<form action="">
		<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th class="text_align_center">S.No</th>
				<th class="text_align_center">Products Image</th>
				<th class="text_align_center">Product</th>
				<th class="text_align_center">Product Code</th>
				<th class="text_align_center">Description</th>
				<th class="text_align_center">Quantity</th>
				<th class="text_align_center">Status</th>
				<th class="text_align_center">Price</th>
			</tr>
		</thead>
		<tbody>
		<?php
		if(!empty($ProductsDetails1)>0)
		$i=1;
		{ 
			foreach($ProductsDetails1 as $value)
			{
		?>
			<tr>
				<td><?php echo $i++; ?></td>
				<td style="float:left;"><a href="ViewOrderDetailsModal.php?urlstring=<?php echo EncryptURL('product_id='.$value->PRODUCT_ID); ?>"><img alt="..." 
				class="img-responsive" style="width:100px;height:75px;" src="<?php   echo "../Images/ProductImages/".$value->IMAGE_ID."_productImages.".$value->IMAGE_EXT; ?>"></a>
				</td>
				<td><?php echo $value->PRODUCT_NAME; ?></td>
				<td><?php echo $value->PRODUCT_CODE;?></td>
				<td><?php echo $value->PRODUCT_DESCRIPTION;?></td>
				<td><?php echo $value->QUANTITY;?></td>
				<td><?php echo $value->PRODUCT_STATUS;?></td>
				<td><?php echo $value->PRODUCT_AMT*$value->QUANTITY;?></td>
			</tr>
		<?php
			}
		}
		?>
		</tbody>
		</table>
	</form>
	</div>
	</div>				
</div>
<div class="clear">
</div>
<div class="modal-footer">
</div>
