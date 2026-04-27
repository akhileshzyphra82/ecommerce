<?php
ob_start();
//ini_set('display_errors',0);
////error_reporting(E_ALL | E_STRICT);
include('../Common.php');
include('../Includes/Functions.php');
require_once ('../../UI/Config/inc_path.php');
require_once "../Includes/ConstantArray.php";
require_once ('../../BL/UserManager.php');
$objUserManager=new UserManager();

$paramsArray = GetQueryStringParameters();
(isset($paramsArray['action']))? $action=$paramsArray['action'] : $action="";
isset($paramsArray["msg"]) ? $msg=$paramsArray["msg"] : $msg="";

switch($action)
{	
	case "update":
		$arrUserDetails=array();
		$arrUserDetails[]=array("oldPassword"=>$_POST['oldPassword'],"newPassword"=>$_POST['newPassword'],"userType"=>'1',"userId"=>'4');
		$result=$objUserManager->UpdateUserPassword($arrUserDetails);
		if($result==1)
			header("location:ChangePassword.php?urlstring=".EncryptURL("action=&msg=update"));
		else
			header("location:ChangePassword.php?urlstring=".EncryptURL("action=&msg=error"));
	break;
	
}
?>
<div class="content-wrapper">
<section class="content-header">
<ol class="breadcrumb">
<li><a href="../User/Home.php"><i class="fa fa-dashboard"></i> Home</a></li>
<li class="active">Change Password</li>
</ol>
</section>
<section class="content">
<?php
if($action=="")
{
?>  
<br/>

<!-- Content Header (Page header) -->
<?php	
if(isset($paramsArray['msg']))
{
	$msg=$paramsArray['msg'];		
?>
	<div class="col-md-12 col-sm-12 col-xs-12 ">
	<?php	
	if($msg=='error')
	{	
	?>
		<div class="alert alert-danger alert-dismissible" style="height:50px;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
			<h4><i class="icon fa fa-ban"></i>Password not updated as old password given is wrong</h4>
		</div>
	<?php	
	}
	else if($msg=='update')
	{
	?>
		<div class="alert alert-success alert-dismissible" style="height:50px;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
			<h4><i class="icon fa fa-check"></i>Password has been updated successfully</h4>
		</div>
	<?php 
	}
	?>
	</div>
<?php	
}	
?>
<!-- Main content -->
	<div class="row">
		<div class="col-md-12 text-center">
		<h2>Change Password </h2>
				<div class="box box-primary">
						<div class="box-body" id="div1">
                            <form action="ChangePassword.php?urlstring=<?php echo EncryptURL('action=update'); ?>" name="module" method="post" enctype="multipart/form-data"
							 id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" onSubmit="return validate(this)" >
                                <div class="form-group"> 
                                    <div class="col-md-12 col-sm-12 col-xs-12 " align="left" >
                                        <label class="control-label " for="last-name">Old Password</label><br/>
                                        <input type="text" name="oldPassword" id="oldPassword" class="form-control col-md-6">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12" align="left">
                                        <label class="control-label " for="last-name">New Password</label><br/>
                                        <input type="password" name="newPassword" id="newPassword" class="form-control col-md-6">
                                    </div>	
                                    <div class="col-md-12 col-sm-12 col-xs-12" align="left">
                                        <label class="control-label " for="last-name">Confirm Password</label><br/>
                                        <input type="text" name="confirmPassword" id="confirmPassword" class="form-control col-md-6">
                                    </div>	
                            
                                    <div class="col-md-12 col-sm-12 col-xs-12" align="left">
                                        <input type="submit" name="submit" value="Submit" class="btn btn-info">&nbsp;&nbsp;
                                        <input type="reset" name="reset" value="Reset" class="btn btn-danger" >
                                    </div>					
                               </div>
							</form>
						</div>
				</div>
		</div>
	</div>
</section>
</div>
<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>
function validate(theForm)
{
	 var x=document.getElementById('newPassword').value;
	 var y=document.getElementById('confirmPassword').value;
	 if(x!=y)
	 {
		 alert("Your new password and confirm password does not match");
		 return false;
	 }
}
</script>
<?php
}
$pageMainContent = ob_get_contents();
ob_end_clean();
$pagetitle = "Change Password";
include('../MasterTemplatePage.php');
?>