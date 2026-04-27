<?php
ob_start();
//ini_set('display_errors',0);
////error_reporting(E_ALL | E_STRICT);
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
		   
if($msg=='Verification')
		echo "<script type='text/javascript'>alert('Your account is not active !!! , Activate your Account');</script>";				   
$price=0;
switch($action)
{	
case "ValidateActivationKey":
		
		$objUserManager = new UserManager(); 
		$result=$objUserManager->GetuserInfo($_POST['Email'],'2');
		if(count($result)>0)
		{
		  if($result[0]->RANDOM_ACTIVATION_KEY==$_POST['activation_key'])
		  {
				$UserId=$objUserManager->VerifyUserAccount($_POST['Email']);
				header("location:login.php?urlstring=".EncryptURL("msg=AccountVerified"));
		  }
		  else
		  {
			header("location:AccountVeryfication.php?urlstring=".EncryptURL("msg=NotMatched&email=".$_POST['Email']."&error=errror"));
		  }
		}
		else
		{
			header("location:AccountVeryfication.php?urlstring=".EncryptURL("msg=NotMatched&email=".$_POST['Email']."&error=errror"));
		}
		break;
	
}
?>
    <!--Breadcrumb-->
    <section class="row page_header section-spacing">
        <div class="container">
            <h3>Account Verification</h3>
            <ol class="breadcrumb">
                <li><a href="index.php">home</a></li>
                <li class="active">Account Verification</li>
            </ol>
        </div>
    </section>

    <!--Login-->
    <section class="row section-spacing2  bg-pattern">
        <div class="container">
        	<div class="sectionTitle p-bottom40">
                <h2>Account Verification</h2>
            </div>
			
		<?php
		if($paramsArray['msg']=='NotMatched')
		{
		?>
		    <div class="row" style="text-align:center">
			  <p style="color:red;font-size:20px"><b>Activation key does not match kindly enter correct key</b></p>
			</div> 
		<?php
		}
		?>
		<?php 
		if(isset($paramsArray['email']) && $paramsArray['email']!="" && $paramsArray['error']!="errror")
		{
		?>
		    <div class="row" style="text-align:center">
			  <p style="color:green;font-size:20px"><b>Kindly enter the activation key received in your mail to activate the account</b></p>
			</div> 
		<?php
		}
		?>
            <div class="row">
            	<div class="col-sm-6 col-md-5 col-xs-12 center-block">
                      <div class="form bg-gray clearfix login-form border">
                            <form class="login-form clearfix" action="AccountVeryfication.php?urlstring=<?php echo EncryptURL('action=ValidateActivationKey'); ?>" method="post" enctype="multipart/form-data">
                                <div class="col-sm-12">
                                	<input type="text" placeholder="Email" name="Email" id="Email" value="<?php if(isset($paramsArray["email"])) echo  $paramsArray["email"]; ?>"/>
                                </div>

                                <div class="col-sm-12">
                                    <input type="text" placeholder="Activation Key"  name="activation_key" id="activation_key" value="<?php if(isset($paramsArray['error']) && $paramsArray['error']=="errror")  echo "";?>"/>
                                    <div class="checkbox text-left">
									<?php 
										if($msg=="ErrorLogin")
										{
									?>
									<span style="color:#FF0000;"><?php echo "UserId or password doesn't match !!!";?></span>
									<?php 
										}
									?> <br> 
                                     <input type="checkbox" name="example_check" id="example_check1">
									 <label for="example_check1">Remember Me</label>
                                    </div>
                                    <button class="btn btn-primary btn-xlg btn-block">login</button>
                                </div>

                                <div class="col-sm-12">
                                    <p class="message p-top30 margin-bottom0"><a href="forgetPassword.php">Forgot password?</a></p>
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