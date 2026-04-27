<?php
ob_start();
ini_set("display_errors",1);
//error_reporting(E_ALL | E_STRICT);
include('../Common.php');
include('../Includes/Functions.php');
require_once ('../../UI/Config/inc_path.php');
require_once ('../../BL/UserManager.php');
require_once ('../../BL/HomeManager.php');
require_once ('../../BO/User.php');
$date=date('Y-m-d');
$paramsArray = GetQueryStringParameters();
(isset($paramsArray['action']))? $action=$paramsArray['action'] : $action="";
isset($paramsArray["msg"]) ? $msg=$paramsArray["msg"] : $msg="";
isset($paramsArray["page"]) ? $page=$paramsArray["page"] : $page="1";
$objUserManager=new UserManager();
$objHomeManager=new HomeManager();
//$UserAllDetails=$objHomeManager->GetAllUserDetails($limit,$maxRecord);//--------------------------------------for searching
//echo "<pre>"; print_r($UserAllDetails);
switch($action)
{	
	case "Delete":
		$objHomeManager=new HomeManager();
		$CustomerId=$paramsArray["CustomerId"];
		$Result=$objHomeManager->DeleteCustomerData($CustomerId);
		header("location:CustomerDetails.php?urlstring=".EncryptURL("action=&msg=delete"));
		 
	break;
	case 'Search':
		$CustomerId=$paramsArray["CustomerId"];
		$customerName =(isset($_POST["customerName"])) ? $_POST["customerName"] : $paramsArray["reference"];
		$startFrom="0";
		$limit="100";
		header("location:CustomerDetails.php?urlstring=". EncryptURL('action=pagging&startFrom='.$startFrom.'&limit='.$limit.'&customerName='.$customerName));
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
			include "../../../admin/smtpmail/classes/class.phpmailer.php"; // include the class name
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
					header("location:CustomerDetails.php?urlstring=".EncryptURL("email=".$toEmailID."msg=insert"));
				}
		}
		else
		{
			header("location:CustomerDetails.php?urlstring=".EncryptURL("msg=Error"));
		}
	break;
	
	case 'UpdateRegister':
	
		$intUserId=$_POST['intUserId'];
		if($_POST["6_letters_code"]==$_SESSION["6_letters_code"])
		{
			$strUserName=$_POST['firstName']." ".$_POST['lastName'];
			$strUserMobNo=$_POST['mobile_country_code'].$_POST['MobileNumber'];
			
			$arrUpdate=array("intUserId"=>$intUserId,"strUserName"=>$strUserName,"strUserMobNo"=>$strUserMobNo,"strUserMobISD"=>$_POST['mobile_country_code'],
			"strCompName"=>$_POST['companyName'],"strDesgName"=>$_POST['DesignationName'],"strEmailId"=>$_POST['Email']);
			$intRetId=$objUserManager->UpdateUserDetail($arrUpdate);
			//echo "<pre>"; print_r($_POST);die;
			if($intRetId || $intRetId>0)
			{
				header("location:CustomerDetails.php?urlstring=".EncryptURL("action=&msg=update"));
			}
			else
			{
			header("location:CustomerDetails.php?urlstring=".EncryptURL("action=EditCustomer&msg=error&CustomerId=".$intUserId));
			}
		}
		else
		{
			header("location:CustomerDetails.php?urlstring=".EncryptURL("action=EditCustomer&msg=error&CustomerId=".$intUserId));
		}
	break;
	
	case 'ExportExcel':
		$objHomeManager=new HomeManager();
		$UserAllDetails=$objHomeManager->GetAllUserDetails();
		$filename = date('Ymd_His').'User Details-export.csv';
		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename={$filename}");
		ob_end_clean();
		$fh = @fopen( 'php://output', 'w' );
		$headerDisplayed = faslse;
		ob_end_clean();
		foreach ($UserAllDetails as $data )
		{
			//$excelKey["User Id"]=$data->CLINET_ID;
			//$excelKey["Client Name"]=$data->CLIENT_NAME;
			//$excelKey["User Id"]=$data->MOBILE_NO;
			//ob_end_clean();
			$data = (array)$data; 
			//$excelKey;
			if (!$headerDisplayed ) 
			{
				fputcsv($fh, array_keys($data));
				$headerDisplayed = true;
			}
			fputcsv($fh, $data);
			//ob_end_clean();
		}
		//ob_end_clean();
		exit();
		break;
}
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
	<li class="active">Customer</li>
	</ol>
	</section>
<br/>

<!-- Content Header (Page header) -->
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
			<h4><i class="icon fa fa-check"></i> Customer has been added successfully</h4>
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
			<h4><i class="icon fa fa-check"></i>Customer has been updated successfully</h4>
		</div>
	<?php 
	}
	else if($msg=='delete')
	{
	?>
		<div class="alert alert-success alert-dismissible" style="height:50px;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
			<h4><i class="icon fa fa-check"></i> Customer has been deleted successfully</h4>
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

<div class="col-md-12 col-sm-12 col-xs-12 "></div>
<!-- Main content -->
<section class="content">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12 ">
		<div class="input-group">
			<a class="btn btn-primary" href="CustomerDetails.php?urlstring=<?php echo EncryptURL('action=AddCustomer'); ?>">Add Customer</a>
		</div>
	</div>
<div class="col-xs-12">
<div class="box" id="SibsSchool">
<div class="box-header">
<h3 class="box-title">Customer Details</h3>
</div>
<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../js/func_ajax.js"></script>
	<div class="box-tools">
		<form  action="CustomerDetails.php?urlstring=<?php echo EncryptURL("action=Search") ?>" method="post">
			<div class="input-group input-group-sm" style="width:100px;">
				<input type="text" name="customerName" id="CustomerId" placeholder="Search by Name" value="" />
				<div class="input-group-btn">
				<button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
				</div>
			</div>
		</form>	
	</div>
    
	
	<?php
	if($action=="pagging")
	{	
		$limit= (isset ($paramsArray['limit'])) ? $paramsArray['limit'] : '';
		$startFrom= (isset ($paramsArray['startFrom'])) ? $paramsArray['startFrom'] : '';
		$customerName=$paramsArray['customerName'];
		
		if(!isset($UserAllDetailspaging))
		{
			$objHomeManager=new HomeManager();
			$UserAllDetailspaging=$objHomeManager->GetAllUserDetailsForPaging($startFrom,$limit,$customerName);
			
		}
			//for searching 
	?>
			
			<a href="CustomerDetails.php?urlstring=<?php echo EncryptURL("action=ExportExcel"); ?>" style=" float:right;"> <button class="btn btn-success"
			onclick="exportToFileopen('excel')">Export to excel</button></a>
			
			<div id="exportopenticket">
			<table id="" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th class="text_align_center">S.No</th>
						<th class="text_align_center">Customer Name</th>
						<th class="text_align_center">Phone No</th>
						<th class="text_align_center">Mobile No</th>
						<th class="text_align_center">Email Id</th>
						<th class="text_align_center">Action</th>
					</tr>
				</thead>
			<tbody>
			<?php
			if(!empty($UserAllDetailspaging))
			{ 
				$index=$startFrom+1;
				foreach($UserAllDetailspaging as  $value)
				{
					//echo "<pre>"; print_r($value); 
				?>
					<tr class="common_table_header">
						<td class="text_align_left"><?php echo $index++; ?></td>
						<td class="text_align_left"><?php echo $value->NAME; ?></td>
						<td class="text_align_left"><?php echo $value->COMMUNICATION_PHONE_NUM; ?></td>
						<td class="text_align_left"><?php echo $value->COMMUNICATION_MOBILE_NUM; ?></td>
						<td class="text_align_left"><?php echo $value->COMMUNICATION_EMAIL_ID; ?></td>
						<td class="text_align_center">
                        <a href="CustomerDetails.php?urlstring=<?php echo EncryptURL('action=EditCustomer&CustomerId='.$value->USER_ID); ?>" class="btn btn-success btn-sm open2">
						<span class="glyphicon glyphicon-edit"  ></span> Edit</a>								
                        <button class="btn btn-success btn-sm open2"  data-Id="<?php echo $value->USER_ID."_".$value->USER_TYPE_ID."_".$value->NAME;?>">
						<span class="glyphicon glyphicon-eye-open"></span>View Address</button>
						<a href="CustomerDetails.php?urlstring=<?php echo EncryptURL('action=Delete&CustomerId='.$value->USER_ID); ?>" class="btn btn-danger btn-sm"
						onclick="return confirm('Are you sure you want to Delete this record ?\n Click OK to Continue, Cancel to Stop')" >
						<span class="glyphicon glyphicon-remove"  ></span> Del</a></td>	
					</tr>
			<?php 
				}
			}
			?>
			</tbody>
			</table>
			</div>		
			<table width="100%" border="0">
				<tr>
					<td style="text-align:center">
					<div class="pagination" width:250%>
						<?php
						$flag="count";
						$UserAllDetails=$objHomeManager->SelectUser($flag);
						$countData=ceil ($UserAllDetails[0]->TOTAL/$limit);
						//$countData=ceil(count($UserAllDetailsCount)/$limit);
						for($page=1;$page<=$countData;$page++)
						{	
							if($page=='' || $page=='1')
							{
								$startFrom="0";
							}
							else
							{
								$startFrom=($page*100)-100;
							}
							?>
							<a href="CustomerDetails.php?urlstring=<?php echo EncryptURL('action=pagging&startFrom='.$startFrom.'&limit='.$limit.'&customerName='.$customerName);?>"><button><?php echo $page ?></button></a>
						<?php
						}
						?>
					</div>			
					</td>													
				</tr>
			</table>
		</div>
		</div>
		</div>
		<?php 
		} 
	if($action=="AddCustomer")
	{
		$objUserManager = new UserManager(); 
		$countryList=$objUserManager->GetAllCountryList();
	?>
        <div class="box box-primary">
            <div class="box-body" id="div1">
                <div class="box-border" style="min-height:400px">
                    <form class="form-horizontal form-label-left" action="CustomerDetails.php?urlstring=<?php echo EncryptURL('action=Register'); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group" align="left">
                            <div class="col-sm-6">
                                <input class="form-control" placeholder="First Name *" type="text" name="firstName" id="firstName" value="<?php ?>">
                                <span style="color:red;margin:0px;padding:0" id="first_name_msg"></span>
                            </div>
                            <div class="col-sm-6">
                                <input class="form-control" placeholder="Last Name" type="text" name="lastName" id="lastName" value="">
                            </div>
                        </div>
                        <div class="form-group" align="left">
                            <div class="col-sm-6"> 
                                <input class="form-control" placeholder="Email *" type="email" name="Email" id="Email" value="<?php echo $_POST['Email']; ?>"> 
                            </div>
                            <div class="col-sm-2">
                                <select class="form-control" name="mobile_country_code" id="mobile_country_code" style="font-size:12px;text-align:left">
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
                                <input class="form-control" placeholder="Phone Number*" type="text" name="MobileNumber" id="MobileNumber" value="" onKeyPress="return validateNumber(event)"><span style="color:red;margin:0px;padding:0" id="mobile_msg"></span>
                            </div>
                        </div>
                        <div class="form-group" align="left">
                            <div class="col-sm-6">
                                <input class="form-control" placeholder="Company Name" type="text" name="companyName" id="companyName" value="">
                            </div>
                            <div class="col-sm-6">
                                <input class="form-control" placeholder="Designation" type="text" name="DesignationName" id="DesignationName" value="">
                            </div>
                        </div>
                        <div class="form-group" align="left">
                            <div class="col-sm-6">
                                <input class="form-control" placeholder="Password *" type="password" name="Password" id="Password">
                            </div>
                            <div class="col-sm-6">
                               <input class="form-control" placeholder="Confirm Password *" type="password" name="ConformPassword" id="ConformPassword">
                            </div>
                        </div>
                        <div class="form-group" align="left">
                            <div class="row" style="text-align:center">
                                <span style="color:red;margin:0px;padding:0;text-align:center"  id="msg_password"></span>
                            </div>
                        </div>
                        <div class="form-group" align="left">
                            <div class="col-sm-12">
                                <input class="form-control" placeholder="Delivery Address*" type="text" name="deliveryaddress" id="deliveryaddress" value="<?php echo $fillDataArray['deliveryaddress'] ?>" required>					
                            </div>
                        </div>
                        <div class="form-group" align="left">
                                <!---------------------------------------------------------->
                            <div class="col-sm-6">
                                <input class="form-control"  placeholder="City/District/Town"  type="text" name="city" id="city" required>
                            </div>
                            <div class="col-sm-6">
                                <input class="form-control"  type="text" placeholder="State"  id="state" name="state" required>
                            </div>
                        </div>
                        <div class="form-group" align="left">
                            <div class="col-sm-6">
                                <input class="form-control" type="text"  id="ZIP" placeholder="zip" name="zip" required>
                            </div>
                            <div class="col-sm-6">
                                <select class="form-control" placeholder="Country"  type="text"  id="country" onChange="showHideVatNo(this.value)" name="country" required>
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
                        </div>
                        <div class="form-group" align="left">
                            <div class="col-sm-12">
                                <input type="checkbox" id="yourBox" style="float:left; width:20px">
                                <label for="yourBox" style=" font-weight:400">Billing Address Different from Delivery address </label>
                            </div>
                        </div>
                        <div class="form-group" align="left">
                            <div class="col-sm-12">
                                <input class="form-control" placeholder="Billing Address" type="text" id="billingAddress" name="billingAddress" disabled  value="">
                            </div>
                        </div>
                        <div class="form-group" align="left">
                            <div class="col-sm-6">
                                <input class="form-control" placeholder="City/District/Town"  type="text" name="cityName" id="cityName" disabled>
                            </div>
                            <div class="col-sm-6">
                                <input class="form-control" placeholder="State" name="stateName" type="text" id="stateName" disabled>
                            </div>
                        </div>
                        <div class="form-group" align="left">
                            <div class="col-sm-6">
                                <input class="form-control" type="text" name="zipName"  placeholder="ZIP" id="zipName" disabled >
                            </div>
                            <div class="col-sm-6">
                                <select class="form-control" placeholder="Country"  type="text" name="countryName" onChange="showHideVatNo(this.value)"  id="countryName" disabled>
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
                        </div>
                        <div class="form-group" align="left">
                            <div class="col-sm-12">
                                <input id="example_check1" type="checkbox" style="float:left; width:20px">
                                <label for="example_check1"  style="float:left; font-weight:400">If you are a company situated outside Germany but in EU and want a 
                                VAT free quote</label>
                                <input class="form-control" placeholder="VAT Number" type="text" id="vatnumber" name="vatnumber" disabled value="">
                            </div>
                        </div>
                        <div class="form-group" align="left">
                            <div class="col-md-6">
                                <div class="g-recaptcha" data-sitekey="captcha_code_file"></div>
                                <img src="../../../website/captcha_code_file.php?rand=<?php echo rand(); ?>" id='captchaimg' >
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
                                    <input class="form-control" id="6_letters_code" name="6_letters_code" type="text"><br>
                                    <small>Can't read the image? click <a href='javascript: refreshCaptcha();'>here</a> to refresh</small>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" align="left">
                            <div class="col-sm-12 text-center">
                                <input type="submit" class="btn btn-primary btn-xlg col-sm-8 col-xs-12 center-block m-top30" onClick=" return confirm ('Are You Sure you want to Save it?\n Click OK to Continue, Cancel to Stop'),ValidateForm();" value="Register">
                            </div>
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
	if($action=="EditCustomer")
	{
		$objUserManager = new UserManager(); 
		$countryList=$objUserManager->GetAllCountryList();
		$intUserId=$paramsArray['CustomerId'];
		$arrSearch=array("intUserId"=>$intUserId);
		$arrUserData=$objUserManager->GetUserCompleteDataById($arrSearch);
		list($strFirstName,$strLastName)=explode(" ",$arrUserData[0]->NAME);

		//echo "<pre>";print_r($arrUserData);die;
	?>
        <div class="box box-primary">
            <div class="box-body" id="div1">
                <div class="box-border" style="min-height:400px">
                    <form class="form-horizontal form-label-left" action="CustomerDetails.php?urlstring=<?php echo EncryptURL('action=UpdateRegister'); ?>" method="post" enctype="multipart/form-data">
                    	<input type="hidden" name="intUserId" value="<?php echo $intUserId; ?>">
                        <div class="form-group" align="left">
                            <div class="col-sm-6">
                                <input class="form-control" placeholder="First Name *" type="text" name="firstName" id="firstName" value="<?php echo $strFirstName; ?>">
                                <span style="color:red;margin:0px;padding:0" id="first_name_msg"></span>
                            </div>
                            <div class="col-sm-6">
                                <input class="form-control" placeholder="Last Name" type="text" name="lastName" id="lastName" value="<?php echo $strLastName; ?>">
                            </div>
                        </div>
                        <div class="form-group" align="left">
                            <div class="col-sm-6"> 
                                <input class="form-control" placeholder="Email *" type="email" name="Email" id="Email" value="<?php echo $arrUserData[0]->COMMUNICATION_EMAIL_ID; ?>"> 
                            </div>
                            <div class="col-sm-2">
                                <input class="form-control" placeholder="ISD Code *" type="text" name="mobile_country_code" id="mobile_country_code" value="<?php echo $arrUserData[0]->COMMUNICATION_MOBILE_NUM_ISD; ?>"> 
                            </div>
                            <div class="col-sm-4">
                                <input class="form-control" placeholder="Phone Number*" type="text" name="MobileNumber" id="MobileNumber" value="<?php echo ltrim($arrUserData[0]->COMMUNICATION_MOBILE_NUM,$arrUserData[0]->COMMUNICATION_MOBILE_NUM_ISD); ?>" onKeyPress="return validateNumber(event)"><span style="color:red;margin:0px;padding:0" id="mobile_msg"></span>
                            </div>
                        </div>
                        <div class="form-group" align="left">
                            <div class="col-sm-6">
                                <input class="form-control" placeholder="Company Name" type="text" name="companyName" id="companyName" value="<?php echo $arrUserData[0]->COMPANY_NAME; ?>">
                            </div>
                            <div class="col-sm-6">
                                <input class="form-control" placeholder="Designation" type="text" name="DesignationName" id="DesignationName" value="<?php echo $arrUserData[0]->DESIGNATION; ?>">
                            </div>
                        </div>
                        <div class="form-group" align="left">
                            <div class="col-md-6">
                                <div class="g-recaptcha" data-sitekey="captcha_code_file"></div>
                                <img src="../../../website/captcha_code_file.php?rand=<?php echo rand(); ?>" id='captchaimg' >
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
                                    <input class="form-control" id="6_letters_code" name="6_letters_code" type="text"><br>
                                    <small>Can't read the image? click <a href='javascript: refreshCaptcha();'>here</a> to refresh</small>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" align="left">
                            <div class="col-sm-12 text-center">
                                <input type="submit" class="btn btn-primary btn-xlg col-sm-8 col-xs-12 center-block m-top30" onClick=" return confirm ('Are You Sure you want to Save it?\n Click OK to Continue, Cancel to Stop'),ValidateForm();" value="Register">
                            </div>
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
</section>
</div>
	<script type="text/javascript" language="JavaScript">
		function exportToFileopen(exportTo)
		{
		//alert('hii');
			var pdfData = document.getElementById("exportopenticket").innerHTML;
			document.getElementById("hiddenExportData").value = pdfData;
			document.getElementById("exportTo").value = exportTo;
			document.forms["exportForm"].submit();
		}
	</script>
		<div id="Open2_popup_modal_show_id" class="modal fade" tabindex="-1"></div>
	<script src="../js/jquery-1.11.2.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
		var $modal = $('#Open2_popup_modal_show_id');
		$('.open2').on('click', function(){
		var val=$(this).attr('data-Id');
		
		$modal.load('ViewCustomerAddress.php',{'val': val},
		function(){
		//alert(val);
		$modal.modal('show');
		});
		});
		});
	</script>
<?php
$pageMainContent = ob_get_contents();
ob_end_clean();
$pagetitle = "Customer Details ::";
//Apply the template
include('../MasterTemplatePage.php');
?>