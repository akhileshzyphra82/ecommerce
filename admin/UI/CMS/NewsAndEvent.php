<?php
ob_start();
ini_set("display_errors",1);
//error_reporting(E_ALL | E_STRICT);
include('../Common.php');
include('../Includes/Functions.php');
require_once ('../../UI/Config/inc_path.php');
require_once "../Includes/ConstantArray.php";
require_once ('../../BL/HomeManager.php');

$objHomeManager=new HomeManager();

$date=date('Y-m-d');
$paramsArray = GetQueryStringParameters();
(isset($paramsArray['action']))? $action=$paramsArray['action'] : $action="";
isset($paramsArray["msg"]) ? $msg=$paramsArray["msg"] : $msg="";
//echo "<pre>";print_r($_SESSION);die;
switch($action)
{	
	case "Insert":
	//echo "<pre>";print_r($_POST);
	//echo "<pre>";print_r($_FILES);
	//die;
	$empId=$_SESSION["EMPLOYEEID"];
	
	$flag=$_POST["News_Event"];
	$title=$_POST["title"];
	$date=$_POST["start_date"];
	$discription=$_POST["description"];

	$imgExt='';
	if($_FILES["Image"]["name"]!="")
	{
		$tempImg = $_FILES["Image"]["tmp_name"];
		$imageName=$_FILES["Image"]["name"];
		list($imgName,$imgExt) = explode(".",$imageName);
	}	
	$docExt='';
	if($_FILES["doc"]["name"]!="")
	{
		$tempDoc = $_FILES["doc"]["tmp_name"];
		$docName=$_FILES["doc"]["name"];
		list($docName,$docExt) = explode(".",$docName);
	}	
	
	$NewsEventId=$objHomeManager->InsertNewsAndEventData($flag,$title,$date,$discription,$empId,$imgExt,$docExt);
	if($NewsEventId!="")
	{	
		if($imgExt!='')
		{
			$NewsEventImagePath = move_uploaded_file($tempImg,"../Images/NewsAndEventPic/".$NewsEventId.".".$imgExt);   //upload at news_vent_img_id in images folder	
		}
		if($docExt!='')
		{
			$NewsEventDocPath = move_uploaded_file($tempDoc,"../Images/NewAndEventDocs/".$NewsEventId.".".$docExt);   //upload at news_vent_img_id in images folder	
		}
				
		header("location:NewsAndEvent.php?urlstring=".EncryptURL("action=&msg=Insert"));
	}
	else
	{ 
		header("location:NewsAndEvent.php?urlstring=".EncryptURL("action=&msg=error"));
	}	  
	break;
	
	case "Delete":
	 	  //echo "<pre>";print_r($paramsArray);die;
		  $intNewsAndEventId = $paramsArray["intNewsAndEventId"];
		  $strImageExt = $paramsArray["strImageExt"];
		  $strDocExt = $paramsArray["strDocExt"];
		  
		  if($strImageExt!='')
		  {
			$imgPath="../Images/NewsAndEventPic/".$intNewsAndEventId.".".$strImageExt;
			if(file_exists($imgPath))
			{
				unlink($imgPath);
			}
		  }	

		  if($strDocExt!='')
		  {
			$docPath="../Images/NewAndEventDocs/".$intNewsAndEventId.".".$strDocExt;
			if(file_exists($docPath))
			{
				unlink($docPath);
			}
		  }	
		  
		  $intDeleteNewEventId=$objHomeManager->DeleteNewsAndEventData($intNewsAndEventId);
		  //echo $intDeleteNewEventId; die;
		  header("location:NewsAndEvent.php?urlstring=".EncryptURL("action=&msg=delete"));
	break;
	
	case "Update":
	//echo "<pre>";print_r($_POST);
	//echo "<pre>";print_r($_FILES);
	//die;
	$empId=$_SESSION["EMPLOYEEID"];
	
	$intNewsEventId=$_POST["intNewsEventId"];
	$flag=$_POST["News_Event"];
	$title=$_POST["title"];
	$date=$_POST["start_date"];
	$discription=$_POST["discription1"];

	$imgExt='';
	if($_FILES["Image"]["name"]!="")
	{
		$tempImg = $_FILES["Image"]["tmp_name"];
		$imageName=$_FILES["Image"]["name"];
		list($imgName,$imgExt) = explode(".",$imageName);
	}
	else
	{
		$imgExt=$_POST["strImgExt"];
	}
	$docExt='';
	if($_FILES["doc"]["name"]!="")
	{
		$tempDoc = $_FILES["doc"]["tmp_name"];
		$docName=$_FILES["doc"]["name"];
		list($docName,$docExt) = explode(".",$docName);
	}	
	else
	{
		$docExt=$_POST["strDocExt"];
	}
	
	$resultNewsEventId=$objHomeManager->UpdateNewsAndEventData($flag,$title,$date,$discription,$empId,$intNewsEventId,$imgExt,$docExt);
	echo $resultNewsEventId;
	//echo '<pre>'; print_r($resultNewsEventId); die;
	if($resultNewsEventId=="0")
	{	
		if($imgExt!='')
		{
			$NewsEventImagePath = move_uploaded_file($tempImg,"../Images/NewsAndEventPic/".$intNewsEventId.".".$imgExt);   //upload at news_vent_img_id in images folder	
		}
		if($docExt!='')
		{
			$NewsEventDocPath = move_uploaded_file($tempDoc,"../Images/NewAndEventDocs/".$intNewsEventId.".".$docExt);   //upload at news_vent_img_id in images folder	
		}
		header("location:NewsAndEvent.php?urlstring=".EncryptURL("action=&msg=update"));
	}
	else
	{ 
		header("location:NewsAndEvent.php?urlstring=".EncryptURL("action=&msg=error"));
	}	  
	break;

   
   
}
?>
  <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
 <?php
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
  <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
<div class="content-wrapper">
<section class="content-header">
<ol class="breadcrumb">
<li><a href="../User/Home.php"><i class="fa fa-dashboard"></i> Home</a></li>
<li class="active">Add News & Event</li>
</ol>
</section>
<br/>

<!-- Content Header (Page header) -->
<div class="col-md-12 col-sm-12 col-xs-12 ">
	<div class="input-group">
	<a href="NewsAndEvent.php?urlstring=<?php echo EncryptURL('action=Add'); ?>"> <button type="button" class="btn btn-primary">Add News/Event</button></a>
	</div>
</div>


<?php	if(isset($paramsArray['msg'])){
	$msg=$paramsArray['msg'];		?>
	<div class="col-md-12 col-sm-12 col-xs-12 ">
	<?php	if($msg=='Insert'){	?>
		<div class="alert alert-success alert-dismissible" style="height:50px;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
			<h4><i class="icon fa fa-check"></i> News And Event has been added successfully</h4>
		</div>	
	<?php	}else if($msg=='error'){	?>
	<div class="alert alert-danger alert-dismissible" style="height:50px;">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
		<h4><i class="icon fa fa-ban"></i> Error in Process</h4>
	</div>
	<?php	}else if($msg=='update'){?>
		<div class="alert alert-success alert-dismissible" style="height:50px;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
			<h4><i class="icon fa fa-check"></i>  News And Event has been updated successfully</h4>
		</div>
		<?php 
	}else if($msg=='delete'){?>
		<div class="alert alert-success alert-dismissible" style="height:50px;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
			<h4><i class="icon fa fa-check"></i>  News And Event has been deleted successfully</h4>
		</div>
		<?php
	}	?>
	</div>
<?php	}	?>
<script src="../js/jquery-1.11.2.min.js"></script>
<script language="javascript" type="text/javascript" src="../js/jquery.coolfieldset.js"></script>
<link rel="stylesheet" type="text/css" href="../bootstrap/css/jquery.coolfieldset.css" />

<!-- Main content -->

	<div class="row">
		<div class="col-xs-12">
			<div class="box" id="SibsSchool">
				<div class="box-header">
				<h3 class="box-title">List News And Event</h3>
				</div>
				<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
				<script src="../bootstrap/js/bootstrap.min.js"></script>
				<script src="../js/func_ajax.js"></script>
				<div class="box-body">
				<?php
					$NewsAndEventDetailArray=$objHomeManager->GetAndDisplayAllNewsAndEventDetails();
					//echo "<pre>";print_r($NewsAndEventDetailArray);die;
					?>
				
				
					<table id="" class="table table-bordered table-striped">
						<thead>
							<tr>
							<th class="text_align_center">S.No</th>
							<th class="text_align_center">Type</th>
							<th class="text_align_center">Image</th>
							<th class="text_align_center">Title</th>
							<th class="text_align_center">Description</th>
							<th class="text_align_center">Created Date</th>
							<th class="text_align_center">Document</th>
							<th class="text_align_center">Action</th>
							
							</tr>
						</thead>
		<tbody>
		<?php 
		if(count($NewsAndEventDetailArray)>0)
		{ 
			$index=1; 
			foreach($NewsAndEventDetailArray as $NewsAndEventDetails)
			{ 
				?>
				<tr class="common_table_header">
				<td class="text_align_left"><?php echo $index++; ?></td>
				<td class="text_align_left"><?php echo $NewsAndEventDetails->FLAG; ?></td>
				<td class="text_align_left"><img src="<?php echo "../Images/NewsAndEventPic/".$NewsAndEventDetails->NEWS_EVENT_ID.".".$NewsAndEventDetails->IMG_EXT;?>" width="50px" height="50px"/></td>
				<td class="text_align_left"><?php echo $NewsAndEventDetails->TITLE; ?></td>
				<td class="text_align_left"><?php echo $NewsAndEventDetails->DESCRIPTION; ?></td>
				<td class="text_align_left"><?php echo $NewsAndEventDetails->CREATED_DATE; ?></td>
		
				<td class="text_align_left">
					<?php 
					if ($NewsAndEventDetails->DOC_EXT!='')
					{
						$docURL = "https://sinelec-tech.com/admin/UI/Images/NewAndEventDocs/".$NewsAndEventDetails->NEWS_EVENT_ID.".".$NewsAndEventDetails->DOC_EXT;
						?>
                   	 	<a href="<?php echo $docURL; ?>" target="_blank">Document</a>
                    <?php
					}
					else
					{
						echo 'No File';
					}
					?>
                </td>
						
				<td>
				
				<a href="NewsAndEvent.php?urlstring=<?php echo EncryptURL('action=Edit&intNewsAndEventId='.$NewsAndEventDetails->NEWS_EVENT_ID);?>" class="btn-success btn-xs"><span class="glyphicon glyphicon-edit"></span>Edit</a>									
				<a href="NewsAndEvent.php?urlstring=<?php echo EncryptURL('action=Delete&intNewsAndEventId='.$NewsAndEventDetails->NEWS_EVENT_ID.'&strImageExt='.$NewsAndEventDetails->IMG_EXT.'&strDocExt='.$NewsAndEventDetails->DOC_EXT); ?>" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to Delete this record ?\n Click OK to Continue, Cancel to Stop')" ><span class="glyphicon glyphicon-remove"  ></span> Del</a>
				</td>	
				</tr>
			<?php 
			} 
		}?>
		</tbody>
					</table>
					
							<div id="Open_popup_modal_show_id" class="modal fade" tabindex="-1"></div>
									<script src="../js/jquery-1.11.2.min.js"></script>
									<script type="text/javascript">
									$(document).ready(function(){
									var $modal = $('#Open_popup_modal_show_id');
									$('.open').on('click', function(){
											var val=$(this).attr('data-Id');
											
											$modal.load('ViewImage.php',{'val': val},
											function(){
											//alert(val);
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

<?php } 
else if($action=='Add')
{

?>
<script>


</script>
<link rel="stylesheet" href="../css/praveen_template.css">		  
<div class="content-wrapper">
<section class="content-header">
	<h1>NewsAndEvent</h1>
	<ol class="breadcrumb">
	<li><a href="../User/Home.php"><i class="fa fa-dashboard"></i>></a></li>
	<li class="active"></li>
	</ol>
</section>
	<form action="NewsAndEvent.php?urlstring=<?php echo EncryptURL('action=Insert'); ?>" name="module" method="post" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" >

		<div class="col-md-12 text-center">
		<h2>News And Event</h2>
		<div class="box box-primary">
		<div class="box-body" id="div1">
		<div class="box-border" >
		<div class="col-md-6 col-sm-12 col-xs-12" align="left">
		        <div class="form-group">
				<div class="col-md-12 col-sm-12 col-xs-12" align="left">
				<label class="control-label" for="last-name">News/Event<span class="MandatoryField" style="color:#FF0000">*</span> </label><br />
				<select class="form-control col-md-12" name="News_Event" id="News_Event">
				<option value="News">NEWS</option>
				<option value="Event">EVENT</option>
				 </select>
			  </div>
              </div>
				<div class="form-group" align="left">
					<div class="col-md-12 col-sm-12 col-xs-12">
                    <label class="control-label" for="last-name"> Title <span class="MandatoryField" style="color:#FF0000">*</span></label><br />
                    <input type="text" name="title" id="title"  value="" class="form-control ">
					</div>
				</div>
							
				<div class="form-group">
				<div class="col-md-12 col-sm-12 col-xs-12" align="left">
				    <label class="control-label" for="last-name">Created Date(yyyy-mm-dd)</label><br />
					<input type="date" name="start_date" id="start_date"   class="form-control  date2" value="<?php echo date('Y-m-d');?>" required>
				</div>
				</div>
				<div class="form-group">
				    <div class="col-md-12 col-sm-12 col-xs-12" align="left">
					<label class="control-label " for="last-name">Description</label><br/>
					<textarea class="form-control" rows="5" id="description" name="description" ></textarea>
					</div>
				</div>
				<div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12" align="left">
                        <label class="control-label " for="last-name">Add Image (only jpg or jpeg images of size 640*360 is allowed)</label><br/>
                        <input type="file" name="Image" id="Image"  multiple onchange="readURL(this);"/>
                    </div>
				</div>
				<div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12" align="left">
                        <label class="control-label " for="last-name">Add Document (only pdf files)</label><br/>
                        <input type="file" name="doc" id="doc"  multiple onchange="readURL(this);"/>
                    </div>
				</div>
                
                
				</div>
					<div class="col-md-6 col-sm-12 col-xs-12" align="left">
					<div class="form-group">
				<div class="col-md-6 col-sm-col-xs-12" align="right" >
				<div id="add">
				</div>
				</div>
					<div>
					</div>
					</div>
					</div>
					<div class="form-group text-center">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <a href="NewsAndEvent.php"><button   type="button"  class="btn btn-danger" >Cancel</button></a>
                    <button type="submit" class="btn btn-success" onclick="return formValidate();" >Submit</button>
                </div>
		</div>
				</div>
				</div>
			
		
		
		
		</div>
		
</div>
</form>

</div>
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>tinymce.init({ 
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
});</script>
<script type="text/javascript">
function formValidate()
{
	if(document.getElementById('News_Event').value=="")
	{ 
	alert('News Or Event name can not be blank.');
	return false;
	}
	if(document.getElementById('title').value=="")
	{ 
	alert('Title name can not be blank.');
	return false;
	}
	/*
	if(document.getElementById("Image").files[0]!="")
  	{
		var oFile = document.getElementById("Image").files[0]; 
		if (oFile.size > 1048576) // 1 mb for bytes.
		{
			alert(" Image file size must less than 1mb!");
			return false;
		}
		if (oFile.type!='jpg' || oFile.type!='JPG' || oFile.type!='jpeg' || oFile.type!='JPEG') // 1 mb for bytes.
		{
			alert("Image extension need to be jpg or jpeg");
			return false;
		}
  	}	
	if(document.getElementById("doc").files[0]!="")
  	{
		var oDocFile = document.getElementById("doc").files[0]; 
		if (oDocFile.size > 4048576) // 4 mb for bytes.
		{
			alert(" Image file size must less than 1mb!");
			return false;
		}
		if (oDocFile.type!='pdf' || oFile.type!='PDF') // 1 mb for bytes.
		{
			alert("Document extension need to be pdf");
			return false;
		}
  	}	
	*/

}
</script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script>
$(document).ready(function() {


  $("#Image").change(function(){
  var inp = document.getElementById('Image');
  
for (var i = 0; i < inp.files.length; ++i) {
  var name = inp.files.item(i).name;
  
    name = name.replace(/\\/g, '/');
var fname = name.substring(name.lastIndexOf('/')+1, name.lastIndexOf('.'));
  
var br2=document.createElement("br");
var mu = document.createElement("img");
    
    mu.setAttribute('src', window.URL.createObjectURL(this.files[i])); 
    mu.setAttribute('width','150');
    mu.setAttribute('height','100');
	
  $("#add").append(mu); 

$("#add").append(br2);
$("#add").append('<br>');
      
}
  });

 
});
</script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script>
$(function () {
$('.date2').datepicker({
    format: 'yyyy-mm-dd',
   // startDate: '-3d'
});
});

</script>


<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>tinymce.init({ 
	selector:'#description1', 
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
});</script>
<script>tinymce.init({ 
	selector:'#description1', 
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
});</script>


<?php
}
else if($action=='Edit')
{		
//echo "<pre>";print_r($paramsArray);die;
$NewsEventId=$paramsArray['intNewsAndEventId'];

$NewsAndEventDetailArray=$objHomeManager->GetNewsAndEventById($NewsEventId);
//echo "<pre>";print_r($NewsAndEventDetailArray);
?>

<link rel="stylesheet" href="../css/praveen_template.css">		  
<div class="content-wrapper">
<section class="content-header">
	<h1>News And Event</h1>
	<ol class="breadcrumb">
	<li><a href="../User/Home.php"><i class="fa fa-dashboard"></i>></a></li>
	<li class="active"></li>
	</ol>
</section>
	<form action="NewsAndEvent.php?urlstring=<?php echo EncryptURL('action=Update'); ?>" name="module" method="post" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" >
	<input type="hidden" name="intNewsEventId" value="<?php echo $NewsAndEventDetailArray[0]->NEWS_EVENT_ID;?>"/>
	<input type="hidden" name="strImgExt" value="<?php echo $NewsAndEventDetailArray[0]->IMG_EXT;?>"/>
	<input type="hidden" name="strDocExt" value="<?php echo $NewsAndEventDetailArray[0]->DOC_EXT;?>"/>
<section class="content">

	<div class="row">
		<div class="col-md-12 text-center">
		<h2>News And Event</h2>
		<div class="box box-primary">
		<div class="box-body" id="div1">
		<div class="box-border" style="min-height:450px">
		        <div class="form-group">
				<div class="col-md-6 col-sm-12 col-xs-12" align="left">
				<label class="control-label" for="last-name">News/Event</label><br />
				<select class="form-control col-md-12" name="News_Event" id="News_Event1">
				<option value="News" <?php if($NewsAndEventDetailArray[0]->FLAG=="News") echo "selected";?>>NEWS</option>
				<option value="Event" <?php if($NewsAndEventDetailArray[0]->FLAG=="Event") echo "selected";?>>EVENT</option>
				 </select>
			  </div>
              </div>
				<div class="form-group" align="left">
				<div class="col-md-6 col-sm-12 col-xs-12">
				            <label class="control-label" for="last-name"> Title</label><br />
							<input type="text" name="title" id="title"  class="form-control col-md-12" value="<?php echo $NewsAndEventDetailArray[0]->TITLE;?>">
				<div class="form-group">
				<div class="col-md-5 col-sm-12 col-xs-12" align="left">
				    <label class="control-label" for="last-name">Created Date(yyyy-mm-dd)</label><br />
					<input type="text" name="start_date" id="start_date" value="<?php if($NewsAndEventDetailArray[0]->CREATED_DATE!='') echo $NewsAndEventDetailArray[0]->CREATED_DATE; else echo date('Y-m-d');?> "  class="form-control col-md-3 col-xs-12 date2" required>
				</div>
				</div>
				<div class="form-group">
				<div class="col-md-12 col-sm-12 col-xs-12" align="left">
				<label class="control-label " for="last-name">Description</label><br/>
<textarea  rows="4" id="discription1" name="discription1"  class="form-control col-md-12" value="<?php echo $NewsAndEventDetailArray[0]->DESCRIPTION;?>"><?php echo $NewsAndEventDetailArray[0]->DESCRIPTION;?></textarea>
				</div>
				</div>
				<div class="form-group">
					<div class="col-md-12 col-sm-12 col-xs-12" align="left">
						<label class="control-label " for="last-name">Add Image (only jpg or jpeg images of size 640*360 is allowed)</label><br/>
						<input type="file"name="Image"  value="<?php echo $NewsAndEventDetailArray[0]->IMG_EXT;?>" id="Image" multiple onchange="readURL(this);"/>
						<?php
						if($NewsAndEventDetailArray[0]->IMG_EXT!='')
	                    {
				   		?>
						<img src="<?php echo "../Images/NewsAndEventPic/".$NewsAndEventDetailArray[0]->NEWS_EVENT_ID.".".$NewsAndEventDetailArray[0]->IMG_EXT;?>" width="100px" height="100px"/>						
						<?php 
						} 
						?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-12 col-sm-12 col-xs-12" align="left">
						<label class="control-label " for="last-name">Add Document (only pdf file allowed)</label><br/>
						<input type="file" name="doc"  value="<?php echo $NewsAndEventDetailArray[0]->DOC_EXT;?>" id="doc" multiple onchange="readURL(this);"/>
						<?php
						if ($NewsAndEventDetailArray[0]->DOC_EXT!='')
						{
							$docURL = "https://sinelec-tech.com/admin/UI/Images/NewAndEventDocs/".$NewsAndEventDetailArray[0]->NEWS_EVENT_ID.".".$NewsAndEventDetailArray[0]->DOC_EXT;
							?>
							<a href="<?php echo $docURL; ?>" target="_blank">View Document</a>
						<?php
						}
						else
						{
							echo 'No File';
						}
						?>						
					</div>
				</div>


				</div>
				<div class="col-md-6 col-sm-col-xs-12" align="right" >
				<div id="add">
				</div>
				</div>
						
					
					</div>
					</div>
					</div>
				</div>
				</div>
			
		
		
		<div class="form-group text-center">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<a href="NewsAndEvent.php"><button   type="button"  class="btn btn-danger" >Cancel</button></a>
				<button type="submit" class="btn btn-success" value="Upate" onclick="return formValidate();">Upate</button>
			</div>
		</div>
		</div>
		</div>
		
</form>
</section>
</div>
<script type="text/javascript">
function formValidate()
{
  
	if(document.getElementById('News_Event1').value=="")
	{
		alert('News Or Event name can not be left blank.');
		return false;
	}
	
	if(document.getElementById('title').value=="")
	{ 
	alert('Title name can not be blank.');
	return false;
	}

	
	if(document.getElementById("Image").files[0]!="")
	{
	var oFile = document.getElementById("Image").files[0]; 
		if (oFile.size > 1048576) // 1 mb for bytes.
		{
		alert("News Image file size must greater than 1mb!");
		return false;
		}
	}	
}
</script>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script>

$(document).ready(function() {


  $("#Image").change(function(){
  var inp = document.getElementById('Image');
  
for (var i = 0; i < inp.files.length; ++i) {
  var name = inp.files.item(i).name;
  
    name = name.replace(/\\/g, '/');
var fname = name.substring(name.lastIndexOf('/')+1, name.lastIndexOf('.'));
  
var br2=document.createElement("br");
var mu = document.createElement("img");
    
    mu.setAttribute('src', window.URL.createObjectURL(this.files[i])); 
    mu.setAttribute('width','150');
    mu.setAttribute('height','100');
	
  $("#add").append(mu); 

$("#add").append(br2);
$("#add").append('<br>');
      
}
  });

 
});
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
<script>
$(function () {
$('.date2').datepicker({
    format: 'yyyy-mm-dd',
  //  startDate: '-3d'
});
});

</script>


<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>tinymce.init({ 
	selector:'#discription1', 
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
});</script>
<?php
}


$pageMainContent = ob_get_contents();
ob_end_clean();
$pagetitle = "News And Event ::";
//Apply the template
include('../MasterTemplatePage.php');
?>