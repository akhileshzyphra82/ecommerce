<?php 
ob_start();
ini_set('display_errors',0);
////error_reporting(E_ALL | E_STRICT);


require_once('../admin/BL/HomeManager.php');
require_once ('../admin/BL/UserManager.php');
require_once("../admin/UI/Includes/Functions.php");
$objHomeManager = new HomeManager(); 
$currentDate=date('Y-m-d');
$parentCategory=$objHomeManager->GetAndDisplayAllListProduct1();
$objUserManager = new UserManager(); 
$countryList=$objUserManager->GetAllCountryList();

$paramsArray = GetQueryStringParameters();
(isset($paramsArray['action']))? $action=$paramsArray['action'] : $action="";
isset($paramsArray["msg"]) ? $msg=$paramsArray["msg"] : $msg="";
switch($action)
{
	case "Insert":
	//echo "<pre>";print_r($_POST); die;
	//if( $_POST["6_letters_code"]==$_SESSION["6_letters_code"])
	//{
		$totalProduct=$_POST['addMore'];
		$arrProductOrderDetails = array();
		for($x=1; $x<=$totalProduct; $x++)
		{
			if($_POST['product_category_id_'.$x]!='' || $_POST['product_id_'.$x]!='' || $_POST['quantity_'.$x]!='')
			{
				$arrProductOrderDetails[] = array("productCategoryId"=>$_POST['product_category_id_'.$x],"proudctId"=>$_POST['product_id_'.$x],"totalProductOrder"=>$x, 
				"productQuantity"=>$_POST['quantity_'.$x]);
			}
		}
		//echo "<pre>"; print_r($arrProductOrderDetails); 
		//die;
		
	   $user_name=$_POST['name'];
	   $company_name=$_POST['companyname'];
	   $user_email=trim($_POST['email']);
	   $user_phone=$_POST['phonenumber'];
	   $phone_country_code=$_POST['phone_country_code'];
	   $vat_number=$_POST['vatnumber'];
	   $delivery_address=$_POST['deliveryaddress'];
	   $city=$_POST['city'];
	   $state=$_POST['state'];
	   $zip=$_POST['zip'];

	   $customerOrderNo=$_POST['customerOrderNo'];
	   $customerSupplierNo=$_POST['customerSupplierNo'];
	   
	   list($deliveryCountryId,$country,$deliveryCountryShipping)=explode('@_@',$_POST['country']);
	   
	   $billing_address=$_POST['billingAddress'];
	   $billing_city=$_POST['cityName'];
	   $billing_state=$_POST['stateName'];
	   $billing_zip=$_POST['zipName'];
	   
	   $address = $delivery_address.', '.$city.', '.$state.', '.$country.'. ZIP - '.$zip;
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
	   
	    $vatCountryList = array("11","18","28","45","48","50","59","62","63","71","80","86","88","99","105","106","113","120","128","143","144","148","162","163","169","174");

	   $arrUserDetails=array('user_name'=>$user_name, 'company_name'=>$company_name, 'user_email'=>$user_email,'user_phone'=>$user_phone,'phone_country_code'=>$phone_country_code,
	   'vat_number'=>$vat_number, 'delivery_address'=>$delivery_address, 'delivery_city'=>$city, 'delivery_state'=>$state, 'delivery_country'=>$country, 
	   'delivery_country_id'=>$deliveryCountryId, 'delivery_zip'=>$zip, 
	   'billing_address'=>$billing_address, 'billing_city'=>$billing_city, 'billing_state'=>$billing_state, 'billing_zip'=>$billing_zip, 'billing_country'=>$billing_country,
	   'customerOrderNo'=>$customerOrderNo, 'customerSupplierNo'=>$customerSupplierNo);
	   
	   //echo "<pre>";print_r($vatCountryList); die;
	   //echo "<pre>";print_r($arrUserDetails);
	   //die;
	   //echo '<pre>'; print_r($arrUserDetails); 
	   //   echo '<pre>'; print_r($arrProductOrderDetails); 
	   $enquiryData=$objHomeManager->InsertProductsEnquiry($arrUserDetails,$arrProductOrderDetails);
	   //echo '<pre>'; print_r($enquiryData);
	   //echo '<pre>'; print_r($enquiryData); die;
	   
	   //mail function 
       // echo '<pre>enquiryData='; echo(count($enquiryData));
		if(count($enquiryData)>0)
		{
			$enquiryId = $enquiryData[0];
			$password = $enquiryData[1];
			$userId = $enquiryData[2];
			$randomKeyward = $enquiryData[3];

			if(trim($_POST["email"])!="")
			{
				include "../admin/smtpmail/classes/class.phpmailer.php"; // include the class name
				$subject = 'Requested Quotation Sinelec Tech';
				
				if($company_name=='')
				{
					$userName = $user_name;
				}
				else
				{
					$userName = $company_name;
				}

				$userMessage='
					Hello '.$user_name.',<br/> <br/> 
					Thank you for your interest in our product(s). <br/> <br/> 
				';
				if($randomKeyward!='')
				{	
					$url = 'https://www.sinelec-tech.com/website/forgetPassword.php?urlstring='.EncryptURL('action=VerifyOTP&email='.$user_email);
					$userMessage.='
						Your account in www.sinelec-tech.com is also created for various communication. Kindly activate your account by clicking the below URL and then using the OTP given below:<br/> <br/> 
						<strong>Login Id: </strong> '.$user_email.'<br/>
						<strong>URL: </strong> <a href="'.$url.'">Click Here</a><br/>
						<strong>OTP: </strong> '.$randomKeyward.'<br/> <br/>
					';
				}
				$userMessage.='	
					As per your request we hearby make you the following offer:<br/> <br/>
				';
				
				
				$message='
				
					<table width="100%" border="0" cellpadding="5">
					  <tr>
						<td width="100%">
							<img src="https://sinelec-tech.com/website/images/Logo.png" alt="logo" width="137" height="39"><br/>
							Sinelec Technologies Deutschland GmbH, Brachvogelweg 9, 85375 Neufahrn, Germany
						</td>
					  </tr>
					 </table>
					
					<table width="100%" border="0" cellpadding="5">
						  <tr>
							<td width="70%" valign="top">
								<br/>
								<font size="+1"><strong><u>Customer Billing Address:</u></strong><br/>
                                <strong>'.$userName.'</strong>  <br/> 
								<strong>Address: </strong>'.$billing_address.'  <br/> 
								<strong>City: </strong>'.$billing_city.', <strong>State: </strong>'.$billing_state.'  <br/>
								<strong>Country: </strong>'.$billing_country.', <strong>Zip No: </strong>'.$billing_zip.'  <br/>
								<strong>VAT No: </strong>'.$vat_number.'    
								
								<br/> <br/>
								<strong>Phone Number:</strong>'.$phone_country_code.' '.$user_phone.'<br/> 
									
								<br/> <br/>
								<strong><u>Quotation Details:</u></strong><br/>     
								<strong>Enquiry No:</strong> '.$enquiryId.' <br/> 
								<strong>Customer Order No.: </strong>'.$customerOrderNo.'
								
								<br/> <br/>
								<strong>Customer No.: </strong>'.$userId.'<br/>
								<strong>Customer Supplier No.: </strong>'.$customerSupplierNo.'

								<br/> <br/>
								<strong>Date:</strong>'.$currentDate.'<br/>
								<strong>Country of Origin: </strong>DE </font>
							</td>
							<td width="30%" valign="top">
								<br/>
								<font size="+1"><strong>Sinelec Technologies Deutschland  GmbH</strong> <br/>
								Brachvogelweg 9, 85375 Neufahrn, Germany 
								
								<br/><br/> 
								
								<strong>Tel:</strong> +49-8165-9906178 <br/>  
								<strong>Fax:</strong> +49-8165-9039998 <br/>  
								<strong>Mail:</strong> contact@sinelec-tech.com <br/>  
								<strong>Web:</strong> www.sinelec-tech.com
								
								<br/> <br/> 
								
								<strong>HRB Munich:</strong> HRB254204 <br/> 
								<strong>VAT-ID:</strong> DE327915746 <br/> 
								<strong>Tax-ID:</strong> 115/137/50686 <br/> 
								<strong>CEO:</strong> Neeru Singh

								<br/> <br/> 

								<strong>Bank:</strong> Sparkasse Freising, Germany <br/>
								<strong>IBAN:</strong> DE12 7005 1003 0025 7945 61  <br/>                                     
								<strong>BIC:</strong> BYLADEM1FSI </font> <br/>
									   
							</td>
							
						  </tr>
						</table>
													 
						<br/>

				';
				
				$message.='
					<table width="100%" border="1" cellpadding="5">
						<tr bgcolor="#5B9BD5">
							<td width="5%">
								<font color="#000000" size="+1"><b>Sl</b></font>
							</td>
							<td width="30%">
								<font color="#000000" size="+1"><b>Category</b></font>
							</td>
							<td width="35%">
								<font color="#000000" size="+1"><b>Product</b></font>
							</td>
							<td width="8%">
								<font color="#000000" size="+1"><b>Quantity</b></font>
							</td>
							<td width="8%">
								<font color="#000000" size="+1"><b>Unit Price</b></font>
							</td>
							<td width="14%" align="center">
								<font color="#000000" size="+1"><b>Total in EURO</b></font>
							</td>
						</tr>
						
						';
					$sno=1;
					$totalAmount=0;
					$WithOutVat=0;
					foreach($arrProductOrderDetails as $productVal)
					{
						list($productCategoryId,$productCategoryName)=explode('_',$productVal['productCategoryId']);
						list($productId,$productName,$productAmt)=explode('@_@',$productVal['proudctId']);
						$quantity=$productVal['productQuantity'];
						$message.='
						<tr>
							<td width="5%"> <font size="+1">' . $sno++ . '</font></td>
							<td width="30%"> <font size="+1">' . $productCategoryName . '</font></td>
							<td width="35%"> <font size="+1">' . $productName . '</font></td>
							<td width="8%"> <font size="+1">' . $quantity . '</font></td>
							<td width="8%"> <font size="+1">' . $productAmt . '</font></td>
							<td width="14%" align="center"> <font size="+1">' . round(($productAmt*$quantity),2) . '</font></td>
						</tr>';
						$totalAmount=$totalAmount+round(($productAmt*$quantity),2);
					}
					
					//$vatAmount = ((($deliveryCountryShipping+$totalAmount)/1.19)-(($deliveryCountryShipping+$totalAmount)));
					$vatAmount = ($deliveryCountryShipping+$totalAmount)*19/100;
					
					//echo 'Dipya --- '.$billingCountryId; die;
					if($billingCountryId=='68')
					{
						$vatAmount = $vatAmount;
					}
					
					elseif(in_array($billingCountryId,$vatCountryList))
					{
						if($vat_number=="")
						{
							$vatAmount = $vatAmount;
						}
						else
						{
							$vatAmount = 0.00;
						}
					}
					else
					{
						$vatAmount = 0.00;
						
					}

						$message.='
						<tr>
							<td colspan="5"><font size="+1"><strong>Product Total Amount </strong></font></td>
							<td align="center"><font size="+1"><strong>'.round($totalAmount,2).'</strong></font></td>
						</tr>';
									
						$message.='
						<tr>
							<td colspan="5"><font size="+1"><strong>Packing & Shipping Amount</strong></font></td>
							<td align="center"><font size="+1"><strong>'.round($deliveryCountryShipping,2).'</strong></font></td>
						</tr>';
						$message.='
						<tr>
							<td colspan="5"><font size="+1"><strong>Net Total Amount</strong></font></td>
							<td align="center"><font size="+1"><strong>'.(round($totalAmount,2)+round($deliveryCountryShipping,2)).'</strong></font></td>
						</tr>';
						
						$message.='
						<tr>
							<td colspan="5"><font size="+1"><strong>VAT Amount (19%)</strong></font></td>
							<td align="center"><font size="+1"><strong>'.round($vatAmount,2).'</strong></font></td>
						</tr>';
						$message.='
						<tr bgcolor="#5B9BD5">
							<td colspan="5"><font color="#000000" size="+1"><strong>Invoice Total Amount </strong></font></td>
							<td align="center"><font color="#000000" size="+1"><strong>'.(round($totalAmount,2)+round($vatAmount,2)+round($deliveryCountryShipping,2)).'</strong></font></td>
						</tr>
					</table>
					
					';
					$message.='
					<br/><br/><br/>
					<table width="100%" border="0" cellpadding="5">		
					  <tr>
						<td>
						<font size="+1">
						For purchase of the selected product(s) kindly reply back with your confirmation to "sales@sinelec-tech.com" or deposit of the total amount in the above German bank account.
						<br/><br/>
						Thanks you for your consideration! Please visit to <a href="https://sinelec-tech.com/" target="_blank">sinelec-tech.com</a> to see how else we can provide you more and better service.
						
						<br/><br/>
						Delivery Address: '.$address.'
						
						<br/><br/>
						Validity of Quotation: 30 days
						
						<br/><br/>
						Check the delivery & shipping time in our website <a href="https://sinelec-tech.com/website/Shipping-Payment.php" target="_blank" >https://sinelec-tech.com/website/Shipping-Payment.php</a>
						</font>	
						</td>
					  </tr>
					</table>
					';
					
				//print_r($message);die;	
				
				$fileNamePdf = 'Quotation-'.$enquiryId;
				require_once "tcpdf/tcpdf.php";
				$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				$pdf->SetHeaderData('', 10, $fileNamePdf, '');
				$pdf->setPrintHeader(false);
				$pdf->setPrintFooter(false);
				$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
				$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
				$pdf->AddPage();
				$pdf->SetFont('helvetica', '', 6);
				$pdf->writeHTML($message, true, false, false, false, 'L');
				ob_end_clean();
				$filePath='../admin/UI/Attachments/';
				$uploadFile= $filePath.$fileNamePdf.'.pdf';
					
				if(file_exists($uploadFile))
				{
					@unlink($uploadFile);
				}
				$pdf->Output($uploadFile, 'F');
				
				$attachFile='https://sinelec-tech.com/admin/UI/Attachments/'.$fileNamePdf.'.pdf';
				$attachFile='https://www.sinelec-tech.com/website/view.php?urlstring='.EncryptURL('fileName='.$fileNamePdf.'.pdf');
				$fotter = '<br/><br/><strong>Attachment:</strong> <a href='.$attachFile.' target="_blank">'.rtrim($fileNamePdf,')').'</a>';
				
				$mailArray=array("firstMail"=>trim($_POST["email"]),"secondMail"=>'contact@sinelec-tech.com',"3rdMail"=>'sales@sinelec-tech.com');
				
				foreach($mailArray as $key=>$emailId)
				{
					if($key=='firstMail')
					{
						$from = 'contact@sinelec-tech.com';
						$fromname = "contact@sinelec-tech.com";
					}
					if($key=='secondMail' || $key=='3rdMail'){
						$from = $emailId;
						$fromname = $user_name;
					}
					
					$host = "box5213.bluehost.com";
					$userName = "web@sinelec-tech.com";
					$password = "{Ge-[]sE(wq,";
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
					$mail->Body = $userMessage.$message.$fotter;
					$mail->AddAddress($emailId); //send to mail id
					 //echo $messageContent;die;
					if (!$mail->Send()) {
						//echo 'MAIL NOT SEND'; die;
							echo "<script>alert('Error in process. Kindly try after sometime')</script>";
							$action="";
					} 
					else 
					{
						$enquiryStatus="Quotation Sent";
						$updateEnquiryStatus=$objHomeManager->UpdateEnquiryStatus($enquiryId,$enquiryStatus,$vatAmount,$deliveryCountryShipping,$totalAmount);
						echo "<script>alert('Quotation sent successfully')</script>";
						$action="";
					}
					
				}
			}
		}
		else
		{
		
			//echo 'ISTSTSTS'; die;
			echo "<script>alert('Error in process. Kindly try after sometime')</script>";
			$action="";
		}
	/*	
	}
	else
	{
		$fillDataArray = $_POST;
		echo "<script>alert('Captcha does not match !!!')</script>";
		$action = '';
	}
	*/
	break;
	case "SendEmailOTP":		
		$objUserManager = new UserManager(); 
		$result=$objUserManager->GetuserInfo(trim($_POST['Email']),'2');
		//echo "<pre>";print_r($result);
		//echo "<pre>";print_r($_POST['strOTP']);die;
		if($_POST["6_letters_code"]==$_SESSION["6_letters_code"])
		{
			if($_POST['Email']!="" && $_POST['strOTP']!="" && (empty($result) || $result[0]->VERIFIED_FLAG=="No"))
			{
				//echo "Hii"; die;
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
					You`re receiving this e-mail because you requested for <strong>Quote</strong>. Please verify Email to Continue.
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
					$action="";
					$strTitle="Request a Quote";
					$strSubTitle="Email Verification";
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
			elseif($_POST['Email']!="" && $_POST['strOTP']!="" && (!empty($result) && $result[0]->VERIFIED_FLAG=="Yes"))
			{
				//echo "Hii 2"; die;
				$action="RequestQuote";
			}
			else
			{
				//echo "Hii 3"; die;
				header("location:request_quote.php?urlstring=".EncryptURL("msg=Error&action="));
			}
		}
		else
		{
			//echo "Hii 4"; die;
			$strTitle="Request a Quote";
			$strSubTitle="Email Verification";
			$action="";
			echo "<script type='text/javascript'>alert('Captcha does not match !!!')</script>";	
		}
	break;
	case "ConfirmOTP":	
		$Email=$_POST['Email'];
		$strOTP=$_POST['strOTP'];
		$strConfirmOTP=$_POST['strConfirmOTP'];
		$objUserManager = new UserManager(); 
		$result=$objUserManager->GetuserInfo(trim($_POST['Email']),'2');
		//echo "<pre>";print_r($_POST);die;
		if($strOTP!="" && $strConfirmOTP!="")
		{
			if($strOTP==$strConfirmOTP)
			{
				if(!empty($result) && $result[0]->VERIFIED_FLAG=="No")
				{
					$objUserManager = new UserManager();
					$intReturnId=$objUserManager->MarkUserVerifiedById($result[0]->USER_ID);
				}
				$action="RequestQuote";
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
}
	//echo "<pre>";print_r($action);die;
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
    
	
	<link rel="stylesheet" href="css/tinystyle.css" />
   
	
	
	<script type="text/javascript" src="js/tinybox.js"></script>
    <!--[if lt IE 9]>
        <script src="js/html5shiv.min.js"></script>
        <script src="js/respond.min.js"></script>
    <![endif]-->
	<style>
		#productdescription
		{
			display:none;
		}
	</style>
	
	
	 <style>
	   #newlink {width:px}
	</style>



</head>

<body>
	<!--Top Header-->
    <?php include 'header.php';
	if($paramsArray['strTitle']=="REGISTER")
	{
		$strTitle="Request a Quote";
		$strSubTitle="Email Verification";
	}
	else
	{
		$strTitle="Request a Quote";
		$strSubTitle="Request a Quote";
	}
	?>

    <!--Bread Crumb-->
    <section class="row page_header section-spacing">
        <div class="container">
            <h3>Request a Quote</h3>
            <ol class="breadcrumb">
                <li><a href="/">home</a></li>
                <li class="active">Request a Quote</li>
            </ol>
        </div>
    </section>

   <section class="row section-spacing bg-pattern">
        <div class="container">
        	<div class="sectionTitle p-bottom40">
                <h2>Request a Quote</h2>
            </div>
            <?php
			if($action=='RequestQuote')
			{
			?>
                <div class="row">
                    <div class="col-sm-8 center-block register-form">
                    
                        <div class="form">
                            <form class="login-form clearfix bg-gray border shadow radius"  method="post" action="request_quote.php?urlstring=<?php echo EncryptURL('action=Insert')?>" onSubmit="return validation();">
                        
								<div  class="col-sm-12">
									<p  style="float:right">
									<button type="button" class="btn btn-danger btn-xs pull-right" onClick="javascript:getNextRow()"><i class="glyphicon glyphicon-plus"></i> Add More</button>
									</p>
								</div>
								<br/>
                            
                               <div class="col-sm-5 form-group required">
                                 <label class="control-label">Product Category*</label>
                                  <select class="form-control" name="product_category_id_1" id="se_1" onChange="getProductByCategoryId(this.value,'1');" required>
                                    <option value="<?php echo $fillDataArray['product_category_id'];?>">Select Product Category</option>
                                    <?php
                                    foreach($parentCategory as $parentCategory1)
                                    { 
                                    
                                    ?>
                                     <option value="<?= $parentCategory1->PRODUCT_CATEGORY_ID."_".$parentCategory1->PRODUCT_CATEGORY_NAME; ?>">
                                     <?= $parentCategory1->PRODUCT_CATEGORY_NAME; ?></option>
                                    <?php  
                                    
                                    }
                                    ?>
                                    </select>
                                </div>
                                <div class="col-sm-4 form-group required">
                                 <label class="control-label">Product *</label>
                                 <div id="productDiv_1">
                                    <select class="form-control" name="product_id" id="product_id_1"  onChange="getProductByDesId(this.value,'1');" required>
                                      <option value="<?php echo $fillDataArray['product_id']; ?>">Select Product</option>
                                    </select>
                                </div>
                                </div>
                                
                                <div class="col-sm-3 form-group required">
                                     <label class="control-label">Product Quantity *</label>
                                      <input size="4" type="number" id="quantity_1" name="quantity_1" min="1" value="<?php echo $fillDataArray['quantity']; ?>" required>
                                      <input size="4" type="hidden" id="product_amt_1" name="product_amt_1" value="<?php echo $fillDataArray['product_amt']; ?>" required>
                                </div>
                                
                                <!--
                                <div class="col-sm-12 form-group required">
                                    <label class="control-label">Product Description</label><br/>
                                    <button class="btn btn-primary btn-sm" onClick="myFunction()">View Description</button>
                                    <div id="productdescription_1">
                                    <input size="4" type="text" style="height:50px" id="Description" name="Description">
                                    </div>
                                </div>
                                -->
                                <!-- this is add more div-->
                                <div id="addMore_1"></div>
                                
                                <input type="hidden" name="addMore" id="total" value="1" class="form-control select2 input-sm">
    
                                
                                <div class="sectionTitle m-top30 m-bottom20">
                                <h4>Personal Information</h4>
                                </div>
                                <div class="col-sm-6">
                                    <input placeholder="Name" type="text" id="name" name="name" value="<?php echo $fillDataArray['name']; ?>" required>
                                </div>
                                
                                <div class="col-sm-6">
                                    <input placeholder="Company Name" type="text"id="companyname" name="companyname" value="<?php echo $fillDataArray['companyname']; ?>" required>
                                </div>
                                
                                <div class="col-sm-6">
                                    <input placeholder="Email Address" readonly type="email" id="email" name="email" value="<?php if($_POST['Email']!='') echo $_POST['Email']; else echo $fillDataArray['email']; ?>" required> 
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class='col-sm-5 form-group required'>
                                        <select name="phone_country_code" id="phone_country_code"  style="font-size:12px;text-align:left; width:100%" required>
                                        <option data-countryCode="GB" value="44" Selected style="font-size:12px;text-align:left">UK (+44)</option>
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
                                         <div class="col-sm-7">
                                            <input placeholder="Phone Number" type="text" id="phonenumber" name="phonenumber" maxlength="15" 
                                            value="<?php echo $fillDataArray['phonenumber']; ?>" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <input placeholder="Customer Order No" type="text" id="customerOrderNo" name="customerOrderNo" value="">
                                </div>
                                
                                <div class="col-sm-6">
                                    <input placeholder="Customer Supplier No" type="text"id="customerSupplierNo" name="customerSupplierNo" value="">
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
                            <input placeholder="Billing Address" type="text" id="billingAddress" name="billingAddress" disabled  value="<?php echo $fillDataArray['example_check1']?>">
    
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
                                    <input placeholder="VAT Number" type="text" id="vatnumber" name="vatnumber" disabled value="<?php echo $fillDataArray['vatnumber']; ?>">
                                </div>
								<?php
								/*
                                <div class="g-recaptcha" data-sitekey="captcha_code_file"></div>
                                <div class="col-sm-12">
                                    <img src="captcha_code_file.php?rand=<?php echo rand(); ?>" id='captchaimg' >
                                <?php 
                                
                                if(isset($_POST['captcha'])==isset($_SESSION['6_letters_code']))
                                {
                                  echo "Captcha match";
                                }
                                    if($captcha=="Error")
                                    {
                                        ?>
                                        <span style="color:#FF0000;"><?php echo "Captch Does not match";?></span>
                                        <?php
                                    }
                                    ?> 
                                </div> 
                                <div class="col-sm-12">
                                    <input type="hidden" name="captcha" id="captcha" value="<?php echo rand(); ?>">
                                    <label for='message'>Enter the code above here :</label><br>
                                    <input id="6_letters_code" name="6_letters_code" type="text">
                                <small>Can't read the image? click <a href='javascript: refreshCaptcha();'>here</a> to refresh</small>
                                </div><br>
								*/
								?>
                                <div class="col-sm-12 text-center">
                                    <button class="btn btn-primary btn-lg col-sm-4 col-xs-12 center-block m-top30" >Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        	<?php
			}
            if($action=='')
			{
				$otp=random_strings(6);
				?>
                <div class="row">
                    <div class="col-sm-6 col-md-5 col-xs-12 center-block">
                          <div class="form bg-gray clearfix login-form border">
                                <form class="login-form clearfix" action="request_quote.php?urlstring=<?php echo EncryptURL('action=SendEmailOTP'); ?>" method="post" enctype="multipart/form-data">
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
                                <form class="login-form clearfix" action="request_quote.php?urlstring=<?php echo EncryptURL('action=ConfirmOTP'); ?>" method="post" enctype="multipart/form-data">
                                	<input type="hidden" name="strOTP" value="<?php echo $_POST['strOTP']; ?>">
                                    <div class="col-sm-12">
                                        <input type="text" placeholder="Email" value="<?php echo $_POST['Email']; ?>" readonly name="Email" id="Email"/>
                                    </div>
                                    <div class="col-sm-12">
                                        <input type="text" required placeholder="Enter OTP" name="strConfirmOTP" id="strConfirmOTP"/>
                                    </div>
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-primary btn-xlg btn-block">Verify</button>
										<br/>	
                                        <button class="btn btn-secondary btn-xlg btn-block" type="button" onClick="window.location.reload();">Re-Send OTP</button>
                                    </div>
                             </form>
                          </div>
                    </div>
                </div>
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
	
<script src="../admin/UI/js/func_ajax.js"></script>
<script>
function getProductByCategoryId(CategoryId,count)
{
	var res=0;
	for(var j=1; j<count;j++)
	{
		var res=res+','+String(document.getElementById('product_id_'+j).value);				
	}
	
	var cat=CategoryId.split('_');
	
	console.log(cat);
	var div_id="productDiv_"+count;
	callAjax(div_id, "../admin/UI/Ajax/getProductByCategoryId.php", {
	params:"CategoryId='"+cat[0]+"'&count="+count+"&res="+res+"&flag=1",
	meth:"get",
	async:true,
	errorfunc:"ajaxError()" }
	);
}

function getProductByDesId(ProductId)
{
    callAjax("productdescription", "../admin/UI/Ajax/getProductByDesId.php", {
    params:"ProductId="+ProductId,
    meth:"get",
    async:true,
    errorfunc:"ajaxError()" 
}

    );
}

function validation()
{
    var Category=document.getElementById('se').value;
    var getProduct=document.getElementById('product_id').value;
    var qua=document.getElementById('quantity').value;
   // var Descrip=document.getElementById('Description').value;
    var username=document.getElementById('name').value;
    var companyname=document.getElementById('companyname').value;
    var phone=document.getElementById('phonenumber').value;
    var Email=document.getElementById('email').value;
    var deliveryaddress=document.getElementById('deliveryaddress').value;
    // document.getElementById("example_check").disabled = true;
            if(Category=='')
                {
                    alert("Plaese select product category");
                    return false;
                }
             if(getProduct=='')
                 {
                     alert("Plaese select a product");
                     return false;
               }  
              if(qua=='')
               {
                alert("Plaese enter quantity");
                document.getElementById('quantity').focus();
                return false;
               } 

               //  if(Descrip=='')
               // {
               //  alert("Plaese fill the Description");
               //  return false;
               // } 
               if(username==''){

                alert("Plaese enter your Name");
                document.getElementById('name').focus();
                document.getElementById('name').value='';
                return false;
               }

               if(!isNaN(username))
               {
                  alert("Enter only Alphabhate in Name");
                  document.getElementById('name').focus();
                  return false;
               }
               if(companyname==''){

                alert("Plaese enter Company Name");
                document.getElementById('companyname').focus();
                return false;
                }

               /*if(!isNaN(companyname))
               {
                  alert("Enter only Alphabhate in Conpany Name");
                 document.getElementById('companyname').focus();
                 return false;
               }*/
               if(phone==''){

                alert("Plaese enter Phone Number");
                document.getElementById('phonenumber').focus();
                return false;
               }
              if(isNaN(phone))
               {
                  alert("Enter only number in Phone Number");
                  document.getElementById('phonenumber').focus();
                  return false;
               }
               /*if((phone).length<15)
               {
                alert("Enter only maximum 15 digit mobile number");
                document.getElementById('phonenumber').focus();
                return false;
               }*/
               if(Email==''){

                alert("Please enter Email Id");
                document.getElementById('email').focus();
                return false;
               }
               if(deliveryaddress=='')
               {
                 alert("Plaese enter Delivery Address");
                 document.getElementById('deliveryaddress').focus();
                 return false;
               }
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
	
function refreshCaptcha()
{
	var img = document.images['captchaimg'];
	img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
}
function myFunction()
{
  var x = document.getElementById("productdescription");
  if (x.style.display === "block")
  {
    x.style.display = "none";
  }
  else 
  {
    x.style.display = "block";
  }
}
/*
This script is identical to the above JavaScript function.
*/
function getNextRow()
{
	var r = document.getElementById('total').value;
	//alert(r);
	if(document.getElementById('se_'+r).value==""){
		alert('Please select category.');
		return false;
	}
	var div_id = "addMore_"+r;
	callAjax(div_id, "discriptionRajat.php", {
	params:"row="+r+"&catId="+document.getElementById('se_'+r).value,
	
	meth:"get",
	async:true,
	errorfunc:"ajaxError()" }
	);
	 document.getElementById('total').value = Number(r)+1;
}
</script>

</body>
</html>