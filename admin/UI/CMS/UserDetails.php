<?php
ob_start();
//ini_set("display_errors",0);
//error_reporting(E_ALL | E_STRICT);
include('../Common.php');
include('../Includes/Functions.php');
require_once ('../../UI/Config/inc_path.php');
require_once ('../../BL/HomeManager.php');
$date=date('Y-m-d');
$paramsArray = GetQueryStringParameters();
(isset($paramsArray['action']))? $action=$paramsArray['action'] : $action="";
isset($paramsArray["msg"]) ? $msg=$paramsArray["msg"] : $msg="";
$objHomeManager=new HomeManager();
switch($action)
{	
case "Insert":

header("location:UserDetails.php?urlstring=".EncryptURL("action=&msg=update"));

break;

	case "Delete":
		$UserObject=new HomeManager();
		$CustomerId=$paramsArray["CustomerId"];
		$Result=$UserObject->DeleteCustomerData($CustomerId);
		header("location:UserDetails.php?urlstring=".EncryptURL("action=&msg=delete"));
	break;
	
	case 'ExportExcel':
	
		$UserObject=new HomeManager();
		$UserAllDetails=$UserObject->GetAllUserDetails();
		$filename = date('Ymd_His').'User Details-export.csv';
		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename={$filename}");
		ob_end_clean();
		$fh = @fopen( 'php://output', 'w' );
		$headerDisplayed = false;
		ob_end_clean();
		foreach ($UserAllDetails as $data ) 
		{
			//ob_end_clean();
			$data = (array)$data;
			if ( !$headerDisplayed ) 
			{
				fputcsv($fh, array_keys($data));
				$headerDisplayed = true;
			}
		fputcsv($fh, $data);
		//ob_end_clean();
		}
		//ob_end_clean();
		exit();
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
			<li class="active">User</li>
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
			<h4><i class="icon fa fa-check"></i> Customer has been added successfully</h4>
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
			<h4><i class="icon fa fa-check"></i>Customer has been updated successfully</h4>
		</div>
	<?php 
	}
	else if($msg=='delete')
	{
	?>
		<div class="alert alert-success alert-dismissible" style="height:50px;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
			<h4><i class="icon fa fa-check"></i> Customer has been deleted successfully</h4>
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
<div class="col-md-12 col-sm-12 col-xs-12 "></div>
<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box" id="SibsSchool">
			<div class="box-header">
			<h3 class="box-title">Customer Details</h3>
			</div>
			<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
			<script src="../bootstrap/js/bootstrap.min.js"></script>
			<script src="../js/func_ajax.js"></script>
			<div class="box-body">
			<?php
			if($action=="")
			{
			$limit="0";
			$maxRecord="100";

			?>
			<table>
				<tr>
					<td>
					<a href="UserDetails.php?urlstring=<?php echo EncryptURL('action=pagging&limit='.$limit.'&maxRecord='.$maxRecord);?>" style=" text-decoration:none;" >
					<button class="btn btn-success">View Customer Details </button></a>
					</td>
				</tr>
			</table>
			<?php
			}
			if($action=="pagging")
			{
			$flag='count';
			$limit="";
			$maxRecord="100";
			$arrUserRecord = $objHomeManager->SelectUser($flag);
			//echo "<pre>"; print_r($arrUserRecord);die;
			$pageCount=$arrUserRecord[0]->TOTAL/$maxRecord;
			$pageCount=ceil($pageCount);
			for($page=1;$page<=$pageCount;$page++)
			{
			
				if($page=='' || $page=='1')
				{
					$limit="0";
					
				}
				else
				{
					$limit=($page*100)-100;
				}
			
			?>
			<tr>
			<a href="UserDetails.php?urlstring=<?php echo EncryptURL('action=pagging&limit='.$limit.'&maxRecord='.$maxRecord);?>" 
			style=" text-decoration:none;" > <button> <?php echo $page ; ?> </button> </a>
			</tr>
			<?php
			}
			$limit= (isset ($paramsArray['limit'])) ? $paramsArray['limit'] : '';
			$maxRecord= (isset ($paramsArray['maxRecord'])) ? $paramsArray['maxRecord'] : '';
			$UserAllDetails=$objHomeManager->GetAllUserDetails($limit,$maxRecord);
			?>
				<div id="exportopenticket">
				<table id="" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th class="text_align_center">S.No</th>
							<th class="text_align_center">Customer Name</th>
							<th class="text_align_center">Phone No</th>
							<th class="text_align_center">Mobile No</th>
							<th class="text_align_center">Email Id</th>
							<th class="text_align_center">Action</th>
						</tr>
				</thead>
				<tbody>
				<?php
				if(!empty($UserAllDetails))
				{
					
					$index=$limit+1;
					foreach($UserAllDetails as $value)
					{
				?>
						
						<tr class="common_table_header">
							<td class="text_align_left"><?php echo $index++; ?></td>
							<td class="text_align_left"><?php echo $value->CLIENT_NAME; ?></td>
							<td class="text_align_left"><?php echo $value->PHONE_NO; ?></td>
							<td class="text_align_left"><?php echo $value->MOBILE_NO; ?></td>
							<td class="text_align_left"><?php echo $value->EMAIL_ID; ?></td>
							<td class="text_align_center">
							<button  class="btn btn-success btn-sm open2"  data-Id="<?php echo $value->USER_ID."_".$value->USER_TYPE_ID."_".$value->NAME;?>"
							<span class="glyphicon glyphicon-eye-open"></span>View Address</button>
							<a href="UserDetails.php?urlstring=<?php echo EncryptURL('action=Delete&CustomerId='.$value->USER_ID); ?>" class="btn btn-danger btn-sm" 
							onclick="return confirm('Are you sure you want to Delete this record ?\n Click OK to Continue, Cancel to Stop')" >
							<span class="glyphicon glyphicon-remove"  ></span> Del</a>
							</td>	
						</tr>
				<?php 
					}
				}
				 ?>
				</tbody>
				</table>
					</div>
					<a href="UserDetails.php?urlstring=<?php echo EncryptURL("action=ExportExcel"); ?>"> <button class="btn btn-success"
					onclick="exportToFileopen('excel')">Export to excel</button></a>
					</div>
			</div>
		</div>
		<?php
		}
		?>

</section>
</div>
<script type="text/javascript" language="JavaScript">
function exportToFileopen(exportTo)
{//alert('hii');
	var pdfData = document.getElementById("exportopenticket").innerHTML;
	document.getElementById("hiddenExportData").value = pdfData;
	document.getElementById("exportTo").value = exportTo;
	document.forms["exportForm"].submit();
}
</script>
	<div id="Open2_popup_modal_show_id" class="modal fade" tabindex="-1"></div>
	<script src="../js/jquery-1.11.2.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
	var $modal = $('#Open2_popup_modal_show_id');
	$('.open2').on('click', function(){
			var val=$(this).attr('data-Id');
			
			$modal.load('ViewCustomerAddress.php',{'val': val},
			function(){
			//alert(val);
			$modal.modal('show');
			});
		});
	});
</script>
<?php
$pageMainContent = ob_get_contents();
ob_end_clean();
$pagetitle = "Customer Details ::";
//Apply the template
include('../MasterTemplatePage.php');
?>