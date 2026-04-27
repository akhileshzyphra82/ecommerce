<?php
ob_start();
//ini_set('display_errors','0');
//error_reporting(E_ALL | E_STRICT);
include('../Common.php');
include('../Includes/Functions.php');
require_once ('../../UI/Config/inc_path.php');
require_once ('../../BL/ProductManager.php');
require_once ('../../BL/HomeManager.php');
$date=date('Y-m-d');
$paramsArray = GetQueryStringParameters();
(isset($paramsArray['action']))? $action=$paramsArray['action'] : $action="";
isset($paramsArray["msg"]) ? $msg=$paramsArray["msg"] : $msg="";
isset($paramsArray["page"]) ? $page=$paramsArray["page"] : $page="1";
isset($paramsArray["start_from"]) ? $msg=$paramsArray["start_from"] : $start_from="";
isset($paramsArray["limit"]) ? $msg=$paramsArray["limit"] : $limit="";
switch($action)
{	
    
	case "UpdateStatus":
		$orderStatus =$_POST["table_search"];
		$orderId =$_POST["orderId"];
		$referenceStatus=$_POST["referenceStatus"];
		$AdminObject=new HomeManager();
		if($AdminObject->UpdateOrderStatusByOrderId($orderStatus,$orderId))
		header("location:ViewOrderDetails.php?urlstring=".EncryptURL("action=Search&msg=update&OrderNo=".$orderId.'&reference='.$referenceStatus));
		else
		header("location:ViewOrderDetails.php?urlstring=".EncryptURL("action=&msg=update"));
		
	break;
	case 'Search':
		$orderStatus =(isset($_POST["table_search"])) ? $_POST["table_search"] : $paramsArray["reference"];
		$AdminObject=new HomeManager();
		$AdminAllDetailsPaging=$AdminObject->GetAllProductOrderedDetails($orderStatus);
		$action="";
	break;
}
?>  

<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
<link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css">
<link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
<link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
<link rel="stylesheet" href="../css/praveen_template.css">		  

<div class="content-wrapper">
	<section class="content-header">
		<ol class="breadcrumb">
			<li><a href="../User/Home.php"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Order Details</li>
		</ol>
	</section>
<br/>
<!-- Content Header (Page header) -->
<?php	
if(isset($paramsArray['msg']))
{
	$msg=$paramsArray['msg'];	
?>
	<div class="col-md-12 col-sm-12 col-xs-12 ">
	<?php	
	if($msg=='insert')
	{	
	?>
		<div class="alert alert-success alert-dismissible" style="height:50px;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
			<h4><i class="icon fa fa-check"></i> Order has been added successfully</h4>
		</div>	
	<?php	
	}
	else if($msg=='error')
	{	
	?>
		<div class="alert alert-danger alert-dismissible" style="height:50px;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
			<h4><i class="icon fa fa-ban"></i>Order No - <?php echo $paramsArray["OrderNo"]; ?> Error in Process</h4>
		</div>
	<?php	
	}
	else if($msg=='update')
	{
	?>
		<div class="alert alert-success alert-dismissible" style="height:50px;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
			<h4><i class="icon fa fa-check"></i>Order No - <?php echo $paramsArray["OrderNo"]; ?> Status has been updated successfully</h4>
		</div>
	<?php 
	}
	else if($msg=='delete')
	{
	?>
		<div class="alert alert-success alert-dismissible" style="height:50px;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
			<h4><i class="icon fa fa-check"></i> Order has been deleted successfully</h4>
		</div>
	<?php
	}	
	?>
	</div>
<?php
}	
?>
<script src="../js/jquery-1.11.2.min.js"></script>
<script language="javascript" type="text/javascript" src="../js/jquery.coolfieldset.js"></script>
<link rel="stylesheet" type="text/css" href="../bootstrap/css/jquery.coolfieldset.css" />
<div class="col-md-12 col-sm-12 col-xs-12 ">
</div>
<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box" id="SibsSchool">
				<div class="box-header">
				<h3 class="box-title">Order Details</h3>
				</div>
				<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
				<script src="../bootstrap/js/bootstrap.min.js"></script>
				<script src="../js/func_ajax.js"></script>
				<div class="box-tools">
				<form  action="ViewOrderDetails.php?urlstring=<?php echo EncryptURL("action=Search") ?>" method="post">
				<div class="input-group input-group-sm" style="width: 250px;">
				
					<select  class="form-control pull-right" name="table_search">
					<!--<option value="">Select Status</option>-->
						<option value="Payment Successful" <?php if(isset($orderStatus) && $orderStatus!="" && $orderStatus=='Payment Successful') echo "selected"; ?>>
						Payment Successful</option>
						<option value="Invoice Payment Successful" <?php if(isset($orderStatus) && $orderStatus!="" && $orderStatus=='Invoice Payment Successful') 
						echo "selected"; ?>>Invoice Payment Successful</option>
						<option value="Payment Failed" <?php if(isset($orderStatus) && $orderStatus!="" && $orderStatus=='Payment Failed') echo "selected"; ?>>
						Payment Failed</option>
						<option value="Dispatched" <?php if(isset($orderStatus) && $orderStatus!="" && $orderStatus=='Dispatched') echo "selected"; ?>>Dispatched</option>
						<option value="Delivered" <?php if(isset($orderStatus) && $orderStatus!="" && $orderStatus=='Delivered') echo "selected"; ?>>Delivered</option>
					</select>
				<div class="input-group-btn">
					<button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
				</div>
				</div>
				</form>	
                </div>
				<div class="box-body">
				<?php
				if($action=="")
				{
					$limit=100000;
					$start_from=($page-1)*$limit;
					$AdminObject=new HomeManager();
					if(!isset($AdminAllDetailsPaging))
					{
						$OrderStatus="Payment Successful";
						$AdminObject=new HomeManager();
						$AdminAllDetailsPaging=$AdminObject->GetAllProductOrderedDetails($OrderStatus);
					}
					else
						$OrderStatus=$orderStatus;
					?>
					<div id="exportopenticket">
					<table id="" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th class="text_align_center">S.No</th>
								<th class="text_align_center">Order No</th>
								<th class="text_align_center">Order Date</th>
								<th class="text_align_center">Customer Name</th>
								<th class="text_align_center" width="30%">Delivery Add</th>
								<th class="text_align_center">Transaction No</th>
								 <th class="text_align_center">Paypal Transaction No</th>
								<th class="text_align_center">Status</th>
								<th class="text_align_center">Action</th>
							</tr>
						</thead>
					<tbody>
					<?php
					if(!empty($AdminAllDetailsPaging))
					{
						$index=1;
						foreach($AdminAllDetailsPaging as $value)
						{
						list($orderDate,$oderTime)= explode(" ",$value->ORDER_DATE);
					?>
							<tr class="common_table_header">
								<td class="text_align_left"><?php echo $index++; ?></td>
								<td class="text_align_left"><?php echo $value->ORDER_ID; ?></td>
								<td class="text_align_left"><?php echo date("Y-m-d",strtotime($orderDate)); ?></td>
								<td class="text_align_left"><?php echo $value->NAME; ?></td>
								<td class="text_align_left">
								<?php echo $value->ADDRESS; ?><br />
								City :<?php echo $value->CITY; ?><br /> 
								State :<?php echo $value->STATE; ?><br /> 
								Country :<?php echo $value->COUNTRY_NAME; ?><br /> 
								Zip :<?php echo $value->ZIP; ?><br /> 
								EU VAT Number :<?php echo $value->EU_VAT; ?>
								</td>
								<td class="text_align_left"><?php echo $value->TRANSACTION_ID; ?></td>
								<td class="text_align_left"><?php echo $value->TRANSACTION_ID; ?></td>
								<td class="text_align_left"><?php echo $value->ORDER_CURRENT_STATUS; ?></td>
								<td class="text_align_center">
								<button class="btn btn-danger btn-sm open" data-Id="<?php echo $value->ORDER_ID."_".$value->ORDER_CURRENT_STATUS.'_'.$OrderStatus ?>">
								<span class="glyphicon glyphicon-eye-open"></span>Update Status</button>
					
								<button class="btn btn-danger btn-sm open1" data-Id="<?php echo $value->ORDER_ID."_".$value->USER_ID;?>"><span class="glyphicon glyphicon-eye-open">
								</span>View details</button>	
					
	<!--<a href="ViewOrderDetails.php?urlstring=" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to Delete this record ?\n Click OK to Continue, Cancel to Stop')" ><span class="glyphicon glyphicon"  ></span> View details</a>-->
								</td>	
							</tr>
					
					<?php 
						}
					}
					else
					{
					?>
						<tr><td colspan="7" style="color:red;font-weight:bold;text-align:center">No Record found</td></tr>
					<?php
					} 
					?>
					</tbody>
					</table>
				 </div>
				<table width="100%" border="0">
					<tr>
						<td style="text-align:center">
						<div class="pagination" width:250%>
						<?php
						$countData=ceil(count($AdminAllDetails)/$limit);
						for($i=1;$i<=$countData;$i++)
						{
						?>
							<a class="active" href="ViewOrderDetails.php?urlstring=<?php echo EncryptURL("page=".$i);?>"><?php echo $i ?></a>
						<?php
						}
						?>
						</div>
						</td>													
					</tr> 
				</table>
				</div>
						 <!--<a href="ViewOrderDetails.php?urlstring=<?php echo EncryptURL("action=ExportExcel") ;?>"><button class="btn btn-success" onclick="exportToFileopen('excel')">Export to excel</button></a>
						 
					<script type="text/javascript" language="JavaScript">
							function exportToFileopen(exportTo)
							{//alert('hii');
							var pdfData = document.getElementById("exportopenticket").innerHTML;
							document.getElementById("hiddenExportData").value = pdfData;
							document.getElementById("exportTo").value = exportTo;
							document.forms["exportForm"].submit();
							}
					</script>-->
	
			</div>
		</div>
	<div id="Open_popup_modal_show_id" class="modal fade" tabindex="-1"></div>
	<script src="../js/jquery-1.11.2.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
	var $modal = $('#Open_popup_modal_show_id');
	$('.open').on('click', function(){
		var val=$(this).attr('data-Id');
		
		$modal.load('UpdateOrderStatus.php',{'val': val},
		function(){
		//alert(val);
		$modal.modal('show');
		});
	});
	});
	</script>
		<div id="Open1_popup_modal_show_id" class="modal fade" tabindex="-1"></div>
		<script src="../js/jquery-1.11.2.min.js"></script>
		<script type="text/javascript">
		$(document).ready(function(){
		var $modal = $('#Open1_popup_modal_show_id');
		$('.open1').on('click', function(){
				var val=$(this).attr('data-Id');
				
				$modal.load('ViewOrderDetailsModal.php',{'val': val},
				function(){
				//alert(val);
				$modal.modal('show');
				});
			});
		});
		</script>
				<?php
				}
			?>
	</section>
</div>
<?php
$pageMainContent = ob_get_contents();
ob_end_clean();
$pagetitle = "View Order Details ::";
include('../MasterTemplatePage.php');
?>