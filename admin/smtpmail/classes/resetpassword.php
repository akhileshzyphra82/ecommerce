<?php
ob_start();
ini_set('display_errors','0');
error_reporting(E_ALL | E_STRICT);
require_once ('../admin/BO/User.php');
require_once ('../admin/BL/UserManager.php');
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
	<link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">

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
	 function GetQueryStringParameters()
	{
	$paramArray = array();
	if(isset($_GET['urlstring']))
	{
		$urlParams = DecryptURL($_GET['urlstring']);
		$params = explode('&', $urlParams);
		$paramArray = array();
		foreach($params as $param)
		{
			list($key, $value) = explode('=',$param);
			$paramArray[$key] = $value;
		}
	}

	return $paramArray;
}
$paramsArray = GetQueryStringParameters();
(isset($paramsArray['action']))? $action=$paramsArray['action'] : $action="";
isset($paramsArray["msg"]) ? $msg=$paramsArray["msg"] : $msg="";
isset($paramsArray["Email"]) ? $Email=$paramsArray["Email"] : $Email=$_POST["Email"];
switch($action)
{	
case "check":
$objUserManager = new UserManager();
if(isset($_GET['flag']) && $_GET['flag']=='1'){
$result1=$objUserManager->GetuserInfo($_POST["Email"],'2');
}
else 
$result=$objUserManager->Getuser($_POST["Email"],$_POST["password"],'2');

if(count($result)>0){
//header("location:resetpassword.php?urlstring=".EncryptURL("action=ResetPassword"));
$action="ResetPassword";
}
else{
header("location:resetpassword.php?urlstring=".EncryptURL("msg=WrongEmail&action="));
}

if(count($result1)>0){
//header("location:resetpassword.php?urlstring=".EncryptURL("action=ResetPassword"));
$action="ResetPasswordProcess";
}
else{
header("location:login.php?urlstring=".EncryptURL("msg=passwordReset&action="));
}

		break;
	
case "UpdatePassword":
$objUser = new User(); 
$objUser->setEmail($_POST['Email']);
$objUser->setPassword($_POST['Password']);
$objUser->setuser_id($_POST['user_id']);
$objUserManager = new UserManager(); 
$result=$objUserManager->GetuserInfo($_POST['Email'],'2');
if($_POST['ConformPassword'] == $_POST['Password'] && $_POST['Password']!="" && $_POST["6_letters_code"]==$_SESSION["6_letters_code"]){
$objUserManager = new UserManager(); 
$update=$objUserManager->UpdateuserPassword($objUser,'2');
include "../admin/smtpmail/classes/class.phpmailer.php"; // include the class name

                $toEmailID = trim($_POST['Email']);
                $subject = 'Registration details of sinelec-tech.com';
                $message = '<strong>Dear ' . $_POST['Name'] . ',</strong><br/><br/>
						    Welcome to sinelec-tech.com<br/><br/>
							Your Sinelec login credentials are as follows:<br/><br/>
							<table rules="all" style="border-color: #666;" cellpadding="10">
							<tr  style="background: #eee;"><td><b>User Id :</b></td><td>' . $toEmailID . '</td></tr>
							<tr  style="background: #eee;"><td><b>Password :</b></td><td>' . $_POST["Password"] . '</td></tr>
							</table>
						<br/><br/>
						<strong>Note:</strong> Kindly do not reply to this email as this is an auto generated email from Sinelec. For any query kindly contact the institution authority';

				$host = "smtp.gmail.com";
				$userName = "imsprime1@gmail.com";
				$password = "IMSPRIME@123";
				$fromname = "sinelec-tech.com";
				$from = 'no-reply@sinelec-tech.com';
                ob_clean();
                $mail = new PHPMailer(); // create a new object
                $mail->IsSMTP(); // enable SMTP
                $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
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
                $mail->AddAddress($toEmailID); //send to mail id
               //echo "<pre>";print_r($mail);die;
                if (!$mail->Send()) {
                    echo "Mailer Error: " . $mail->ErrorInfo;
                } else {
                    echo "Message has been sent";
                }

}
else{
$action="ResetPassword";
$msg='passwordnotMatch';
}
if($update==O){
session_start();
$_SESSION["CUSTOMER"] = $_POST['Email'];	
$_SESSION["CUSTOMER"] = $_POST['Name'];
$_SESSION["CUSTOMER_ID"] = $_POST['user_id'];
header("location:index.php");
}
else
$action="ResetPassword";
$msg='passwordnotMatch';

		break;
}

	?>

    <!-- Breadcrumb -->
    <section class="row page_header section-spacing">
        <div class="container">
            <h3>Password Reset</h3>
            <ol class="breadcrumb">
                <li><a href="index.php">home</a></li>
                <li class="active">Password Reset</li>
            </ol>
        </div>
    </section>

    <!-- Password Reset -->
    <section class="row section-spacing2">
        <div class="container">
        	<div class="sectionTitle p-bottom40">
                <h2><?php if(isset($_GET['flag']) && $_GET['flag']=='1') echo "Forget Password"; else echo "Password Reset";?></h2>
            </div>
			<?php  if($action==""){?>
            <div class="row">
                <div class="col-sm-6 col-md-5 col-xs-12 center-block">
                  <div class="form password-reset">
                    <form class="login-form bg-gray clearfix border" action="resetpassword.php?urlstring=<?php echo EncryptURL('action=check'); ?>"  method="post" enctype="multipart/form-data">
                      <div class="col-sm-12">
                        <input type="text" placeholder="Enter Email Address" name="Email" id="Email"/>
						</div>
						<div class="col-sm-12" <?php if(isset($_GET['flag']) && $_GET['flag']=='1') echo "style='display:none;'"; ?>> 
						<input type="password" placeholder="Password"  name="password" id="password"/>
						</div>
						<?php if($msg=="WrongEmail"){?>
									<span style="color:#FF0000;"><?php echo "Email does not exist !!!";?></span>
						<?php }
						?>  
                        <button class="btn btn-primary btn-xlg btn-block">Submit</button>
                      </div>
                      <div class="col-sm-12">
                        <p class="message p-top30 margin-bottom0">Remember Password ? <a href="login.php">Account Login </a></p>
                      </div>
                    </form>
                  </div>
                </div>
            </div>
			<?php } if($action=="ResetPassword"){
		
			?>
			<div class="row">
                <div class="col-sm-6 col-md-5 col-xs-12 center-block">
                  <div class="form password-reset">
                    <form class="login-form bg-gray clearfix border" action="resetpassword.php?urlstring=<?php echo EncryptURL('action=UpdatePassword'); ?>"  method="post" enctype="multipart/form-data">
                      <div class="col-sm-12">
                                <input placeholder="Password" type="password" name="Password" id="Password">
                            </div>
                            <div class="col-sm-12"  >
                               <input placeholder="Conform Password" type="password" name="ConformPassword" id="ConformPassword">
							    <input  type="hidden" name="Email" id="Email" value="<?php if(isset($result)) echo $result[0]->COMMUNICATION_EMAIL_ID; ?>">
								<input  type="hidden" name="user_id" id="user_id" value="<?php if(isset($result)) echo $result[0]->USER_ID; ?>">
                                <input  type="hidden" name="Name" id="Name" value="<?php if(isset($result)) echo $result[0]->NAME; ?>">
								
									 <div class="g-recaptcha" data-sitekey="captcha_code_file"></div>
									<img src="captcha_code_file.php?rand=<?php echo rand(); ?>" id='captchaimg' >
									<div > 
									<input  type="hidden" name="captcha" id="captcha" value="<?php echo rand(); ?>">
									<label for='message'>Enter the code above here :</label><br>
								<input id="6_letters_code" name="6_letters_code" type="text"><br>
								<?php if($msg=="passwordnotMatch"){?>
									<span style="color:#FF0000;"><?php echo " Either captcha or password does not match !!!";?></span>
									<?php }
									?> <br>
								<small>Can't read the image? click <a href='javascript: refreshCaptcha();'>here</a> to refresh</small>
                            </div>
                        <button class="btn btn-primary btn-xlg btn-block">Submit</button>
                      </div>
                      <div class="col-sm-12">
                        <p class="message p-top30 margin-bottom0">Remember Password ? <a href="login.php">Account Login </a></p>
                      </div>
                    </form>
                  </div>
                </div>
            </div>
			<?php }?> 
        </div>
    </section>
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

    <!--Theme JS-->
    <script src="js/theme.js"></script>
	<script>
	function refreshCaptcha()
{
	var img = document.images['captchaimg'];
	img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
}
</script>
</body>
</html>