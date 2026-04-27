<?php
ob_start();
ini_set('display_errors','0');
error_reporting(E_ALL | E_STRICT);
require_once ('../admin/BO/User.php');
require_once ('../admin/BL/UserManager.php');
require_once("../admin/UI/Includes/Functions.php");
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
(isset($paramsArray['msg']))? $msg=$paramsArray['msg'] : $msg="";


switch($action)
{	
case "profile":
$user_id=$paramsArray['CUSTOMER_ID'];
$objUserManager = new UserManager(); 
$result=$objUserManager->Updateuser($user_id);
break;

}
	?>

    <!-- Breadcrumb -->
    <section class="row page_header section-spacing">
        <div class="container">
            <h3>Register</h3>
            <ol class="breadcrumb">
                <li><a href="index.php">home</a></li>
                <li class="active">Register</li>
            </ol>
        </div>
    </section>

    <!-- Register -->
    <section class="row section-spacing2 bg-pattern">
        <div class="container">
        	<div class="sectionTitle p-bottom40">
                <h2><?php if(isset($result)) echo "Update Account"; else echo "Account Register"; ?></h2>
            </div>
            <div class="row">
                <div class="col-sm-8 center-block register-form">
                    <div class="form">
                        <form class="login-form clearfix bg-gray border" action="login.php?urlstring=<?php echo EncryptURL('action=Register'); ?>" method="post" enctype="multipart/form-data">
						<?php if(isset($result)) list($fname,$lname)=explode(" ",$result[0]->NAME); ?>
                            <div class="col-sm-6">
                                <input placeholder="First Name" type="text" name="firstName" id="firstName" value="<?php if(isset($result)) echo $fname; ?>">
                            </div>
                            <div class="col-sm-6">
                                <input placeholder="Last Name" type="text" name="lastName" id="lastName" value="<?php if(isset($result)) echo $lname; ?>">
                            </div>
                           
                            <div class="col-sm-6">
                                <input placeholder="Email Address" type="email" name="Email" id="Email" value="<?php if(isset($result)) echo $result[0]->COMMUNICATION_EMAIL_ID; ?>"> 									<?php if($msg=="DuplicatId"){?>
									<span style="color:#FF0000;"><?php echo "Email Address already exist !!!";?></span>
									<?php }
									?>
                            </div>
                            
                             <div class="col-sm-6">
                                <input placeholder="Mobile Number" type="text" name="MobileNumber" id="MobileNumber" value="<?php if(isset($result)) echo $result[0]->COMMUNICATION_MOBILE_NUM; ?>">
                            </div>
							<div class="col-sm-6" >
                                <input placeholder="Phone Number" type="text" name="PhoneNumber" id="PhoneNumber" value="<?php if(isset($result)) echo $result[0]->COMMUNICATION_PHONE_NUM; ?>">
								 <input  type="hidden" name="user_id" id="user_id" value="<?php if(isset($result)) echo $result[0]->USER_ID; ?>">
                            </div>
							<div class="clearfix"></div>
							<div class="col-sm-6" <?php if(isset($result)) echo "style='display:none;'"; ?>>
                                <input placeholder="Password" type="password" name="Password" id="Password">
                            </div>
                            <div class="col-sm-6" <?php if(isset($result)) echo "style='display:none;'"; ?> >
                               <input placeholder="Conform Password" type="password" name="ConformPassword" id="ConformPassword">
                            </div>
							 <div class="col-md-6">
                                       
                                        <div class="g-recaptcha" data-sitekey="captcha_code_file"></div>
									<img src="captcha_code_file.php?rand=<?php echo rand(); ?>" id='captchaimg' >
									<div >
									<?php if($msg=="Error"){?>
									<span style="color:#FF0000;"><?php echo "Either captcha or password does not match !!!";?></span>
									<?php }
									?>  
									<input  type="hidden" name="captcha" id="captcha" value="<?php echo rand(); ?>">
									<label for='message'>Enter the code above here :</label><br>
								<input id="6_letters_code" name="6_letters_code" type="text"><br>
								<small>Can't read the image? click <a href='javascript: refreshCaptcha();'>here</a> to refresh</small>
									</div>
                                </div><div class="clearfix"></div>
							
                            <div class="col-sm-12 text-center">
                                <input type="submit" class="btn btn-primary btn-xlg col-sm-8 col-xs-12 center-block m-top30" onClick=" return confirm ('Are You Sure you want to Save it?\n Click OK to Continue, Cancel to Stop'),ValidateForm();" value="<?php if(isset($result)) echo "Update"; else echo " Register"; ?>">
                                <p class="message p-top30 margin-bottom0">Already registered ? <a href="login.php">Account Login</a></p>
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
	<script>
	function refreshCaptcha()
{
	var img = document.images['captchaimg'];
	img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
}
function ValidateForm() {
 var x = document.getElementById("firstName").value;
	var string = /^[a-zA-Z ]+$/;
    if (x == "") {
        alert("Name can not be left blank.");
        return false;
    }
	
   
 if(!x.match(string))   
  {  
  alert("plss use character");
   return false;  
  }  
  
  
 var x1 = document.getElementById("lastName").value;
	var string = /^[a-zA-Z ]+$/;
    if (x1 == "") {
        alert("Last Name can not be left blank.");
        return false;
    }
	
   
 if(!x1.match(string))   
  {  
  alert("plss use character");
   return false;  
  }  
  
 if(document.getElementById("MobileNumber").value=="") {
        alert("Mobile can not be left blank.");
        return false;
    }
	 

	    
 if(!x2.match(string))   
  {  
  alert("plss use Numeric");
   return false;  
  } 
 var x3 = document.getElementById("MobileNumber").value;
	var string = !/^[0-9]+$/.test(z);
    if (x3 == "") {
        alert("Phone can not be left blank.");
        return false;
    }
	
   
 if(!x3.match(string))   
  {  
  alert("plss use Numeric");
   return false;  
  } 
  if(document.getElementById("Password").value=="") {
        alert("Password can not be left blank.");
        return false;
    } 
   	  }
</script>


</body>
</html>