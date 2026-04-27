<?php
ob_start();
date_default_timezone_set('Asia/Kolkata');
include('../Common.php');
include('../Includes/Functions.php');
require_once ('../../UI/Config/inc_path.php');
require_once "../Includes/ConstantArray.php";
require_once ('../../BL/HomeManager.php');
$JobObject=new HomeManager();
$paramArray = GetQueryStringParameters();
(isset($paramArray['action']))? $action=$paramArray['action'] : $action="";
isset($paramArray["msg"]) ? $msg=$paramArray["msg"] : $msg="";

switch($action)
{
	case 'Insert':
		$JobId=$_POST['job_id'];
		$JobPosition=$_POST['strStdName'];
		$JobPriority=$_POST['priority'];
		$Location=addslashes($_POST['location']);
		$Description=$_POST['description'];
		$Status=$_POST['status'];
		if($_POST["job_id"] =="")
		{
			$JobId=$JobObject->InsertJobPost($JobPosition,$JobPriority,$Location,$Description,$Status);
			if($JobId)
			{
				header("location:JobPost.php?urlstring=". EncryptURL("action=&msg=insert"));
			}
			else
			{
				header("location:JobPost.php?urlstring=". EncryptURL("action=&msg=error"));
			}
		}
		
		if($_POST["job_id"]!="")
		{
			$JobId=$JobObject->UpdateJobPost($JobId,$JobPosition,$JobPriority,$Location,$Description,$Status);
			header("location:JobPost.php?urlstring=".EncryptURL("action=&msg=update"));
		}
	break;
	
	case "Delete":
		$JobId=$paramArray["JobId"];
		$result=$JobObject->DeleteJobPost($JobId);
		if($result)
		{
			header("location:JobPost.php?urlstring=".EncryptURL("action=&msg=delete"));
		}
		else
		{
			header("location:JobPost.php?urlstring=". EncryptURL("action=&msg=error"));
		}
	break;
}
?>
  
    <!-- Content Header (Page header) -->
<div class="content-wrapper">
	<section class="content-header">
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="JobPost.php">Enquiry List</a></li>
			<li class="active">Add Enquiry</li>
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
	if($action=="")
	{
		$JobObject=new HomeManager();
		$JobCareerDetail=$JobObject->GetAllJobData();	
		?>
		<table id="" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th class="text_align_center">S.No</th>
					<th class="text_align_center">Job Position</th>
					<th class="text_align_center">Priority</th>
					<th class="text_align_center">Location</th>
					<th class="text_align_center">Description</th>
					<th class="text_align_center">Status</th>
					<th class="text_align_center">Action</th>
				</tr>
			</thead>
		<tbody>
		<?php 
		if(!empty($JobCareerDetail))
		{
			$index=1;
			foreach($JobCareerDetail as $KeyJob)
			{ 
			?>
				<tr class="common_table_header">
					<td class="text_align_left"><?php echo $index++; ?></td>
					<td class="text_align_left"><?php echo $KeyJob->JOB_POSITION; ?></td>
					<td class="text_align_left"><?php echo $KeyJob->JOB_PRIORITY; ?></td>
					<td class="text_align_left"><?php echo $KeyJob->JOB_LOCATION; ?></td>
					<td class="text_align_left"><?php echo $KeyJob->JOB_DISCRIPTION; ?></td>
					<td class="text_align_left"><?php echo $KeyJob->JOB_STATUS; ?></td>
					<td class="text_align_center">
					<a href="JobPost.php?urlstring=<?php echo EncryptURL('action=Add&JobId='.$KeyJob->JOB_POST_ID); ?>" class="btn btn-info btn-xs edit">
					<span class="glyphicon glyphicon-view"></span>Edit</a>									
					<a href="JobPost.php?urlstring=<?php echo EncryptURL('action=Delete&JobId='.$KeyJob->JOB_POST_ID); ?>" class="btn btn-danger btn-xs" 
					onclick="return confirm('Are you sure you want to Delete this record ?\n Click OK to Continue, Cancel to Stop')" ><span class="glyphicon glyphicon-remove"  >
					</span> Del</a>
					</td>	
				</tr>
	<?php
		 	}
		}
	}
	
	?>
			<div class="input-group">
			<a href="JobPost.php?urlstring=<?php echo EncryptURL('action=Add'); ?>"> <button type="button" class="btn btn-primary">Add JOB</button></a>
			</div>
		</tbody>
		</table>
</div>
<?php
if($action=='Add')
{
	$ProductObj=new HomeManager();
	$JobDetailArray=$ProductObj->GetAllJobById($paramArray["JobId"]);	
?>			
	<form role="form" id="add-form" name="add-form" method="post" action="JobPost.php?urlstring=<?php echo EncryptURL('action=Insert'); ?>" >
	<h1>Job Post<small></small></h1>
		<div class="box-body">
		<div class="col-md-3 col-sm-4">
			<div class="form-group has-feedback">
				<label class="control-label">Job Position<span class="MandatoryField" style="color:#FF0000">*</span></label>
				<input type="text" class="form-control input-sm"  id="strStdName" name="strStdName" 
				value="<?php if(isset($JobDetailArray)) echo $JobDetailArray[0]->JOB_POSITION; ?>">
			</div>
			</div>
			<div class="col-md-3  col-sm-4">
			<div class="form-group">
				<label>Job Priority<span class="MandatoryField" style="color:#FF0000">*</span></label>
				<input type="text" class="form-control input-sm" id="priority" name="priority"
				value="<?php if(isset($JobDetailArray)) echo $JobDetailArray[0]->JOB_PRIORITY; ?>">    
			</div> 
			</div>
			<div class="col-md-3  col-sm-4">
			<div class="form-group has-feedback">
				<label class="control-label">Status</label><br />
				<select id="status" name="status"  class="form-control input-sm" >
				<option value="">Select</option>
				<option value="Active"selected<?php if( $JobDetailArray[0]->JOB_STATUS=='Active' ) echo " selected"; ?>>Active</option>
				<option value="Inactive"<?php if( $JobDetailArray[0]->JOB_STATUS=='In-Active' ) echo " selected"; ?>>Inactive</option>
				</select>
			</div>
			</div>	 
			 <div class="col-md-3 col-sm-4">
			<div class="form-group">
				<label >Location<span class="MandatoryField" style="color:#FF0000">*</span></label>
				<input type="text" class="form-control input-sm" id="location" name="location" value="<?php if(isset($JobDetailArray)) echo $JobDetailArray[0]->JOB_LOCATION; ?>">
			</div>
			</div>
			<div class="col-md-12 col-sm-8">
			<div class="form-group">
				<label>Description</label>
				<textarea class="form-control input-sm" rows="6" placeholder="Enter ..." id="description" 
				name="description"<?php if(isset($JobDetailArray)) echo $JobDetailArray[0]->JOB_DISCRIPTION;?>> </textarea>
				<input type="hidden" id="job_id" name="job_id" value="<?php echo $paramArray["JobId"]; ?>" />    
			</div>
			</div>
			<div class="col-md-12  col-sm-12">
			<div class="form-group">
				<button type="submit" class="btn btn-primary"onclick="return confirm('Are You Sure you want to Save it?\n Click OK to Continue,
				Cancel to Stop'),ValidateForm()">Submit</button>
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
	plugins: [
		'advlist autolink lists link image charmap print preview hr anchor pagebreak',
		'searchreplace wordcount visualblocks visualchars code fullscreen',
		'insertdatetime media nonbreaking save table contextmenu directionality',
		'emoticons template paste textcolor colorpicker textpattern imagetools'
	],
	toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
	toolbar2: 'print preview media | forecolor backcolor emoticons',
	image_advtab: true,
	templates: [
	{ title: 'Test template 1', content: 'Test 1' },
	{ title: 'Test template 2', content: 'Test 2' }
	],
	content_css: [
		'//www.tinymce.com/css/codepen.min.css'
	]
});
</script>  
<script src="../js/func_ajax.js"></script>
<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/validate.js"></script>
<style >
form label.error {
    color:#E22C3E !important;
	font-size:10px;
      
}

</style>
<script language="JavaScript" type="text/javascript">
	var kp = $.noConflict();
	kp(document).ready(function() {
	kp("#add-form").validate({
		rules: {
				entryDate: "required",
				entryTime: "required",
				intInstituteId : "required",
				intCourseId : "required",
				strStdName : "required",
				enqSource : "required",
				strAddress : "required",
				intPhoneNo: {
					required: true,
					number: true,
					minlength: 10,
					maxlength: 10
				},
				strEmailId: {
					required: true,
					email: true
				}
		},

		// Specify the validation error messages
		messages: {
			entryDate: "Please enter date",
			
			entryTime: "Please enter time ",
			intInstituteId: "Please select parent institute/center",
			intCourseId: "Please enter course",
			strStdName: "Please enter Student Name",
			enqSource: "Please select source",
			strAddress: "Please select address",
			intPhoneNo: {
			required: "Please provide a contact number",
			minlength: "Your contact number must be 10 digit long",
			maxlength: "Your contact number must be 10 digit long"
			}, 
			strEmailId: "Please enter a valid email address"
		},

		submitHandler: function(form) {
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
$pagetitle = "IMSPrime Campus :: Home";
//Apply the template
include('../MasterTemplatePage.php');
?>