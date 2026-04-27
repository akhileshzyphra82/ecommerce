<?php
ob_start();
ini_set('display_errors',0);
include('../admin/UI/Includes/Functions.php');
//include ('Includes/SendEmail.php');
require_once ('../admin/BL/UserManager.php');
$objUserManager = new UserManager();
//echo "<pre>";print_r($_POST); die;

$paramArray = GetQueryStringParameters();
(isset($paramArray['action']))? $action=$paramArray['action'] : $action="";
(isset($paramArray['msg']))? $msg=$paramArray['msg'] : $msg="";
//echo "<pre>";print_r($paramArray); die;
switch($action)
{
	case 'SendOTP':
	if(isset($_POST['g-recaptcha-response']))
	{
		$secretKey = '6LcXLrIZAAAAANiQ5tFs1TfUpdXrQaijOh-jIe--';   //// do not modify
		$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$_POST['g-recaptcha-response']);
		//echo $verifyResponse; die;
		//echo "<pre>";print_r($_POST);
		$response=json_decode($verifyResponse);
		
		if($response->success)
		{
			$otp=random_strings(6);
			$strUserEmail=$_POST['strUserEmailId'];
			
			$arrUserData=$objUserManager->UpdateInsUserOTP($strUserEmail,$otp);
			//echo "<pre>";print_r($arrUserData); die;
			if(count($arrUserData)>0)
			{
				if($arrUserData[0]->ACCOUNT_ACTIVATION_FLAG=='1')
				{
					include "../admin/smtpmail/classes/class.phpmailer.php"; // include the class name
					$subject='OTP To Reset Password - Sinelec Technologies';
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
					<td colspan="2" style="padding-top: 72px; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; color: #000000; font-size: 48px; font-smoothing: always; font-style: normal; font-weight: 600; letter-spacing: -2.6px; line-height: 52px; mso-line-height-rule: exactly; text-decoration: none;">Reset your password</td>
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
					You`re receiving this e-mail because you requested a password reset for your account.
					</td>
					</tr>
					<tr>
					<td style="padding-top: 24px; -ms-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; color: #9095a2; font-size: 16px; font-smoothing: always; font-style: normal; font-weight: 400; letter-spacing: -0.18px; line-height: 24px; mso-line-height-rule: exactly; text-decoration: none; vertical-align: top; width: 100%;">
					Please Use This OTP to Reset Your Password.
					</td>
					</tr>
					<tr>
					<td> <span style="margin-top: 36px; -ms-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; color: #ffffff; font-size: 25px; font-smoothing: always; font-style: normal; font-weight: 600; letter-spacing: 0.7px; line-height: 48px; mso-line-height-rule: exactly; text-decoration: none; vertical-align: top; width: 220px; background-color: #00cc99; border-radius: 28px; display: block; text-align: center;">'.$otp.'</span>
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
					$successMsg='';
					//$sendEmailArray[] = array('EMAILID'=>$strUserEmailId,'EMAILSUBJECT'=>$subject,'MESSAGE'=>$message,'successMsg'=>$successMsg);
					//echo "<pre>";print_r($sendEmailArray); die;
					//$sendEmail=sendDirectEmail($sendEmailArray);
					
					$host = "box5213.bluehost.com";
					$strUserEmailId = "web@sinelec-tech.com";
					$password = "{Ge-[]sE(wq,";
					$fromname = "info@sinelec-tech.com";
					$from = 'info@sinelec-tech.com';
					ob_clean();
					$mail = new PHPMailer(); // create a new object
					$mail->IsSMTP(); // enable SMTP
					$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
					$mail->SMTPAuth = true; // authentication enabled
					$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
					$mail->Host = $host;
					$mail->Port = 465; // 465 or 587
					$mail->IsHTML(true);
					$mail->Username = $strUserEmailId;
					$mail->Password = $password;
					$mail->FromName = $fromname;
					$mail->From = $from;             
					$mail->Subject = $subject;
					$mail->Body = $message;
					$mail->AddAddress($strUserEmail); //send to mail id
				   //echo "<pre>";print_r($mail);die;
					if (!$mail->Send()) {
					   // echo "Mailer Error: " . $mail->ErrorInfo;
						header("location:forgetPassword.php?urlstring=".EncryptURL("action=&msg=WrongEmailError&ErorMail=".$mail->ErrorInfo));
					} 
					else 
					{
						header("location:forgetPassword.php?urlstring=".EncryptURL("action=VerifyOTP&email=".$strUserEmail));
					}
				}
				else
				{
					echo "<script>alert('Account is in-active. Kindly contant institution admin.')</script>";
				}
			}
		}
		else
		{
			echo "<script>alert('Please complete the captcha!!!!!')</script>";
		}
	}
	
	break;
	case 'Verify':
		$strUserEmail=$_POST['strUserEmail'];
		$strOTP=$_POST['strOTP'];
		$arrReturnData=$objUserManager->GetInsUserOTPDetailsForVerification($strUserEmail,$strOTP);
		//echo "<pre>";print_r($arrReturnData); die;
		if($arrReturnData[0]->RANDOM_ACTIVATION_KEY==$strOTP)
		{
			header("location:forgetPassword.php?urlstring=".EncryptURL("action=GeneratePassword&email=".$strUserEmail."&strOTP=".$strOTP."&msg=&intUserId=".$arrReturnData[0]->USER_ID));
		}
		else
		{
			header("location:forgetPassword.php?urlstring=".EncryptURL("action=VerifyOTP&email=".$strUserEmail."&msg=Invalid OTP Try Again"));
		}
	break;
	
	case 'Change':
		$intUserId=$_POST['intUserId'];
		$password=$_POST['password'];
		$strUserEmailId=$_POST['strUserEmailId'];
		$conPassword=$_POST['confirm_password'];
		$otp=$_POST['otp'];

		if($password==$conPassword)
		{
			$arrReturnData=$objUserManager->UpdateUserPasswordByOtp($intUserId,$password,$strUserEmailId,$otp);
		}
		//echo "<pre>";print_r($_POST); die;
		if($arrReturnData>0)
		{
			header("location:forgetPassword.php?urlstring=".EncryptURL("action=&msg=success"));
		}
	break;
}
?>
<link href='https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css' rel='stylesheet'>
<style>
body {
     background-position: center;
     background-color: #eee;
     background-repeat: no-repeat;
     background-size: cover;
     color: #505050;
     font-family: "Rubik", Helvetica, Arial, sans-serif;
     font-size: 14px;
     font-weight: normal;
     line-height: 1.5;
     text-transform: none
 }

 .forgot {
     background-color: #fff;
     padding: 12px;
     border: 1px solid #dfdfdf
 }

 .padding-bottom-3x {
     padding-bottom: 72px !important
 }

 .card-footer {
     background-color: #fff
 }

 .btn {
     font-size: 13px
 }

 .form-control:focus {
     color: #495057;
     background-color: #fff;
     border-color: #76b7e9;
     outline: 0;
     box-shadow: 0 0 0 0px #28a745
 }
 </style>
<!DOCTYPE html>
<head>
    <link rel="icon" href="../images/favicon.ico">
    <title>Sinelec Technologies Forget/Change Password</title>
</head>
<body oncontextmenu='return false' class='snippet-body'>
<?php 
if($action=='')
{
	//echo "<pre>";print_r($paramArray); die;
	$msg=$paramArray['msg'];
	//echo 'Dipya msg: '.$msg;
	?>
    <div class="container padding-bottom-3x mb-2 mt-5"> 
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="forgot">
                	<?php 
					if($msg=='success')
					{
					?>
                    	<h4 style="text-align:center; color:#FFFFFF;background:#0BAA30">Password Have Been Changed</h4>
                    	<br>
						<h5 style="text-align:center"><a href="https://sinelec-tech.com/website/login.php">Click Hear to Login</a></h5>
                    <?php
					}
					else
					{
					?>
                    <h2>Forgot/Change your password?</h2>
                    <p>Change your password in three easy steps.</p>
                    <ol class="list-unstyled">
                        <li><span class="text-primary text-medium">1. </span>Enter your email address below.</li>
                        <li><span class="text-primary text-medium">2. </span>System will send an OTP to the given Email address.</li>
                        <li><span class="text-primary text-medium">3. </span>Use the OTP to reset your password</li>
                    </ol>
					<?php
					}
					?>
                </div>
	
			<form class="card mt-4" action="forgetPassword.php?urlstring=<?php echo EncryptURL("action=SendOTP") ?>" method="post" enctype="multipart/form-data"
			name="myForm">
				<?php 
				if($msg=='')
				{
				?>
					<div class="card-body">
						<div class="form-group"> 
							<label for="email-for-pass">Enter your Email ID</label> <input class="form-control" type="email" id="email-for-pass" name="strUserEmailId" required="">
						</div>
						<div class="form-group">
							<div class="g-recaptcha" data-sitekey="6LcXLrIZAAAAAHeCZweR8RCQHz8hHI7jsvsa1C4r"></div>
						</div>
					</div>
					<div class="card-footer"> <button class="btn btn-success" type="submit">Send OTP</button></div>
				<?php
				}	
				?>
			</form>
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
			<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
			<script src="https://www.google.com/recaptcha/api.js" async defer></script>
			<script>
			window.onload = function()
			{
				var recaptcha = document.forms["myForm"]["g-recaptcha-response"];
				recaptcha.required = true;
				recaptcha.oninvalid = function(e)
				{
					alert("Please complete the captcha");
				}
			}
			
			</script>
		</div>
	</div>		
	</div>
</body>
<?php
}
if($action=='VerifyOTP')
{
	//echo "<pre>";print_r($paramArray); die;
	$msg=$paramArray['msg'];

	?>
    <div class="container padding-bottom-3x mb-2 mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="forgot">
                    <h2>Enter OTP to Reset Password</h2>
                </div>
                <form class="card mt-4" action="forgetPassword.php?urlstring=<?php echo EncryptURL("action=Verify") ?>" method="post" enctype="multipart/form-data">
                    <div class="card-body">
                    <input type="hidden" name="strUserEmail" id="strUserEmail" value="<?php echo $paramArray['email']; ?>">
                        <div class="form-group"> <label for="email-for-pass">OTP</label> <input class="form-control" type="text" maxlength="6" id="email-for-pass" name="strOTP" required=""></div>
                    </div>
                    <div class="card-footer"> <button class="btn btn-success" type="submit">Verify</button>
                	<?php
					if($msg!='')
					{
					?>
                    <p style="color:#A60000"><br><?php echo $msg; ?></p>
                    <?php
					}
					?>
                  </div>
                </form>
            </div>
        </div>
    </div>
    </body>
<?php
}
if($action=='GeneratePassword')
{
	//echo "<pre>";print_r($paramArray); die;
	?>
    <div class="container padding-bottom-3x mb-2 mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="forgot">
                    <h2>Enter New Password</h2>
                </div>
                <form class="card mt-4" action="forgetPassword.php?urlstring=<?php echo EncryptURL("action=Change") ?>" method="post" enctype="multipart/form-data"
				name="myForm">
                    <div class="card-body">
                    <input type="hidden" name="strUserEmailId" id="strUserEmailId" value="<?php echo $paramArray['email']; ?>">
                    <input type="hidden" name="intUserId" id="intUserId" value="<?php echo $paramArray['intUserId']; ?>">
                    <input type="hidden" name="otp" id="otp" value="<?php echo $paramArray['strOTP']; ?>">
                        <div class="form-group"> <label for="email-for-pass">New Password</label> <input class="form-control" onkeyup='check();' type="password" id="password" name="password" required=""></div>
                        <div class="form-group"> <label for="email-for-pass">Confirm Password</label> <input class="form-control" onkeyup='check();' type="password" id="confirm_password" name="confirm_password" required=""><span id='message'></span></div>
                    </div>
					
                    <div class="card-footer"> <button class="btn btn-success" disabled id="submit_button" type="submit">Change</button>
<!--                    <p>Invalid OTP</p>
-->                    </div>
                </form>
            </div>
        </div>
    </div>


<script>
var check = function() 
{
  if (document.getElementById('password').value ==
    document.getElementById('confirm_password').value) {
    document.getElementById('message').style.color = 'green';
    document.getElementById('message').innerHTML = 'Matching';
	document.getElementById("submit_button").disabled=false;
  } 
  else {
    document.getElementById('message').style.color = 'red';
    document.getElementById('message').innerHTML = 'Not Matching';
	document.getElementById("submit_button").disabled=true;
  }
}
</script>
<?php
}
?>
</body>