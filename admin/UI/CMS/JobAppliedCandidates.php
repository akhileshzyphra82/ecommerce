<?php
ob_start();
ini_set('display_errors',0);
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
$JobCareer=$JobObject->GetAllJobData();

//echo '<pre>';print_r($JobCareerDetail);die;
$arrSrchData=array();
$JobCareerDetail=$JobObject->GetAllAppliedJobCandidate($arrSrchData);
switch($action)
{

	case 'Search':
	
		$intPosition=$_POST['intPosition'];
		$fromDate=$_POST['fromDate'];
		$toDate=$_POST['toDate'];
		
		$arrSrchData=array('intPosition'=>$intPosition,'fromDate'=>$fromDate,'toDate'=>$toDate);
			//echo '<pre>';print_r($arrSrchData);die;
		$JobCareerDetail=$JobObject->GetAllAppliedJobCandidate($arrSrchData);
		$action='';
	break;	

	case 'Del':
		$arrDelete=$_POST['delete'];
		
		$strAppliedId='';
		if(count($arrDelete)>0)
		{
			foreach($arrDelete as $arrDeleteVal)
			{
				$strAppliedId=$strAppliedId.','.$arrDeleteVal;
			
			}
			
		}
		$strAppliedId=ltrim($strAppliedId,',');
		
		if($strAppliedId!='')
		{
			$arrDelData=array('strAppliedId'=>$strAppliedId);
			$intId=$JobObject->DeleteMultiCandidate($arrDelData);
		}
		
		if($intId)
		{
			header("location:JobAppliedCandidates.php?urlstring=". EncryptURL("action=&msg=delete"));
		}
		else
		{
			header("location:JobAppliedCandidates.php?urlstring=". EncryptURL("action=&msg=error"));
		}
		$action='';
		//echo '<pre>';print_r($strAppliedId);die;
	
	
	break;
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
	
	case "download":
			
		$name=$paramArray["url"];
	//$name= $_GET['nama'];
    header('Content-Description: File Transfer');
    header('Content-Type: application/force-download');
    header("Content-Disposition: attachment; filename=\"" . basename($name) . "\";");
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($name));
    ob_clean();
    flush();
    readfile($name); //showing the path to the server where the file is to be download
    exit;
			
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
  <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
    <!-- Content Header (Page header) -->
<div class="content-wrapper"> 
<section class="content-header">
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="JobAppliedCandidates.php">Candidate List</a></li>
		<li class="active">Job Applied Candidates</li>
	</ol>
</section>
<br /><br/>
<?php	
//echo '<pre>';print_r($paramArray);die;
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
			<h4><i class="icon fa fa-check"></i>Deleted successfully</h4>
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
				<h3 class="box-title"> Candidate List</h3>
				</div>
				<div class="row">
					<div class="col-xs-12">
						<form  action="JobAppliedCandidates.php?urlstring=<?php echo EncryptURL('action=Search'); ?>" method="post">
							<?php
							?>
							<div class="col-md-3 col-sm-3 col-xs-3 ">
								Position
								<select  class="form-control" name="intPosition">
									<option value="">Select Position</option>
									<?php 
									if(count($JobCareer)>0)
									{
										foreach($JobCareer as $job)
										{
										?>
											<option value="<?php echo $job->JOB_POST_ID; ?>" <?php if($_POST['intPosition']==$job->JOB_POST_ID) echo 'selected';?>> <?php echo $job->JOB_POSITION; ?></option>
										<?php
										}
									}
									?>
								</select>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-3 ">
								From Date
								<input type="date" name="fromDate" id="fromDate"  class="form-control"  value="<?php echo  $_POST['fromDate'];?>"/> 
							</div>  
							<div class="col-md-3 col-sm-3 col-xs-3 ">
								To Date
								<input type="date" name="toDate" id="toDate" class="form-control"  value="<?php echo  $_POST['toDate'];?>" /> 
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
				<div class="box-body">
				<?php 
				if($action=="")
				{
					//$JobObject=new HomeManager();
					
					?>
					<form  action="JobAppliedCandidates.php?urlstring=<?php echo EncryptURL('action=Del'); ?>" method="post">
						<table  class="table table-bordered table-striped">
							<thead>
								<tr>
									<th >S.No</th>
									<th >Candidate Name</th>
									<th >Email</th>
									<th >Phone</th>
									<th >Experience(Yrs)</th>
									<th >Job Position</th>
									<th >Apply Date</th>
									<th >Resume</th>
									<th ><input type="submit" name="del" value="Delete" class="btn btn-danger" /></th>
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
								<tr >
									<td ><?php echo $index++; ?></td>
									<td ><?php echo $KeyJob->CANDIDATE_NAME; ?></td>
									<td ><?php echo $KeyJob->CANDIDATE_EMAIL; ?></td>
									<td ><?php echo $KeyJob->CANDIDATE_PHONE; ?></td>
									<td ><?php echo $KeyJob->CANDIDATE_EXPERIENCE; ?></td>
									<td ><?php echo $KeyJob->JOB_POSITION; ?></td>
									<td ><?php echo date($KeyJob->APPLIED_DATE); ?></td>
									<td >
									<?php
									$ResumeLoc="../Images/CandResume/".$KeyJob->CANDIDATE_APPLIED_JOB_ID.".".$KeyJob->RESUME_FILE_EXT;
									if(file_exists($ResumeLoc))
									{
										//$url = urlencode(base64_encode($ResumeLoc));
										//base64_decode(urldecode($url));
									?>
									<a href="JobAppliedCandidates.php?urlstring=<?php echo EncryptURL('action=download&url='.$ResumeLoc); ?>"
									 ><button type="submit" name="submit" class="btn btn-submit">View Resume</button></a>
									<?php
									}
									else
									{
									?>
									<span style="color:red;font-size:12px">No Resume available</span>
									<?php
									}
									?>
									</td>
									<td>
										
										<input type="checkbox"  id="yourBox" style="float:left; width:20px" name="delete[]" value="<?php  echo $KeyJob->CANDIDATE_APPLIED_JOB_ID;?>">
										<label for="yourBox" > </label>
									</td>
								</tr>
					<?php 
							}
							
							?>
							<tr style="background-color:#FFFFFF" >
								<th colspan="100%" style="background-color:#FFFFFF" > <input type="submit" name="del" value="Delete" class="btn btn-danger"  style="float:right"/></th>
							</tr>
							<?php
						}
						?>
						</tbody>
						</table>
					</form>
					<?php
				} 
				?>
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
</div>
<?php 
$pageMainContent = ob_get_contents();
ob_end_clean();
$pagetitle = "sinelec-tech :: Home";
//Apply the template
include('../MasterTemplatePage.php');
?>