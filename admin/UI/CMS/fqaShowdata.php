<?php
ob_start();
//ini_set('display_errors',0);
//error_reporting(E_ALL | E_STRICT);
include('../Common.php');
include('../Includes/Functions.php');
require_once ('../../UI/Config/inc_path.php');
require_once "../Includes/ConstantArray.php";
require_once ('../../BL/ProductManager.php');
require_once ('../../BL/UserManager.php');
require_once ('../../BL/HomeManager.php');

$paramsArray = GetQueryStringParameters();
(isset($paramsArray['action']))? $action=$paramsArray['action'] : $action="";
isset($paramsArray["msg"]) ? $msg=$paramsArray["msg"] : $msg="";
if($action=="")
{
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
	<div class="box-header">
	<h3 class="box-title">List Product</h3>
	</div>
	<ol class="breadcrumb">
		<li><a href="../User/Home.php"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Add Product</li>
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
			<h4><i class="icon fa fa-check"></i> Products has been added successfully</h4>
		</div>	
	<?php
	}
	else if($msg=='error')
	{	
	?>
		<div class="alert alert-danger alert-dismissible" style="height:50px;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
			<h4><i class="icon fa fa-ban"></i> Error in Process</h4>
		</div>
	<?php	
	}
	else if($msg=='update')
	{
	?>
		<div class="alert alert-success alert-dismissible" style="height:50px;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
			<h4><i class="icon fa fa-check"></i>Products has been updated successfully</h4>
		</div>
	<?php 
	}
	else if($msg=='delete')
	{
	?>
		<div class="alert alert-success alert-dismissible" style="height:50px;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
			<h4><i class="icon fa fa-check"></i> Enquiry has been deleted successfully</h4>
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
				<h3 class="box-title">List Product</h3>
				</div>
				<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
				<script src="../bootstrap/js/bootstrap.min.js"></script>
				<script src="../js/func_ajax.js"></script>
				<div class="box-body">
				<?php
				$objAddProductsDetailsHomeManager = new HomeManager(); 
				$AddProductsDetailsArray=$objAddProductsDetailsHomeManager->GetAndDisplayAllAddProductsDetails();
				if(!empty($AddProductsDetailsArray))
				{
				?>
					<table id="" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th class="text_align_center">S.No</th>
								<th class="text_align_center">PRODUCT NAME</th>
								<th class="text_align_center">PRODUCT CATEGORY NAME </th>
								<th class="text_align_center">PRODUCT ENTRY DATE</th>
								<th class="text_align_center">PRODUCT STATUS</th>
								<th class="text_align_center">PRODUCT DISPLAY</th>
								<th class="text_align_center">ACTION</th>
							</tr>
						</thead>
					<tbody>
					<?php 
					if(!empty($AddProductsDetailsArray))
					{
						$index=1; 
						foreach($AddProductsDetailsArray as $AddProductsDetails)
						{ 
							$ProductList=$objAddProductsDetailsHomeManager->GetAndDisplayAllListProductById($AddProductsDetails->PRODUCT_CATEGORY_ID); 
					?>
							<tr class="common_table_header">
								<td class="text_align_left"><?php echo $index++; ?></td>
								<td class="text_align_left"><?php echo $AddProductsDetails->PRODUCT_NAME; ?></td>
								<td class="text_align_left"><?php echo $ProductList[0]->PRODUCT_CATEGORY_NAME; ?></td>
								<td class="text_align_left"><?php echo $AddProductsDetails->PRODUCT_ENTRY_DATE; ?></td>
								<td class="text_align_left"><?php echo $AddProductsDetails->PRODUCT_STATUS; ?></td>
								<td class="text_align_left"><?php echo $AddProductsDetails->DISPLAY_FLAG; ?></td>
								<td class="text_align_center">
								<a href="AddEditProduct.php?urlstring=<?php echo EncryptURL('action=Add&AddProductId='.$AddProductsDetails->PRODUCT_ID); ?>" 
								class="btn btn-info btn-xs edit"><span class="glyphicon glyphicon-view"></span></span>Edit</a>									
								<a href="AddEditProduct.php?urlstring=<?php echo EncryptURL('action=Delete&AddProductId='.$AddProductsDetails->PRODUCT_ID); ?>"
								class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to Delete this record ?\n Click OK to Continue, Cancel to Stop')" >
								<span class="glyphicon glyphicon-remove"  ></span> Del</a>
								</td>	
							</tr>
					<?php 
						}
					}
					?>
					 </tbody>
					 </table>
				<?php 			
				}
				else
				{
				?>		
					<table>
					<thead>
						<tr>
							<th style="color:#FF0000">No data found</th>
						</tr></thead>
					</table>
				<?php
				 } 
				 ?>				
					<div id="Open_popup_modal_show_id" class="modal fade" tabindex="-1"></div>
						<script src="../js/jquery-1.11.2.min.js"></script>
						<script type="text/javascript">
							$(document).ready(function(){
							var $modal = $('#Open_popup_modal_show_id');
							$('.open').on('click', function(){
							var val=$(this).attr('data-Id');
							//alert("loading wait");
							$modal.load('ViewBannerImage.php',{'val': val},
							function(){
							$modal.modal('show');
							});
							});
							});
						</script>
					</div>
			</div>
		</div>
	</div>
</section>
</div> 

<?php
} 
else if($action=='Add')
{
}

$pageMainContent = ob_get_contents();
ob_end_clean();
$pagetitle = "Add Product ::";
//Apply the template
include('../MasterTemplatePage.php');
?>