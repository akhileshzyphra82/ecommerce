<?php
ob_start();
ini_set('display_errors','0');
//error_reporting(E_ALL | E_STRICT);
require_once ('../admin/BO/User.php');
require_once ('../admin/BL/UserManager.php');
require_once("../admin/UI/Includes/Functions.php");
$userId=$_SESSION['CUSTOMER_ID'];
$objUserManager = new UserManager(); 
$countryList=$objUserManager->GetAllCountryList();
if($_SESSION['CUSTOMER_ID']=='')
{
	header("location:login.php");
	exit;
}
//echo "<pre>"; print_r($_SESSION['CUSTOMER_EMAIL']);die;
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
(isset($paramsArray['msg']))? $msg=$paramsArray['msg'] : $msg="";


switch($action)
{	
	case "InsertUpdate":
		//echo "<pre>"; print_r($_POST);die;
		$intUserId=$_POST['intUserId'];
		$strCountry=$_POST['strCountry'];
		list($intCountryId,$intCountryName)=explode("@_@",$strCountry);
		
		if($intUserId==$userId)
		{
			$arrInsertUpdate=array("intUserId"=>$_POST['intUserId'],"intUserAddId"=>$_POST['intUserAddId'],"strName"=>$_POST['strName'],
			"strCompName"=>$_POST['strCompName'],"strAddress"=>$_POST['strAddress'],"strCity"=>$_POST['strCity'],"strState"=>$_POST['strState'],
			"strZipCode"=>$_POST['strZipCode'],"strVatNo"=>$_POST['strVatNo'],"intCountryId"=>$intCountryId,
			"intCountryName"=>$intCountryName);
			$intRetId=$objUserManager->InsertUpdateUserAddresses($arrInsertUpdate);
			if($intRetId || $intRetId>0)
			{
				if($intUserAddId=='')
				{
					header("location:YourAccount.php?urlstring=".EncryptURL("action=Address&msg=Address Added Successfully."));
				}
				else
				{
					header("location:YourAccount.php?urlstring=".EncryptURL("action=Address&msg=Address Updated Successfully."));
				}
			}
			else
			{
				header("location:YourAccount.php?urlstring=".EncryptURL("action=Address&msg=Error"));
			}
		}
		else
		{
			header("location:login.php");
		}
		
	break;
	case 'UpdateUserDetails':
	
		$intUserId=$_POST['intUserId'];
		if($intUserId==$userId)
		{
			$strUserName=$_POST['firstName']." ".$_POST['lastName'];
			$strUserMobNo=$_POST['strMobNoISD'].$_POST['MobileNumber'];
			
			$arrUpdate=array("intUserId"=>$intUserId,"strUserName"=>$strUserName,"strUserMobNo"=>$strUserMobNo,
			"strCompName"=>$_POST['companyName'],"strDesgName"=>$_POST['DesignationName']);
			$intRetId=$objUserManager->UpdateUserDetail($arrUpdate);
			//echo "<pre>"; print_r($intRetId);die;
			if($intRetId || $intRetId>0)
			{
				header("location:YourAccount.php?urlstring=".EncryptURL("action=&msg=User Details Updated Successfully."));
			}
			else
			{
				header("location:YourAccount.php?urlstring=".EncryptURL("action=EditProfile&msg=Error"));
			}
		}
		else
		{
			header("location:login.php");
		}
	break;
	
	case 'DeleteAddress':
	$intUserAddId=$paramsArray['intUserAddId'];
	$intDelId=$objUserManager->DeleteUserAddressById($intUserAddId,$userId);
	if($intDelId)
	{
		header("location:YourAccount.php?urlstring=".EncryptURL("action=Address&msg=Address Deleted Successfully."));
	}
	else
	{
		header("location:YourAccount.php?urlstring=".EncryptURL("action=Address&msg=Error"));
	}
	$action='Address';	
	break;
}
	?>
	<style>
form select {
    font-family: "Roboto", sans-serif;
    outline: 0;
    background: #fff;
    width: 100%;
    margin: 0 0 15px;
    padding: 15px 15px;
    box-sizing: border-box;
    font-size: 14px;
    border: 1px solid #dae0e2;
	}
	</style>

    <!-- Breadcrumb -->
    <section class="row page_header section-spacing">
        <div class="container">
            <h3>Your Account</h3>
            <ol class="breadcrumb">
                <li><a href="index.php">home</a></li>
                <li class="active">Your Account</li>
            </ol>
        </div>
    </section>
    <section class="row section-spacing2 bg-pattern">
        <div class="container">
		<?php
		if($paramsArray['msg']!='Error')
		{
		?>
            <div class="row" style="text-align:center">
              <p style="color:green;font-size:20px"><b><?php echo $paramsArray['msg']; ?></b></p>
            </div> 
		<?php
		}
		else
		{
		?>
            <div class="row" style="text-align:center">
              <p style="color:red;font-size:20px"><b><?php echo $paramsArray['msg']; ?></b></p>
            </div> 
		<?php
		}
		?>
		<?php
		if($action=='')
		{
		?>
			<style>
				.db-icon
				{
					display: flex;
					align-items: center;
					justify-content: center;
					font-size: 50px;
				}
				.db-number
				{
					text-align: left;
					font-size: 22px;
					color: #000000;
				}
				.db-card-title
				{
					text-align: left;
					font-size: 20px;
					color: #000000;
				}
				.db-card-category
				{
					color: #67757c;
					font-size: 12px;
					line-height: 1.4em;
				}
				.db-border
				{
					border:1px solid #d0cfcf;
					border-radius:5px;
					margin-left:5px;
					margin-right:5px;
					margin-bottom:30px;
					padding:10px;
					height:75px;
					box-shadow:0px 2px 6px #88888840;
				}
				.your-acc >a
				{
					color:#7F7D7D;
				}
				.your-acc >a:hover
				{
					color:#616060;
				}
            </style>
        	<div class="sectionTitle p-bottom40">
                <h2>Your Account</h2>
            </div>
            <div class="row">
                <div class="col-sm-8 center-block register-form">
                    <div class="row">
                        <div class="col-sm-6">
                        	<div class="your-acc">
                                <a href="OrderDetails.php?urlstring=<?php echo EncryptURL('user_id='.$_SESSION['CUSTOMER_ID']); ?>">
                                    <div class="row db-border">
                                        <div class="col-sm-3">
                                            <div class="db-icon">
                                                <i class="fa fa-shopping-cart"></i>
                                            </div>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="db-number">
                                                <p class="db-card-title">Your Orders<br>
                                                    <span class="db-card-category">Track or buy things again</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-6 your-acc">
                        	<a href="YourAccount.php?urlstring=<?php echo EncryptURL('action=EditProfile&user_id='.$_SESSION['CUSTOMER_ID']); ?>">
                                <div class="row db-border">
                                    <div class="col-sm-3">
                                        <div class="db-icon">
                                            <i class="fa fa-shield"></i>
                                        </div>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="db-number">
                                            <p class="db-card-title">Login & Security<br>
                                            <span class="db-card-category">Edit name and mobile number</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 your-acc">
                        	<a href="YourAccount.php?urlstring=<?php echo EncryptURL('action=Address&user_id='.$_SESSION['CUSTOMER_ID']); ?>">
                                <div class="row db-border">
                                    <div class="col-sm-3">
                                        <div class="db-icon">
                                            <i class="fa fa-address-book"></i>
                                        </div>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="db-number">
                                            <p class="db-card-title">Your Addresses<br>
                                            <span class="db-card-category">Edit addresses for orders</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
		<?php
		}
		if($action=='EditProfile')
		{
			$arrUserData=$objUserManager->Updateuser($userId);
			$arrUserDetails=$arrUserData[0];
			list($strFirstName,$strLastName)=explode(" ",$arrUserDetails->NAME);
			//echo "<pre>"; print_r($arrUserDetails);
		?>
        	<div class="sectionTitle p-bottom40">
                <h2>Update Account</h2>
            </div>
            <div class="row">
                <div class="col-sm-8 center-block register-form">
                    <div class="form"s style="white-space:normal">
                        <form class="login-form clearfix bg-gray border" action="YourAccount.php?urlstring=<?php echo EncryptURL('action=UpdateUserDetails'); ?>" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="intUserId" id="intUserId" value="<?php echo $arrUserDetails->USER_ID;?>">
                            <input type="hidden" name="strMobNoISD" id="strMobNoISD" value="<?php echo $arrUserDetails->COMMUNICATION_MOBILE_NUM_ISD;?>">
                            <div class="col-sm-6">
                                <input placeholder="First Name *" type="text" name="firstName" id="firstName" value="<?php echo $strFirstName ; ?>">
                                <span style="color:red;margin:0px;padding:0" id="first_name_msg"></span>
                            </div>
                            <div class="col-sm-6">
                                <input placeholder="Last Name" type="text" name="lastName" id="lastName" value="<?php echo $strLastName ; ?>">
                            </div>
                            <div class="col-sm-6"> 
                                <input placeholder="Email *" type="email" readonly name="Email" id="Email" value="<?php echo $_SESSION['CUSTOMER_EMAIL']; ?>"> 
                            </div>
                            <div class="col-sm-6">
                                <input placeholder="Phone Number*" type="text" name="MobileNumber" id="MobileNumber" value="<?php echo str_replace($arrUserDetails->COMMUNICATION_MOBILE_NUM_ISD,"",$arrUserDetails->COMMUNICATION_MOBILE_NUM) ; ?>" onKeyPress="return validateNumber(event)">
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-sm-6">
                                <input placeholder="Company Name" type="text" name="companyName" id="companyName" value="<?php echo $arrUserDetails->COMPANY_NAME ; ?>">
                            </div>
                            <div class="col-sm-6">
                                <input placeholder="Designation" type="text" name="DesignationName" id="DesignationName" value="<?php echo $arrUserDetails->DESIGNATION ; ?>">
                            </div>							
                            <div class="clearfix"></div>
                            <div class="col-sm-12 text-center">
                                <a href="YourAccount.php" type="button" class="btn btn-primary btn-sm m-top10">Cancel</a>
                                <button type="submit" class="btn btn-primary btn-sm m-top10" onClick=" return confirm ('Are You Sure you want to Save it?\n Click OK to Continue, Cancel to Stop')">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
		<?php
		}
		if($action=='Address')
		{
			$arrAddresses=$objUserManager->GetuserAddress($userId);
			//echo "<pre>"; print_r($arrAddresses);die;
		?>
			<style>
                .db-border
                {
                    border:1px solid #d0cfcf;
                    border-radius:5px;
                    margin:10px;
                    padding:10px;
					height:200px;
                }
				.add-new
				{
                    border:2px dotted #d0cfcf;
					width:95%;
				}
				.db-icon
				{
					display: flex;
					align-items: center;
					justify-content: center;
					font-size: 50px;
					height:200px;
					text-align:center;
				}
				.db-card-title
				{
					font-size: 14px;
					color: #000000;
					font-family:'Source Sans Pro', sans-serif;
					font-weight:600;
				}
				.db-card-category
				{
					color: #67757c;
					font-size:12px;
					color: #000000;
				}
				.your-add >a
				{
					color:#7F7D7D;
				}
				.your-add >a:hover
				{
					color:#616060;
					cursor:pointer;
				}
				.add-footer
				{
					
				}
				.add-header
				{
					height:160px;
				}
                .add-header >p
				{
					line-height:16px;
				}
				.add-footer >a
				{
					margin-left:5px;
					margin-right:5px;
					font-size:14px;
					color:#009cde;
				}
            </style>
        	<div class="sectionTitle p-bottom40">
                <h2>Your Addresses</h2>
            </div>
            <div class="row">
                <div class="col-sm-12 center-block register-form">
                    <div class="row">
                        <div class="col-sm-4 your-add">
                        	<a onClick="AddEditAddress('<?php echo $userId;?>');">
                                <div class="row db-border add-new">
                                    <div class="db-icon">
                                        <i class="fa fa-plus"><br><span class="db-card-title">Add Address</span></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php
						if(count($arrAddresses)>0)
						{
							foreach($arrAddresses as $addVal)
							{
							?>
                                <div class="col-sm-4">
                                    <div class="row db-border">
                                        <div class="add-header">
                                            <p class="db-card-title"><?php echo $addVal->COMPANY_NAME; ?></p>
                                            <p class="db-card-category"><?php echo $addVal->USER_NAME; ?></p>
                                            <p class="db-card-category"><?php echo $addVal->ADDRESS." ".$addVal->CITY." ".$addVal->STATE." ".
											$addVal->COUNTRY." ".$addVal->ZIP." ".$addVal->LANDMARK; ?></p>
                                        </div>
                                        <div class="add-footer your-add">
                                        <a onClick="AddEditAddress('<?php echo $addVal->USER_ID."@_@".$addVal->USER_ADDRESS_ID ?>');">Edit</a> <strong>|</strong> <a href="YourAccount.php?urlstring=<?php echo EncryptURL('action=DeleteAddress&intUserAddId='.$addVal->USER_ADDRESS_ID); ?>" onClick="return confirm('Are you sure you want to delete it?\n Click OK to Continue, Cancel to Stop')">Remove</a>
                                        </div>
                                    </div>
                                </div>
							<?php
							}
						}
						?>
                    </div>
                    <div id="AddEditAddressDiv"></div>
                </div>
            </div>
			<script src="../admin/UI/js/func_ajax.js"></script>
            <script>
				function AddEditAddress(id)
				{
					callAjax('AddEditAddressDiv',"AddEditAddress.php", 
					{
						params:"addId="+id,
						meth:"get",
						async:true,
						startfunc:"s_function('AddEditAddressDiv')",
						errorfunc:"ajaxError()" 
					}
					);
				}
			</script>
			<script>
                function YourVatBoxEnable() 
                {
					if(document.getElementById('example_check1').checked==true)
					{
						document.getElementById('strVatNo').disabled = false;
					}
					else
					{
						document.getElementById('strVatNo').disabled = true;
					}
                }
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