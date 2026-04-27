<?php 
ob_start();
ini_set("display_errors",0);
require_once ('../admin/BL/HomeManager.php');
$JobObject=new HomeManager();
$JobCareerDetail=$JobObject->GetAllJobData();	
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="favicon/sinelec.png" />
    <title>Sinelec Technologies</title>

    <!--Bootstrap-->
    <link rel="stylesheet" href="vendors/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="vendors/bootstrap/bootstrap-theme.min.css">

    <!-- Vendors -->
    <link rel="stylesheet" href="vendors/owl.carousel/owl.carousel.css">
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/fontawesome/font-awesome.min.css">
    <link rel="stylesheet" href="vendors/et-line-icons/et-line-icons.css">
    <link rel="stylesheet" href="vendors/themify-icons/themify-icons.css">
    <link rel="stylesheet" href="vendors/lineariconsFree/style.css">
    <link rel="stylesheet" href="vendors/magnificpopup/magnific-popup.css">

    <!--Fonts-->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700" rel="stylesheet">


    <!--Theme Styles-->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
    
    <!--[if lt IE 9]>
        <script src="js/html5shiv.min.js"></script>
        <script src="js/respond.min.js"></script>
    <![endif]-->

</head>

<body>
	<!--Top Header-->
<?php include 'header.php';

$paramsArray = GetQueryStringParameters();
(isset($paramsArray['action']))? $action=$paramsArray['action'] : $action="";
isset($paramsArray["msg"]) ? $msg=$paramsArray["msg"] : $msg="";
switch($action)
{	
	case "SendMail":
	
	//echo "<pre>"; print_r($_SESSION);
	///echo "<pre>"; print_r($_POST);die;
	if($_POST["6_letters_code"]==$_SESSION["6_letters_code"])
	{
	
		list($positionId,$positionTitle)=explode("////",$_POST["position"]);
		if($_FILES["example"]["name"]!="")
		{
		   $FileTempName=$_FILES["example"]["tmp_name"];
		   list($FileName,$FileExt)= explode(".",$_FILES["example"]["name"]);
		}   
		
			//SELECT COUNT(*) AS TOTA_APPLY FROM `tbl_candidate_applied_for_job` WHERE `job_post_id`='37' AND `candidate_email`='saiba.naz@gmail.com'
		
			$arrSrch=array('positionId'=>$positionId,'email'=>$_POST["email"]);
			$arrAlreadyApply=$JobObject->GetAlreadyApplied($arrSrch);
			//echo "<pre>"; print_r($arrAlreadyApply);die;
			
		if($arrAlreadyApply[0]->TOTA_APPLY==0)
		{
		
			$AppliedId=$JobObject->InsertCandidateDetails($_POST["name"],$positionId,$_POST["email"],$_POST["Phone"],
			$_POST["experience_in_year"],$FileExt,$FileTempName);
			if($AppliedId!="")
			{
				include "../admin/smtpmail/classes/class.phpmailer.php"; // include the class name
				$subject = 'Candidate detail applied for job position '.$positionTitle.'  at sinelec-tech.com ';
				$message = '<strong>
							Candidate Detail :<br/><br/>
							<table rules="all" style="border-color: #666;" cellpadding="10">
							<tr  style="background: #eee;"> 
							<td><b>Name: '.$_POST["name"].'</b></td></tr>
							<tr  style="background: #eee;"> <td><b>Email: </b>'.$_POST["email"].'</td></tr>
							<tr  style="background: #eee;"> <td><b>Phone No.: </b>'.$_POST["Phone"].'</td></tr>
							<tr  style="background: #eee;"> <td><b>Applied For Position: </b>'.$positionTitle.'</td></tr>
							</tr>
							
							</table>'.$message.
						'<br/><br/>
						<strong>Note:</strong> Kindly do not reply to this email as this is an auto generated email from Sinelec. For any query kindly contact info@sinelec-tech.com';
					$host = "box5213.bluehost.com";
					$userName = "web@sinelec-tech.com";
					$password = "{Ge-[]sE(wq,";
					$fromname = "info@sinelec-tech.com";
					//$fromname = "akhileshredomud@gmail.com";
					$from = $_POST["email"];
			   
					ob_clean();
					$mail = new PHPMailer(); // create a new object
					$mail->IsSMTP(); // enable SMTP
					$mail->SMTPAuth = true; // authentication enabled
					$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
					$mail->Host = $host;
					$mail->Port = 465; // 465 or 587
					$mail->IsHTML(true);
					$mail->Username = $userName;
					$mail->Password = $password;
					$mail->FromName = $fromname;
					$mail->From = $from;                
					$mail->Subject = $subject;
					$mail->Body = $message;
					$mail->AddAttachment("../admin/UI/Images/CandResume/".$AppliedId.".".$FileExt); 
					$mail->AddAddress($fromname);
					$mail->AddAddress($fromname); //send to mail id
					$messageCompleted = 1;
					$mail->Send();
			}	
			header("location:careers.php?urlstring=".EncryptURL("action=&msg=CareerSent"));
		}
		else
		{
		
			echo "<script>alert('You are already applied for this position.')</script>";
			$action = '';
			
		}
	}
	else
	{
			echo "<script>alert('Captcha does not match !!!')</script>";
			$action = '';
	}
	break;
}
	
?>
    
    <!--Contact Banner-->
    <section class="row">
        <div class="row m0 sub_banner career-banner overly relative">
            <div class="container overly-content">
            	<div class="col-sm-12">
                </div>
            </div>
        </div>
    </section>

    <!--Contact Form-->
    <section class="row section-spacing careers bg-pattern">
        <div class="container">
		<?php
		if($msg=='CareerSent')
		{
		?>
		    <div class="row" style="text-align:center">
			  <p style="color:green;font-size:24px"><b>We have received you details. Our HR team will contact you soon</b></p>
			</div> 
		<?php
		}
		?>
		
		 <div class="row">
		 <div class="col-md-12">
            <h2 class="part_title">CAREERS </h2>
           	<p>
				Sinelec Technologies is a young and dynamic company with an aim to pioneer electronics and semiconductor education in India and 
				to provide high quality product development/design service to Indian and European OEM customers in electronics and semiconductor domain.
			</p>
            
            <p>
				We provides excellent opportunities for professional growth, a competitive compensation package, a young and talented international team and 
				international clients to work with.Here you will find an exciting and challenging workplace, with a chance to work with some of 
				the best minds in electronics and semiconductor industry.
			</p>

			<p>
				We’re building an international team, based in New Delhi, India and Freising(near Munich), Germany. If you join us,
				you could find yourself experiencing life at one of these locations.
			 </p>
 			<h4 class="part_title2">CURRENT OPENINGS</h4>
            </div>
			<?php 
			if(!empty($JobCareerDetail))
			{ 
				foreach($JobCareerDetail as $KeyJob)
				{ 
			?>
              <div class="col-md-6">
             	  <div class="career-wrapper">            
                        <div class="career-box">
                            <h4>Position : <b><?php echo $KeyJob->JOB_POSITION; ?></b></h4>
                            <p>Location : <?php echo $KeyJob->JOB_LOCATION; ?></p>
                            <div class="pull-left">
							   <a href="#" class="open" data-id="<?php echo $KeyJob->JOB_POST_ID; ?>" >Job Description </a>
                            </div>
                        </div>            	  
        			</div>             
                </div>
               <?php 
				   }
			   } 
			   ?>
                
            </div>
        </div>
    </section>
    <!--Contact Features-->
	
    <section class="row applynow-section section-spacing2 bg-gray">
        <div class="container">
            <div class="sectionTitle p-bottom30">
                <h2>APPLY NOW</h2>
            </div>
             <form name="contact-form" class="form" id="contactForm" action="careers.php?urlstring=<?php echo EncryptURL('action=SendMail'); ?>" method="POST"  autocomplete="off" enctype="multipart/form-data">
            <div class="row">
            
            <div class="col-md-6">
            <div class="form-group m-bottom40">
            <label class="sr-only" for="name">Full Name *</label>
            <input type="" name="name" class="form-control" id="Title" placeholder="Full Name *" onKeyPress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || event.charCode==32" required>
            </div>
            </div>
            <div class="col-md-6">
            <div class="form-group m-bottom40">
            <label class="sr-only" for="email">Email *</label>
            <input type="email" name="email" class="form-control" id="email" placeholder="Your Email *" required>
            </div>
            </div>
            <div class=""></div>
            <div class="col-md-6">
            <div class="form-group m-bottom40">
            <label class="sr-only" for="Phone">Phone *</label>
            <input type="text" name="Phone" onKeyPress="return isNumber(event)" class="form-control" id="subject" placeholder="Phone *" required>
            </div>
            </div>
            
            <div class="col-md-6">
            <div class="form-group m-bottom40">
            <label class="sr-only" for="Phone">Experience in years *</label>
            <input type="text" maxlength="2" name="experience_in_year" class="form-control" id="experience_in_year" placeholder="Experience *" onKeyPress="return isNumber(event)" required>
            </div>
            </div>
            
            <div class="col-md-6">
				<div class="form-group m-bottom40">
				<label class="sr-only" for="Phone">Please Select Job Position *</label>
			<select class="form-control"  name="position" required>
				<option>Please select Job Position *</option>
			<?php
			if(!empty($JobCareerDetail))
			{ 
				foreach($JobCareerDetail as $KeyJob)
				{
			 ?>
				<option value="<?php echo $KeyJob->JOB_POST_ID."////".$KeyJob->JOB_POSITION; ?>"><?php echo $KeyJob->JOB_POSITION; ?></option>
			  <?php
				 }
			}
			  ?>			  
			</select>
				</div>
            </div>
            
            <div class="col-md-6">
            <label class="sr-only" for="message">Upload Resume (pdf)</label>
            <input type="file" name="example" accept="application/pdf" id="resume_file" placeholder="Browse to upload Resume" class="file" required onChange="ValidateSingleInput(this)">
            </div>
			
			<div class="g-recaptcha" data-sitekey="captcha_code_file"></div>
			<div class="col-sm-12">
				<img src="captcha_code_file.php?rand=<?php echo rand(); ?>" id='captchaimg' >
			<?php 
			if(isset($_POST['captcha'])==isset($_SESSION['6_letters_code']))
			{
			 
			}
				if($captcha=="Error")
				{
					?>
					<span style="color:#FF0000;"><?php echo "Captch Does not match";?></span>
					<?php
				}
				?> 
			</div> 
			<div class="col-sm-6">
				<input type="hidden" name="captcha" id="captcha" value="<?php echo rand(); ?>">
				<label for='message'>Enter the code  here *:</label><br>
				<input id="6_letters_code" name="6_letters_code" type="text" required>
			<small>Can't read the image? click <a href='javascript: refreshCaptcha();'>here</a> to refresh</small>
			</div><br>

            </div>
              <div class="col-md-12 text-center">
            <button type="submit" name="submit" class="btn btn-submit" onClick="return validateImage();">SUBMIT</button>
            </div>
            </form>

            </div>
    </section>
	<script>
	
	var _validFileExtensions = [".doc", ".docx", ".docs", ".pdf", ".Pdf"];    
	function ValidateSingleInput(oInput) {
	if (oInput.type == "file") {
		var sFileName = oInput.value;
		 if (sFileName.length > 0) {
			var blnValid = false;
			for (var j = 0; j < _validFileExtensions.length; j++) {
				var sCurExtension = _validFileExtensions[j];
				if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
					blnValid = true;
					break;
				}
			}
			 
			if (!blnValid) {
				alert("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
				oInput.value = "";
				return false;
			}
		}
	}
	return true;
	}
	function checkextension()
	{
	  var file = document.querySelector("#resume_file");
	  if ( /\.(pdf?g|Doc|Docx)$/i.test(file.files[0].name) === false )
	  {
	   	alert("Please choose correct file format ! only allowed pdf | Doc | Docx"); 
	  }
	}
	function refreshCaptcha()
	{
		var img = document.images['captchaimg'];
		img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
	}

	function validateImage()
	{
		var file=document.getElementById("resume_file");
		var FileSize = file.files[0].size / 1024 / 1024; // in MB
        if (FileSize > 4) 
		{
            alert('File size exceeds 4 MB');
			return false;
           // $(file).val(''); //for clearing with Jquery
        } 
	}
	function isNumber(evt) 
	{
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) 
		{
		   alert("Kindly enter numeric value");
			return false;
		}
		return true;
	}
	</script>
	
	

     <!-- Footer -->
    <footer class="row">
    	<?php include 'footer.php';?>
    </footer>

    <!--  Back to Top-->
    <a href="top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

    <!--jQuery-->
    <script src="js/jquery-2.2.4.min.js"></script>

    <!--Bootstrap JS-->
    <script src="js/bootstrap.min.js"></script>

    <!--Magnific Popup-->
    <script src="js/jquery.magnific-popup.min.js"></script>

    <!--Owl Carousel-->
    <script src="vendors/owl.carousel/owl.carousel.min.js"></script>

    <!--Waypoints-->
    <script src="vendors/waypoints/waypoints.min.js"></script>

    <!--Counter Up-->
    <script src="vendors/counterup/jquery.counterup.min.js"></script>

    <!--Isotope-->
    

    <!--Infinite Scroll-->
    <script src="vendors/infinitescrol/jquery.infinitescroll.min.js"></script>

	<script type="text/javascript" src="js/bootstrap-filestyle.min.js"> </script>

    <!--Theme JS-->
    <script src="js/theme.js"></script>
    <script type="text/javascript">
			$('#input01').filestyle()
			$('#input001').filestyle({
				'placeholder' : 'Browse to upload resume'
			});
			
		</script>
 <!-- Modal -->
		<div id="Open_popup_modal_show_id" class="modal fade" tabindex="-1"></div>
			<script src="../js/jquery-1.11.2.min.js"></script>
			<script type="text/javascript">
			$(document).ready(function(){
			var $modal = $('#Open_popup_modal_show_id');
			$('.open').on('click', function(){
					var val=$(this).data("id");
					$modal.load('ViewCarrerDesc.php',{'val': val},
					function(){
					$modal.modal('show');
					});
				});
			});
			</script>
		</div>
</body>
</html>