<?php
ob_start();
ini_set('display_errors',0);
//error_reporting(E_ALL | E_STRICT);
require_once ('../admin/BO/User.php');
require_once ('../admin/BL/UserManager.php');
include('../admin/UI/Includes/Functions.php');
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
if($msg=='passwordReset')
	echo "<script type='text/javascript'>alert('Password has been sent to your registered email-id');</script>";				   		   

if($paramsArray['strTitle']=="REGISTER")
{
	$strTitle="REGISTER";
	$strSubTitle="Account Register";
}
else
{
	$strTitle="LOGIN";
	$strSubTitle="Account Login";
}

$price=0;
switch($action)
{	
	case "Login":
		if($_POST["6_letters_code"]==$_SESSION["6_letters_code"])
		{
			$objUserManager = new UserManager(); 
			$result=$objUserManager->Getuser(trim($_POST["Email"]),trim($_POST["password"]),'2');
			if(count($result)>0)
			{
				if($result[0]->ACCOUNT_ACTIVATION_FLAG=='1')
				{
					session_start();
					$_SESSION["CUSTOMER_EMAIL"] = $result[0]->COMMUNICATION_EMAIL_ID;
					$_SESSION["CUSTOMER"] = $result[0]->NAME;
					$_SESSION["CUSTOMER_ID"] = $result[0]->USER_ID;
					$_SESSION["USER_TYPE_ID_WEBSITE"] = 2;
					header("location:index.php");
				}
				else
				{
					header("location:login.php?urlstring=".EncryptURL("msg=Verification&email=".trim($_POST["Email"])));
					
				}
			}
			else
			{
				$msg="ErrorLogin";
			}
		}
		else
		{
			$msg="ErrorCaptcha";
			echo "<script type='text/javascript'>alert('Captcha does not match !!!')</script>";	
		}
		$action="";
	break;
	case "LoginPopUp":
		$objUserManager = new UserManager(); 
		$result=$objUserManager->GetUser(trim($_POST["Email"]),$_POST["password"],'2');
		if(count($result)>0)
		{
			if($result[0]->ACCOUNT_ACTIVATION_FLAG=='1')
			{
				session_start();
				$_SESSION["CUSTOMER_EMAIL"] = $result[0]->COMMUNICATION_EMAIL_ID;
				$_SESSION["CUSTOMER"] = $result[0]->NAME;
				$_SESSION["CUSTOMER_ID"] = $result[0]->USER_ID;
				$_SESSION["USER_TYPE_ID_WEBSITE"] = 2;
				header("location:cart.php?urlstring=".EncryptURL("action=Add&productId=".$_POST['productId']));
			}
			else
				header("location:login.php?urlstring=".EncryptURL("msg=Verification&email=".trim($_POST["Email"])));
		}
		else{
		header("location:Expansion-modules.php?urlstring=".EncryptURL("msg=ErrorLogin&product_id=".$_POST['productId']));
		}
	break;	
	
	case "SendEmailOTP":		
		$objUserManager = new UserManager(); 
		$result=$objUserManager->GetuserInfo(trim($_POST['Email']),'2');
		//echo "<pre>";print_r($result);
		//echo "<pre>";print_r($_POST['strOTP']);die;
		if($_POST["6_letters_code"]==$_SESSION["6_letters_code"])
		{
			if($_POST['Email']!="" && $_POST['strOTP']!="")
			{
				if(!count($result)>0)
				{
					include "../admin/smtpmail/classes/class.phpmailer.php"; // include the class name
					$toEmailID = trim($_POST['Email']);
					$subject = 'OTP for Email Verification';
					$message='<body style="background-color: #f4f4f5;">
						<table cellpadding="0" cellspacing="0" style="width: 100%; height: 100%; background-color: #f4f4f5; text-align: center;">
						<tbody><tr>
						<td style="text-align: center;">
						<table align="center" cellpadding="0" cellspacing="0" id="body" style="background-color: #fff; width: 100%; max-width: 680px; height: 100%;">
						<tbody><tr>
						<td>
						<table align="center" cellpadding="0" cellspacing="0" class="page-center" style="text-align: left; padding-bottom: 88px; width: 100%; padding-left: 120px; padding-right: 120px;">
						<tbody><tr>
						
						</tr>
						<tr>
						<td colspan="2" style="padding-top: 72px; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; color: #000000; font-size: 48px; font-smoothing: always; font-style: normal; font-weight: 600; letter-spacing: -2.6px; line-height: 52px; mso-line-height-rule: exactly; text-decoration: none;">Verify Email</td>
						</tr>
						<tr>
						<td style="padding-top: 48px; padding-bottom: 48px;">
						<table cellpadding="0" cellspacing="0" style="width: 100%">
						<tbody><tr>
						<td style="width: 100%; height: 1px; max-height: 1px; background-color: #d9dbe0; opacity: 0.81"></td>
						</tr>
						</tbody></table>
						</td>
						</tr>
						<tr>
						<td style="-ms-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; color: #9095a2; font-family: font-size: 16px; font-smoothing: always; font-style: normal; font-weight: 400; letter-spacing: -0.18px; line-height: 24px; mso-line-height-rule: exactly; text-decoration: none; vertical-align: top; width: 100%;">
						You`re receiving this e-mail because you requested for <strong>New Account</strong>.
						</td>
						</tr>
						<tr>
						<td style="padding-top: 24px; -ms-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; color: #9095a2; font-size: 16px; font-smoothing: always; font-style: normal; font-weight: 400; letter-spacing: -0.18px; line-height: 24px; mso-line-height-rule: exactly; text-decoration: none; vertical-align: top; width: 100%;">
						Please Use This OTP to Verify Email.
						</td>
						</tr>
						<tr>
						<td> <span style="margin-top: 36px; -ms-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; color: #ffffff; font-size: 25px; font-smoothing: always; font-style: normal; font-weight: 600; letter-spacing: 0.7px; line-height: 48px; mso-line-height-rule: exactly; text-decoration: none; vertical-align: top; width: 220px; background-color: #00cc99; border-radius: 28px; display: block; text-align: center;">'.$_POST['strOTP'].'</span>
						</td>
						</tr>
						</tbody></table>
						</td>
						</tr>
						</tbody></table>
						</td>
						</tr>
						</tbody></table>
						</body>';
					//echo $message; die;
					
					$host = "box5213.bluehost.com";
					$userName = "web@sinelec-tech.com";
					$password = "{Ge-[]sE(wq,";
					$fromname = "info@sinelec-tech.com";
					$from = 'info@sinelec-tech.com';
					$mail = new PHPMailer(); // create a new object
					$mail->IsSMTP(); // enable SMTP
					// $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
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
					
					if (!$mail->Send()) 
					{
						//echo "Mailer Error: " . $mail->ErrorInfo;
						$action="CreateNewAcc";
						$strTitle="REGISTER";
						$strSubTitle="Account Register";
						$msg=$mail->ErrorInfo;
					} 
					else 
					{
						echo "<script type='text/javascript'>alert('OTP Succesfully Sent. Please Check Email')</script>";				   		   
						$action="VerifyOTP";
						$strTitle="REGISTER";
						$strSubTitle="Email Verification";
					}
				}
				else
				{
					echo "<script type='text/javascript'>alert('User Already Exists !!!')</script>";	
					$action="CreateNewAcc";
				}
			}
			else
			{
				header("location:login.php?urlstring=".EncryptURL("msg=Error&action=CreateNewAcc"));
			}
		}
		else
		{
			$strTitle="REGISTER";
			$strSubTitle="Account Register";
			$action="CreateNewAcc";
			echo "<script type='text/javascript'>alert('Captcha does not match !!!')</script>";	
		}
	break;
	case "ConfirmOTP":	
		$Email=$_POST['Email'];
		$strOTP=$_POST['strOTP'];
		$strConfirmOTP=$_POST['strConfirmOTP'];
		//echo "<pre>";print_r($_POST);die;
		if($strOTP!="" && $strConfirmOTP!="")
		{
			if($strOTP==$strConfirmOTP)
			{
				$action="Register";
				$strTitle="REGISTER";
				$strSubTitle="Account Register";
				$msg="AccountVerified";
			}
			else
			{
				echo "<script type='text/javascript'>alert('Wrong OTP !!.')</script>";	
				$action="VerifyOTP";			   		   
			}
			
		}
		else
		{
				echo "<script type='text/javascript'>alert('Please Fill OTP First !!.')</script>";	
				$action="VerifyOTP";			   		   
		}
		
	break;
	
	case "Register":
		$Name = $_POST["firstName"].' '.$_POST["lastName"];
		$Email = trim($_POST["Email"]);
		$PhoneIsdcode=$_POST["phone_country_code"];
		$PhoneNumber = $_POST["phone_country_code"].$_POST["PhoneNumber"];
		$MobileNoIsd = $_POST["mobile_country_code"];
		$MobileNumber =$_POST["mobile_country_code"].$_POST["MobileNumber"];
		$Password = $_POST["Password"];
		$ConfirmPassword = $_POST["ConformPassword"];
		$companyName=$_POST["companyName"];
		$DesignationName=$_POST["DesignationName"];
		$objUser = new User(); 
		$objUser->setName($Name);
		$objUser->setEmail($Email);
		$objUser->setPhoneISD($PhoneIsdcode);
		$objUser->setPhoneNumber($PhoneNumber);
		$objUser->setMobileISD($MobileNoIsd);
		$objUser->setMobileNumber($MobileNumber);
		$objUser->setPassword($Password);
		$objUser->setuser_id($user_id);
		$objUser->setCompanyname($companyName);
		$objUser->setDesignation($DesignationName);
		$RandomKeyward=rand(100,10000);
		$objUser->setUserRandomKey($RandomKeyward);
		$arrAddress=array();
		
	   $vat_number=$_POST['vatnumber'];
	   $delivery_address=$_POST['deliveryaddress'];
	   $city=$_POST['city'];
	   $state=$_POST['state'];
	   $zip=$_POST['zip'];
	   
	   list($deliveryCountryId,$country,$deliveryCountryShipping)=explode('@_@',$_POST['country']);
	   
	   $billing_address=$_POST['billingAddress'];
	   $billing_city=$_POST['cityName'];
	   $billing_state=$_POST['stateName'];
	   $billing_zip=$_POST['zipName'];
	   
	   list($billingCountryId,$billing_country,$billingCountryShipping)=explode('@_@',$_POST['countryName']);

		if($billing_address=='')
		{
			$billing_address=$delivery_address;
			$billing_city=$city;
			$billing_state=$state;
			$billing_zip=$zip;
			$billing_country=$country;
			$billingCountryId=$deliveryCountryId;
			$billingCountryShipping=$deliveryCountryShipping;
		}
		
	   $arrAddress=array('vat_number'=>$vat_number, 'delivery_address'=>$delivery_address, 'delivery_city'=>$city, 'delivery_state'=>$state,
	   'delivery_country'=>$country,'delivery_country_id'=>$deliveryCountryId, 'delivery_zip'=>$zip, 
	   'billing_address'=>$billing_address, 'billing_city'=>$billing_city, 'billing_state'=>$billing_state, 'billing_zip'=>$billing_zip, 
	   'billing_country'=>$billing_country);
		//echo "<pre>";print_r($arrAddress);die;
		if($_POST["ConformPassword"]==$_POST["Password"] && $_POST["6_letters_code"]==$_SESSION["6_letters_code"] && $_POST['Password']!="" )
		{
			$objUserManager = new UserManager(); 
			$userId=$objUserManager->InsertUserFromWeb($objUser,'2',$Password,$arrAddress);
			$RandomUrl="http://www.sinelec-tech.com/website/AccountVeryfication.php?urlstring=".EncryptURL("email=".$Email);
			include "../admin/smtpmail/classes/class.phpmailer.php"; // include the class name
			$toEmailID = trim($Email);
			$subject = 'Welcome to Sinelec Technologies';
			$message = '<table width="800" border="1" cellpadding="10">
							  <tr>
								<td width="137"><img src="https://sinelec-tech.com/website/images/Logo.png" alt="logo" width="137" height="39" longdesc="https://sinelec-tech.com/"></td>
								<td width="638" colspan="4" align="right"><strong><font size="+1">Hello '.$Name.'. Thanks for creating an  account in <a href="https://sinelec-tech.com/" target="_blank">sinelec-tech.com</a></font></strong></td>
							  </tr>
							  <tr>
								<td colspan="5"><img src="https://sinelec-tech.com/website/images/welcome.jpg" width="100%" height="289"></td>
							  </tr>
							  <tr>
								<td colspan="5" bgcolor="#002e62"><strong><font color="#FFFFFF" size="+1">Your sinelec-tech.com login credentials are as follows:</font></strong>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<strong><font color="#FFFFFF" size="-1"><a href="https://sinelec-tech.com/website/privacy-policy.php" target="_blank">Privacy Policy</a> | <a href="https://sinelec-tech.com/website/terms-of-use.php" target="_blank">Terms of Use</a></font></strong>
								</td>
							  </tr>
							  <tr>
								<td colspan="5">
									<table width="100%" border="1" cellpadding="10">
									  <tr>
										<td width="50%" bgcolor="#002e62"><strong><font color="#FFFFFF">Login / Email Id</font></strong></td>
										<td width="50%"><strong>' . $toEmailID . '</strong></td>
									  </tr>
									</table>
								 </td>
							  </tr>
							  <tr>
								<td style="font-family:Open Sans, Arial, sans-serif; font-size:12px; line-height:15px;" colspan="5">
								Note: This is an auto generated email from Sinelec. For any query kindly contact info@sinelec-tech.com.
								</td>
							  </tr>
							</table>';

				//echo $message; die;
				
				$host = "box5213.bluehost.com";
				$userName = "web@sinelec-tech.com";
				$password = "{Ge-[]sE(wq,";
				$fromname = "info@sinelec-tech.com";
				$from = 'info@sinelec-tech.com';
			   
				ob_clean();
				$mail = new PHPMailer(); // create a new object
				$mail->IsSMTP(); // enable SMTP
				// $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
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
					header("location:login.php?urlstring=".EncryptURL("email=".$toEmailID."msg=SuccessReg"));
				}
		}
		else
		{
			header("location:register.php?urlstring=".EncryptURL("msg=Error"));
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
            <h3><?php echo $strTitle;?></h3>
            <ol class="breadcrumb">
                <li><a href="index.php">home</a></li>
                <li class="active"><?php echo $strTitle;?></li>
            </ol>
        </div>
    </section>

    <!--Login-->
    <section class="row section-spacing2  bg-pattern">
        <div class="container">
        	<div class="sectionTitle p-bottom40">
                <h2><?php echo $strSubTitle;?></h2>
            </div>
			<?php
            $msg=isset($paramsArray['msg']);
			if(isset($msg))
			{
				if($msg=='inserted')
				{
				?>
				<?php
				}
				elseif($msg=='MailSentForgetPassword')
				{
				?>
					<div class="row" style="text-align:center">
					  <p style="color:green;font-size:20px"><b>Password is successfully sent to your e-mail</b></p>
					</div> 
				  <?php
				}
				elseif($msg=='AccountVerified')
				{
				?>
					<div class="row" style="text-align:center">
					  <p style="color:green;font-size:20px"><b>Your Email has been successfully verified</b></p>
					</div> 
				  <?php
				}
				elseif($msg=='SuccessReg')
				{
				?>
					<div class="row" style="text-align:center">
					  <p style="color:green;font-size:20px"><b>Welcome to www.sinelec-tech.com. Kindly login to purchase our products.</b></p>
					</div> 
				<?php
				}
				else
				{
				?>
					<div class="row" style="text-align:center">
					  <p style="color:green;font-size:20px"><b><?php echo $msg;?></b></p>
					</div> 
				<?php
				}
			}
            ?>
			<?php 
            if($action=='')
            {
            ?>
                <div class="row">
                    <div class="col-sm-6 col-md-5 col-xs-12 center-block">
                          <div class="form bg-gray clearfix login-form border">
                                <form class="login-form clearfix" action="login.php?urlstring=<?php echo EncryptURL('action=Login'); ?>" method="post" enctype="multipart/form-data">
                                    <div class="col-sm-12">
                                        <input type="text" placeholder="Email" value="<?php echo $_POST["Email"]; ?>" name="Email" id="Email"/>
                                    </div>
                                    <div class="col-sm-12">
                                        <input type="password" placeholder="Password" value="<?php echo $_POST["password"]; ?>" name="password" id="password"/>
                                        <?php if($msg=="ErrorLogin"){?>
                                        <span style="color:#FF0000;"><?php echo "Login / Email Id or Password does not match !!!";?></span>
                                        <?php }
                                        ?> 
                                    </div>
                                    <div class="col-md-6">
                                        <div class="g-recaptcha" data-sitekey="captcha_code_file"></div>
                                        <img src="captcha_code_file.php?rand=<?php echo rand(); ?>" id='captchaimg' >
                                        <div>
                                            <input  type="hidden" name="captcha" id="captcha" value="<?php echo rand(); ?>">
                                            <label for='message'>Enter the code above here :</label><br>
                                            <input id="6_letters_code" name="6_letters_code" type="text"><br>
                                            <small>Can't read the image? click <a href='javascript: refreshCaptcha();'>here</a> to refresh</small>
                                        </div>
                                        <div class="checkbox text-left">
                                            <input type="checkbox" name="example_check" id="example_check1">
                                            <label for="example_check1">Remember Me</label>  
                                        </div>                                  
                                     </div>
                                    <div class="col-md-12">
                                        <button class="btn btn-primary btn-xlg btn-block">login</button>
                                    </div>
									<?php 
                                    if($msg=="ErrorCaptcha")
                                    {
                                    ?>
                                        <span style="color:#FF0000;"><?php echo "Captcha does not match !!!";?></span>
                                    <?php 
                                    }
                                    ?>  
                                    <div class="col-sm-12">
                                        <p class="message p-top30 margin-bottom0"><a href="forgetPassword.php">Forgot password?</a></p>
                                        <p class="message p-top10 margin-bottom0">Not registered? <a href="login.php?urlstring=<?php echo EncryptURL('action=CreateNewAcc&strTitle=REGISTER'); ?>">Create an account</a></p>
                                    </div>
                                </form>
                          </div>
                    </div>
                </div>
            <?php
            }
            if($action=='CreateNewAcc')
			{
				$otp=random_strings(6);
			?>
                <div class="row">
                    <div class="col-sm-6 col-md-5 col-xs-12 center-block">
                          <div class="form bg-gray clearfix login-form border">
                                <form class="login-form clearfix" action="login.php?urlstring=<?php echo EncryptURL('action=SendEmailOTP'); ?>" method="post" enctype="multipart/form-data">
                                	<input type="hidden" name="strOTP" value="<?php echo $otp; ?>">
                                    <div class="col-sm-12">
                                        <input type="text" required value="<?php echo $_POST['Email']; ?>" placeholder="Email" name="Email" id="Email"/>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="g-recaptcha" data-sitekey="captcha_code_file"></div>
                                        <img src="captcha_code_file.php?rand=<?php echo rand(); ?>" id='captchaimg' >
                                        <div>
                                            <input  type="hidden" name="captcha" id="captcha" value="<?php echo rand(); ?>">
                                            <label for='message'>Enter the code above here :</label><br>
                                            <input id="6_letters_code" name="6_letters_code" type="text"><br>
                                            <small>Can't read the image? click <a href='javascript: refreshCaptcha();'>here</a> to refresh</small>
                                        </div>
                                        <div class="checkbox text-left">
                                            <input type="checkbox" name="example_check" id="example_check1">
                                            <label for="example_check1">Remember Me</label>  
                                        </div>                                  
                                     </div>
                                    <div class="col-sm-12">
                                        <button class="btn btn-primary btn-xlg btn-block">Continue</button>
                                    </div>
                             </form>
                          </div>
                    </div>
                </div>
            <?php
			}
            if($action=='VerifyOTP')
			{
			?>
                <div class="row">
                    <div class="col-sm-6 col-md-5 col-xs-12 center-block">
                          <div class="form bg-gray clearfix login-form border">
                                <form class="login-form clearfix" action="login.php?urlstring=<?php echo EncryptURL('action=ConfirmOTP'); ?>" method="post" enctype="multipart/form-data">
                                	<input type="hidden" name="strOTP" value="<?php echo $_POST['strOTP']; ?>">
                                    <div class="col-sm-12">
                                        <input type="text" placeholder="Email" value="<?php echo $_POST['Email']; ?>" readonly name="Email" id="Email"/>
                                    </div>
                                    <div class="col-sm-12">
                                        <input type="text" required placeholder="Enter OTP" name="strConfirmOTP" id="strConfirmOTP"/>
                                    </div>
                                    <div class="col-sm-12">
                                        <button class="btn btn-primary btn-xlg btn-block" type="button" onClick="window.location.reload();">Re-Send OTP</button>
                                        <button type="submit" class="btn btn-primary btn-xlg btn-block">Verify</button>
                                    </div>
                             </form>
                          </div>
                    </div>
                </div>
            <?php
			}
            if($action=='Register')
			{
				$objUserManager = new UserManager(); 
				$countryList=$objUserManager->GetAllCountryList();
			?>
                <div class="row">
                    <div class="col-sm-8 center-block register-form">
                        <div class="form"s style="white-space:normal">
                            <form class="login-form clearfix bg-gray border" action="login.php?urlstring=<?php echo EncryptURL('action=Register'); ?>" method="post" enctype="multipart/form-data">
                                <div class="col-sm-6">
                                    <input placeholder="First Name *" type="text" name="firstName" id="firstName" value="">
                                    <span style="color:red;margin:0px;padding:0" id="first_name_msg"></span>
                                </div>
                                <div class="col-sm-6">
                                    <input placeholder="Last Name" type="text" name="lastName" id="lastName" value="">
                                </div>
                                <div class="col-sm-6"> 
                                    <input placeholder="Email *" type="email" readonly name="Email" id="Email" value="<?php echo $_POST['Email']; ?>"> 
                                </div>
                                <div class="col-sm-2">
                                    <select name="mobile_country_code" id="mobile_country_code" style="font-size:12px;text-align:left">
                                        <option data-countryCode="GB" value="44" Selected>UK (+44)</option>
                                        <option data-countryCode="US" value="1">USA (+1)</option>
                                        <optgroup label="Other countries">
                                            <option data-countryCode="DZ" value="213">Algeria (+213)</option>
                                            <option data-countryCode="AD" value="376">Andorra (+376)</option>
                                            <option data-countryCode="AO" value="244">Angola (+244)</option>
                                            <option data-countryCode="AI" value="1264">Anguilla (+1264)</option>
                                            <option data-countryCode="AG" value="1268">Antigua &amp; Barbuda (+1268)</option>
                                            <option data-countryCode="AR" value="54">Argentina (+54)</option>
                                            <option data-countryCode="AM" value="374">Armenia (+374)</option>
                                            <option data-countryCode="AW" value="297">Aruba (+297)</option>
                                            <option data-countryCode="AU" value="61">Australia (+61)</option>
                                            <option data-countryCode="AT" value="43">Austria (+43)</option>
                                            <option data-countryCode="AZ" value="994">Azerbaijan (+994)</option>
                                            <option data-countryCode="BS" value="1242">Bahamas (+1242)</option>
                                            <option data-countryCode="BH" value="973">Bahrain (+973)</option>
                                            <option data-countryCode="BD" value="880">Bangladesh (+880)</option>
                                            <option data-countryCode="BB" value="1246">Barbados (+1246)</option>
                                            <option data-countryCode="BY" value="375">Belarus (+375)</option>
                                            <option data-countryCode="BE" value="32">Belgium (+32)</option>
                                            <option data-countryCode="BZ" value="501">Belize (+501)</option>
                                            <option data-countryCode="BJ" value="229">Benin (+229)</option>
                                            <option data-countryCode="BM" value="1441">Bermuda (+1441)</option>
                                            <option data-countryCode="BT" value="975">Bhutan (+975)</option>
                                            <option data-countryCode="BO" value="591">Bolivia (+591)</option>
                                            <option data-countryCode="BA" value="387">Bosnia Herzegovina (+387)</option>
                                            <option data-countryCode="BW" value="267">Botswana (+267)</option>
                                            <option data-countryCode="BR" value="55">Brazil (+55)</option>
                                            <option data-countryCode="BN" value="673">Brunei (+673)</option>
                                            <option data-countryCode="BG" value="359">Bulgaria (+359)</option>
                                            <option data-countryCode="BF" value="226">Burkina Faso (+226)</option>
                                            <option data-countryCode="BI" value="257">Burundi (+257)</option>
                                            <option data-countryCode="KH" value="855">Cambodia (+855)</option>
                                            <option data-countryCode="CM" value="237">Cameroon (+237)</option>
                                            <option data-countryCode="CA" value="1">Canada (+1)</option>
                                            <option data-countryCode="CV" value="238">Cape Verde Islands (+238)</option>
                                            <option data-countryCode="KY" value="1345">Cayman Islands (+1345)</option>
                                            <option data-countryCode="CF" value="236">Central African Republic (+236)</option>
                                            <option data-countryCode="CL" value="56">Chile (+56)</option>
                                            <option data-countryCode="CN" value="86">China (+86)</option>
                                            <option data-countryCode="CO" value="57">Colombia (+57)</option>
                                            <option data-countryCode="KM" value="269">Comoros (+269)</option>
                                            <option data-countryCode="CG" value="242">Congo (+242)</option>
                                            <option data-countryCode="CK" value="682">Cook Islands (+682)</option>
                                            <option data-countryCode="CR" value="506">Costa Rica (+506)</option>
                                            <option data-countryCode="HR" value="385">Croatia (+385)</option>
                                            <option data-countryCode="CU" value="53">Cuba (+53)</option>
                                            <option data-countryCode="CY" value="90392">Cyprus North (+90392)</option>
                                            <option data-countryCode="CY" value="357">Cyprus South (+357)</option>
                                            <option data-countryCode="CZ" value="42">Czech Republic (+42)</option>
                                            <option data-countryCode="DK" value="45">Denmark (+45)</option>
                                            <option data-countryCode="DJ" value="253">Djibouti (+253)</option>
                                            <option data-countryCode="DM" value="1809">Dominica (+1809)</option>
                                            <option data-countryCode="DO" value="1809">Dominican Republic (+1809)</option>
                                            <option data-countryCode="EC" value="593">Ecuador (+593)</option>
                                            <option data-countryCode="EG" value="20">Egypt (+20)</option>
                                            <option data-countryCode="SV" value="503">El Salvador (+503)</option>
                                            <option data-countryCode="GQ" value="240">Equatorial Guinea (+240)</option>
                                            <option data-countryCode="ER" value="291">Eritrea (+291)</option>
                                            <option data-countryCode="EE" value="372">Estonia (+372)</option>
                                            <option data-countryCode="ET" value="251">Ethiopia (+251)</option>
                                            <option data-countryCode="FK" value="500">Falkland Islands (+500)</option>
                                            <option data-countryCode="FO" value="298">Faroe Islands (+298)</option>
                                            <option data-countryCode="FJ" value="679">Fiji (+679)</option>
                                            <option data-countryCode="FI" value="358">Finland (+358)</option>
                                            <option data-countryCode="FR" value="33">France (+33)</option>
                                            <option data-countryCode="GF" value="594">French Guiana (+594)</option>
                                            <option data-countryCode="PF" value="689">French Polynesia (+689)</option>
                                            <option data-countryCode="GA" value="241">Gabon (+241)</option>
                                            <option data-countryCode="GM" value="220">Gambia (+220)</option>
                                            <option data-countryCode="GE" value="7880">Georgia (+7880)</option>
                                            <option data-countryCode="DE" value="49">Germany (+49)</option>
                                            <option data-countryCode="GH" value="233">Ghana (+233)</option>
                                            <option data-countryCode="GI" value="350">Gibraltar (+350)</option>
                                            <option data-countryCode="GR" value="30">Greece (+30)</option>
                                            <option data-countryCode="GL" value="299">Greenland (+299)</option>
                                            <option data-countryCode="GD" value="1473">Grenada (+1473)</option>
                                            <option data-countryCode="GP" value="590">Guadeloupe (+590)</option>
                                            <option data-countryCode="GU" value="671">Guam (+671)</option>
                                            <option data-countryCode="GT" value="502">Guatemala (+502)</option>
                                            <option data-countryCode="GN" value="224">Guinea (+224)</option>
                                            <option data-countryCode="GW" value="245">Guinea - Bissau (+245)</option>
                                            <option data-countryCode="GY" value="592">Guyana (+592)</option>
                                            <option data-countryCode="HT" value="509">Haiti (+509)</option>
                                            <option data-countryCode="HN" value="504">Honduras (+504)</option>
                                            <option data-countryCode="HK" value="852">Hong Kong (+852)</option>
                                            <option data-countryCode="HU" value="36">Hungary (+36)</option>
                                            <option data-countryCode="IS" value="354">Iceland (+354)</option>
                                            <option data-countryCode="IN" value="91">India (+91)</option>
                                            <option data-countryCode="ID" value="62">Indonesia (+62)</option>
                                            <option data-countryCode="IR" value="98">Iran (+98)</option>
                                            <option data-countryCode="IQ" value="964">Iraq (+964)</option>
                                            <option data-countryCode="IE" value="353">Ireland (+353)</option>
                                            <option data-countryCode="IL" value="972">Israel (+972)</option>
                                            <option data-countryCode="IT" value="39">Italy (+39)</option>
                                            <option data-countryCode="JM" value="1876">Jamaica (+1876)</option>
                                            <option data-countryCode="JP" value="81">Japan (+81)</option>
                                            <option data-countryCode="JO" value="962">Jordan (+962)</option>
                                            <option data-countryCode="KZ" value="7">Kazakhstan (+7)</option>
                                            <option data-countryCode="KE" value="254">Kenya (+254)</option>
                                            <option data-countryCode="KI" value="686">Kiribati (+686)</option>
                                            <option data-countryCode="KP" value="850">Korea North (+850)</option>
                                            <option data-countryCode="KR" value="82">Korea South (+82)</option>
                                            <option data-countryCode="KW" value="965">Kuwait (+965)</option>
                                            <option data-countryCode="KG" value="996">Kyrgyzstan (+996)</option>
                                            <option data-countryCode="LA" value="856">Laos (+856)</option>
                                            <option data-countryCode="LV" value="371">Latvia (+371)</option>
                                            <option data-countryCode="LB" value="961">Lebanon (+961)</option>
                                            <option data-countryCode="LS" value="266">Lesotho (+266)</option>
                                            <option data-countryCode="LR" value="231">Liberia (+231)</option>
                                            <option data-countryCode="LY" value="218">Libya (+218)</option>
                                            <option data-countryCode="LI" value="417">Liechtenstein (+417)</option>
                                            <option data-countryCode="LT" value="370">Lithuania (+370)</option>
                                            <option data-countryCode="LU" value="352">Luxembourg (+352)</option>
                                            <option data-countryCode="MO" value="853">Macao (+853)</option>
                                            <option data-countryCode="MK" value="389">Macedonia (+389)</option>
                                            <option data-countryCode="MG" value="261">Madagascar (+261)</option>
                                            <option data-countryCode="MW" value="265">Malawi (+265)</option>
                                            <option data-countryCode="MY" value="60">Malaysia (+60)</option>
                                            <option data-countryCode="MV" value="960">Maldives (+960)</option>
                                            <option data-countryCode="ML" value="223">Mali (+223)</option>
                                            <option data-countryCode="MT" value="356">Malta (+356)</option>
                                            <option data-countryCode="MH" value="692">Marshall Islands (+692)</option>
                                            <option data-countryCode="MQ" value="596">Martinique (+596)</option>
                                            <option data-countryCode="MR" value="222">Mauritania (+222)</option>
                                            <option data-countryCode="YT" value="269">Mayotte (+269)</option>
                                            <option data-countryCode="MX" value="52">Mexico (+52)</option>
                                            <option data-countryCode="FM" value="691">Micronesia (+691)</option>
                                            <option data-countryCode="MD" value="373">Moldova (+373)</option>
                                            <option data-countryCode="MC" value="377">Monaco (+377)</option>
                                            <option data-countryCode="MN" value="976">Mongolia (+976)</option>
                                            <option data-countryCode="MS" value="1664">Montserrat (+1664)</option>
                                            <option data-countryCode="MA" value="212">Morocco (+212)</option>
                                            <option data-countryCode="MZ" value="258">Mozambique (+258)</option>
                                            <option data-countryCode="MN" value="95">Myanmar (+95)</option>
                                            <option data-countryCode="NA" value="264">Namibia (+264)</option>
                                            <option data-countryCode="NR" value="674">Nauru (+674)</option>
                                            <option data-countryCode="NP" value="977">Nepal (+977)</option>
                                            <option data-countryCode="NL" value="31">Netherlands (+31)</option>
                                            <option data-countryCode="NC" value="687">New Caledonia (+687)</option>
                                            <option data-countryCode="NZ" value="64">New Zealand (+64)</option>
                                            <option data-countryCode="NI" value="505">Nicaragua (+505)</option>
                                            <option data-countryCode="NE" value="227">Niger (+227)</option>
                                            <option data-countryCode="NG" value="234">Nigeria (+234)</option>
                                            <option data-countryCode="NU" value="683">Niue (+683)</option>
                                            <option data-countryCode="NF" value="672">Norfolk Islands (+672)</option>
                                            <option data-countryCode="NP" value="670">Northern Marianas (+670)</option>
                                            <option data-countryCode="NO" value="47">Norway (+47)</option>
                                            <option data-countryCode="OM" value="968">Oman (+968)</option>
                                            <option data-countryCode="PW" value="680">Palau (+680)</option>
                                            <option data-countryCode="PA" value="507">Panama (+507)</option>
                                            <option data-countryCode="PG" value="675">Papua New Guinea (+675)</option>
                                            <option data-countryCode="PY" value="595">Paraguay (+595)</option>
                                            <option data-countryCode="PE" value="51">Peru (+51)</option>
                                            <option data-countryCode="PH" value="63">Philippines (+63)</option>
                                            <option data-countryCode="PL" value="48">Poland (+48)</option>
                                            <option data-countryCode="PT" value="351">Portugal (+351)</option>
                                            <option data-countryCode="PR" value="1787">Puerto Rico (+1787)</option>
                                            <option data-countryCode="QA" value="974">Qatar (+974)</option>
                                            <option data-countryCode="RE" value="262">Reunion (+262)</option>
                                            <option data-countryCode="RO" value="40">Romania (+40)</option>
                                            <option data-countryCode="RU" value="7">Russia (+7)</option>
                                            <option data-countryCode="RW" value="250">Rwanda (+250)</option>
                                            <option data-countryCode="SM" value="378">San Marino (+378)</option>
                                            <option data-countryCode="ST" value="239">Sao Tome &amp; Principe (+239)</option>
                                            <option data-countryCode="SA" value="966">Saudi Arabia (+966)</option>
                                            <option data-countryCode="SN" value="221">Senegal (+221)</option>
                                            <option data-countryCode="CS" value="381">Serbia (+381)</option>
                                            <option data-countryCode="SC" value="248">Seychelles (+248)</option>
                                            <option data-countryCode="SL" value="232">Sierra Leone (+232)</option>
                                            <option data-countryCode="SG" value="65">Singapore (+65)</option>
                                            <option data-countryCode="SK" value="421">Slovak Republic (+421)</option>
                                            <option data-countryCode="SI" value="386">Slovenia (+386)</option>
                                            <option data-countryCode="SB" value="677">Solomon Islands (+677)</option>
                                            <option data-countryCode="SO" value="252">Somalia (+252)</option>
                                            <option data-countryCode="ZA" value="27">South Africa (+27)</option>
                                            <option data-countryCode="ES" value="34">Spain (+34)</option>
                                            <option data-countryCode="LK" value="94">Sri Lanka (+94)</option>
                                            <option data-countryCode="SH" value="290">St. Helena (+290)</option>
                                            <option data-countryCode="KN" value="1869">St. Kitts (+1869)</option>
                                            <option data-countryCode="SC" value="1758">St. Lucia (+1758)</option>
                                            <option data-countryCode="SD" value="249">Sudan (+249)</option>
                                            <option data-countryCode="SR" value="597">Suriname (+597)</option>
                                            <option data-countryCode="SZ" value="268">Swaziland (+268)</option>
                                            <option data-countryCode="SE" value="46">Sweden (+46)</option>
                                            <option data-countryCode="CH" value="41">Switzerland (+41)</option>
                                            <option data-countryCode="SI" value="963">Syria (+963)</option>
                                            <option data-countryCode="TW" value="886">Taiwan (+886)</option>
                                            <option data-countryCode="TJ" value="7">Tajikstan (+7)</option>
                                            <option data-countryCode="TH" value="66">Thailand (+66)</option>
                                            <option data-countryCode="TG" value="228">Togo (+228)</option>
                                            <option data-countryCode="TO" value="676">Tonga (+676)</option>
                                            <option data-countryCode="TT" value="1868">Trinidad &amp; Tobago (+1868)</option>
                                            <option data-countryCode="TN" value="216">Tunisia (+216)</option>
                                            <option data-countryCode="TR" value="90">Turkey (+90)</option>
                                            <option data-countryCode="TM" value="7">Turkmenistan (+7)</option>
                                            <option data-countryCode="TM" value="993">Turkmenistan (+993)</option>
                                            <option data-countryCode="TC" value="1649">Turks &amp; Caicos Islands (+1649)</option>
                                            <option data-countryCode="TV" value="688">Tuvalu (+688)</option>
                                            <option data-countryCode="UG" value="256">Uganda (+256)</option>
                                            <!-- <option data-countryCode="GB" value="44">UK (+44)</option> -->
                                            <option data-countryCode="UA" value="380">Ukraine (+380)</option>
                                            <option data-countryCode="AE" value="971">United Arab Emirates (+971)</option>
                                            <option data-countryCode="UY" value="598">Uruguay (+598)</option>
                                            <!-- <option data-countryCode="US" value="1">USA (+1)</option> -->
                                            <option data-countryCode="UZ" value="7">Uzbekistan (+7)</option>
                                            <option data-countryCode="VU" value="678">Vanuatu (+678)</option>
                                            <option data-countryCode="VA" value="379">Vatican City (+379)</option>
                                            <option data-countryCode="VE" value="58">Venezuela (+58)</option>
                                            <option data-countryCode="VN" value="84">Vietnam (+84)</option>
                                            <option data-countryCode="VG" value="84">Virgin Islands - British (+1284)</option>
                                            <option data-countryCode="VI" value="84">Virgin Islands - US (+1340)</option>
                                            <option data-countryCode="WF" value="681">Wallis &amp; Futuna (+681)</option>
                                            <option data-countryCode="YE" value="969">Yemen (North)(+969)</option>
                                            <option data-countryCode="YE" value="967">Yemen (South)(+967)</option>
                                            <option data-countryCode="ZM" value="260">Zambia (+260)</option>
                                            <option data-countryCode="ZW" value="263">Zimbabwe (+263)</option>
                                        </optgroup>								
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <input placeholder="Phone Number*" type="text" name="MobileNumber" id="MobileNumber" value="" onKeyPress="return validateNumber(event)"><span style="color:red;margin:0px;padding:0" id="mobile_msg"></span>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-sm-6">
                                    <input placeholder="Company Name" type="text" name="companyName" id="companyName" value="">
                                </div>
                                <div class="col-sm-6">
                                    <input placeholder="Designation" type="text" name="DesignationName" id="DesignationName" value="">
                                </div>							
                                <div class="clearfix"></div>
                                <div class="col-sm-6">
                                    <input placeholder="Password *" type="password" name="Password" id="Password">
                                </div>
                                <div class="col-sm-6">
                                   <input placeholder="Confirm Password *" type="password" name="ConformPassword" id="ConformPassword">
                                </div>
                                <div class="row" style="text-align:center">
                                    <span style="color:red;margin:0px;padding:0;text-align:center"  id="msg_password"></span>
                                </div>
                                <div class="col-sm-12">
                                    <input placeholder="Delivery Address*" type="text" name="deliveryaddress" id="deliveryaddress" value="<?php echo $fillDataArray['deliveryaddress'] ?>" required>					
                                </div>	
                                    <!---------------------------------------------------------->
                                <div class="col-sm-6">
                                    <input  placeholder="City/District/Town"  type="text" name="city" id="city" required>
                                </div>
                                <div class="col-sm-6">
                                    <input  type="text" placeholder="State"  id="state" name="state" required>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text"  id="ZIP" placeholder="zip" name="zip" required>
                                </div>
                                <div class="col-sm-6">
                                    <select  placeholder="Country"  type="text"  id="country" onChange="showHideVatNo(this.value)" name="country" required>
                                        <option value=''>Select Country</option>
                                        <?php 
                                        if(count($countryList)>0)
                                        {
                                            foreach($countryList as $country)
                                            {
                                            ?> 
                                                <option  value="<?php echo $country->COUNTRY_ID.'@_@'.$country->COUNTRY.'@_@'.$country->SHIPPING_AMT; ?>" >
                                                <?php echo $country->COUNTRY; ?></option>
                                            <?php
                                            } 
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-12">
                                    <input type="checkbox"  id="yourBox" style="float:left; width:20px">
                                    <label for="example_check1" style=" font-weight:400">Billing Address Different from Delivery address </label>
                                </div>
                                <div class="col-sm-12">
                                    <input placeholder="Billing Address" type="text" id="billingAddress" name="billingAddress" disabled  value="">
                                </div>
                                <div class="col-sm-6">
                                    <input  placeholder="City/District/Town"  type="text" name="cityName" id="cityName" disabled>
                                </div>
                                <div class="col-sm-6">
                                    <input  placeholder="State" name="stateName" type="text" id="stateName" disabled>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" name="zipName"  placeholder="ZIP" id="zipName" disabled >
                                </div>
                                <div class="col-sm-6">
                                    <select  placeholder="Country"  type="text" name="countryName" onChange="showHideVatNo(this.value)"  id="countryName" disabled>
                                        <option value=''>Select Country</option>
                                        <?php 
                                        if(count($countryList)>0)
                                        {
                                            foreach($countryList as $countryBill)
                                            {
                                            ?> 
                                                <option  value="<?php echo $countryBill->COUNTRY_ID.'@_@'.$countryBill->COUNTRY.'@_@'.$countryBill->SHIPPING_AMT; ?>" >
                                                <?php echo $countryBill->COUNTRY; ?></option>
                                            <?php
                                            } 
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-12">
                                    <input id="example_check1" type="checkbox" style="float:left; width:20px">
                                    <label for="example_check1"  style="float:left; font-weight:400">If you are a company situated outside Germany but in EU and want a 
                                    VAT free quote</label>
                                    <input placeholder="VAT Number" type="text" id="vatnumber" name="vatnumber" disabled value="">
                                </div>
                                <div class="col-md-6">
                                    <div class="g-recaptcha" data-sitekey="captcha_code_file"></div>
                                    <img src="captcha_code_file.php?rand=<?php echo rand(); ?>" id='captchaimg' >
                                    <div>
										<?php 
                                        if($msg=="Error")
                                        {
                                        ?>
                                            <span style="color:#FF0000;"><?php echo "Either captcha or password does not match !!!";?></span>
                                        <?php 
                                        }
                                        ?>  
                                        <input  type="hidden" name="captcha" id="captcha" value="<?php echo rand(); ?>">
                                        <label for='message'>Enter the code above here :</label><br>
                                        <input id="6_letters_code" name="6_letters_code" type="text"><br>
                                        <small>Can't read the image? click <a href='javascript: refreshCaptcha();'>here</a> to refresh</small>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-sm-12 text-center">
                                    <input type="submit" class="btn btn-primary btn-xlg col-sm-8 col-xs-12 center-block m-top30" onClick=" return confirm ('Are You Sure you want to Save it?\n Click OK to Continue, Cancel to Stop'),ValidateForm();" value="Register">
                                    <p class="message p-top30 margin-bottom0">Already registered ? <a href="login.php">Account Login</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
				<script>
                function refreshCaptcha()
                {
                    var img = document.images['captchaimg'];
                    img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
                }
                
                document.getElementById('yourBox').onchange = function() 
                {
                    document.getElementById('billingAddress').disabled = !this.checked;
                    document.getElementById('cityName').disabled = !this.checked;
                    
                    document.getElementById('stateName').disabled = !this.checked;
                    
                    document.getElementById('zipName').disabled = !this.checked;
                    document.getElementById('countryName').disabled = !this.checked;
                
                };
                
                document.getElementById('example_check1').onchange = function() 
                {
                    document.getElementById('vatnumber').disabled = !this.checked;
                };
            
            function ValidateForm() 
            {
                var x = document.getElementById("firstName").value;
                var string = /^[a-zA-Z ]+$/;
                if (x == "") 
                {
                    document.getElementById("first_name_msg").innerHTML="Name is Mandatory";
                    document.getElementById("firstName").focus();
                    return false;
                }
                else
                {
                    document.getElementById("first_name_msg").innerHTML="";
                }
                if(!x.match(string))   
                {  
                    alert("plss use character");
                    return false;  
                }  
                var email = document.getElementById("Email").value;
                if(email=="" || email.trim()=="")
                {
                     document.getElementById("email_msg").innerHTML="E-mail is Mandatory";
                     document.getElementById("Email").focus();
                    return false;
                }
                else
                {
                    var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
                    if (reg.test(email) == false) 
                    {
                        document.getElementById("email_msg").innerHTML="Invalid E-mail";
                        return false;
                    }
                    else
                    {
                        document.getElementById("email_msg").innerHTML="";
                    }
                }
                var MobileNumber = document.getElementById("MobileNumber").value;
                if(MobileNumber=="" || MobileNumber.trim()=="")
                {
                     document.getElementById("mobile_msg").innerHTML="Mobile No. is Mandatory";
                     document.getElementById("MobileNumber").focus();
                     return false;
                }
                else
                {
                    document.getElementById("MobileNumber").innerHTML="";
                }
              
                var Password=   document.getElementById("Password").value;
                if(document.getElementById("pass_flag_applicable").value==0)
                {
                    var ConformPassword=   document.getElementById("ConformPassword").value;
                    if((Password=="" || Password.trim()=="") || (ConformPassword=="" || ConformPassword.trim()=="") )	
                    {
                        document.getElementById("msg_password").innerHTML="Password or confirm password should not blank";
                        return false;
                    }
                    else
                    {
                        if(Password!=ConformPassword)
                        {
                            document.getElementById("msg_password").innerHTML="Password & confirm password should be same";
                            return false;
                        }
                    }
                }
                
                if(!x1.match(string))   
                {  
                    alert("Please use character");
                    return false;  
                }  
                    
                if(!x2.match(string))   
                {  
                    alert("Please use Numeric");
                    return false;  
                } 
                
                var x3 = document.getElementById("MobileNumber").value;
                var string = !/^[0-9]+$/.test(z);
                if (x3 == "") 
                {
                    alert("Phone can not be left blank.");
                    return false;
                }
                if(!x3.match(string))   
                {  
                    alert("Please use Numeric");
                    return false;  
                } 
                
                
                if(document.getElementById("Password").value=="") 
                {
                    alert("Password can not be left blank.");
                    return false;
                } 
            }
                  
            function validateNumber(event) {
                var key = window.event ? event.keyCode : event.which;
                if (event.keyCode === 8 || event.keyCode === 46) {
                return true;
                } else if ( key < 48 || key > 57 ) {
                  document.getElementById("MobileNumber").innerHTML="Mobile no should numeric";
                return false;
                } else {
                return true;
                }
            };
            </script>

            <?php
			}
            ?>
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