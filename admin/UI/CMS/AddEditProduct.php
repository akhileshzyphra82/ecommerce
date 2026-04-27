<?php
ob_start();
ini_set('display_errors',0);
//error_reporting(E_ALL | E_STRICT);
include('../Common.php');
include('../Includes/Functions.php');
require_once ('../../UI/Config/inc_path.php');
require_once "../Includes/ConstantArray.php";
require_once ('../../BL/HomeManager.php');


//echo '<pre>'; print_r($_SESSION); die;

$objHomeManager=new HomeManager();
$arrSrchData=array();
$date=date('Y-m-d');
$paramsArray = GetQueryStringParameters();
(isset($paramsArray['action']))? $action=$paramsArray['action'] : $action="";
isset($paramsArray["msg"]) ? $msg=$paramsArray["msg"] : $msg="";
$AddProductsDetailsArray=$objHomeManager->GetAndDisplayAllAddProductsDetails($arrSrchData);
switch($action)
{	
	case 'Search':
	  
		$productCategory=$_POST['productCategory'];
		$productName=$_POST['productName'];
		$productCode=$_POST['productCode'];
		$arrSrchData=array('productCategory'=>$productCategory,'productName'=>$productName,'productCode'=>$productCode);
		$AddProductsDetailsArray=$objHomeManager->GetAndDisplayAllAddProductsDetails($arrSrchData);
		//echo "<pre>"; print_r($AddProductsDetailsArray);
		$action='';
	break;
	case "Insert":
		//echo "<pre>"; print_r($_POST);die;
		$ProductName=$_POST["product_name"];
		$EntryDate=$_POST["entry_date_1"];
		$product_code=$_POST["product_code"];
		$SubCategory=$_POST["product_sub_category_1"];
		$ProductDisplay=$_POST["product_display_1"];
		$Status=$_POST["product_status_1"];
		$product_specification=$_POST["product_specification"];
		$product_description=$_POST["product_description"];
		$Priority=$_POST["Priority"];
		$product_Details=$_POST["product_Details"];
		$ManualTitle=$_POST["manual_title_1"];
		$manual_upload_date=$_POST["file_upload_date_1"];
		$ManualFile=$_FILES[""];
		$ProductImages=$_FILES[""][""];
		$count=$_POST["addMoreUploadManual_1"];
		$count1=$_POST["addMore"];
		$count2=$_POST["addMoreUploadSample_code_1"];
		
		$InsertProductDetailArray=array("product_name"=>$ProductName,"product_entry_date"=>$EntryDate,"product_category_id"=>$SubCategory,
		"product_display"=>$ProductDisplay,"product_status"=>$Status,"product_specification"=>$product_specification,"product_description"=>$product_description,
		"product_Details"=>$product_Details,"product_manual_title"=>$ManualTitle,"product_upload_date"=>$UploadDate,"Priority"=>$Priority,"product_amt"=>$_POST["product_amt"],
		"product_tax"=>$_POST["product_tax"],"product_discount"=>$_POST["product_discount"],"product_code"=>$product_code);
		$manualArray=array();
		$imageArray=array();
		$SampleCodeArray=array();
	
		for($i=1;$i<=$count1;$i++)
		{
			$file='product_image_'.$i.'';
			$path = $_FILES['product_image_'.$i.'']['name'];
			$tmp_name = $_FILES['product_image_'.$i.'']['tmp_name'];
			$ext = pathinfo($path, PATHINFO_EXTENSION);
			$imageArray[]=array("image_name"=>$path,"image_ext"=>$ext,"image_size"=>$_FILES['product_image_'.$i.'']["size"],"image_for"=>'Product',
			"manual_upload_date"=>$manual_upload_date,"tmp_name"=>$tmp_name);
		}
		
		for($i=1;$i<=$count;$i++)
		{
			$path = $_FILES['manual_file_'.$i.'']['name'];
			$tmp_name = $_FILES['manual_file_'.$i.'']['tmp_name'];
			$ext = pathinfo($path, PATHINFO_EXTENSION);
			$manualArray[]=array("product_manual_title"=>$_POST['manual_title_'.$i.''],"image_ext"=>$ext,"image_size"=>$_FILES['manual_file_'.$i.'']["size"],
			"image_for"=>'Product Mannual',"manual_upload_date"=>$_POST['file_upload_date_'.$i.''],"tmp_name"=>$tmp_name,"URL"=>$_POST['Url_'.$i.'']);
			
		}
		
		for($i=1;$i<=$count2;$i++)
		{
			if($_POST['Sample_code_file_'.$i.'']!="")
			{
				$path = $_FILES['Sample_code_file_'.$i.'']['name'];
				$tmp_name = $_FILES['Sample_code_file_'.$i.'']['tmp_name'];
				$ext = pathinfo($path, PATHINFO_EXTENSION);
				$SampleCodeArray[]=array("image_name"=>$path,"image_ext"=>$_POST['Sample_code_file_'.$i.''],"image_size"=>$_FILES['Sample_code_file_'.$i.'']["size"],
				"Language"=>$_POST['Language_'.$i.''],"upload_date"=>$_POST['sample_code_upload_date_'.$i.''],"tmp_name"=>$tmp_name,"IDE"=>$_POST['IDE_'.$i.''],
				"Type"=>$_POST['Type_'.$i.''],"OS"=>$_POST['OS_'.$i.'']);
			}
		}
		
		if($_POST["AddProductId"]=="")
		{
			$ProductId=$objHomeManager->InsertProduct($InsertProductDetailArray,$imageArray,$manualArray,$SampleCodeArray);
			header("location:AddEditProduct.php?urlstring=".EncryptURL("action=&msg=insert"));
		}
		if($_POST["AddProductId"]!="")
		{
			$ProductId=$objHomeManager->UpdateProduct($InsertProductDetailArray,$imageArray,$manualArray,$_POST["AddProductId"],$SampleCodeArray);
			header("location:AddEditProduct.php?urlstring=".EncryptURL("action=&msg=update"));
		}
	break;
	
	case "Delete":
	
	      $AddProductId=$paramsArray["AddProductId"];
		  
		 
		  $result=$objHomeManager->DeleteAddedProductData($AddProductId);
		  
		 // echo '<pre>'; print_r($result);die;
		  if($result=='Deleted')
		  {
			  $resultforImage=$objHomeManager->GetProductData($AddProductId);
			  foreach($resultforImage as $value)
			  { 
				if($value->IMAGE_FOR=='Product')
				{
					$path="ProductImages";
					$for="productImages";
				}
				if($value->IMAGE_FOR=='Product Mannual')
				{
					$path="ProductManuals";
					$for="productManual";
				}
				$target_file="../../UI/Images/".$path."/".$value->IMAGE_ID."_".$for.".".$value->IMAGE_EXT;
				unlink($target_file);
			  }
		  }
		  header("location:AddEditProduct.php?urlstring=".EncryptURL("action=&msg=".$result));

	break;
	case "Remove":
			//echo "<pre>";print_r($paramsArray); die;
	      $ImageId=$paramsArray["IMAGE_ID"];
		  $ext=$paramsArray["ext"];
		  $imageFor=$paramsArray["imageFor"];
		  $AddProductId=$paramsArray["AddProductId"];
		  $result=$objHomeManager->DeleteProductImage($ImageId,$ext,$imageFor);
		  header("location:AddEditProduct.php?urlstring=".EncryptURL("action=Add&msg=delete&AddProductId=".$AddProductId));

	break;
	case "RemoveSampleCode":
	      $SampleCodeID=$paramsArray["SampleCodeID"];
		  $ext=$paramsArray["ext"];
		  $imageFor=$paramsArray["imageFor"];
		  $AddProductId=$paramsArray["AddProductId"];
		  $result=$objHomeManager->DeleteSampleCode($SampleCodeID,$ext);
		  header("location:AddEditProduct.php?urlstring=".EncryptURL("action=Add&msg=delete&AddProductId=".$AddProductId));

	break;
	
	case 'ExportExcel' :

		$body='';
		$body='<table width="100%" border="1" cellspacing="0" cellpadding="0" class="table"  style="background-color:#dff7f3">
				<tr style="background-color:#91afb8">
					<th class="text_align_center">S.No</th>
					<th class="text_align_center">Name</th>
					<th class="text_align_center">Code</th>
					<th class="text_align_center">Price</th>
					<th class="text_align_center">Category</th>
					<th class="text_align_center">Entry Date</th>
					<th class="text_align_center">Status</th>
					<th class="text_align_center">Display</th>
				</tr>
				';
		
		$AddProductsDetailsArray=$objHomeManager->GetAndDisplayAllAddProductsDetails($arrSrchData);

		if(count($AddProductsDetailsArray)>0)
		{
			$index=1;	
			foreach($AddProductsDetailsArray as $value)
			{
				$body.='
					<tr>
						<td>'.$index++ .'</td>
						<td>'.$value->PRODUCT_NAME.'</td>
						<td>'.$value->PRODUCT_CODE.'</td>
						<td>'.$value->PRODUCT_AMT.'</td>
						<td>'.$value->PRODUCT_CATEGORY_NAME.'</td>
						<td>'.$value->PRODUCT_ENTRY_DATE.'</td>
						<td>'.$value->PRODUCT_STATUS.'</td>
						<td>'.$value->DISPLAY_FLAG.'</td>
					</tr>';
			}
		}
		else
		{
			$body.='
			<tr>
					<td colspan="27">No Product Found.</td>
			</tr>';
		}
		$body.='</table>';
	
		//print_r($body); die;
		$filename="ProductList.xls";
		excelReport($body, $filename);
		exit();	
	break;
}
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
		<ol class="breadcrumb">
			<li><a href="../User/Home.php"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Add Product</li>
		</ol>
	</section>
	<br/>
<!-- Content Header (Page header) -->
	<div class="col-md-12 col-sm-12 col-xs-12 ">
		<div class="input-group">
			<a href="AddEditProduct.php?urlstring=<?php echo EncryptURL('action=Add'); ?>"> <button type="button" class="btn btn-primary">Add Product</button></a>
		</div>
	</div>
	<div class="col-md-12 col-sm-12 col-xs-12 ">
		<div class="input-group">
			<a href="AddEditProduct.php?urlstring=<?php echo EncryptURL('action=Add'); ?>"> </a>
		</div>
	</div>

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
				<h4><i class="icon fa fa-check"></i> Products has been deleted successfully</h4>
			</div>
		<?php
		}	
		else if($msg!='')
		{
			?>
			<div class="alert alert-success alert-dismissible" style="height:50px;">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
				<h4><i class="icon fa fa-check"></i> <?php echo $msg; ?></h4>
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
				<div class="row">
					<div class="col-xs-12">
						<form  action="AddEditProduct.php?urlstring=<?php echo EncryptURL('action=Search'); ?>" method="post">
							<?php
							$CategoryDetailArray=$objHomeManager->GetAndDisplayAllListProduct();
							
							//echo '<pre>'; print_r($CategoryDetailArray); die;
							?>
							<div class="col-md-3 col-sm-3 col-xs-3 ">
								Product Category: 
								<select  class="form-control" name="productCategory">
									<option value="">Select Category</option>
									<?php 
									if(count($CategoryDetailArray)>0)
									{
										foreach($CategoryDetailArray as $subCat)
										{
											if($subCat->PARENT_CATEGORY_ID!=0)
											{
											?>
											<option value="<?php echo $subCat->PRODUCT_CATEGORY_ID; ?>" <?php if($_POST['productCategory']==$subCat->PRODUCT_CATEGORY_ID) echo 'selected';?>> <?php echo $subCat->PRODUCT_CATEGORY_NAME; ?></option>
											<?php
											}
										}
									}
									?>
								</select>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-3 ">
								Product Name:
								<input type="text" name="productName" id="productName"  class="form-control"  value="<?php echo  $_POST['productName'];?>"/> 
							</div>  
							<div class="col-md-3 col-sm-3 col-xs-3 ">
								Product Code:
								<input type="text" name="productCode" id="productCode" class="form-control"  value="<?php echo  $_POST['productCode'];?>" /> 
							</div> 
							<br/>						 
							<div class="input-group-btn">
								
								<button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
							</div>
					 </form>	
					</div>
				</div>
				<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
				<script src="../bootstrap/js/bootstrap.min.js"></script>
				<script src="../js/func_ajax.js"></script>
                <div style="float:right">
                <table>
                    <tr>
                        <td> 
                         <a href="AddEditProduct.php?urlstring=<?php echo EncryptURL('action=ExportExcel'); ?>">
                         <button class="btn btn-success" onclick="exportToFileopen('excel')">Export to excel</button></a> 
                        </td> 
                    </tr> 	 
                </table>
                </div>
				<div class="box-body">
				<?php
				
				
				//echo '<pre>';print_r($AddProductsDetailsArray); die;
				if(!empty($AddProductsDetailsArray))
				{
				?>
					<table id="" class="table table-bordered table-striped">
						<thead>
							<tr>
							<th class="text_align_center">S.No</th>
							<th class="text_align_center">Name</th>
							<th class="text_align_center">Code</th>
							<th class="text_align_center">Price</th>
							<th class="text_align_center">Category</th>
							<th class="text_align_center">Entry Date</th>
							<th class="text_align_center">Status</th>
							<th class="text_align_center">Display</th>
							<th class="text_align_center">Action</th>
							</tr>
						</thead>
					<tbody>
				<?php 
				if(!empty($AddProductsDetailsArray))
				{
					$index=1; 
					foreach($AddProductsDetailsArray as $AddProductsDetails)
					{ 
				 	?>
						<tr class="common_table_header">
						<td class="text_align_left"><?php echo $index++; ?></td>
						<td class="text_align_left"><?php echo $AddProductsDetails->PRODUCT_NAME; ?></td>
						<td class="text_align_left"><?php echo $AddProductsDetails->PRODUCT_CODE; ?></td>
						<td class="text_align_left"><?php echo $AddProductsDetails->PRODUCT_AMT; ?></td>
						<td class="text_align_left"><?php echo $AddProductsDetails->PRODUCT_CATEGORY_NAME; ?></td>
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
				</tr></thead></table>
			
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
	if(isset($paramsArray["AddProductId"]))
	{
		$AddProductId=$paramsArray["AddProductId"];
		
		$result=$objHomeManager->GetProductData($AddProductId);
		//echo "<pre>"; print_r($result); die;
		$ArrForSampleCode=array();
		$ArrForImage=array();
		foreach($result as $val)
		{
			$ArrForSampleCode[$val->PRODUCT_SAMPLE_CODE_ID]=array("ext"=>$val->EXT,"LANGUAGE_TECHNOLOGY"=>$val->LANGUAGE_TECHNOLOGY,
			"IDE_COMPILER"=>$val->IDE_COMPILER,"TYPE"=>$val->TYPE,"OS"=>$val->OS,"DATE"=>$val->DATE);
			$ArrForImage[$val->IMAGE_ID]=array("ext"=>$val->IMAGE_EXT,"IMAGE_NAME"=>$val->IMAGE_NAME,"IMAGE_FOR"=>$val->IMAGE_FOR,
			"PRODUCT_MANUAL_TITLE"=>$val->PRODUCT_MANUAL_TITLE,"IMAGE_SIZE"=>$val->IMAGE_SIZE,"MANUAL_UPLOAD_DATE"=>$val->MANUAL_UPLOAD_DATE,"HYPER_LINK"=>$val->HYPER_LINK);
			//echo "<pre>"; print_r($ArrForImage); die;
		}
	}
	function getCategory($catId)
	{
	$CategoryList=$objHomeManager->GetAllSubProductDetailByProductCatId($catId);
	foreach($CategoryList as $row)
	{
		$rows1 = $objHomeManager->GetAllSubProductDetailByProductCatId($row->PRODUCT_CATEGORY_ID);//return all child of the category
	?>
		<option value="<?php echo $row->PRODUCT_CATEGORY_ID; ?>" style="font-weight:<?php if($row->PARENT_CATEGORY_ID == 0) echo '1000';?>"
		<?php if( isset($result) && $row->PRODUCT_CATEGORY_ID == $result[0]->PRODUCT_CATEGORY_ID ) echo " selected"; ?>><?php   echo $row->PRODUCT_CATEGORY_NAME;  ?></option>
	<?php
		getCategory($row->PRODUCT_CATEGORY_ID);
	}
}
	?>

<link rel="stylesheet" href="../css/praveen_template.css">
<div class="content-wrapper"> 
<section class="content-header">
	<h1><?php if(isset($result)) echo "Update Product"; else echo "Add Product"; ?></h1>
	<ol class="breadcrumb">
		<li><a href="../9User/Home.php"><i class="fa fa-dashboard"></i>Home</a></li>
		<li class="active"><?php if(isset($result)) echo "Update Product"; else echo "Add Product"; ?></li>
	</ol>
</section>
<section class="content">
	<form action="AddEditProduct.php?urlstring=<?php echo EncryptURL('action=Insert'); ?>" name="module" method="post" enctype="multipart/form-data" 
	id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" onSubmit="return validateAdd(this)" >

		<div class="row">
			<div class="col-md-12 text-center">
				<h2>Product</h2>
				<div class="box box-primary">
						<div class="box-body" id="div1">
						  <div class="box-border" style="min-height:400px">
							<div class="col-md-12" style="background-color:#005abb;color:#FFFFFF">
							   <label class="control-label" style="font-size:18px;">Basic Info</label>
							</div>
							<div class="form-group" align="left">
								<div class="col-md-6 col-sm-12 col-xs-12">
									<label class="control-label" for="last-name"> Product Name<span class="MandatoryField" style="color:#FF0000">*</span></label><br />
									<input type="text" name="product_name" id="product_name_1"  value="<?php if(isset($result)) echo $result[0]->PRODUCT_NAME; ?>" 
									class="form-control col-md-12">
								</div>		
								<?php
								$CategoryDetailArray=$objHomeManager->GetAndDisplayAllListProduct();
								?>		 
								<div class="form-group">
									<div class="col-md-3 col-sm-12 col-xs-12" align="left">
									<label class="control-label" for="last-name">Product Category</label><span class="MandatoryField" style="color:#FF0000">*</span><br />
									 <div id="sub_cat_div">
									<select name="product_sub_category_1" id="product_sub_category_1"   class="form-control col-md-12" >
										<option value="">Select Category</option>
										<?php 
										if(count($CategoryDetailArray)>0)
										{
											foreach($CategoryDetailArray as $subCat)
											{
											?>
												<option value="<?php echo $subCat->PRODUCT_CATEGORY_ID; ?>" 
												<?php if( isset($result) && $subCat->PRODUCT_CATEGORY_ID == $result[0]->PRODUCT_CATEGORY_ID ) echo " selected"; ?>>
												<?php echo $subCat->PRODUCT_CATEGORY_NAME; ?></option>
											<?php
											}
										}
										?>
									</select>
									</div>
									</div>
									<div class="col-md-2 col-sm-12 col-xs-12" align="left">
									<label class="control-label" for="last-name">Product Code</label><span class="MandatoryField" style="color:#FF0000">*</span><br />
										<input type="text" name="product_code" id="product_code"  value="<?php if(isset($result)) echo $result[0]->PRODUCT_CODE;  ?>" 
										class="form-control " required>
									</div>
									</div>
							</div>
							  <div class="form-group" align="left">
								<div class="col-md-4 col-sm-12 col-xs-12">
									<label class="control-label" for="Entry Date">Entry Date<span class="MandatoryField" style="color:#FF0000">*</span></label><br />
										<input type="text" name="entry_date_1" id="entry_date_1"  value="<?php if(isset($result)) echo $result[0]->PRODUCT_ENTRY_DATE;
										 else echo date('Y-m-d', time()); ?>" class="form-control col-md-12 date2" required>
								</div>		
								
								<div class="col-md-4 col-sm-12 col-xs-12" align="left">
									<label class="control-label" for="Display">Display<span class="MandatoryField" style="color:#FF0000">*</span></label><br />
									<select name="product_display_1" id="product_display_1"   class="form-control col-md-12" >
										<option value="Yes" <?php if(  isset($result) && $result[0]->DISPLAY_FLAG=='Yes') echo "selected"; ?>>Yes</option>
										<option value="No" <?php if( isset($result) && $result[0]->DISPLAY_FLAG=='No') echo "selected"; ?>>No</option>
									</select>
								</div>
								<div class="col-md-4 col-sm-12 col-xs-12" align="left">
								<label class="control-label" for="last-name">Priority<span class="MandatoryField" style="color:#FF0000">*</span></label><br />
									<input type="text" name="Priority" id="Priority"  class="form-control col-md-12 " value="<?php if(isset($result)) echo $result[0]->PRIORTY; ?>" >
								</div>
							</div>
							<div class="form-group" align="left">
							<div class="col-md-4 col-sm-12 col-xs-12">
							<label class="control-label" for="Entry Date">Amount<span class="MandatoryField" style="color:#FF0000">*</span></label><br />
								<input type="text" name="product_amt" id="product_amt" class="form-control col-md-12 " value="<?php if(isset($result)) 
								echo $result[0]->PRODUCT_AMT; ?>">
							</div>		
							<div class="col-md-4 col-sm-12 col-xs-12" align="left">
							<label class="control-label" >Tax%</label><br />
								<input type="text" name="product_tax" id="product_tax"  class="form-control col-md-12 "
								value="<?php if(isset($result)) echo $result[0]->PRODUCT_TAX; ?>" >
							</div>
								<div class="col-md-4 col-sm-12 col-xs-12" align="left">
								<label class="control-label" for="last-name">Discount%</label><br />
								<input type="float" name="product_discount" id="product_discount"  class="form-control col-md-12 " 
								value="<?php if(isset($result)) echo $result[0]->PRODUCT_DISCOUNT; ?>">
								</div>
							</div>
								
							<div class="form-group" align="left">
							<div class="col-md-3 col-sm-12 col-xs-12">
							<label class="control-label" for="last-name">Product Images</label> <button type="button" class="btn btn-danger btn-xs pull-right" 
							onclick="javascript:removeNode()"><span class="glyphicon glyphicon-remove"></span> Remove</button>
							<button type="button" class="btn btn-danger btn-xs pull-right" onclick="javascript:getNextRow()" ><i class="glyphicon glyphicon-plus">
							</i> Add More</button>	<br />
								<input type="file" name="product_image_1" id="product_image_1"  class="form-control col-md-12" onchange="ValidateSize(this)" >
								<div id="addMore_1"></div>
								<input type="hidden" name="addMore" id="total" value="1">
								<input type="hidden" name="AddProductId" id="AddProductId" value="<?php echo $AddProductId; ?>">
							</div>		
							<div class="col-md-3 col-sm-12 col-xs-12">
							<?php
							if(isset($result))
							{
							
								//echo '<pre>'; print_r($ArrForImage);
							
								foreach($ArrForImage as $key=>$value)
								{ 
									if($value['IMAGE_FOR']=="Product" && $value['ext']!="")
									{
							?>
							<img src='<?php if(isset($result)) echo "../Images/ProductImages/".$key."_productImages.".$value['ext']; ?>' style="width:150px;" />
							<a href="AddEditProduct.php?urlstring=<?php echo EncryptURL('action=Remove&IMAGE_ID='.$key.'&ext='.$value['ext'].'&imageFor='.$value['IMAGE_FOR'].'&AddProductId='.$AddProductId); ?>" class="btn btn-danger btn-xs edit" style="padding-top:0%;" title="Remove this image"><span class="glyphicon glyphicon-remove"></span></a>
							<?php 
									} 
								}
							} 
							?>
							</div>
							<div class="col-md-6 col-sm-12 col-xs-12" align="left">
							<label class="control-label" for="last-name">Status</label><br />
								<select name="product_status_1" id="product_status_1"   class="form-control col-md-12" >
									<option value="Active" <?php if( $result[0]->PRODUCT_STATUS=='Active' ) echo " selected"; ?>>Active</option>
									<option value="In-Active" <?php if( $result[0]->PRODUCT_STATUS=='In-Active' ) echo " selected"; ?>>In-Active</option>
								</select>
							</div>
							</div>		
							<div class="col-md-12" style="background-color:#005abb;color:#FFFFFF">
							<label class="control-label" style="font-size:18px">Technical Description</label>
							</div>
							<div class="form-group">
							<div class="col-md-1 col-sm-12 col-xs-12" align="left"></div>
							<div class="col-md-10 col-sm-12 col-xs-12" align="left">
							<label class="control-label " for="last-name">Product Details</label><br/>
								<textarea name="product_Details" id="product_Details" rows="7"   class="form-control ckeditor col-md-12">
								<?php if(isset($result)) echo $result[0]->PRODUCT_DETAILS; ?></textarea>
							</div>
							<div class="col-md-1 col-sm-12 col-xs-12" align="left"></div>
							</div>						
							<div class="form-group"> 
							<div class="col-md-6 col-sm-12 col-xs-12 " align="left" >
							<label class="control-label " for="last-name">Product Description</label><br/>
								<textarea rows="7" id="product_description" name="product_description" class="form-control ckeditor col-md-12">
								<?php if(isset($result)) echo $result[0]->PRODUCT_DESCRIPTION; ?></textarea>
							</div>
							<div class="col-md-6 col-sm-12 col-xs-12" align="left" >
							<label class="control-label " for="last-name">Product Specification</label><br/>
								 <textarea rows="7" id="product_specification" name="product_specification"  class="form-control ckeditor col-md-12">
								 <?php if(isset($result)) echo $result[0]->PRODUCT_SPECIFICATION; ?></textarea>
							</div>	
							</div>								
									<?php
									$i=0;
									foreach($ArrForImage as $key=>$value)
									{
										if($value['MANUAL_UPLOAD_DATE']!="" )
										{
											$i=1;
										}
									}
									?>
							  
							<div class="col-md-12" style="background-color:#005abb;color:#FFFFFF" >
							<label class="control-label" style="font-size:18px">Downloads</label>
							</div>
							
							<div class="form-group col-md-12" <?php if(!isset($ArrForImage) || $i==0) echo 'style="display:none;"'; ?> >
							
							<div class="col-md-3 col-sm-12 col-xs-12" align="left">
							<label class="control-label" for="last-name"> Title</label>
							</div>
							
							<div class="col-md-3 col-sm-12 col-xs-12" align="left">
							<label class="control-label" for="last-name">URL</label>
							</div>
							
							<div class="col-md-3 col-sm-12 col-xs-12" align="left">
							<label class="control-label" for="last-name">Download </label>
							</div>
							
							<div class="col-md-3 col-sm-12 col-xs-12" align="left">
							<label class="control-label" for="last-name">Delete</label>
							</div>
							<?php
							
							foreach($ArrForImage as $key=>$value)
							{ 
								if($value['IMAGE_FOR']=="Product Mannual" )
								{
							?>
							
								<div class="col-md-3 col-sm-12 col-xs-12" align="left">
									<a href="<?php echo $value['HYPER_LINK']; ?>" target="_blank" ><?php  if($value['PRODUCT_MANUAL_TITLE']!="")
									echo $value['PRODUCT_MANUAL_TITLE']; else echo "URL"; ?></a>
								</div>
								
								<div class="col-md-3 col-sm-12 col-xs-12" align="left">
								<?php  echo $value['PRODUCT_MANUAL_TITLE']; ?>
								</div>
								<div class="col-md-3 col-sm-12 col-xs-12" align="left">
									<a href="<?php   echo "../Images/ProductManuals/".$key."_productManual.".$value['IMAGE_EXT']; ?>" 
									target="_blank" ><span class="glyphicon glyphicon-file" style="width:150px;">Download</span></a>
								</div>
								<div class="col-md-3 col-sm-12 col-xs-12" align="left">
									<a href="AddEditProduct.php?urlstring=<?php echo EncryptURL('action=Remove&IMAGE_ID='.$key.'&ext='.$value['IMAGE_EXT'].'&imageFor='.$value['IMAGE_FOR'].'&AddProductId='.$AddProductId); ?>" 
									class="btn btn-danger btn-xs " style="padding-top:0%;" title="Remove this image"><span class="glyphicon glyphicon-remove">Delete</span></a>
								</div>
							 <?php
								}
							 }
							
							 ?>
						
							</div>
							<div class="form-group col-md-12"  >
							<div class="col-md-3 col-sm-12 col-xs-12" align="left">
								<label class="control-label" for="last-name">Download Title</label>
								<input type="text" id="manual_title_1" name="manual_title_1"   class="form-control col-md-6" >
								</div>
							<div class="col-md-3 col-sm-12 col-xs-12" align="left">
								<label class="control-label" >Download File</label>	
								<input type="file" id="manual_file_1" name="manual_file_1"   class="form-control col-md-6" onchange="ValidateSize(this)">
								<input type="hidden" name="addMoreUploadManual_1" id="total_upload" value="1">
							</div>
							
							<div class="col-md-4 col-sm-12 col-xs-12" align="left">
								<label class="control-label" for="last-name">Url</label>
								<input type="text" id="Url_1" name="Url_1"   class="form-control "   >
							</div>
							<div class="col-md-2 col-sm-12 col-xs-12" align="left">
								<label class="control-label" for="last-name">Upload Date</label>
								<input type="text" id="sample_code_upload_date_1" name="sample_code_upload_date_1"   class="form-control col-md-6 date2" 
								value="<?php  echo date('Y-m-d', time()); ?>"  ><button type="button" class="btn btn-danger btn-xs pull-right" 
								onclick="javascript:removeManualUploadNode()"><span class="glyphicon glyphicon-remove"></span> Remove</button>&nbsp;
								<button type="button" class="btn btn-danger btn-xs pull-right" onclick="javascript:getNextManualUploadRow()" style="margin-right: 3px;">
								<i class="glyphicon glyphicon-plus"></i> Add More</button>
							</div>
							</div>	
							<div id="addMoreUploadManual_1"></div>
							<div class="col-md-12" style="background-color:#005abb;color:#FFFFFF">
							<label class="control-label" style="font-size:18px">Sample code</label>
							</div>
							<?php
							$i=0;
							foreach($ArrForSampleCode as $key=>$value)
							{ 
								if($value['ext']!="" )
								{
									$i=1;
								}
							}
							?>
							<div class="form-group col-md-12" <?php if(!isset($ArrForSampleCode) || $i==0) echo 'style="display:none;"'; ?> >
							
							<div class="col-md-2 col-sm-12 col-xs-12" align="left">
							<label class="control-label" for="last-name"> Language/Technology</label>
							
							</div>
							<div class="col-md-2 col-sm-12 col-xs-12" align="left">
							<label class="control-label" for="last-name">IDE/Compiler</label>
							
							</div>
							<div class="col-md-2 col-sm-12 col-xs-12" align="left">
							<label class="control-label" for="last-name">Type </label>
							
							</div>
							<div class="col-md-2 col-sm-12 col-xs-12" align="left">
							<label class="control-label" for="last-name">OS</label>
							</div>
							<div class="col-md-2 col-sm-12 col-xs-12" align="left">
							<label class="control-label" for="last-name">url</label>
							</div>
							<div class="col-md-2 col-sm-12 col-xs-12" align="left">
							<label class="control-label" for="last-name">Delete</label>
						 </div>
						 <?php
						 foreach($ArrForSampleCode as $key=>$value)
						 { 
							 if($value['ext']!="" )
							 {
						?>
								<div class="col-md-2 col-sm-12 col-xs-12" align="left">
								<?php  echo $value['LANGUAGE_TECHNOLOGY']; ?>
								</div>
								<div class="col-md-2 col-sm-12 col-xs-12" align="left">
								<?php  echo $value['IDE_COMPILER']; ?>
								</div>
								<div class="col-md-2 col-sm-12 col-xs-12" align="left">
								<?php  echo $value['TYPE']; ?>
								</div>
								<div class="col-md-2 col-sm-12 col-xs-12" align="left">
								<?php  echo $value['OS']; ?>
								</div>
								<div class="col-md-2 col-sm-12 col-xs-12" align="left">
								<a href="<?php echo $value['ext']; ?>" target="_blank" ><span class="glyphicon glyphicon-file" style="width:150px;">Download</span></a>
								</div>
								<div class="col-md-2 col-sm-12 col-xs-12" align="left">
								<a href="AddEditProduct.php?urlstring=<?php echo EncryptURL('action=RemoveSampleCode&SampleCodeID='.$key.'&ext='.$value['ext'].'&AddProductId='.$AddProductId); ?>" class="btn btn-danger btn-xs " style="padding-top:0%;" title="Remove this image"><span class="glyphicon glyphicon-remove">Delete</span></a>
								
								</div>
					
						<?php
						
							 }
						}
						 ?>
						
							</div>
							<div class="form-group col-md-12">
							<div class="col-md-2 col-sm-12 col-xs-12" align="left">
								<label class="control-label" for="last-name">Language/Technology</label>
									<input type="text" id="manual_title_1" name="Language_1"   class="form-control col-md-6" >
								</div>
								<div class="col-md-2 col-sm-12 col-xs-12" align="left">
								<label class="control-label" for="last-name">IDE/Compiler</label>
									<input type="text" id="IDE_1" name="IDE_1"   class="form-control col-md-6" >
								</div>
								<div class="col-md-2 col-sm-12 col-xs-12" align="left">
								<label class="control-label" for="last-name">Type</label>
									<input type="text" id="Type_1" name="Type_1"   class="form-control col-md-6" >
								</div>
								<div class="col-md-2 col-sm-12 col-xs-12" align="left">
								<label class="control-label" for="last-name">OS</label>
									<input type="text" id="OS_1" name="OS_1"   class="form-control col-md-6" >
								</div>
								<div class="col-md-2 col-sm-12 col-xs-12" align="left">
								<label class="control-label" >URL</label>
									<input type="text" id="Sample_code_file_1" name="Sample_code_file_1"   class="form-control " >
									<input type="hidden" name="addMoreUploadSample_code_1" id="total_Sample_code_upload" value="1">
								</div>
								<div class="col-md-2 col-sm-12 col-xs-12" align="left">
								<label class="control-label" for="last-name">Upload Date</label>
									<input type="text" id="file_upload_date_1" name="file_upload_date_1"   class="form-control col-md-6 date2" 
									value="<?php  echo date('Y-m-d', time()); ?>"  ><button type="button" class="btn btn-danger btn-xs pull-right" 
									onclick="javascript:removeSampleUploadNode()"><span class="glyphicon glyphicon-remove"></span> Remove</button>&nbsp;
									<button type="button" class="btn btn-danger btn-xs pull-right" onclick="javascript:getNextSampleUploadRow()" style="margin-right: 3px;">
									<i class="glyphicon glyphicon-plus"></i> Add More</button>	
								</div>
								
						   </div>	
						   <div id="addMoreUploadSample_1"></div>		
						</div>		
					</div>
				</div>
			</div>
		</div>
		<div class="form-group text-center">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<a href="AddEditProduct.php"><button   type="button"  class="btn btn-danger" >Cancel</button></a>
				<button type="submit" class="btn btn-success" onclick="return confirm('Are You Sure you want to Save it?\n Click OK to Continue, Cancel to Stop')">Submit
				</button>
			</div>
		</div>

	</form>
</section> 
</div>
<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script>
var kp = $.noConflict();
kp(function (){
	kp("#example1").DataTable();
	kp('#example2').DataTable({
		"paging": true,
		"lengthChange": false,
		"searching": false,
		"ordering": true,
		"info": true,
		"autoWidth": false
	});
});
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
	$(".AddDownload").click(function(){
           $("#AddDownload").css("display", "inline");
    });
});
</script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script>
function validateAdd()
{
	var display='';
	
	if(document.getElementById("product_name_1").value=="")
		display = "The field product name can not left blank.\n" ;

	if(document.getElementById("product_code").value=="")
		display = "The field product code can not left blank.\n" ;
	
	if(document.getElementById("product_sub_category_1").value=="")
		display =display+ "The field Product category can not left blank.\n" ;

	if(document.getElementById("product_display_1").value=="")
		display =display+ "The field display can not left blank.\n" ;

	if(document.getElementById("Priority").value=="")
		display =display+ "The field Priority can not left blank.\n" ;
	
	if(document.getElementById("product_amt").value=="")
		display =display+ "The field amount can not left blank.\n" ;
	
	if(display != '')
		return MsgDisplay(display);
}
function ValidateSize(file) 
{
 		var display='';
        var FileSize = file.files[0].size / 1024 / 1024; // in MB
        if (FileSize > 4) {
           
			display =display+ "File size exceeds 4 MB.\n" ;
        } 
		if(display != '')
		return MsgDisplay(display);
}
   
function MsgDisplay(display)
{

	var msg;
	var empty_fields = '';
	var errors = '';
	
	msg = '______________________________________________________\n\n'
	msg += 'The form was not submitted because of the following error(s).\n';
	msg += 'Please correct these error(s) and re submit.\n';
	msg += '______________________________________________________\n\n'
	if  (empty_fields) {

		msg += '- Please fill in all required fields:\n'
		msg += '- The following field(s) are empty:\n'
				+ empty_fields + '\n';
		if (errors) msg += '\n';
}
errors +=display;
msg += errors;
alert(msg);
return false;
}

</script>
<script>
$(function () {
$('.date2').datepicker({
    format: 'yyyy-mm-dd',
    startDate: '-3d'
});
});

</script>
   


<script src="../js/func_ajax.js"></script>
<script>
function getNextRow()
{
	var r = document.getElementById('total').value;
	var div_id = "addMore_"+r;
	callAjax(div_id, "../Ajax/addMoreProductImages.php", {
	params:"row="+r,
	meth:"get",
	async:true,
	errorfunc:"ajaxError()" }
	);
	document.getElementById('total').value = Number(r)+1;
}

function getSubcategoryByCatgoryId()
{
	var val = document.getElementById('maincategory').value;
	//alert("hello")
	//var div_id = document.getElementById('hello').value;
	callAjax('sub_cat_div', "../Ajax/getSubcategoryByCategory.php", {
	params:"val="+val,
	meth:"get",
	async:true,
	errorfunc:"ajaxError()" }
	);
}
function removeNode()
{
	var r = document.getElementById('total').value;
	var a = Number(r)-1;
	var div_id = "addMore_"+a;
	document.getElementById(div_id).innerHTML = '';
	document.getElementById('total').value =Number(a);
}

function getNextManualUploadRow()
{
	var r = document.getElementById('total_upload').value;
	var div_id = "addMoreUploadManual_"+r;
	callAjax(div_id, "../Ajax/addMoreManualImages.php", {
	params:"row="+r,
	meth:"get",
	async:true,
	errorfunc:"ajaxError()" }
	);
	document.getElementById('total_upload').value = Number(r)+1;
}
function removeManualUploadNode()
{
	var r = document.getElementById('total_upload').value;
	var a = Number(r)-1;
	var div_id = "addMoreUploadManual_"+a;
	document.getElementById(div_id).innerHTML = '';
	document.getElementById('total_upload').value =Number(a);
}
function getNextSampleUploadRow()
{
	var r = document.getElementById('total_Sample_code_upload').value;
	var div_id = "addMoreUploadSample_"+r;
	callAjax(div_id, "../Ajax/addMoreSampleImages.php", {
	params:"row="+r,
	meth:"get",
	async:true,
	errorfunc:"ajaxError()" }
	);
	document.getElementById('total_Sample_code_upload').value = Number(r)+1;
}
function removeSampleUploadNode()
{
	var r = document.getElementById('total_Sample_code_upload').value;
	var a = Number(r)-1;
	var div_id = "addMoreUploadSample_"+a;
	document.getElementById(div_id).innerHTML = '';
	document.getElementById('total_Sample_code_upload').value =Number(a);
}
</script>
<?php
}
?>
<script src="../plugins/ckeditor/ckeditor.js"></script>
<?php
$pageMainContent = ob_get_contents();
ob_end_clean();
$pagetitle = "Add Product";
//Apply the template
include('../MasterTemplatePage.php');
?>