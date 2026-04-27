<?php
ob_start();
date_default_timezone_set('Asia/Kolkata');
include_once('../Common.php');
include('../Includes/Functions.php');
require_once "../Includes/ConstantArray.php";
require_once "../../BL/HRManager.php";
$objEmployee = new HRManager();
$objEmployee = $_SESSION['BasePrincipal'];

//$intLoginUserSchoolId = $objEmployee->getSchoolId();
/*$intEmployeeId = $objEmployee->getEmployeeId();
$strEmpMobileNo = $objEmployee->getMobileNo();
$strEmpEmailId = $objEmployee->getEmailId();
$empName = $objEmployee->getEmployeeFirstName()." ".$objEmployee->getEmployeeMiddleName()." ".$objEmployee->getEmployeeLastName();
$userid = $objEmployee->getUserId();
*/
$paramsArray = GetQueryStringParameters();
(isset($paramsArray['action']))? $action=$paramsArray['action'] : $action="";
switch($action)
{
	case 'changeUserPassword':
		$objHRManager = new HRManager();
		$newPassword = $_POST["newPassword"];
		$objHRManager->ChangePassword($_SESSION["EMAILID"], trim($_POST["currentPassword"]), trim($_POST["newPassword"]),  trim($_POST["confirmPassword"]));
		header("location:ChangePassword.php?urlstring=".EncryptURL('msg=change'));
	break;
}
?>
<script src="../js/jquery-1.11.2.min.js"></script>

<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" />
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" />
<!-- Ionicons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css" />
<!-- DataTables -->
<link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css" />
<!-- Theme style -->
<link rel="stylesheet" href="../dist/css/AdminLTE.min.css" />
<!-- AdminLTE Skins. Choose a skin from the css/skins
   folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css" />
  
<div class="content-wrapper">
	<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<script src="../js/jquery-1.11.2.min.js"></script>
	<script language="javascript" type="text/javascript" src="../js/jquery.coolfieldset.js"></script>
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/jquery.coolfieldset.css" />
	<link rel="stylesheet" href="../css/praveen_template.css">
	<!-- Content Header (Page header) -->
<section class="content-header">
<h4>Change Password</h4>
<ol class="breadcrumb">
	<li><a href="../9User/Home.php"><i class="fa fa-dashboard"></i> Home</a></li>
	 <li class="active">Change Password</li>
</ol>
</section>
<section class="content">
	<div class="row">
				<!-- left column -->
		<div class="col-md-12" >
			<div class="box">
			<div class="box-header">
			  <h1 class="box-title" style="font-size:15px">Change Password</h1>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
			<script src="../js/jquery-1.11.2.min.js"></script>
			<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
			<form name="form1" method="post" action="ChangePassword.php?urlstring=<?php echo EncryptURL('action=changeUserPassword')?>" onSubmit="return validate(this);">
				<?php
				if(isset($paramsArray['msg']) && $paramsArray['msg']=='change' )
				{
				?>
					<div class="row col-md-12">
					<font style="color:green">Password Changed Successfully</font>&nbsp;
					</div>
					
				<?php 
				}
				if(isset($Message))
				{
				?>
					<div class="row col-md-12">
					<?php echo $Message;?></font>&nbsp;
					</div>
					
				<?php 
				}
				?>
				<div class="row col-md-12">
						<span class="control-label-right col-md-2 col-sm-4 col-xs-1" for="first-name" style="font-size:12px;font-size:11px;text-align:right">User ID
						<span class="MandatoryField">*</span></span>
					<div class="col-md-3 col-sm-4 col-xs-12">
						<span class="control-label-right col-md-2 col-sm-4 col-xs-1" for="first-name" style="font-size:12px;font-size:11px;text-align:right">
						<?php echo $_SESSION["EMAILID"];?></span>
					</div>
				</div>
				<div class="row col-md-12">
				<br />
					<span class="control-label-right col-md-2 col-sm-4 col-xs-1" for="first-name" style="font-size:12px;font-size:10px;text-align:right">Current Password 
					<span class="MandatoryField">*</span></span>
					<div class="col-md-3 col-sm-4 col-xs-12">
						<input type="password" name="currentPassword" id="currentPassword" class="form-control col-md-3 col-xs-12 search_filter_field_style" required>
					</div>
				</div>
				<div class="row col-md-12">
				<br />
					<span class="control-label-right col-md-2 col-sm-4 col-xs-1" for="first-name" style="font-size:12px;font-size:10px;text-align:right">New Password 
					<span class="MandatoryField">*</span></span>
					<div class="col-md-3 col-sm-4 col-xs-12">
						<input type="password" name="newPassword" id="newPassword" class="form-control col-md-3 col-xs-12 search_filter_field_style" required>
					</div>
				</div>
				<div class="row col-md-12">
				<br />
					<span class="control-label-right col-md-2 col-sm-4 col-xs-1" for="first-name" style="font-size:12px;font-size:10px;text-align:right">Confirm Password 
					<span class="MandatoryField">*</span></span>
					<div class="col-md-3 col-sm-4 col-xs-12">
						<input type="password" name="confirmPassword" id="confirmPassword" class="form-control col-md-3 col-xs-12 search_filter_field_style" required>
					</div>
				<div class="row col-md-12">
						<input type="hidden" name="schoolArray" value="<?php echo $strAdminValue->SCHOOL_ID;?>" />
						<input type="submit" name="submit" value="Save" class="btn btn-info">&nbsp;&nbsp;<input type="reset" name="reset" value="Reset" class="btn btn-danger" >
				 </div>
			</form>
			</div>
			</div>
		</div>
	</div>
</section>
</div>
<script type="text/javascript" language="JavaScript">
function validate(theForm)
{
 var x=document.getElementById('newPassword').value;
 var y=document.getElementById('confirmPassword').value;
 if(x!=y)
 {
 alert("Your newpassword and confirmpassword does not match");
 return false;
 }
}
</script>
<?php
$pageMainContent = ob_get_contents();
ob_end_clean();
$pagetitle = "Change Password :: Sadara Housing Society Management ERP";
//Apply the template
include('../MasterTemplatePage.php');
?>		