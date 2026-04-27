<?php
ob_start();
//ini_set("display_errors",0);
//error_reporting(E_ALL | E_STRICT);
include('../Common.php');
include('../Includes/Functions.php');
require_once ('../../UI/Config/inc_path.php');
require_once "../Includes/ConstantArray.php";
require_once ('../../BL/HomeManager.php');
$ProductObj=new HomeManager();

$date=date('Y-m-d');
$paramsArray = GetQueryStringParameters();
(isset($paramsArray['action']))? $action=$paramsArray['action'] : $action="";
isset($paramsArray["msg"]) ? $msg=$paramsArray["msg"] : $msg="";
switch($action)
{	
	case "Insert":
		$ProductId1=$_POST["product_category_id"];
		$ProductCategoryName=$_POST["product_category_name"]; 
		$ParentCategoryId=$_POST["maincategory"];
		$priority=$_POST["priority_1"];
		$Description=$_POST["description"];
		$Image=$_FILES["Image"]["temp_name"];
		if($_FILES["Image"]["name"]!="")
		{
			$imageName=$_FILES["Image"]["name"];
			list($imagName,$imagExt) = explode(".", strtolower($_FILES['Image']['name']));
			
		}
		
		if($_POST["product_category_id"] =="")
		{
			$ProductId=$ProductObj->InsertProductData($ProductCategoryName,$ParentCategoryId,$priority,$Description,$imagExt);
			if($ProductId)	
			{
				if($_FILES["Image"]["name"]!="")
				{
					$path="../Images/ProductCategory/".$ProductId.".".$imagExt;
					move_uploaded_file($_FILES["Image"]["tmp_name"],$path);
				}	
				header("location:AddProductCategory.php?urlstring=".EncryptURL("action=&msg=insert"));
			}
		}
		if($_POST["product_category_id"]!="")
		{
			if($_FILES["Image"]["name"]!="")
			{
				$path="../Images/ProductCategory/".$_POST["product_category_id"].".".$imagExt;
				move_uploaded_file($_FILES["Image"]["tmp_name"],$path);
			}
			$arrList=$ProductObj->UpdateProductData($ProductId1,$ProductCategoryName,$ParentCategoryId,$priority,$Description,$imagExt);
			header("location:AddProductCategory.php?urlstring=".EncryptURL("action=&msg=update"));
		}
	break;
	case "Remove":
		
		$ProductId=$paramsArray["PRODUCT_CATEGORY_ID"];
		$ext=$paramsArray["ext"];
		$result=$ProductObj->DeleteImageById($ProductId,$ext);
		header("location:AddProductCategory.php?urlstring=".EncryptURL("action=Add&msg=delete&product_category_id=".$ProductId));

	break;
	
	case "Delete":
			
		$ProductId=$paramsArray["product_category_id"];
		$result=$ProductObj->DeleteProductCategory($ProductId);
		if($result=='delete')
		{
			header("location:AddProductCategory.php?urlstring=".EncryptURL("action=&msg=delete"));
		}
		elseif($result!='')
		{
			header("location:AddProductCategory.php?urlstring=".EncryptURL("action=&msg=".$result));
		}
		else
		{
			header("location:AddProductCategory.php?urlstring=". EncryptURL("action=&msg=error"));
		}
		
	break;
	
	case "Upate":
		$ProductId=$paramsArray["product_category_id"];
		$ProductCategoryName=$_POST["product_category_name"]; 
		$ParentCategoryId=$_POST["maincategory"];
		$priority=$_POST["priority_1"];
		$arrList=$ProductObj->UpdateProductData($ProductId,$ProductCategoryName,$ParentCategoryId,$priority,$Description,$imagExt);
		header("location:AddProductCategory.php?urlstring=".EncryptURL("action=&msg=update"));
		if($ProductId!="")
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
<li class="active">Add Product Category</li>
</ol>
</section>
<br/>

<!-- Content Header (Page header) -->
<div class="col-md-12 col-sm-12 col-xs-12 ">
	<div class="input-group">
	<a href="AddProductCategory.php?urlstring=<?php echo EncryptURL('action=Add'); ?>"> <button type="button" class="btn btn-primary">Add Product Category</button></a>
	</div>
</div>
<div class="col-md-12 col-sm-12 col-xs-12 ">
	<div class="input-group">
	<a href="AddProductCategory.php?urlstring=<?php echo EncryptURL('action=Add'); ?>"> </a>
	</div>
</div>

<?php	
if
(isset($paramsArray['msg']))
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
			<h4><i class="icon fa fa-check"></i> Product  Category has been added successfully</h4>
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
	elseif($msg=='update')
	{
	?>
		<div class="alert alert-success alert-dismissible" style="height:50px;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
			<h4><i class="icon fa fa-check"></i> Product has been updated successfully</h4>
		</div>
	<?php 
	}
	elseif($msg=='delete')
	{
	?>
		<div class="alert alert-success alert-dismissible" style="height:50px;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
			<h4><i class="icon fa fa-check"></i> Product has been deleted successfully</h4>
		</div>
	<?php 
	}
	elseif($msg=='error')
	{
	?>
		<div class="alert alert-success alert-dismissible" style="height:50px;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="false">x</button>
			<h4><i class="icon fa fa-check"></i> Error in Process</h4>
		</div>
	<?php
	}
	elseif($msg!='')
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
<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box" >
				<div class="box-header">
				<h3 class="box-title"> Product List</h3>
				</div>
				<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
				<script src="../bootstrap/js/bootstrap.min.js"></script>
				<script src="../js/func_ajax.js"></script>
				<div class="box-body">
			
					<table  class="table table-bordered table-striped">
						<thead>
							<tr>
								<th class="text_align_center">S.No</th>
								<th class="text_align_center">Product category name</th>
								<th class="text_align_center">Priority</th>
								<th class="text_align_center">Parent category name</th>
								<th class="text_align_center">Action</th>
							</tr>
						</thead>
					<tbody>
						<?php
						function getCategory($catId,$index,$index1)
						{
						$objHomeManager = new HomeManager(); 
						$CategoryList=$objHomeManager->GetAllSubProductDetailByProductCatId($catId);
						foreach($CategoryList as $row)
						{
							
							$rows1 = $objHomeManager->GetAllSubProductDetailByProductCatId($row->PRODUCT_CATEGORY_ID);//return all child of the category
							if($row->PARENT_CATEGORY_ID!= 0)
							$index1=1;
							
							$CategoryList=$objHomeManager->GetParentNameByCatId($row->PARENT_CATEGORY_ID);
							//echo "<pre>"; print_r($CategoryList);
							?>
							<tr style="background-color: <?php if($row->PARENT_CATEGORY_ID== 0) echo "#999999"; elseif(count($rows1)!= 0) echo "#CCCCCC";?>">
								<td><?php echo $index1++.'.'.$index++; ?></td>
								<td><?php  echo $row->PRODUCT_CATEGORY_NAME; ?></td>
								<td><?php echo $row->PRIORITY ; ?></td>
								<td><?php  echo $CategoryList[0]->PRODUCT_CATEGORY_NAME; ?></td>
								<td class="hideInPrint"><a href="AddProductCategory.php?urlstring=<?php 
								echo EncryptURL('action=Add&product_category_id='.$row->PRODUCT_CATEGORY_ID); ?>" class="btn btn-success btn-xs">
								<span class="glyphicon-edit glyphicon" ></span>Edit</a>&nbsp;
								<?php
								if(count($rows1) == 0)
								{
								?>
								<a href="AddProductCategory.php?urlstring=<?php echo EncryptURL('action=Delete&product_category_id='.$row->PRODUCT_CATEGORY_ID); ?>" 
								onClick="javascript: return confirm('This Category will be deleted.\nClick Ok to Proceed, Cancel to Stop');" class="btn btn-danger btn-xs">
								<span class="glyphicon-remove glyphicon" ></span>Delete</a>&nbsp;
								<?php
								}
								?>
								</td>
							</tr>
							<?php
								getCategory($row->PRODUCT_CATEGORY_ID,$index,$index1);
							}
							}
							 	getCategory('0',1,1); 
							?>
					</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>
<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script>
var kp = $.noConflict();
kp(function () {
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
<script>
function GetEnabledDisabled()
{	//alert('hii');
	var sibs=document.getElementById('Sibs').checked;
	var kids=document.getElementById('Kids').checked;
	//alert(sibs);
	if(sibs==true)
	{	//alert(sibs);
		document.getElementById('SibsSchool').style.display='block';
		document.getElementById('KidsSchool').style.display='none';
	}
	if(kids==true)
	{
		document.getElementById('SibsSchool').style.display='none';
		document.getElementById('KidsSchool').style.display='block';
	}
}

</script>

<?php 
} 
else if($action=='Add')
{ 
	if(isset($paramsArray["product_category_id"]) && $paramsArray["product_category_id"]!="" )
	{
		$ProductId=$paramsArray["product_category_id"];
		$ProductId=$paramsArray["product_category_id"];
		$objBannerHomeManager = new HomeManager(); 
		$ProductList=$ProductObj->GetAndDisplayAllListProductById($ProductId); 
	}
?>
<script>
function checkFileSize()
{
  if(document.getElementById("spring_eng_file_name").files[0]!="")
  {
	//var oFile = document.getElementById("spring_eng_file_name").files[0]; // <input type="file" id="fileUpload" accept=".jpg,.png,.gif,.jpeg"/>
	if (oFile.size < 387152) // 2 mb for bytes.
	{
		alert("Product Category image file size must greater than 4 mb!");
		return false;
	}
  }	
  
  if(document.getElementById("spring_chn_file_name").files[0]!="")
  {
	//var oFileChn = document.getElementById("spring_chn_file_name").files[0]; // <input type="file" id="fileUpload" accept=".jpg,.png,.gif,.jpeg"/>
	if (oFileChn.size < 387152) // 2 mb for bytes.
	{
		alert("Product Category image file size must greater than 4 mb!");
		return false;
	}
  }	
  
}

</script>
<link rel="stylesheet" href="../css/praveen_template.css">		  
<div class="content-wrapper">
<section class="content-header">
	<h1>Add Poduct Category</h1>
	<ol class="breadcrumb">
		<li><a href="../9User/Home.php"><i class="fa fa-dashboard"></i>Home</a></li>
		<li class="active">Add Poduct Category</li>
	</ol>
</section>
<form action="AddProductCategory.php?urlstring=<?php echo EncryptURL('action=Insert'); ?>" name="module" method="post" enctype="multipart/form-data"
 id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
<section class="content">
	<div class="row">
		<div class="col-md-12 text-center">
		<h2>Product Category</h2>
		<div class="box box-primary">
				<div class="box-body" id="div1">
				<div class="box-border">
					<div class="form-group" align="left">
					<div class="col-md-4 col-sm-12 col-xs-12">
						<label class="control-label" for="last-name"> Product Category Name</label><br />
						<input type="text" name="product_category_name" id="product_category_name"  value="<?php echo $ProductList[0]->PRODUCT_CATEGORY_NAME; ?>" 
						class="form-control">
					</div>
					<div class="col-md-4 col-sm-12 col-xs-12">
						<label class="control-label " for="last-name">Priority</label><br/>
						<input type="text" id="priority_1" name="priority_1"   class="form-control col-md-12" value="<?php echo $ProductList[0]->PRIORITY; ?>">
					</div>				
					<?php
					$ProductObj=new HomeManager();
					$ParentDetailArray=$ProductObj->GetAndDisplayAllListProduct();	
					?>		
						<input type="hidden" id="product_category_id" name="product_category_id" value="<?php echo $ProductId; ?>"  />  
					<div class="col-md-4 col-sm-12 col-xs-12">
					<label class="control-label " for="last-name" >Category</label>
						<select  name="maincategory" id="maincategory" class="form-control">
                   		   <option value="0"  >Main Category </option>
							<?php
							foreach($ParentDetailArray as $values)
							{ 
								?>
								<option value="<?php echo $values->PRODUCT_CATEGORY_ID; ?>" <?php if($values->PRODUCT_CATEGORY_ID == $ProductList[0]->PARENT_CATEGORY_ID )
								 echo " selected"; ?>><?php echo $values->PRODUCT_CATEGORY_NAME; ?></option>
								<?php
							}
							?>
                   		</select>
					</div>
					<div class="col-md-8 col-sm-8 col-xs-12">
						<label class="control-label " for="last-name">Description</label>
						<textarea  class="form-control ckeditor col-md-12" id="description" name="description"<?php echo $ProductList[0]->DESCRIPTION; ?>> </textarea>
					</div>	
					
					<div class="col-md-4 col-sm-4 col-xs-12">
						<label class="control-label " for="last-name">Browse</label><br/>
						<input type="file"name="Image" id="Image" multiple onchange="readURL(this);"  class="form-control" value="<?php echo $ProductList[0]->EXT; ?>"/>
					<br/>
					<?php 
					if($ProductList[0]->EXT!="")
					{	
					?>
						<img src="<?php echo "../Images/ProductCategory/".$ProductList[0]->PRODUCT_CATEGORY_ID.".".$ProductList[0]->EXT;?>" width="100px"/>
						<a href="AddProductCategory.php?urlstring=<?php
						echo EncryptURL('action=Remove&PRODUCT_CATEGORY_ID='.$ProductList[0]->PRODUCT_CATEGORY_ID.'&ext='.$ProductList[0]->EXT); ?>" 
						class="btn btn-danger btn-sm " style="padding-top:0%;" title="Remove this image"><span class="glyphicon glyphicon-remove"></span></a>
					<?php
					}
					?>
					</div>	
					</div>	
					<div class="form-group text-center">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<a href="AddHomePageBanner.php"><button   type="button"  class="btn btn-danger" >Cancel</button></a>
						<button type="submit" class="btn btn-success" onclick="return confirm('Are You Sure you want to Save it?\n Click OK to Continue, 
						Cancel to Stop'),ValidateForm()">Submit</button>
					</div>
					</div>	
				</div>
				</div>
				</div>
			<div>
		</div>
	</div>
</div>
</div>
</section>
</form>
</div>
<script>
function ValidateForm() 
{
	if(document.getElementById("product_category_name").value=="") 
	{
		alert("Product can not be left blank.");
		return false;
	}
}
</script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript">
function readURL(input) 
{
	if (input.files && input.files[0])
	{
		var reader = new FileReader();
		reader.onload = function (e) {
		$('#imageShow').attr('src', e.target.result);
	}
	
	reader.readAsDataURL(input.files[0]);
	}
}
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function ($) { // wait until the document is ready
$('div#chatroom').click(function(){
$.ajax
({
    type: 'GET',
    url: '../Ajax/AddMoreBanner.php',
    data: { 'counter': '<?php echo $chatroomid; ?>'},
    success: function(response) {
        alert('Load was performed.');
    },
    error: function(){
        alert('Fuuuuuuuuuuuuuu');
    }
}); // End Ajax  

alert('Fail');

}); // End onclick
});
</script>
<script src="../plugins/ckeditor/ckeditor.js"></script>
<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
<?php
}
$pageMainContent = ob_get_contents();
ob_end_clean();
$pagetitle = "Add Product ::";
//Apply the template
include('../MasterTemplatePage.php');
?>