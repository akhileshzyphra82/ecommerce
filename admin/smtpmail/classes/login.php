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
if($msg=='passwordReset')
echo "<script type='text/javascript'>alert('Password has been sent to your registered email-id');</script>";				   

$price=0;
switch($action)
{	
case "Login":
$objUserManager = new UserManager(); 
$result=$objUserManager->Getuser($_POST["Email"],$_POST["password"],'2');
if(count($result)>0){
session_start();
$_SESSION["CUSTOMER"] = $result[0]->COMMUNICATION_EMAIL_ID;
$_SESSION["CUSTOMER"] = $result[0]->NAME;
$_SESSION["CUSTOMER_ID"] = $result[0]->USER_ID;
header("location:index.php");
}
else{
$msg="ErrorLogin";
}
		break;
case "LoginPopUp":
$objUserManager = new UserManager(); 
$result=$objUserManager->Getuser($_POST["Email"],$_POST["password"],'2');
if(count($result)>0){
session_start();
$_SESSION["CUSTOMER"] = $result[0]->COMMUNICATION_EMAIL_ID;
$_SESSION["CUSTOMER"] = $result[0]->NAME;
$_SESSION["CUSTOMER_ID"] = $result[0]->USER_ID;
header("location:cart.php?urlstring=".EncryptURL("action=Add&productId=".$_POST['productId']));
}
else{
header("location:Expansion-modules.php?urlstring=".EncryptURL("msg=ErrorLogin&product_id=".$_POST['productId']));
}
		break;	
case "Register":
$Name = $_POST["firstName"].' '.$_POST["lastName"];
$Email = $_POST["Email"];
$user_id = $_POST["user_id"];
$PhoneNumber = $_POST["PhoneNumber"];
$MobileNumber = $_POST["MobileNumber"];
$Password = $_POST["Password"];
$ConformPassword = $_POST["ConformPassword"];
$objUser = new User(); 
$objUser->setName($Name);
$objUser->setEmail($Email);
$objUser->setPhoneNumber($PhoneNumber);
$objUser->setMobileNumber($MobileNumber);
$objUser->setPassword($Password);
$objUser->setuser_id($user_id);
$objUserManager = new UserManager(); 
$result=$objUserManager->GetuserInfo($_POST['Email'],'2');
//echo "<pre>";print_r($result);die();
if($user_id=="" )
{
	if($_POST["ConformPassword"]==$_POST["Password"] && $_POST["6_letters_code"]==$_SESSION["6_letters_code"] && $_POST['Password']!="" )
	{
		if(!count($result)>0)
		{
			$objUserManager = new UserManager(); 
			$userId=$objUserManager->Insertuser($objUser,'2');
			session_start();
			$_SESSION["CUSTOMER"] = $Email;
			$_SESSION["CUSTOMER"] = $Name;
			$_SESSION["CUSTOMER_ID"] = $userId;

  
        include "../admin/smtpmail/classes/class.phpmailer.php"; // include the class name
        
           
                $toEmailID = trim($Email);

                $subject = 'Registration details of sinelec-tech.com';
                $message = '<strong>Dear ' . $Name . ',</strong><br/><br/>
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
//                $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
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
                
                $messageCompleted = 1;
                if (!$mail->Send()) {
                    echo "Mailer Error: " . $mail->ErrorInfo;
                    $messageCompleted = 1;
                } else {
                    echo "Message has been sent";
                    $messageCompleted = 2;
                }
                //$insertEmailArray[]=array($EmailArray['EMAIL_CONFIGURATOR_ID'],$EmailArray['USER_TYPE'],$EmailArray['USER_ID'],trim($EmailArray['EMAILID']),date('Y-m-d'),addslashes($EmailArray['EMAILSUBJECT']),addslashes($EmailArray['MESSAGE']),$EmailArray['CONFIG_GRP_ID'],$EmailArray['SCHOOL_ID'],$messageCompleted,$EmailArray['FEATURE_ID'],$EmailArray['ITEM_ID'],$attachmentArray);
           

			
			header("location:index.php?urlstring=".EncryptURL("action=&msg=inserted"));
		}
		else
		header("location:register.php?urlstring=".EncryptURL("msg=DuplicatId"));
	}
	else
	header("location:register.php?urlstring=".EncryptURL("msg=Error"));
}
else
{
$objUserManager = new UserManager(); 
if($_POST["6_letters_code"]==$_SESSION["6_letters_code"]){
$userId=$objUserManager->UpdateuserProfile($objUser,'2');
header("location:index.php");
}
else
header("location:register.php?urlstring=".EncryptURL("msg=Error&action=profile&CUSTOMER_ID=".$user_id));

}
	break;
	
	case "signOut":
session_destroy(); 
header("location:index.php");
		break;
}

	?>
    <!--Breadcrumb-->
    <section class="row page_header section-spacing">
        <div class="container">
            <h3>Login</h3>
            <ol class="breadcrumb">
                <li><a href="index.php">home</a></li>
                <li class="active">Login</li>
            </ol>
        </div>
    </section>

    <!--Login-->
    <section class="row section-spacing2  bg-pattern">
        <div class="container">
        	<div class="sectionTitle p-bottom40">
                <h2>Account Login</h2>
            </div>
            <div class="row">
            	<div class="col-sm-6 col-md-5 col-xs-12 center-block">
                      <div class="form bg-gray clearfix login-form border">
                            <form class="login-form clearfix" action="login.php?urlstring=<?php echo EncryptURL('action=Login'); ?>" method="post" enctype="multipart/form-data">
                                <div class="col-sm-12">
                                	<input type="text" placeholder="Email" name="Email" id="Email"/>
                                </div>

                                <div class="col-sm-12">
                                    <input type="password" placeholder="Password"  name="password" id="password"/>
                                    <div class="checkbox text-left">
									<?php if($msg=="ErrorLogin"){?>
									<span style="color:#FF0000;"><?php echo "UserId or password doesn't match !!!";?></span>
									<?php }
									?> <br> 
                                     <input type="checkbox" name="example_check" id="example_check1">
									 <label for="example_check1">Remember Me</label>
                                    </div>
                                    <button class="btn btn-primary btn-xlg btn-block">login</button>
                                </div>

                                <div class="col-sm-12">
                                    <p class="message p-top30 margin-bottom0"><a href="resetpassword.php?flag=1">Forgot password?</a></p>
                                    <p class="message p-top10 margin-bottom0">Not registered? <a href="register.php">Create an account</a></p>
                                </div>
                            </form>
                      </div>
                </div>
            </div>
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

</body>
</html>