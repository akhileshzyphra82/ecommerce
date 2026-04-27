<?php
ob_start();
//ini_set("display_errors",0);
date_default_timezone_set('Asia/Kolkata');
include('../Common.php');
include('../Includes/Functions.php');
require_once ('../../UI/Config/inc_path.php');
require_once "../Includes/ConstantArray.php";
require_once ('../../BL/HomeManager.php');
require_once ('../../BL/UserManager.php');
$JobObject=new HomeManager();
$paramArray = GetQueryStringParameters();
(isset($paramArray['action']))? $action=$paramArray['action'] : $action="";
isset($paramArray["msg"]) ? $msg=$paramArray["msg"] : $msg="";

switch($action)
{
	case 'Insert':
	       $updateArray=array();
		   $insertArray=array();
	       $CountryId=$_POST["intCountryId"];
		   $Country=$_POST['Country'];
		   $Shipping=$_POST['Shipping'];
		   if($CountryId!=="")
		   {
		   $updateArray=array($Country,$Shipping,$CountryId);
		   }
		   else
		   {
		   $insertArray=array($Country,$Shipping);
		   }
		   $objUserManager=new UserManager();
	       $UpdateAllCountryAmountById=$objUserManager->UpdateAllCountryAmountById($insertArray,$updateArray,$CountryId);
		   if( $UpdateAllCountryAmountById>0)
		   {
		     header('Location:AddEditCountryAndShippingAmount.php');
		   }
	break;
	
	case 'Delete':
	      $CountryId=$paramArray["CountryId"];
		  $objUserManager=new UserManager();
		  $resultforImage=$objUserManager->DeleteCountryAmountById($CountryId);
		  header("location:AddEditCountryAndShippingAmount.php?urlstring="."EncryptURL('msg=Delete&action=");
	break;
}
?>
    <!-- Content Header (Page header) -->
<div class="content-wrapper"> 
	<section class="content-header">
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="JobPost.php">CMS</a></li>
			<li class="active">Add Edit Country And Shipping Amount </li>
		</ol>
	</section>
		
	<div class="col-md-12 col-sm-12 col-xs-12 ">
	<?php
	if(isset($paramArray['msg']))
	{
			$msg=$paramArray['msg'];	
	?>
		<div class="col-md-12 col-sm-12 col-xs-12 ">
		<?php	
		if($msg=='insert')
		{	
			?>
			<div class="alert alert-success alert-dismissible" style="height:50px;">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
				<h4><i class="icon fa fa-check"></i> Job Category has been added successfully</h4>
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
				<h4><i class="icon fa fa-check"></i> Job Category has been updated successfully</h4>
			</div>
			<?php 
		}
		else if($msg=='delete')
		{
			?>
			<div class="alert alert-success alert-dismissible" style="height:50px;">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
				<h4><i class="icon fa fa-check"></i> Job Category has been deleted successfully</h4>
			</div>
			<?php 
		}
		else if($msg=='error')
		{
			?>
			<div class="alert alert-success alert-dismissible" style="height:50px;">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="false">x</button>
				<h4><i class="icon fa fa-check"></i> Error in Process</h4>
			</div>
		<?php
		}	
		?>
		</div>
		
	<?php	
	}
	?>
	
	<?php 
		if($action=="")
		{
			$objUserManager=new UserManager();
			$CountryDetail=$objUserManager->GetAddDeleteCountryAmount();	
			?>
			<div class="box-header">
				<h3 class="box-title">List Country Amount</h3>
			</div>
			<hr/>
			<a href="AddEditCountryAndShippingAmount.php?urlstring=<?php echo EncryptURL('action=Add'); ?>"> <button type="button" class="btn btn-primary">
			Add Country Amount</button></a>
			<table id="" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th class="text_align_center">S.No</th>
						<th class="text_align_center">COUNTRY</th>
						<th class="text_align_center">SHIPPING AMT</th>
						<th class="text_align_center">Action</th>
					</tr>
				</thead>
			<tbody>
			<?php 
				if(!empty($CountryDetail))
				{ 
					$index=1;
					foreach($CountryDetail as $data)
					{ 
			?>
					<tr class="common_table_header">
						<td class="text_align_left"><?php echo $index++; ?></td>
						<td class="text_align_left"><?php echo $data->COUNTRY; ?></td>
						<td class="text_align_left"><?php echo $data->SHIPPING_AMT; ?></td>
						<td class="text_align_center">
		
						<a href="AddEditCountryAndShippingAmount.php?urlstring=<?php echo EncryptURL('action=Add&CountryId='.$data->COUNTRY_ID); ?>"
						 class="btn btn-info btn-xs edit"><span class="glyphicon glyphicon-view"></span>Edit</a>									
						<a href="AddEditCountryAndShippingAmount.php?urlstring=<?php echo EncryptURL('action=Delete&CountryId='.$data->COUNTRY_ID); ?>" 
						class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to Delete this record ?\n Click OK to Continue, Cancel to Stop')" >
						<span class="glyphicon glyphicon-remove"  ></span> Del</a>
						</td>	
					</tr>
		<?php 
					}
				}
		} 
		?>

			<div class="input-group">
			</div>
			</tbody>
			</table>
	</div>

<?php
if($action=='Add')
{
   $objUserManager=new UserManager();
   $CountryArrayDetail=$objUserManager->GetAllCountryAmountById($paramArray["CountryId"]);
?>
	 <form role="form" id="add-form" name="add-form" method="post" action="AddEditCountryAndShippingAmount.php?urlstring=<?php echo EncryptURL('action=Insert'); ?>">
	 <div class="col-md-12" style="text-align:center">
		<h2><?php if(count($CountryArrayDetail)>0) echo 'Edit Country & Shipping Amount'; else echo 'Add Country & Shipping Amount'; ?></h2>
		<div class="box box-primary">
		<div class="box-body" id="div1">
		<div class="box-border" >
		<div class="col-md-6 col-sm-12 col-xs-12" align="left">
			<div class="form-group">
			<div class="col-md-12 col-sm-12 col-xs-12" align="left">
			<label class="control-label" for="last-name">Country Name <span class="MandatoryField" style="color:#FF0000">*</span> </label><br />
				<input type="text" class="form-control " id="Country" name="Country" required="true" 
				value="<?php if(isset($CountryArrayDetail)) echo $CountryArrayDetail[0]->COUNTRY; ?>">
			</div>
			</div>
			<div class="form-group" align="left">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<label class="control-label" for="last-name"> Shipping Amt <span class="MandatoryField" style="color:#FF0000">*</span></label><br />
				<input type="text" class="form-control " id="Shipping" name="Shipping" required="true" 
				value="<?php  if(isset($CountryArrayDetail)) echo $CountryArrayDetail[0]->SHIPPING_AMT; ?>">
			</div>
			</div>
				<input type="hidden" id="intCountryId" name="intCountryId" value="<?php echo $paramArray["CountryId"]; ?>" />
			<div class="form-group text-center">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<a href="AddEditCountryAndShippingAmount.php"><button type="submit" class="btn btn-success" onclick="return formValidate();" style="text-align:right">Submit
				</button></a>
				<a href="AddEditCountryAndShippingAmount.php"><button   type="button"  class="btn btn-danger" >Cancel</button></a>
			</div>
			</div>
		</div>
		</div>
		</div>
		</div>  
	</div>  
	</form>
<?php 
}
?>

<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>
tinymce.init
({ 
	selector:'#description', 
	height: 300,
	
	theme: 'modern',
	plugins: 
	[
		'advlist autolink lists link image charmap print preview hr anchor pagebreak',
		'searchreplace wordcount visualblocks visualchars code fullscreen',
		'insertdatetime media nonbreaking save table contextmenu directionality',
		'emoticons template paste textcolor colorpicker textpattern imagetools'
	],
	toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
	toolbar2: 'print preview media | forecolor backcolor emoticons',
	image_advtab: true,
	templates:
	[
	{ title: 'Test template 1', content: 'Test 1' },
	{ title: 'Test template 2', content: 'Test 2' }
	],
	content_css: 
	[
		'//www.tinymce.com/css/codepen.min.css'
	]
});
</script>  
<script src="../js/func_ajax.js"></script>
<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/validate.js"></script>
<style >
form label.error
 {
    color:#E22C3E !important;
	font-size:10px;
}

</style>
<script language="JavaScript" type="text/javascript">
var kp = $.noConflict();
kp(document).ready(function() {
	
	kp("#add-form").validate({
		rules: {
				Country: "required",
				Shipping: "required",
				},
		},

		// Specify the validation error messages
		messages: {
			Country: "Please enter Country",
		    Shipping: "Please enter Shipping ",
			}, 
			form.submit();
			}
	});
});
	function getAllCourseByInstituteId(instituteId)
	{
		callAjax("courseList","../Ajax/getAllCourseByInstituteId.php",{
		params:"instituteId="+instituteId,
		meth:"get",
		async:true,
		startfunc:"s_function('courseList')",
		endfunc:"e_function()",
		errorfunc:"ajaxerror()" }
		);
	}
</script>	
<!-- jQuery 2.2.3 -->
<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<!-- Page script -->
<script>
  $(function () {
    //Initialize Select2 Elements
    //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    });

    //Timepicker
    $(".timepicker").timepicker({
      showInputs: false
    });
  });
</script>
<script>
function ValidateForm()
{
	if(document.getElementById("strStdName").value=="")
	{
		alert("Job position can not be left blank.");
		return false;
	}
	if(document.getElementById("priority").value=="") 
	{
		alert("Pariority can not be left blank.");
		return false;
	}
	if(document.getElementById("location").value=="")
	{
		alert("Location can not be left blank.");
		return false;
	}
	
}
</script>
<?php 
$pageMainContent = ob_get_contents();
ob_end_clean();
$pagetitle = "Add Edit Country And Shipping Amount :: ";
include('../MasterTemplatePage.php');
?>