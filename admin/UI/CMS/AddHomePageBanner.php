<?php
ob_start();
//ini_set("display_errors",0);
//error_reporting(E_ALL | E_STRICT);
include('../Common.php');
include('../Includes/Functions.php');
require_once ('../../UI/Config/inc_path.php');
require_once "../Includes/ConstantArray.php";
require_once ('../../BL/HomeManager.php');
$BannerObj=new HomeManager();
$date=date('Y-m-d');
$paramsArray = GetQueryStringParameters();
(isset($paramsArray['action']))? $action=$paramsArray['action'] : $action="";
isset($paramsArray["msg"]) ? $msg=$paramsArray["msg"] : $msg="";
switch($action)
{	
	case "Insert":
		$title=$_POST["title_1"];
		$bannerImage=$_FILES["bannerImage_1"]["tmp_name"];
	
		if($_FILES["bannerImage_1"]["name"]!="")
		{
			$imageName=$_FILES["bannerImage_1"]["name"];
			list($imagName,$imagExt) = explode(".", strtolower($_FILES['bannerImage_1']['name']));
	
		}
		
		$priority=$_POST["priority_1"];
		$discription=$_POST["discription_1"];
		$hyperlink=$_POST["hyperlink"];
		
		$BannerObj=new HomeManager();
		$BannerId=$BannerObj->InsertBannerData($title,$imagExt,$priority,$discription,$hyperlink);
		if($BannerId)	
		{
			$path="../Images/Banner/".$BannerId.".".$imagExt;
			move_uploaded_file($_FILES["bannerImage_1"]["tmp_name"],$path);
			header("location:AddHomePageBanner.php?urlstring=".EncryptURL("action=&msg=insert"));
		}
		else
		{ 
			echo "Error";
		}	  
	break;
	
	case "Delete":
		$BannerId=$paramsArray["bannerId"];
		$result=$BannerObj->DeleteBannerData($paramsArray["bannerId"]);
		header("location:AddHomePageBanner.php?urlstring=".EncryptURL("action=&msg=delete"));
	break;
	
	case "Upate":
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
<li class="active">Add Banner</li>
</ol>
</section>
<br/>

<!-- Content Header (Page header) -->
<div class="col-md-12 col-sm-12 col-xs-12 ">
	<div class="input-group">
	<a href="AddHomePageBanner.php?urlstring=<?php echo EncryptURL('action=Add'); ?>"> <button type="button" class="btn btn-primary">Add Banner</button></a>
	</div>
</div>
<div class="col-md-12 col-sm-12 col-xs-12 ">
	<div class="input-group">
	<a href="AddHomePageBanner.php?urlstring=<?php echo EncryptURL('action=Add'); ?>"> </a>
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
			<h4><i class="icon fa fa-check"></i> Home Banner has been added successfully</h4>
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
			<h4><i class="icon fa fa-check"></i> Home Banner has been updated successfully</h4>
		</div>
	<?php 
	}
	else if($msg=='delete')
	{
	?>
		<div class="alert alert-success alert-dismissible" style="height:50px;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
			<h4><i class="icon fa fa-check"></i> Home Banner has been deleted successfully</h4>
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
				<h3 class="box-title">List Home Banner</h3>
				</div>
				<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
				<script src="../bootstrap/js/bootstrap.min.js"></script>
				<script src="../js/func_ajax.js"></script>
				<div class="box-body">
				<?php
				$objBannerHomeManager = new HomeManager(); 
				$bannerDetailArray=$objBannerHomeManager->GetAndDisplayAllBannerDetails();
				?>
				<table id="" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th class="text_align_center">S.No</th>
							<th class="text_align_center">Banner Name</th>
							<th class="text_align_center">Priority</th>
							<th class="text_align_center">discription</th>
							<th class="text_align_center">BannerImage</th>
							<th class="text_align_center">Action</th>
						</tr>
					</thead>
				<tbody>
				<?php
				if(!empty($bannerDetailArray))
				{
					$index=1; 
					foreach($bannerDetailArray as $bannerDetail)
					{ 
				?>
						<tr class="common_table_header">
							<td class="text_align_left"><?php echo $index++; ?></td>
							<td class="text_align_left"><?php echo $bannerDetail->BANNER_NAME; ?></td>
							<td class="text_align_left"><?php echo $bannerDetail->PRIORITY; ?></td>
							<td class="text_align_left"><?php echo $bannerDetail->BANNER_DESCRIPTION; ?></td>
							<td class="text_align_left"><a href="<?php echo "../Images/Banner/".$bannerDetail->BANNER_ID.".".$bannerDetail->BANNER_IMG_EXT;?>" target="new_tab" >
							<img src='<?php echo "../Images/Banner/".$bannerDetail->BANNER_ID.".".$bannerDetail->BANNER_IMG_EXT;?>' style="width:100px;"/></a></td>
							<td class="text_align_center">
							<a href="AddHomePageBanner.php?urlstring=<?php echo EncryptURL('action=Edit&bannerId='.$bannerDetail->BANNER_ID); ?>"></a>									
							<a href="AddHomePageBanner.php?urlstring=<?php echo EncryptURL('action=Delete&bannerId='.$bannerDetail->BANNER_ID); ?>" 
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
?>
<script>
function checkFileSize()
{
  if(document.getElementById("spring_eng_file_name").files[0]!="")
  {
	//var oFile = document.getElementById("spring_eng_file_name").files[0]; // <input type="file" id="fileUpload" accept=".jpg,.png,.gif,.jpeg"/>
	if (oFile.size < 197152) // 2 mb for bytes.
	{
		alert("Banner Image file size must greater than 2mb!");
		return false;
	}
  }	
  
  
  if(document.getElementById("spring_chn_file_name").files[0]!="")
  {
	//var oFileChn = document.getElementById("spring_chn_file_name").files[0]; // <input type="file" id="fileUpload" accept=".jpg,.png,.gif,.jpeg"/>
	if (oFileChn.size < 197152) // 2 mb for bytes.
	{
		alert("Banner image file size must greater than 2mb!");
		return false;
	}
  }	
  
}

</script>
<link rel="stylesheet" href="../css/praveen_template.css">		  
<div class="content-wrapper">
<section class="content-header">
	<h1>Add Banner</h1>
	<ol class="breadcrumb">
	<li><a href="../9User/Home.php"><i class="fa fa-dashboard"></i>Home</a></li>
	<li class="active">Add Banner</li>
	</ol>
</section>
<form action="AddHomePageBanner.php?urlstring=<?php echo EncryptURL('action=Insert'); ?>" name="module" method="post" enctype="multipart/form-data" 
id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
<div class="row">
	<div class="col-md-12 text-center">
	<h2>Banner</h2>
		<div class="box box-primary">
			<div class="box-body" id="div1">
				<div class="box-border" >
					<div class="form-group" align="left">
					<div class="col-md-6 col-sm-12 col-xs-12">
						<label class="control-label" for="last-name"> Title<span class="MandatoryField">*</span></label><br />
							<input type="text" name="title_1" id="title_1"  value="" class="form-control col-md-12">
						<div class="form-group">
						<div class="col-md-12 col-sm-12 col-xs-12" align="left">
						<label class="control-label" for="last-name"> Banner Image<span class="MandatoryField">*</span></label><br />
							<input type="file" name="bannerImage_1" id="bannerImage_1"  value=""  class="form-control col-md-12" onchange="readURL(this);">
						</div>
						</div>
							
						<div class="form-group">
						<div class="col-md-12 col-sm-12 col-xs-12" align="left">
							<label class="control-label " for="last-name">Priority</label><br/>
							<input type="text" id="priority_1" name="priority_1"   class="form-control ">
						</div>
						</div>
						<div class="form-group">
						<div class="col-md-12 col-sm-12 col-xs-12" align="left">
							<label class="control-label " for="last-name">HyperLink</label><br/>
							<input type="text" id="hyperlink" name="hyperlink"   class="form-control ">
						</div>
						</div>
						<div class="form-group">
						<div class="col-md-12 col-sm-12 col-xs-12" align="left">
						<label class="control-label " for="last-name">Description</label><br/>
							<textarea class="form-control" rows="5" id="discription_1" name="discription_1" ></textarea>
						</div>
						</div>
						</div>		
						<div class="col-md-6 col-sm-col-xs-12">
							<img src="../Images/blankimg.jpg" id="imageShow" name="imageshow" class="col-md-8 col-sm-12 col-xs-12" style="float:right;"/>
						</div>
					</div>
					<div>
				</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="form-group text-center">
<div class="col-md-12 col-sm-12 col-xs-12">
	<a href="AddHomePageBanner.php"><button type="button"  class="btn btn-danger" >Cancel</button></a>
	<button type="submit" class="btn btn-success" onclick="return confirm('Are You Sure you want to Save it?\n Click OK to Continue,
	 Cancel to Stop'),ValidateForm()">Submit</button>
</div>
</form>
</div>
<script>
function ValidateForm() 
{
	if(document.getElementById("title_1").value=="")
	{
		alert("Title can not be left blank.");
		return false;
	}
	if(document.getElementById("bannerImage_1").value=="")
	{
		alert("Banner can not be left blank.");
		return false;
	}
	if(document.getElementById("bannerImage_1").files[0]!="")
	{
		var oFile = document.getElementById("bannerImage_1").files[0]; // <input type="file" id="fileUpload" accept=".jpg,.png,.gif,.jpeg"/>
		if (oFile.size > 1048576) // 1 mb for bytes.
		{
			alert("Banner Image file size must not be greater than 1mb!");
			return false;
		}
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
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function ($) { // wait until the document is ready
$('div#chatroom').click(function(){
$.ajax({
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
<?php
}
else if($action=="Edit")
{	
	$intBannerId=$paramsArray['bannerId'];
	$ApplyFor="English";
	$Flag="Banner";
	$objHomeManager = new HomeManager();
	$EnglishBannerDetail=$objHomeManager->getImageDetailByBannerId($intBannerId,$ApplyFor,$Flag);
	$ApplyForChinese="Chinese";
	$objHomeManager = new HomeManager();
	$ChinsesBannerDetailsArray=$objHomeManager->getImageDetailByBannerId($intBannerId,$ApplyForChinese,$Flag);
?>
<script>
function checkFileSize()
{
  if(document.getElementById("spring_eng_file_name").files[0]!="")
  {
	//var oFile = document.getElementById("spring_eng_file_name").files[0]; // <input type="file" id="fileUpload" accept=".jpg,.png,.gif,.jpeg"/>
	if (oFile.size < 197152) // 2 mb for bytes.
	{
		alert("Banner Image file size must greater than 2mb!");
		return false;
	}
  }	
  
  
  if(document.getElementById("spring_chn_file_name").files[0]!="")
  {
	//var oFileChn = document.getElementById("spring_chn_file_name").files[0]; // <input type="file" id="fileUpload" accept=".jpg,.png,.gif,.jpeg"/>
	if (oFileChn.size < 197152) // 2 mb for bytes.
	{
		alert("Banner image file size must greater than 2mb!");
		return false;
	}
  }	
  
}

</script>
<link rel="stylesheet" href="../css/praveen_template.css">		  
<div class="content-wrapper">
	<section class="content-header">
		<h1>Edit Banner</h1>
		<ol class="breadcrumb">
		<li><a href="../9User/Home.php"><i class="fa fa-dashboard"></i>Home</a></li>
		<li class="active">Edit Banner</li>
		</ol>
	</section>
	<form action="AddHomePageBanner.php?urlstring=<?php echo EncryptURL('action=Upate'); ?>" name="module" method="post" enctype="multipart/form-data" 
	id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" >
	<section class="content">
	<div class="row text-center">
	</div>
		<div class="row">
			<div class="col-md-6 text-center">
			<h2>Banner</h2>
			<div class="box box-primary">
					<div class="box-body">
					<div class="box-border">
						<div class="form-group">
						<div class="col-md-6 col-sm-12 col-xs-12">
							<label class="control-label" for="last-name"> Title</label><br />
								<input type="text" name="spring_eng_title_name" id="spring_eng_title_name"  value="<?php echo $EnglishBannerDetail[0]->IMAGE_NAME;  ?>" 
								class="form-control col-md-12">
						</div>
						<div class="col-md-6 col-sm-12 col-xs-12">
							<label class="control-label" for="last-name"> Banner Image</label><br />
								<input type="file" name="spring_eng_file_name" id="spring_eng_file_name"   class="form-control col-md-12">
						</div>
						</div>
			
						<div class="form-group">
						<div class="col-md-6 ">
							<label class="control-label " for="last-name">Priority</label><br/>
							<input type="text" id="spring_eng_priority" name="spring_eng_priority" value="<?php echo $EnglishBannerDetail[0]->PRIORTY;  ?>" 
							 class="form-control col-md-12 ">
						</div>
						</div>
						</div>
					</div>
			</div>
			</div>
			<div class="col-md-6 text-center">
			<h2>Chinese</h2>
			<div class="box box-primary">
					<div class="box-body">
					<div class="box-border">
						<div class="form-group">
						<div class="col-md-6 col-sm-12 col-xs-12">
							<label class="control-label" for="last-name"> Title</label><br />
								<input type="text" name="spring_chn_title_name" id="spring_chn_title_name"   value="<?php echo $ChinsesBannerDetailsArray[0]->IMAGE_NAME;  ?>" 
								class="form-control col-md-12">
						</div>
						<div class="col-md-6 col-sm-12 col-xs-12">
							<label class="control-label" for="last-name"> Banner Image</label><br />
								<input type="file" name="spring_chn_file_name" id="spring_chn_file_name"   value="" class="form-control col-md-12" onclick="">
						</div>
						</div>
			
						<div class="form-group">
						<div class="col-md-6 ">
							<label class="control-label " for="last-name">Priority</label><br/>
							<input type="text" id="spring_chn_priority" name="spring_chn_priority" value="<?php echo $ChinsesBannerDetailsArray[0]->PRIORTY;  ?>" 
							class="form-control col-md-12 ">
							<input type="hidden" name="banner_id" id="banner_id" value="<?php echo $intBannerId; ?>" />
							<input type="hidden" name="english_banner_ext" id="english_banner_ext" value="<?php echo $EnglishBannerDetail[0]->IMAGE_EXT; ?>" />
							<input type="hidden" name="banner_chn_ext" id="banner_chn_ext" value="<?php echo $ChinsesBannerDetailsArray[0]->IMAGE_EXT; ?>" />
						</div>
						</div>
						</div>
					</div>
			</div>
			</div>
		</div>
			<div class="form-group text-center">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<a href="AddHomePageBanner.php"><button   type="button"  class="btn btn-danger" >Cancel</button></a>
					<button type="submit" class="btn btn-success" onclick="return confirm('Are You Sure you want to Save it?\n Click OK to Continue,
					Cancel to Stop'),checkFileSize()">Update</button>
				</div>
			</div>
		</div>
	</div>
	</form>
	</section>
</div>	

<?php
}
$pageMainContent = ob_get_contents();
ob_end_clean();
$pagetitle = "Add Banner ::";
//Apply the template
include('../MasterTemplatePage.php');
?>