<?php

ini_set('display_errors','0');

//error_reporting(E_ALL | E_STRICT);
require_once ('../admin/BL/UserManager.php');
require_once ('../admin/BL/ProductManager.php');
require_once "tcpdf/tcpdf.php";
require_once('config.php');
require_once ('Common.php');
$objProductManager = new ProductManager(); 
$objUserManager = new UserManager();
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
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

<body>
<!--Top Header-->
<?php include 'header.php';
$paramsArray = GetQueryStringParameters();
//echo "<pre>";print_r($_SESSION);die;
if(isset($paramsArray["OrderTotalAmt"]))
$_SESSION['OrderTotalAmt'] = $paramsArray["OrderTotalAmt"];
$user_id=$_SESSION['CUSTOMER_ID'];
	$price="";
if(isset($_SESSION['CUSTOMER_ID']) && $_SESSION['CUSTOMER_ID']!=NULL)
{
	
	$ProductsDetails=$objProductManager->GetOrderAmountByUserIdStatusOrderId($user_id,'Cart','');
	$price=$ProductsDetails[0]->ORDER_TOTAL_AMT; 
	 
	$address=$objUserManager->GetuserAddress($user_id);
	$countryList=$objUserManager->GetAllCountryList();
}
(isset($paramsArray['action']))? $action=$paramsArray['action'] : $action="";
isset($paramsArray["msg"]) ? $msg=$paramsArray["msg"] : $msg="";
switch($action)
	{	
		case "Insert":
		$USER_ADDRESS_ID = $_POST['USER_ADDRESS_ID'];
		$Name = $_POST['Name'];
		$Phone = $_POST["phone_country_code"]."_".$_POST['Phone'];
		$ZIP = $_POST['ZIP'];
		$Locality = $_POST['Locality'];
		$Address= str_replace("'","`",$_POST['Address']);
		$City = str_replace("'","`",$_POST['City']);
		$State = str_replace("'","`",$_POST['State']);
		$landmark = str_replace("'","`",$_POST['landmark']);
		$Country =str_replace("'","`", $_POST['Country']);
		$EU_VAT =$_POST['EU_VAT'];
		$companyName =$_POST['companyName'];
		$adressArray=array("Country"=>$Country,"landmark"=>$landmark,"State"=>$State,"City"=>$City,"Address"=>$Address,"Locality"=>$Locality,"ZIP"=>$ZIP,"Phone"=>$Phone,"Name"=>$Name,"user_id"=>$user_id,"USER_ADDRESS_ID"=>$USER_ADDRESS_ID,"eu_vat"=>$EU_VAT,"company_name"=>$companyName);
		
		$result=$objUserManager->InsertUserAddress($adressArray);
		header("location:cart-checkout.php?urlstring=".EncryptURL("action=&msg=added"));
		break;
		
		
		case "Delete":
		//echo"<pre>";print_r($paramsArray);die;
		$addressId = $paramsArray['addressId'];
		
		$adressDelArray=array("addressId"=>$addressId);
		
		$result=$objUserManager->DeleteUserAddress($adressDelArray);
		header("location:cart-checkout.php?urlstring=".EncryptURL("action=&msg=Deleted"));
		break;
		
		case "checkOut":
			$current_address = $_POST['current_address'];
			$billing_address = $_POST['billing_address'];
			//echo"<pre>";print_r($_POST);die;
			$user_id = $_POST['user_id'];
			$status="Cart";
			if($current_address!="")
			{
				$vat_amt=$_POST["vat_amt"];
				 
				$ProductsDetails=$objProductManager->UpdateProductStatus($user_id,$status,$current_address,$billing_address);
				$objProductManager->UpdateProductShipingAmt($user_id,$_POST["shiping_amt"],$vat_amt);
				//echo"<pre>";print_r($ProductsDetails);die;
				$transactionId=$ProductsDetails[0]->TRANSACTION_ID;
				$OrderId=$ProductsDetails[0]->ORDER_ID;
				$shipingAmt=$_POST["shiping_amt"];  
				$orderAmt=$_POST["total_order_amt"];
				$ProductsDetails=$objProductManager->GetOrderAmountByUserIdStatusOrderId($user_id,'Cart',$OrderId);
				$price=round(($ProductsDetails[0]->ORDER_TOTAL_AMT+$ProductsDetails[0]->SHIPING_AMT+$ProductsDetails[0]->TAX_TOTAL_AMOUNT),2);
				//echo"<pre>";print_r($ProductsDetails);die;
				
				if(trim($_POST["paymentMethod"])=='Paypal')
				{
					if($price!="0.00" && $price!="")
					{ 
						$action='payment';
					}
					else
					{
						 header("location:cart-checkout.php?urlstring=".EncryptURL("action=&msg=error"));
					}
				}
				if(trim($_POST["paymentMethod"])=='PaypalPlus')
				{
					//echo"<pre>";print_r($price);die;
					if($price!="0.00" && $price!="")
					{ 
						$ch = curl_init();
						$clientId ="Ad55_KWfqLNGNtTw1yi6Tx7C24zRGHQH2IU3pc1kMu2jFblxXFjrDFu98PCte3BTF7yX3R85T-thCKue";						
						//AYELJCS-P1ELzWsjRo8M34ekDmDlCEHq8VsIPrgHB4LVNj7NeHD2QIQsDBtqJm5iaJFn4IyVjyCGa8yz
						$secret ="EMue7bqoQ6wmM0N9czZ97Va8DxbUQVCaN5W0z5-mQO18NOrbANfkAxR60c-tN8uhS2Z1bO1JWbLXaun4";
						//EDX0BbJUTsKqDJbw19lJbOIjdsaBuvdiE95vdtZRa2Te8ekP2SICcqQQPfHsPQyWXV025d10nvjfdtIE
						curl_setopt($ch, CURLOPT_URL, "https://api.paypal.com/v1/oauth2/token");//https://api.paypal.com
						curl_setopt($ch, CURLOPT_HEADER, false);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
						curl_setopt($ch, CURLOPT_USERPWD, $clientId.":".$secret);
						curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
						$result = curl_exec($ch);
						if(empty($result))die("Error: No response.");
						else
						{
								$json = json_decode($result);
								$access_token = $json->access_token;
								$_SESSION['token']=$access_token;
								//echo"<pre>";print_r($_SESSION);die;
								$data = '{
								  "intent":"sale",
								  "redirect_urls":{
									"return_url":"http://www.sinelec-tech.com/website/responsePaypalPlus.php?access_token='.$access_token.'&OrderId='.$OrderId.'",
									"cancel_url":"https://sinelec-tech.com/website/index.php"
								  },
								  "payer":{
									"payment_method":"paypal"
								  },
								  "transactions":[
									{
									  "amount":{
										"total":"'.$price.'",
										"currency":"EUR"
									  },
									  "description":"'.$current_address.'"
									}
								  ]
								}';
								
								curl_setopt($ch, CURLOPT_URL, "https://api.paypal.com/v1/payments/payment");
								curl_setopt($ch, CURLOPT_POST, true);
								curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
								curl_setopt($ch, CURLOPT_HTTPHEADER, array(
								  "Content-Type: application/json",
								  "Authorization: Bearer $access_token", 
								  "Content-length: ".strlen($data))
								);
								$result = curl_exec($ch);
								$json = json_decode($result);
								$links  = $json->links ;
								$appove_url=$links[1]->href;
								//echo"<pre>";print_r($links);die;
								?>
								<script src="https://www.paypalobjects.com/webstatic/ppplus/ppplus.min.js" type="text/javascript"></script>	
									<div id="ppplus"></div>
									<script type="application/javascript">
										var ppp = PAYPAL.apps.PPP({
										"approvalUrl": '<?php echo $appove_url;?>',
										"placeholder": "ppplus",
										"mode": "live",
										"country": "DE"
										});
									</script>
								<?php
						}
						curl_close($ch);
						die;
					}
					else
					{
						 header("location:cart-checkout.php?urlstring=".EncryptURL("action=&msg=error"));
					}
				}
				if(trim($_POST["paymentMethod"])=='CreditCard')
				{
					$_SESSION['ORDER_ID']=$OrderId;
				?>
					<!DOCTYPE html>
					<html lang="en">
					<head> 
					  <title>Example Form</title>
					</head>
					<br><br><br>
					<body style="text-align:center; margin-top:20px; padding-top:50px"> 
						<form id="myCCForm" action="payment.php" method="post">
							<input id="token" name="token" type="hidden" value="">
							<div>
								<label>
									<span>Card Number</span>
								</label>
								<input id="ccNo" type="text" size="20" value="" style="width:40%" autocomplete="off" required />
							</div>
							<div>
								<label>
									<span>Expiration Date (MM/YYYY)</span>
								</label>
								<input type="text" size="2" id="expMonth" style="width:20%" required />
								<span> / </span>
								<input type="text" size="2" id="expYear" style="width:20%" required />
							</div>
							<div>
								<label>
									<span>CVC</span>
								</label>
								<input id="cvv" size="4" style="width:20%" type="text" value="" autocomplete="off" required />
							</div>
							<font color="#FF0000"><strong>Work in progress</strong></font>
							<!--<input type="submit" value="Submit Payment" style="width:10%">-->
						</form>					
						<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
						<script src="https://www.2checkout.com/checkout/api/2co.min.js"></script>
						
						<script>
							// Called when token created successfully.
							var successCallback = function(data) {
								var myForm = document.getElementById('myCCForm');
						
								// Set the token as the value for the token input
								myForm.token.value = data.response.token.token;
						
								// IMPORTANT: Here we call `submit()` on the form element directly instead of using jQuery to prevent and infinite token request loop.
								myForm.submit();
							};
						
							// Called when token creation fails.
							var errorCallback = function(data) {
								if (data.errorCode === 200) {tokenRequest();} else {alert(data.errorMsg);}
							};
						
							var tokenRequest = function() {
								// Setup token request arguments
								var args = {
									sellerId: "901396527",
									publishableKey: "CF6000CD-A7AF-4446-8561-EFE9D26DB3F4",
									ccNo: $("#ccNo").val(),
									cvv: $("#cvv").val(),
									expMonth: $("#expMonth").val(),
									expYear: $("#expYear").val()
								};
						
								// Make the token request
								TCO.requestToken(successCallback, errorCallback, args);
							};
						
							$(function() {
								// Pull in the public encryption key for our environment
								TCO.loadPubKey('sandbox');
						
								$("#myCCForm").submit(function(e) {
									// Call our token request function
									tokenRequest();
						
									// Prevent form from submitting
									return false;
								});
							});
						</script>
					</body>
					</html>
				<?php
				die;
				}
				if(trim($_POST["paymentMethod"])=='BankTransfer')
				{
					if($_SESSION['CUSTOMER_EMAIL']!="")
					{
						//echo"<pre>";print_r($_SESSION);die;	
						$status="Bank Transfer Payment Pending";
						$orderId=$objProductManager->UpdateProductStatus($user_id,$status,$current_address,$billing_address);

						$orderDetails=$objProductManager->GetOrderDetails($ProductsDetails[0]->ORDER_ID);
						//echo"<pre>";print_r($orderDetails); die;
						include "../admin/smtpmail/classes/class.phpmailer.php"; // include the class name
						$toEmailID = trim($_SESSION['CUSTOMER_EMAIL']);
						$subject = 'Bank Transfer: Order Detail Sinelec Tech';
						
						if($orderDetails[0]->COMPANY_NAME=='')
						{
							$userName = $orderDetails[0]->USER_NAME;
						}
						else
						{
							$userName = $orderDetails[0]->COMPANY_NAME;
						}

						if($orderDetails[0]->ORDER_ID > 1743)
						{
							if($orderDetails[0]->ORDER_NUMBER!='' || $orderDetails[0]->ORDER_NUMBER!=NULL)
							{
								$invoiceNo = $orderDetails[0]->ORDER_YEAR.' - '.$orderDetails[0]->ORDER_NUMBER ;
							}
							else
							{
								$invoiceNo = 'Not Generated';
							}
						}
						else
						{
							$invoiceNo = $orderDetails[0]->ORDER_ID;
						}
						
						//////////////////billing Address////////////////////////////
						
						$billingAdress=$orderDetails[0]->BILLING_ADDRESS;
						if($billingAdress=='')
							$billingAdress=$orderDetails[0]->ADDRESS;
							
						$billingCity=$orderDetails[0]->BILLING_CITY;
						if($billingCity=='')
							$billingCity=$orderDetails[0]->CITY;	
							
						$billingState=$orderDetails[0]->BILLING_STATE;
						if($billingState=='')
							$billingState=$orderDetails[0]->STATE;		
						
						$billingZip=$orderDetails[0]->BILLING_ZIP;
						if($billingZip=='')
							$billingZip=$orderDetails[0]->ZIP;		
					
						$billingCountry=$orderDetails[0]->BILLING_COUNTRY;
						if($billingCountry=='')
							$billingCountry=$orderDetails[0]->COUNTRY;	
								
						$billingLadmark=$orderDetails[0]->BILLING_LANDMARK;
						if($billingLadmark=='')
							$billingLadmark=$orderDetails[0]->LANDMARK;		
						
						$billingPhoneNo=$orderDetails[0]->BILLING_DELIVERY_PHONE_NO;
						if($billingPhoneNo=='')
							$billingPhoneNo=$orderDetails[0]->DELIVERY_PHONE_NO;		

						$billingCountryCode=$orderDetails[0]->BILLING_MOBILE_COUNTRY_CODE;
						if($billingCountryCode=='')
							$billingCountryCode=$orderDetails[0]->MOBILE_COUNTRY_CODE;		

						$billingUser=$orderDetails[0]->BILLING_COMPANY_NAME;
						if($billingUser=='')
							$billingUser=$orderDetails[0]->COMPANY_NAME;	
						
						if($billingUser=='')
							$billingUser=$orderDetails[0]->BILLING_USER_NAME;
							
						if($billingUser=='')
							$billingUser=$orderDetails[0]->USER_NAME;			
							
						$billingVat=$orderDetails[0]->BILLING_EU_VAT;
						if($billingVat=='')
							$billingVat=$orderDetails[0]->EU_VAT;			


						$userMessage='
							Hello '.$orderDetails[0]->USER_NAME.',<br/> <br/> 
							Thanks for your purchase today! <br/> 
							Please visit to <a href="https://sinelec-tech.com/" target="_blank">sinelec-tech.com</a> see how else we can provide you more and better service.<br/> 
							Kindly find the purchase details below: <br/> <br/> 
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
									<strong>'.$billingUser.'</strong>  <br/> 
									<strong>Address: </strong>'.$billingAdress.'  <br/> 
									<strong>City: </strong>'.$billingCity.', <strong>State: </strong>'.$billingState.'  <br/>
									<strong>Country: </strong>'.$billingCountry.', <strong>Zip No: </strong>'.$billingZip.'  <br/>
									<strong>VAT No: </strong>'.$billingVat.'    
									
									<br/> <br/>
									<strong>Mobile Number:</strong>'.$orderDetails[0]->COMMUNICATION_MOBILE_NUM_ISD.' '.$orderDetails[0]->COMMUNICATION_MOBILE_NUM.'<br/> 
                                        
                                    <br/> <br/>
                                    <strong><u>Invoice Details:</u></strong><br/>     
                                    <strong>Invoice No: </strong>'.$invoiceNo.'<br/>
									<strong>Order Id: </strong>'.$orderDetails[0]->ORDER_ID.'<br/>
                                    <strong>Customer Order No.: </strong>'.$orderDetails[0]->CUSTOMER_ORDER_NO.'
                                    
                                    <br/> <br/>
									<strong>Customer No.: </strong>'.$orderDetails[0]->USER_ID.'<br/>
									<strong>Customer Supplier No.: </strong>'.$orderDetails[0]->CUSTOMER_SUPPLIER_NO.'

									<br/> <br/>
									<strong>Date: </strong>'.$orderDetails[0]->ORDER_DATE.'<br/>
									<strong>Country of Origin: </strong>DE </font>
								</td>
								<td width="30%" valign="top">
                                    <br/>
									<font size="+1"><font size="+1"><strong>Sinelec Technologies Deutschland  GmbH</strong> <br/>
                                    Brachvogelweg 9, 85375 Neufahrn, Germany </font>
                                    
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
                                    <strong>BIC:</strong> BYLADEM1FSI </font>
                                           
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
										<td width="65%">
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
								foreach($orderDetails as $order)
								{
								$message.='<tr>
												<td width="5%"> <font size="+1">' . $sno++ . '</font></td>
												<td width="65%"> <font size="+1">' . $order->PRODUCT_NAME . ' </font></td>
												<td width="8%"> <font size="+1">' . $order->QUANTITY . '</font></td>
												<td width="8%"> <font size="+1">' . $order->PRODUCT_AMT . '</font></td>
												<td width="14%" align="center"> <font size="+1">' . round(($order->PRODUCT_AMT*$order->QUANTITY),2) . '</font></td>
											</tr>';
								$totalAmount=$totalAmount+round(($order->PRODUCT_AMT*$order->QUANTITY),2);
								}
								$message.='<tr>
												<td colspan="4"><font size="+1"><strong>Product Total Amount </strong></font></td>
												<td align="center"><font size="+1"><strong>'.round($totalAmount,2).'</strong></font></td>
											</tr>';
									
								$message.='<tr>
												<td colspan="4"><font size="+1"><strong>Packing & Shipping Amount </strong></font></td>
												<td align="center"><font size="+1"><strong>'.round($orderDetails[0]->SHIPING_AMT,2).'</strong></font></td>
											</tr>';
								$message.='<tr>
												<td colspan="4"><font size="+1"><strong>Net Total Amount </strong></font></td>
												<td align="center"><font size="+1"><strong>'.(round($totalAmount,2)+round($orderDetails[0]->SHIPING_AMT,2)).'</strong></font></td>
											</tr>';

								$message.='<tr>
												<td colspan="4"><font size="+1"><strong>VAT Amount (19%) </strong></font></td>
												<td align="center"><font size="+1"><strong>'.round($orderDetails[0]->TAX_TOTAL_AMOUNT,2).'</strong></font></td>
											</tr>';
								$message.='<tr bgcolor="#5B9BD5">
												<td colspan="4"><font color="#000000" size="+1"><strong>Invoice Total Amount </strong></font></td>
												<td align="center"><font color="#000000" size="+1"><strong>'.(round($totalAmount,2)+round($orderDetails[0]->TAX_TOTAL_AMOUNT,2)+round($orderDetails[0]->SHIPING_AMT,2)).'</strong></font></td>
											</tr>
										</table>
									
										';
								
									  
									$message.='<table width="100%" border="0" cellpadding="5">
									  <tr>
										<td width="70%" valign="top">
											<br/>
											<font size="+1"><strong><u>Customer Delivery Address:</u></strong><br/>
											<strong>'.$orderDetails[0]->USER_NAME.'</strong>  <br/> 
											<strong>Address: </strong>'.$orderDetails[0]->ADDRESS.'  <br/> 
											<strong>City: </strong>'.$orderDetails[0]->CITY.', <strong>State: </strong>'.$orderDetails[0]->STATE.'  <br/>
											<strong>Mobile No: </strong>'.$orderDetails[0]->DELIVERY_PHONE_NO.', <strong>Zip No: </strong>'.$orderDetails[0]->ZIP.'  <br/>
											<strong>Country: </strong>'.$orderDetails[0]->COUNTRY.'</font> 
										</td>
										</tr>	  
										  <tr>
											<td>
												<font size="+1">
												We thank you for your purchase. Please transfer the Invoice amount in the above given German bank account within 30 days. Once amount is received we will dispatch the goods immediately.
												</font>			
											</td>
										  </tr>
										</table>';

								
						//echo $message; die;
						
						
						$fileNamePdf = 'PB-Invoice-'.$orderDetails[0]->ORDER_ID;
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
						
						//$attachFile='https://sinelec-tech.com/admin/UI/Attachments/'.$fileNamePdf.'.pdf';
						$attachFile='https://www.sinelec-tech.com/website/view.php?urlstring='.EncryptURL('fileName='.$fileNamePdf.'.pdf');
						$fotter = '<br/><br/><strong>Attachment:</strong> <a href='.$attachFile.' target="_blank">'.rtrim($fileNamePdf,')').'</a>';
						
						$emailArray=array($orderDetails[0]->COMMUNICATION_EMAIL_ID,'sales@sinelec-tech.com');
						$flag='false';
						foreach($emailArray as $email)
						{
								$host = "box5213.bluehost.com";
								$userName = "web@sinelec-tech.com";
								$password = "{Ge-[]sE(wq,";
								$fromname = "sales@sinelec-tech.com";
								$from = 'sales@sinelec-tech.com';
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

								$mail->AddAddress($email); //send to mail id
							   //echo "<pre>";print_r($mail);
								if (!$mail->Send()) {
								   // echo "Mailer Error: " . $mail->ErrorInfo;
								} 
								else 
								{
										$flag='true';
								}
							}
							
							//echo "<pre>";print_r($message);die;
							if($flag=='true'){
								$_SESSION['AddressStatus']="";
								$_SESSION['current_address']="";
								$_SESSION['OrderProductTotalPrice']="";
								$_SESSION['shiping_amt']="";
								$_SESSION['vat_amt']="";
								$_SESSION['total_order_amt']="";
								echo '<script language="javascript">';
								echo 'alert("Invoice has been sent to your email with our bank account details. Kindly make the payment so that we can process your selected goods")';
								echo '</script>';
								echo '<script>document.location.href ="https://sinelec-tech.com/website/index.php";</script>';
							}
					}
					die;
				} 
				if(trim($_POST["paymentMethod"])=='Invoice')
				{
					if($_SESSION['CUSTOMER_EMAIL']!="")
					{
						 
						$status="Invoice Payment Pending";
						$orderId=$objProductManager->UpdateProductStatus($user_id,$status,$current_address,$billing_address);
 
						$orderDetails=$objProductManager->GetOrderDetails($ProductsDetails[0]->ORDER_ID);
						//echo"<pre>";print_r($orderDetails); die;
						include "../admin/smtpmail/classes/class.phpmailer.php"; // include the class name
						$toEmailID = trim($_SESSION['CUSTOMER_EMAIL']);
						$subject = 'Invoice: Order Detail Sinelec Tech';
						
						if($orderDetails[0]->COMPANY_NAME=='')
						{
							$userName = $orderDetails[0]->USER_NAME;
						}
						else
						{
							$userName = $orderDetails[0]->COMPANY_NAME;
						}
						
						if($orderDetails[0]->ORDER_ID > 1743)
						{
							if($orderDetails[0]->ORDER_NUMBER!='' || $orderDetails[0]->ORDER_NUMBER!=NULL)
							{
								$invoiceNo = $orderDetails[0]->ORDER_YEAR.' - '.$orderDetails[0]->ORDER_NUMBER ;
							}
							else
							{
								$invoiceNo = 'Not Generated';
							}
						}
						else
						{
							$invoiceNo = $orderDetails[0]->ORDER_ID;
						}
						
						//////////////////billing Address////////////////////////////
						
						$billingAdress=$orderDetails[0]->BILLING_ADDRESS;
						if($billingAdress=='')
							$billingAdress=$orderDetails[0]->ADDRESS;
							
						$billingCity=$orderDetails[0]->BILLING_CITY;
						if($billingCity=='')
							$billingCity=$orderDetails[0]->CITY;	
							
						$billingState=$orderDetails[0]->BILLING_STATE;
						if($billingState=='')
							$billingState=$orderDetails[0]->STATE;		
						
						$billingZip=$orderDetails[0]->BILLING_ZIP;
						if($billingZip=='')
							$billingZip=$orderDetails[0]->ZIP;		
					
						$billingCountry=$orderDetails[0]->BILLING_COUNTRY;
						if($billingCountry=='')
							$billingCountry=$orderDetails[0]->COUNTRY;	
								
						$billingLadmark=$orderDetails[0]->BILLING_LANDMARK;
						if($billingLadmark=='')
							$billingLadmark=$orderDetails[0]->LANDMARK;		
						
						$billingPhoneNo=$orderDetails[0]->BILLING_DELIVERY_PHONE_NO;
						if($billingPhoneNo=='')
							$billingPhoneNo=$orderDetails[0]->DELIVERY_PHONE_NO;		

						$billingCountryCode=$orderDetails[0]->BILLING_MOBILE_COUNTRY_CODE;
						if($billingCountryCode=='')
							$billingCountryCode=$orderDetails[0]->MOBILE_COUNTRY_CODE;		

						$billingUser=$orderDetails[0]->BILLING_COMPANY_NAME;
						if($billingUser=='')
							$billingUser=$orderDetails[0]->COMPANY_NAME;	
						
						if($billingUser=='')
							$billingUser=$orderDetails[0]->BILLING_USER_NAME;
							
						if($billingUser=='')
							$billingUser=$orderDetails[0]->USER_NAME;			
							
						$billingVat=$orderDetails[0]->BILLING_EU_VAT;
						if($billingVat=='')
							$billingVat=$orderDetails[0]->EU_VAT;
						
						
						
						$userMessage='
							Hello '.$orderDetails[0]->USER_NAME.',<br/> <br/> 
							Thanks for your purchase today! <br/> 
							Please visit to <a href="https://sinelec-tech.com/" target="_blank">sinelec-tech.com</a> see how else we can provide you more and better service.<br/> 
							Kindly find the purchase details below: <br/> <br/> 
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
									<strong>'.$billingUser.'</strong>  <br/> 
									<strong>Address: </strong>'.$billingAdress.'  <br/> 
									<strong>City: </strong>'.$billingCity.', <strong>State: </strong>'.$billingState.'  <br/>
									<strong>Country: </strong>'.$billingCountry.', <strong>Zip No: </strong>'.$billingZip.'  <br/>
									<strong>VAT No: </strong>'.$billingVat.'      
									
									<br/> <br/>
									<strong>Mobile Number:</strong>'.$orderDetails[0]->COMMUNICATION_MOBILE_NUM_ISD.' '.$orderDetails[0]->COMMUNICATION_MOBILE_NUM.'<br/> 
									
									<br/> <br/>
                                    <strong><u>Invoice Details:</u></strong><br/>     
                                    <strong>Invoice No: </strong>'.$invoiceNo.'<br/>
									<strong>Order Id: </strong>'.$orderDetails[0]->ORDER_ID.'<br/>
                                    <strong>Customer Order No.: </strong>'.$orderDetails[0]->CUSTOMER_ORDER_NO.'
                                    
                                    <br/> <br/>
									<strong>Customer No.: </strong>'.$orderDetails[0]->USER_ID.'<br/>
									<strong>Customer Supplier No.: </strong>'.$orderDetails[0]->CUSTOMER_SUPPLIER_NO.'

									<br/> <br/>
									<strong>Date: </strong>'.$orderDetails[0]->ORDER_DATE.'<br/>
									<strong>Country of Origin: </strong>DE </font>
								</td>
								<td width="30%" valign="top">
                                    <br/>
									<font size="+1"><font size="+1"><strong>Sinelec Technologies Deutschland  GmbH</strong> <br/>
                                    Brachvogelweg 9, 85375 Neufahrn, Germany </font> 
                                    
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
										<td width="65%">
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
								foreach($orderDetails as $order)
								{
								$message.='<tr>
												<td width="5%"> <font size="+1">' . $sno++ . '</font></td>
												<td width="65%"> <font size="+1">' . $order->PRODUCT_NAME . ' </font></td>
												<td width="8%"> <font size="+1">' . $order->QUANTITY . '</font></td>
												<td width="8%"> <font size="+1">' . $order->PRODUCT_AMT . '</font></td>
												<td width="14%" align="center"> <font size="+1">' . round(($order->PRODUCT_AMT*$order->QUANTITY),2) . '</font></td>
											</tr>';
								$totalAmount=$totalAmount+round(($order->PRODUCT_AMT*$order->QUANTITY),2);
								}
								$message.='<tr>
												<td colspan="4"><font size="+1"><strong>Product Total Amount </strong></font></td>
												<td align="center"><font size="+1"><strong>'.round($totalAmount,2).'</strong></font></td>
											</tr>';
									
								$message.='<tr>
												<td colspan="4"><font size="+1"><strong>Packing & Shipping Amount </strong></font></td>
												<td align="center"><font size="+1"><strong>'.round($orderDetails[0]->SHIPING_AMT,2).'</strong></font></td>
											</tr>';
								$message.='<tr>
												<td colspan="4"><font size="+1"><strong>Net Total Amount </strong></font></td>
												<td align="center"><font size="+1"><strong>'.(round($totalAmount,2)+round($orderDetails[0]->SHIPING_AMT,2)).'</strong></font></td>
											</tr>';

								$message.='<tr>
												<td colspan="4"><font size="+1"><strong>VAT Amount (19%) </strong></font></td>
												<td align="center"><font size="+1"><strong>'.round($orderDetails[0]->TAX_TOTAL_AMOUNT,2).'</strong></font></td>
											</tr>';
								$message.='<tr bgcolor="#5B9BD5">
												<td colspan="4"><font color="#000000" size="+1"><strong>Invoice Total Amount </strong></font></td>
												<td align="center"><font color="#000000" size="+1"><strong>'.(round($totalAmount,2)+round($orderDetails[0]->TAX_TOTAL_AMOUNT,2)+round($orderDetails[0]->SHIPING_AMT,2)).'</strong></font></td>
											</tr>
										</table>
									';
								$message.='
									<br/>
									<table width="100%" border="0" cellpadding="5">	
									 <tr>
										<td width="100%" valign="top">
											<br/>
											<font size="+1"><strong><u>Customer Delivery Address:</u></strong><br/>
											<strong>'.$orderDetails[0]->USER_NAME.'</strong>  <br/> 
											<strong>Address: </strong>'.$orderDetails[0]->ADDRESS.'  <br/> 
											<strong>City: </strong>'.$orderDetails[0]->CITY.', <strong>State: </strong>'.$orderDetails[0]->STATE.'  <br/>
											<strong>Mobile No.: </strong>'.$orderDetails[0]->MOBILE_COUNTRY_CODE.' '.$orderDetails[0]->DELIVERY_PHONE_NO.'  <br/> 
											<strong>Country: </strong>'.$orderDetails[0]->COUNTRY.', <strong>Zip No: </strong>'.$orderDetails[0]->ZIP.' <br/><br/> 
											</font>
											</td>
									  </tr> 							
									  <tr>
										<td>
											<font size="+1">
											We thank you for your purchase. Please transfer the Invoice amount in above given German bank account within 30 days of receiving goods.
											</font>
										</td>
									  </tr>
									</table>
								';
								
							//echo "<pre>";print_r($message);die;
							
							$fileNamePdf = 'Invoice-'.$orderDetails[0]->ORDER_ID;
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
							
							//$attachFile='https://sinelec-tech.com/admin/UI/Attachments/'.$fileNamePdf.'.pdf';
							$attachFile='https://www.sinelec-tech.com/website/view.php?urlstring='.EncryptURL('fileName='.$fileNamePdf.'.pdf');
							$fotter = '<br/><br/><strong>Attachment:</strong> <a href='.$attachFile.' target="_blank">'.rtrim($fileNamePdf,')').'</a>';
							
							$emailArray=array($orderDetails[0]->COMMUNICATION_EMAIL_ID,'sales@sinelec-tech.com');
							$flag='false';
							//echo"<pre>";print_r($emailArray);
							foreach($emailArray as $email)
							{
								$host = "box5213.bluehost.com";
								$userName = "web@sinelec-tech.com";
								$password = "{Ge-[]sE(wq,";
								$fromname = "sales@sinelec-tech.com";
								$from = 'sales@sinelec-tech.com';
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
								
								$mail->AddAddress($email); //send to mail id
								if (!$mail->Send()) {
								   // echo "Mailer Error: " . $mail->ErrorInfo;
								} else {
										$flag='true';
								  }
							  }
							if($flag=='true'){
								$_SESSION['AddressStatus']="";
								$_SESSION['current_address']="";
								$_SESSION['OrderProductTotalPrice']="";
								$_SESSION['shiping_amt']="";
								$_SESSION['vat_amt']="";
								$_SESSION['total_order_amt']="";
								echo '<script language="javascript">';
								echo 'alert("Invoice has been sent to your email with our bank account details. Kindly make the payment within 30 days of receiving goods")';
								echo '</script>';
								echo '<script>document.location.href ="https://sinelec-tech.com/website/index.php";</script>';
							}
						}
				}
			}
		break;
		
		case "PlaceOrder":
		$current_address = $_POST['current_address'];
		$user_id = $_POST['user_id'];
		$status="Cart";
		if($current_address!="")
		{
			$ProductsDetails=$objProductManager->UpdateProductStatus($user_id,$status,$current_address,$billing_address);
			$transactionId=$ProductsDetails[0]->TRANSACTION_ID;
			$OrderId=$ProductsDetails[0]->ORDER_ID;
			$orderAmt=$ProductsDetails[0]->ORDER_TOTAL_AMT;
			 
			$action='payment';
		}
		else
		{
		 header("location:cart-checkout.php?urlstring=".EncryptURL("action=&msg=error"));
		}
		break;
		
		
		
	}
	if($action=='payment')
	{
	// echo "<pre>";print_r($orderAmt);
		?>
		<script>
			var hash = 'manish.singh-facilitator@sinelec-tech.com';
			function submitPayuForm() {
			  if(hash == '') {
				return;
			  }
			  var payPal = document.forms.payPal;
			  payPal.submit();
			}
		  </script><body  onLoad="submitPayuForm();" >
			<center>
				<h2>Please do not refresh this page.</h2>
			</center>  
            
			<form action="<?php echo PAYPAL_URL; ?>" method="post" name="payPal">
				<input type="hidden" name="business" value="<?php echo PAYPAL_ID; ?>">
				<input type="hidden" name="cmd" value="_xclick">
				<input type="hidden" name="item_name" value="<?php echo $transactionId; ?>">
				<input type="hidden" name="item_number" value="<?php echo $OrderId; ?>">
				
				<input type="hidden" name="amount" value="<?php echo ($orderAmt); ?>">
				<input type="hidden" name="shiping_amount" value="<?php echo ($shipingAmt); ?>">
				<input type="hidden" name="no_shipping" value="1">
				<input type="hidden" name="currency_code" value="<?php echo CURRENCY;?>">
				<input type="hidden" name="cancel_return" value="http://www.sinelec-tech.com/website/response.php?type=cancel&item_name=<?php echo $transactionId; ?>&item_number=<?php echo $OrderId; ?>">
				<input type="hidden" name="return" value="http://www.sinelec-tech.com/website/response.php?type=success&item_name=<?php echo $transactionId; ?>&item_number=<?php echo $OrderId; ?>&amount=<?php echo ($orderAmt); ?>&currency_code=<?php echo CURRENCY;?>">
			</form>
			</body>
		   <?php 
		die;
		
	}
?>

    <!--Breadcrumb-->
    <section class="row page_header section-spacing">
        <div class="container">
            <h3>Place Order </h3>
            <ol class="breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li class="active">Cart</li>
            </ol>
        </div>
    </section>
	
			<style>
	.btn-info {
		-moz-user-select: none;
		background-image: none;
		border: 1px solid transparent;
		border-radius: 4px;
		cursor: pointer;
		display: inline-block;
		font-size: 14px;
		font-weight: 400;
		color:#FFFFFF;
		line-height: 1.42857;
		margin-bottom: 0;
		padding: 6px 12px;
		text-align: center;
		touch-action: manipulation;
		vertical-align: middle;
		white-space: nowrap;
		background-color: #5bc0de;
		border-color: #2455f4;
		color: #fff;}
		/*
		Step Bar which indicates progress when the user arrives at the step, and 
		indicates the end of the progress with a different shape and color.
		*/	
		.stepwizard-step p {
			margin-top: 10px;    
		}
		
/*		.stepwizard-row {
			display: table-row;
		}*/
		
		.stepwizard {
			display: table;     
			width: 100%;
			position: relative;
		}
		
		.stepwizard-step button[disabled] {
			opacity: 1 !important;
			filter: alpha(opacity=100) !important;
		}
		
		.stepwizard-row:before {
			top: 14px;
			bottom: 0;
			position: absolute;
			content: " ";
			width: 100%;
			height: 1px;
			background-color: #ccc;
			z-order: 0;
			
		}
		
		.stepwizard-step {    
			display: table-cell;
			text-align: center;
			position: relative;
		}
		
		.button-circle {
		  width: 30px;
		  height: 30px;
		  text-align: center;
		  padding: 6px 0;
		  font-size: 12px;
		  line-height: 1.428571429;
		  border-radius: 15px;
		  border:0
		}
		.btn-danger {
		color: #fff;
		background-color: #d9534f;
		border-color: #d43f3a;
	}
	
	.btn-primary {
		color: #fff;
		background-color: #3276b1;
		border-color: #285e8e;
		}
		
	.btn-success {
		color: #fff;
		background-color: #5cb85c;
		border-color: #4cae4c;
	}
	</style>
	<style>
	.fontsizehead
	{
	  font-size:10px;
	}
	.font-size-small
	{
	  font-size:8px;
	}
	</style>
    <!--Shopping Cart-->
    <section class="row shopping-cart payment section-spacing2">
        <div class="container">
            <div class="row">
			<?php 
			if($action=="")
			{
				$_SESSION['AddressStatus']=$price;
				
				//echo"<pre>";print_r($_SESSION);
				if($action=="" && $price!="0.00" && $price!="")
				{ 
				?>
				<script language="javascript">
                var country = ["11","18","28","45","48","50","59","62","63","71","80","86","88","99","105","106","113","120","128","143","144","148","162","163","169","174"];
                function inArray(needle, haystack)
                 {
                    var length = haystack.length;
                    for(var i = 0; i < length; i++) {
                        if(haystack[i] == needle) return true;
                    }
                    return false;
                 }
				 
				function setBillingAddress(id)
                {
				 	document.getElementById("billing_address").value=id;
				 
				}
				
                function deliveryAddress(id,shiping_amt,eu_vat,countryId,company_name)
                {
                    var OrderProductTotalPrice = document.getElementById("OrderProductTotalPrice").value;
					
                    document.getElementById("current_address").value=id;
                    document.getElementById("company_name").value=company_name;
                    $("#SHIPPING_AMT").text(shiping_amt);
                    $("#shiping_amt").val(shiping_amt);
                    //var vatAmount = (((Number(shiping_amt)+Number(OrderProductTotalPrice))/1.19)-((Number(shiping_amt)+Number(OrderProductTotalPrice))));

                    var vatAmount = (Number(shiping_amt)+Number(OrderProductTotalPrice))*19/100;
					//alert (OrderProductTotalPrice);
					//alert (shiping_amt);
					//alert (vatAmount);

                    //var deductedAmount = vatAmount;
                    if(countryId=='68')
                    {
                        vatAmount = vatAmount;						
                        //vatAmount = (((Number(shiping_amt)+Number(OrderProductTotalPrice))/1.19)-((Number(shiping_amt)+Number(OrderProductTotalPrice))));
                    }
                    else if (inArray(countryId,country))
                    {
                        if(eu_vat=="")
                        {
                            //document.getElementById('vatRow').style.display="none";
                            vatAmount = vatAmount;
                        }
                        else
                        {
                            vatAmount = 0.00;
                            //document.getElementById('vatRow').style.display="";
                        }
                    }
                    else{
                            vatAmount = 0.00;
                    }
                    $("#VAT_AMT").text(vatAmount.toFixed(2));
                    var totalAmountToPay = Number(OrderProductTotalPrice)+Number(vatAmount)+Number(shiping_amt);
                    $("#GrandTotalPrice").text(totalAmountToPay.toFixed(2));
                    $("#total_order_amt").val(totalAmountToPay.toFixed(2));
                    $("#vat_amt").val(vatAmount);
                    
                }
                </script>
				<div class="col-sm-12">
				<div class="sectionTitle p-bottom40">
					<h2>Address</h2>
				</div>
				<div class="stepwizard">
					<div class="stepwizard-row">
						<div class="stepwizard-step" style="width:5%">
							<a href="https://sinelec-tech.com/website/cart.php"><button type="button" class="btn-danger button-circle">1</button></a>
							<p>Cart</p>
						</div>
						<div class="stepwizard-step" style="width:95%">
							<button type="button" class="btn-success button-circle">2</button>
							<p>Address</p>
						</div>
						<div class="stepwizard-step" style="width:0%">
							<?php if($_SESSION['current_address']!="") {?><a href="cart-checkout.php?urlstring=<?php echo EncryptURL('action=PaymentMethod'); ?>"><button type="button" class="btn-danger button-circle">3</button></a><?php } else { ?><button type="button" class="btn-danger button-circle">3</button><?php }?>
							<p>Payment</p>
						</div>
					</div>
				</div>		
			</div>
		 <div class="col-sm-8">
				<div class="row">
					<div class="col-sm-12 col-md-12 col-xs-12 center-block">
                        <div class="form bg-gray clearfix login-form border">
                            <div class="sectionTitle p-bottom40">
                                <h3>Choose Delivery And Billing Address</h3>
                            </div>
                            <?php 
                            //echo "<pre>";print_r($address);die;
							if(isset($address))
							{
							?>
							<form class="login-form clearfix" action="login.php?urlstring=<?php echo EncryptURL('action=Login'); ?>" method="post" enctype="multipart/form-data">
							
							
							<div class="row">
								<div class="col-sm-2" >
									<span style="font-weight:600; white-space:nowrap">Delivery Address</span>
								</div>
								<div class="col-sm-2" >
									<span style="font-weight:600; white-space:nowrap">Billing Address</span>
								</div>
								<div class="col-sm-5" >
										<span style="font-weight:600; white-space:nowrap"> Address</span>
								</div>
								
								<div class="col-sm-1" >
										<span style="font-weight:600; white-space:nowrap">&nbsp;</span>
								</div>
							
								<div class="col-sm-1" >
										<span style="font-weight:600; white-space:nowrap">&nbsp;</span>
								</div>
							
							</div>
							<hr/>
							<?php 
							$addressStr="";
							if(count($address)>0)
							{
								foreach($address as $val)
								{ 
								$addressStr=$addressStr.",".$val->USER_ADDRESS_ID;?>
								<div class="row">
									<div class="col-sm-2" >
											<input type="radio" name="address" id="<?php echo 'address'.$val->USER_ADDRESS_ID ?>" style="float:left;" onClick="deliveryAddress('<?php echo $val->USER_ADDRESS_ID;?>','<?php echo $val->SHIPPING_AMT ;?>','<?php echo $val->EU_VAT ;?>','<?php echo $val->COUNTRY_ID ;?>','<?php echo $val->COMPANY_NAME ;?>')" >
									</div>
									<div class="col-sm-2" > 
										<input type="radio" name="addressBilling" id="<?php echo 'addressBilling'.$val->USER_ADDRESS_ID ?>" style="float:left;" value="<?php echo $val->USER_ADDRESS_ID ?>"  onclick="setBillingAddress('<?php echo $val->USER_ADDRESS_ID;?>');">
									</div>
									<div class="col-sm-5" >
											<?php echo $val->USER_NAME ?> &nbsp;<?php echo $val->MOBILE_COUNTRY_CODE.' '.$val->DELIVERY_PHONE_NO ?><br>
											 <?php echo $val->COMPANY_NAME.','.$val->ADDRESS; ?><br>
									  <?php echo $val->LANDMARK.','.$val->CITY.','.$val->STATE.'-'.$val->ZIP.','.$val->COUNTRY; ?>
									</div>
									<div class="col-sm-1" >
									<a href="cart-checkout.php?urlstring=<?php echo EncryptURL('action=AddAddress&addressId='.$val->USER_ADDRESS_ID); ?>" class="btn btn-border"><i style='font-size:12px' class='fa fa-edit'></i></a>
									</div>
									<div class="col-sm-1" >
									<a href="cart-checkout.php?urlstring=<?php echo EncryptURL('action=Delete&addressId='.$val->USER_ADDRESS_ID); ?>" class="btn btn-border btn-danger" onClick="return confirm('Are you sure you want to Delete it?\n Click OK to Continue, Cancel to Stop');" ><i style='font-size:12px; ' class='fa fa-trash'></i></a>

									</div>
									</div>
									&nbsp;
									<?php 
									} 
							}
							else
							{
							?>
								<hr/>
								<p style="color:#FF0000">No Address Found ,Please add new address for shopping.</p>
							
							<?php
							}


												
								$addressStr=ltrim($addressStr,",");?>
								<input type="hidden" name="addressStr" id="addressStr" value="<?php echo $addressStr;?>" />
								
							</form>
							 <?php 
							 }
							 ?>
						</div>
						<a href="cart-checkout.php?urlstring=<?php echo EncryptURL('action=AddAddress'); ?>" class="btn btn-border btn-xlg" >Add New Address</a>
					</div>
				</div>
			</div>
			
			<?php 
			} 
			?>
					<!--Order Summary-->
					<?php 
					if($price!="0.00" && $price!="") 
					{ 
					$price=number_format((float)$price, 2, '.', '');
					//$ShipingProductAmt=$objProductManager->getShipingAmt();
					$OrderProductTotalPrice=$price;
					?>
					<div class="col-sm-4">
						<div class="summary-box m-bottom30 bg-gray">
							<h3 class="text-center padding20 summary-boxtitle">Order Summary</h3>
							<table class="table m-bottom0">
								<tbody>
									<tr class="">
										<th scope="row">Cart Subtotal</th>
										<td class="text-right"><span class="glyphicon glyphicon-euro"></span><?php if(isset($OrderProductTotalPrice)) echo $OrderProductTotalPrice; ?></td>
									</tr>
									
									<tr>
										<th scope="row">Packing & Shipping Amt</th>
										<td class="text-right text-muted">
											<small><span class="glyphicon glyphicon-euro"></span> <span id="SHIPPING_AMT">0.00</span></small>
										</td>
									</tr>
									<tr id="vatRow">
										<th scope="row">VAT (19%)</th>
										<td class="text-right text-muted">
											<small><span class="glyphicon glyphicon-euro"></span> <span id="VAT_AMT">0.00</span></small>
										</td>
									</tr>
									
									
									<tr>
										<th scope="row">Grand Total</th>
										<td>
											<h4 class="price text-right"><span class="glyphicon glyphicon-euro"></span><span id="GrandTotalPrice"><?php if(isset($OrderProductTotalPrice)) echo $OrderProductTotalPrice; ?></span></h4>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<?php if($action!="AddAddress") { ?>
						 <form action="cart-checkout.php?urlstring=<?php echo EncryptURL('action=PaymentMethod'); ?>" name="checkOut" id="checkOut" method="post">
							<input type="hidden" name="current_address" id="current_address" />
							<input type="hidden" name="billing_address" id="billing_address" />
							<input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id;?>" />
							<input type="hidden" name="OrderProductTotalPrice" id="OrderProductTotalPrice" value="<?php echo  $OrderProductTotalPrice;?>" />
							<input type="hidden" name="shiping_amt" id="shiping_amt" value="" />
							<input type="hidden" name="vat_amt" id="vat_amt" value="" />
							<input type="hidden" name="company_name" id="company_name" value="" />
							<input type="hidden" name="total_order_amt" id="total_order_amt" value="<?php echo $OrderProductTotalPrice;?>" />
						   <input class="btn btn-primary btn-xlg m-bottom20" type="submit" value="Place Order" onClick="return validateAddress()" />
						   <a href="products.php"> <input class="btn btn-border btn-xlg"  type="button" value="Continue Shopping" /></a>
						 </form>
						 <?php } ?>
					</div>
					<script language="javascript">
					function validateAddress() 
					{
						address=document.getElementById("addressStr").value;
						if(address=="")
						{
						  alert("Kindly add address");
						  return false;
						}
						else
						{
								var CheckBoxAddress=document.getElementsByName("address");
								var is_true_flag=false;
								var AddressValue='';
								for(var i = 0; i < CheckBoxAddress.length; i++)
								{
									if(CheckBoxAddress[i].checked)
									{
									   AddressValue=CheckBoxAddress[i].value;
									   is_true_flag=true;
									}
								}
								////////////////////billing Address
								var CheckBoxAddressBilling=document.getElementsByName("addressBilling");
								var is_true_flag_billing=false;
								var AddressValueBilling='';
								for(var i = 0; i < CheckBoxAddressBilling.length; i++)
								{
									if(CheckBoxAddressBilling[i].checked)
									{
									   AddressValueBilling=CheckBoxAddressBilling[i].value;
									   is_true_flag_billing=true;
									}
								}
								
								if(is_true_flag==false || is_true_flag_billing==false)
								{
									
									alert("Kindly select the delivery address and billing address");
									return false;
								}
								
								document.document.getElementsByName("current_address").value=AddressValue;
								document.document.getElementsByName("billing_address").value=is_true_flag_billing;
								return is_true_flag;
						}
					}
					</script>
					<?php } 
			}	
			else if($action=="PaymentMethod")
			{
				//echo"<pre>";print_r($_POST);die;
			?>	
			<script language="javascript">
			function validatePaymentMethod() 
			{
			   var is_true_flag=false;
			   var methods=document.getElementById("methodStr").value.split(",");
				for(var j = 0; j < methods.length; j++)
				{
					var unique=methods[j];
					if(document.getElementById("payMethod_"+unique))
					{
						if(document.getElementById("payMethod_"+unique).checked==true)
						{
						   var methodName=unique;
						   is_true_flag=true;
						}
					}
				}
				if(is_true_flag==false)
				{
				  alert("Kindly select the payment method");
				  return false;
				}
				 document.getElementById("paymentMethod").value=methodName;
				 return is_true_flag;
			}
			</script>
				<div class="col-sm-12">
					<div class="sectionTitle p-bottom40">
						<h2>Payment</h2>
					</div>
					<div class="stepwizard">
						<div class="stepwizard-row">
							<div class="stepwizard-step" style="width:5%">
								<a href="https://sinelec-tech.com/website/cart.php"><button type="button" class="btn-danger button-circle">1</button></a>
								<p>Cart</p>
							</div>
							<div class="stepwizard-step" style="width:95%">
								 <a href="cart-checkout.php?urlstring=<?php echo EncryptURL('action=&OrderTotalAmt='.$_POST['total_order_amt']); ?>"><button type="button" class="btn-danger button-circle">2</button></a>
								<p>Address</p>
							</div>
							<div class="stepwizard-step" style="width:0%">
								<button type="button" class="btn-success button-circle">3</button>
								<p>Payment</p>
							</div>
						</div>
					</div>		
				</div>
			 <div class="col-sm-12">
				<div class="summary-box m-bottom30 bg-gray">
					<h3 class="text-center padding20 summary-boxtitle">Payment Methods</h3>
					<table class="table m-bottom0">
						<tbody>
							<?php 
							
							if($_POST['current_address']=="")
								$_POST['current_address']=$_SESSION['current_address'];
							if($_POST['OrderProductTotalPrice']=="")
								$_POST['OrderProductTotalPrice']=$_SESSION['OrderProductTotalPrice'];
							if($_POST['shiping_amt']=="")
								$_POST['shiping_amt']=$_SESSION['shiping_amt'];
							if($_POST['vat_amt']=="")
								$_POST['vat_amt']=$_SESSION['vat_amt'];
							if($_POST['total_order_amt']=="")
								$_POST['total_order_amt']=$_SESSION['total_order_amt'];
							
							if($_POST['billing_address']=="")
								$_POST['billing_address']=$_SESSION['current_address'];	
								
								
								
							$paymentMethodArray=array(/*"Paypal"=>"Paypal",*/"PaypalPlus"=>"Paypal","BankTransfer"=>"Bank Transfer (Payment in Advance)","Invoice"=>"Invoice");			//"CreditCard"=>"Credit Card",				
							//echo "<pre>";print_r($user_id);162	
							
							if($_POST['current_address']!='')
							{
								$userAddressId = $_POST['current_address'];
								$arrUserAddressVatInfo=$objProductManager->GetUserAddressVatInfo($userAddressId);
								
								if(count($arrUserAddressVatInfo)>0)
								{
									$euVatNo = $arrUserAddressVatInfo[0]->EU_VAT;
								}
								else
								{
									$euVatNo = '';
								}
							}
							
							$methodStr="";
							foreach($paymentMethodArray as $key=>$val)
							{ 
								$methodStr=$methodStr.",".$key;
								?>
								<tr>
									<td class="text-right text-muted">
										<input type="radio" name="payMethods" id="payMethod_<?php echo $key ;?>" <?php if($euVatNo=="" && $key=='Invoice') echo 'disabled="disabled"'; ?> style="float:left;" onClick="paymentMethodFun('payMethod_<?php echo $val->COUNTRY_ID ;?>')">
									</td>
                                    									
									<th scope="row">&nbsp;<?php echo $val;?> </th>
									<th scope="row"><?php /*if($key=='Paypal') { ?><img src="images/paypal1.png" ><?php } else */ if($key=='PaypalPlus' /*&& $user_id=='162'*/) { ?><img src="images/paypal1.png" ><?php } else if($key=='CreditCard') { ?><img src="images/paypal1.png" ><?php } else if($key=='BankTransfer') { ?><img src="images/Bank-Transfer.png" ><?php } else if($key=='Invoice'){ ?><img src="images/Invoice.png" ><?php }?></th>
								</tr>
							&nbsp;
							<?php } $methodStr=ltrim($methodStr,",");?>
							<tr>
								<td> <a href="products.php"> <input class="btn btn-border btn-xlg"  type="button" value="Continue Shopping" /></a></td>
								<td>&nbsp;</td>
								<td>
									 <form action="cart-checkout.php?urlstring=<?php echo EncryptURL('action=checkOut'); ?>" name="checkOut" id="checkOut" method="post">
										<input type="hidden" name="current_address" id="current_address" value="<?php echo $_POST['current_address'];?>" />
										<input type="hidden" name="billing_address" id="billing_address" value="<?php echo $_POST['billing_address'];?>" />
										<input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id;?>" />
										<input type="hidden" name="OrderProductTotalPrice" id="OrderProductTotalPrice" value="<?php echo $_POST['OrderProductTotalPrice'];?>" />
										<input type="hidden" name="shiping_amt" id="shiping_amt" value="<?php echo $_POST['shiping_amt'];?>" />
										<input type="hidden" name="vat_amt" id="vat_amt" value="<?php echo $_POST['vat_amt'];?>" />
										<input type="hidden" name="paymentMethod" id="paymentMethod" value="" />
										<input type="hidden" name="total_order_amt" id="total_order_amt" value="<?php echo $_POST['total_order_amt'];?>" />
									   <input class="btn btn-primary btn-xlg m-bottom20" type="submit" style="font-size:14px" value="Click to pay Amount <?php echo $_POST['total_order_amt'];?>" onClick="return validatePaymentMethod()" />
									 </form>
								 </td>
							</tr>
						</tbody>
						<input type="hidden" name="methodStr" id="methodStr" value="<?php echo $methodStr;?>" />
					</table>
				</div>
			</div>
			<?php
				$_SESSION['current_address']=$_POST['current_address'];
				$_SESSION['OrderProductTotalPrice']=$_POST['OrderProductTotalPrice'];
				$_SESSION['shiping_amt']=$_POST['shiping_amt'];
				$_SESSION['vat_amt']=$_POST['vat_amt'];
				$_SESSION['total_order_amt']=$_POST['total_order_amt'];
			}
			else if($action=="AddAddress"){
			if(isset($paramsArray['addressId'])){
			$user_address_id=$paramsArray['addressId'];
			$userAddress=$objUserManager->UpdateuserAddress($user_address_id);
			//echo "<pre>";print_r($userAddress);die;
			}
			//echo "<pre>";print_r($countryList);die;
			?>
			 <body onLoad="showHideVatNo('');"></body>
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
			
			
			<div class="col-sm-8">
			
				 <div class="bg-gray p-bottom30 border">
				 <input type="radio" checked="checked" >Add New Address
					 <div class="form text-left">
						 <form class="clearfix  bg-gray p-top30" action="cart-checkout.php?urlstring=<?php echo EncryptURL('action=Insert'); ?>" method="post" enctype="multipart/form-data">
						
							<div class='form-row'>
							  <div class='col-xs-6 form-group required'>
								<label class='control-label'>Name <span style="color:red">*</span></label>
								<input  type="text" name="Name" id="Name" value="<?php if(isset($userAddress)) echo $userAddress[0]->USER_NAME;  ?>" required>
								<input type="hidden" name="USER_ADDRESS_ID" id="USER_ADDRESS_ID" value="<?php if(isset($userAddress)) echo $userAddress[0]->USER_ADDRESS_ID;  ?>">
							  </div>
							  <div class='col-xs-2 form-group required'>
							  <label class='control-label'>ISD<span style="color:red">*</span></label>
							<select name="phone_country_code" id="phone_country_code"  style="font-size:12px;text-align:left" required>
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
							  <div class='col-xs-4 form-group required'>
								<label class='control-label'>Mobile Number<span style="color:red">*</span></label>
								<input  type="text" name="Phone" id="Phone" value="<?php if(isset($userAddress)) echo $userAddress[0]->DELIVERY_PHONE_NO;?>" required>
							  </div>
							</div>
							<div class="form-row">
							  <div class="col-xs-12 form-group required">
								<label class="control-label">Company Name</label>
								<input type="text"  name="companyName" id="companyName" class="col-xs-12" value="<?php if(isset($userAddress)) echo $userAddress[0]->COMPANY_NAME;  ?>">
							  </div>
						  </div>
							<div class="form-row">
							  <div class="col-xs-12 form-group required">
								<label class="control-label">Address<span style="color:red">*</span></label>
								<textarea rows="6"  name="Address" id="Address" class="col-xs-12" required><?php if(isset($userAddress)) echo $userAddress[0]->ADDRESS;  ?></textarea>
							  </div>
						  </div>
							<div class="form-row">
							  <div class="col-xs-6 form-group  required">
								<label class="control-label">City/District/Town<span style="color:red">*</span></label>
								<input class="card-expiry-month" placeholder="City/District/Town"  type="text" name="City" id="City" value="<?php if(isset($userAddress)) echo $userAddress[0]->CITY;  ?>" required>
							  </div>
							  <div class="col-xs-6 form-group  required">
								<label class="control-label">State/Province<span style="color:red">*</span></label>
								<input class="card-expiry-year" placeholder="State" name="State" id="State" type="text" value="<?php if(isset($userAddress)) echo $userAddress[0]->STATE;  ?>" required>
							  </div>
							</div>
							<div class='form-row'>
							  <div class='col-xs-6 form-group card required'>
								<label class='control-label'>ZIP Code<span style="color:red">*</span></label>
								<input type="text" name="ZIP" id="ZIP" value="<?php if(isset($userAddress)) echo $userAddress[0]->ZIP;  ?>" required>
							  </div>
							  <div class='col-xs-6 form-group card required'>
							  
								<label class="control-label">Country<span style="color:red">*</span></label>
								<select class="card-expiry-month" placeholder="Country"  type="text" name="Country" id="Country" onChange="showHideVatNo(this.value)" required >
								<option  value=""  >Select Country</option>
								<?php 
								if(count($countryList)>0){
								foreach($countryList as $country){ ?> 
								<option  value="<?php echo $country->COUNTRY_ID.'@_@'.$country->COUNTRY; ?>"  <?php if(isset($userAddress) && $userAddress[0]->COUNTRY_ID==$country->COUNTRY_ID) echo 'selected';  ?>><?php echo $country->COUNTRY; ?></option>
								<?php } }?>
								</select>
							  </div>
							</div>
							  <div class="form-row" id="vatNo">
								  <div class="col-xs-6 form-group  required">
									<label class="control-label">EU VAT Number</label>
									<input placeholder="EU VAT Number" type="text" name="EU_VAT" id="EU_VAT" value="<?php if(isset($userAddress)) echo $userAddress[0]->EU_VAT; ?>" >
								 </div>
								  <div class="col-xs-6 form-group  required">
									<span style="color:#FF0000" id="note">Note :-If your company is situated in EU but outside of Germany  then by providing your company name and a Valid EU VAT Number you can shop VAT free from us.If you provide Invalid EU VAT Number , your order will be cancelled !!!.</span>
								 </div>
							</div>
							<div class="form-row">
							  <div class="col-xs-12 form-group  required">
								<label class="control-label">Landmark</label>
								<input class="card-expiry-month" placeholder="landmark" type="text" name="landmark" id="landmark" value="<?php if(isset($userAddress)) echo $userAddress[0]->LANDMARK;  ?>"> 
							  </div>
							  
							</div>
						<div class="form-row">
							  <div class="col-sm-12 col-xs-12 col-md-12 form-group  required">
								<button class="btn btn-primary btn-xlg col-sm-12 col-xs-12 col-md-12 center-block m-top30" onClick=" return confirm ('Are You Sure you want to Save it?\n Click OK to Continue, Cancel to Stop'),ValidateForm();">Save</button>
							  </div>
							  
							</div>
							
							<div class="clearfix"></div>
							
						</form>
						<script>
						var country = ["11","18","28","45","48","50","59","62","63","68","70","71","80","86","88","99","105","106","113","120","128","143","144","148","162","163","169","174"];
						function inArray(needle, haystack)
						 {
							var length = haystack.length;
							for(var i = 0; i < length; i++) {
								if(haystack[i] == needle) return true;
							}
							return false;
						 }
						function showHideVatNo(cId)
						{
							var fields = cId.split('@_@');
							var countryId = fields[0];
							var countryName = fields[1];
							
							if(countryId=="")
							{
								cId=document.getElementById('Country').value;
								var fields = cId.split('@_@');
								var countryName = fields[0];
								var countryId = fields[1];
							}	
							
							if (inArray(countryId,country))
							{
								document.getElementById("EU_VAT").readOnly = false; 
							}	
							else
							{
								document.getElementById('EU_VAT').value="";
								document.getElementById("EU_VAT").readOnly = true; 
							}					
						}
						</script>
					</div>
				</div>
			</div>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
$("#EU_VAT").click(function(){
	$("#note").toggle("display", "");
});
});
</script>
-->				<?php }
			?>
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