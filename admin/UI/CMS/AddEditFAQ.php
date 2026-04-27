<?php
ob_start();
//ini_set('display_errors',0);
////error_reporting(E_ALL | E_STRICT);
include('../Common.php');
include('../Includes/Functions.php');
require_once ('../../UI/Config/inc_path.php');
require_once "../Includes/ConstantArray.php";
require_once ('../../BL/UserManager.php');

$paramsArray = GetQueryStringParameters();
(isset($paramsArray['action']))? $action=$paramsArray['action'] : $action="";
isset($paramsArray["msg"]) ? $msg=$paramsArray["msg"] : $msg="";

switch($action)
{	
	case "Insert":
		$InsertArray=array();
		$UpdateArray=array();
		$IntfaqId=$_POST['IntfaqId'];
		if($IntfaqId!=='')
		{
			$msg="update";
			$UpdateArray=array($_POST['faqQuestion'],$_POST['faqAnswar'],$_POST['faqorder'],$IntfaqId);
		}
		else
		{
			$msg="insert";
			$InsertArray=array($_POST['faqQuestion'],$_POST['faqAnswar'],$_POST['faqorder']);
		}
		$objUserManager = new UserManager(); 
		$FqaResult=$objUserManager->InsertFaq($InsertArray,$UpdateArray,$IntfaqId);
		header("location:AddEditFAQ.php?urlstring=".EncryptURL("action=&msg=".$msg));
	break;
	
	case "Delete":
	      $faqId=$paramsArray["AddfaqId"];
		  $objHomeManager= new UserManager();
		  $result=$objHomeManager->DeleteFAQById($faqId);
		  header("location:AddEditFAQ.php?urlstring=".EncryptURL("action=&msg=delete"));
	break;
}
?>
<div class="content-wrapper">
	<section class="content-header">
		<ol class="breadcrumb">
			<li><a href="../User/Home.php"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Add FAQ</li>
		</ol>
	</section>
<section class="content">
<?php
if($action=="")
{
?>  
	<br/>
	<!-- Content Header (Page header) -->
	<div class="col-md-12 col-sm-12 col-xs-12 ">
		<div class="input-group">
		<a href="AddEditFAQ.php?urlstring=<?php echo EncryptURL('action=Add'); ?>"> <button type="button" class="btn btn-primary">Add FAQ</button></a>
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
				<h4><i class="icon fa fa-check"></i> FAQ has been added successfully</h4>
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
				<h4><i class="icon fa fa-check"></i>FAQ has been updated successfully</h4>
			</div>
		<?php 
		}
		else if($msg=='delete')
		{
		?>
			<div class="alert alert-success alert-dismissible" style="height:50px;">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
				<h4><i class="icon fa fa-check"></i> FAQ has been deleted successfully</h4>
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
		<div class="col-xs-12">
			<div class="box" id="SibsSchool">
				<div class="box-header">
				<h3 class="box-title">List FAQ</h3>
				</div>
				<div class="box-body">
				<?php
				    $objUserManager = new UserManager(); 
					$FqaResult=$objUserManager->GetAllFqadata();
					if(!empty($FqaResult))
					{
					?>
						<table id="" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text_align_center">S.No</th>
                                    <th class="text_align_center">Faq Question</th>
                                    <th class="text_align_center">Faq Answer </th>
                                    <th class="text_align_center">Faq Order</th>
                                    <th class="text_align_center">Action</th>
                            	</tr>
                            </thead>
							<tbody>
							<?php 
                            if(!empty($FqaResult))
                            {
                                $index=1; 
                                foreach($FqaResult as $FqaResultValue)
                                { 
                             ?>
                                <tr class="common_table_header">
                                    <td class="text_align_left"><?php echo $index++; ?></td>
                                    <td class="text_align_left"><?php echo $FqaResultValue->FAQ_QUESTION; ?></td>
                                    <td class="text_align_left"><?php echo $FqaResultValue->FAQ_ANSWER; ?></td>
                                    <td class="text_align_left"><?php echo $FqaResultValue->FAQ_ORDER; ?></td>
                                    <td><a href="AddEditFAQ.php?urlstring=<?php echo EncryptURL('action=Add&AddfaqId='.$FqaResultValue->FAQ_ID); ?>"
									 class="btn btn-info btn-xs edit"><span class="glyphicon glyphicon-view"></span></span>Edit</a>									
                                    <a href="AddEditFAQ.php?urlstring=<?php echo EncryptURL('action=Delete&AddfaqId='.$FqaResultValue->FAQ_ID); ?>"
									 class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to Delete this record ?\n Click OK to Continue, 
									 Cancel to Stop')" ><span class="glyphicon glyphicon-remove"  ></span> Del</a>
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
                    		</tr>
                       	</thead>
                    </table>
					<?php 
					} 
					?>				
				</div>
			</div>
		</div>
	</div>
<?php 
} 
else if($action=='Add')
{
	      $faqId=$paramsArray["AddfaqId"];
          $objHomeManager= new UserManager();
		  $Upresult=$objHomeManager->GetFaqById($faqId);
?>
<h1><?php if(isset($Upresult)) echo "Add FAQ"; else echo "Update FAQ"; ?></h1>
<div class="row">
	<div class="col-md-12 text-center">
	<h2>FAQ</h2>
		<div class="box box-primary">
		<div class="box-body" id="div1">
		<form action="AddEditFAQ.php?urlstring=<?php echo EncryptURL('action=Insert'); ?>" name="module" method="post" enctype="multipart/form-data" 
		id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" onSubmit="return validateAdd(this)" >
		<div class="form-group"> 
			<div class="col-md-6 col-sm-12 col-xs-12 " align="left" >
				<label class="control-label " for="last-name">Faq Question</label><br/>
				<textarea  rows="7" id="faqQuestion" name="faqQuestion"  class="form-control ckeditor col-md-12"><?php if(isset($Upresult)) echo $Upresult[0]->FAQ_QUESTION; ?></textarea>
			</div>
			<div class="col-md-6 col-sm-12 col-xs-12" align="left">
				<label class="control-label " for="last-name">Faq Answer</label><br/>
				<textarea  rows="7" id="faqAnswar" name="faqAnswar"   class="form-control ckeditor col-md-12"><?php if(isset($Upresult)) echo $Upresult[0]->FAQ_ANSWER; ?></textarea>
			</div>	
		</div>
			
		<div class="col-md-6 col-sm-6 col-xs-6 " align="left" >
			<label class="control-label " for="last-name">Faq Order</label><br/>
			<input type="text" name="faqorder" id="faqorder" class="form-control col-md-6" value="<?php if(isset($Upresult)) echo $Upresult[0]->FAQ_ORDER; ?>">
			<input type="hidden" name="IntfaqId" id="IntfaqId" value="<?php if(isset($Upresult)) echo $Upresult[0]->FAQ_ID; ?>"/>
		</div>
		<div class="form-group text-center">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<button type="submit" class="btn btn-success" onclick="return confirm('Are You Sure you want to Save it?\n Click OK to Continue, Cancel to Stop')">Submit
			</button><a href="AddEditFAQ.php"><button   type="button"  class="btn btn-danger" >Cancel</button></a>
		</div>
		</div>
		
		</form>
		</div>
		</div>
	</div>
</div>
<?php
}
?>
</section>
</div>
<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="../plugins/ckeditor/ckeditor.js"></script><?php
$pageMainContent = ob_get_contents();
ob_end_clean();
$pagetitle = "Add FAQ";
//Apply the template
include('../MasterTemplatePage.php');
?>