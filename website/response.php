<?php
ini_set("display_errors",0);
//ob_start();
require_once ('Common.php');
require_once ('../admin/BL/ProductManager.php');
//echo "<pre>";print_r($_GET);
//echo "AkkResposnse<pre>";print_r($_REQUEST);die;
$type = $_GET['type'];
if($type == 'success') {
	$orderId            = $_REQUEST['item_number'];
	$item_transaction   = $_REQUEST['item_name']; // Paypal transaction ID
	$item_price         = $_REQUEST['amount']; // Paypal received amount
	$item_currency      = $_REQUEST['currency_code']; // Paypal received currency type
	$shiping_amount     = $_REQUEST['shiping_amount'];
	//$currency='USD';
	
	$status="Payment Successful"; 
	$objProductManager = new ProductManager(); 
	$objProductManager->UpdateProductPaymentStatus($orderId,$item_transaction,$status);
	$ProductsDetails= $objProductManager->GetOrderDetails($orderId);
	//////////////////billing Address////////////////////////////
						
		$billingAdress=$ProductsDetails[0]->BILLING_ADDRESS;
		if($billingAdress=='')
			$billingAdress=$ProductsDetails[0]->ADDRESS;
			
		$billingCity=$ProductsDetails[0]->BILLING_CITY;
		if($billingCity=='')
			$billingCity=$ProductsDetails[0]->CITY;	
			
		$billingState=$ProductsDetails[0]->BILLING_STATE;
		if($billingState=='')
			$billingState=$ProductsDetails[0]->STATE;		
		
		$billingZip=$ProductsDetails[0]->BILLING_ZIP;
		if($billingZip=='')
			$billingZip=$ProductsDetails[0]->ZIP;		
	
		$billingCountry=$ProductsDetails[0]->BILLING_COUNTRY;
		if($billingCountry=='')
			$billingCountry=$ProductsDetails[0]->COUNTRY;	
				
		$billingLadmark=$ProductsDetails[0]->BILLING_LANDMARK;
		if($billingLadmark=='')
			$billingLadmark=$ProductsDetails[0]->LANDMARK;		
		
		$billingPhoneNo=$ProductsDetails[0]->BILLING_DELIVERY_PHONE_NO;
		if($billingPhoneNo=='')
			$billingPhoneNo=$ProductsDetails[0]->DELIVERY_PHONE_NO;		

		$billingCountryCode=$ProductsDetails[0]->BILLING_MOBILE_COUNTRY_CODE;
		if($billingCountryCode=='')
			$billingCountryCode=$ProductsDetails[0]->MOBILE_COUNTRY_CODE;		

		$billingUser=$ProductsDetails[0]->BILLING_COMPANY_NAME;
		if($billingUser=='')
			$billingUser=$ProductsDetails[0]->COMPANY_NAME;	
		
		if($billingUser=='')
			$billingUser=$ProductsDetails[0]->BILLING_USER_NAME;
			
		if($billingUser=='')
			$billingUser=$ProductsDetails[0]->USER_NAME;			
			
		$billingVat=$ProductsDetails[0]->BILLING_EU_VAT;
		if($billingVat=='')
			$billingVat=$ProductsDetails[0]->EU_VAT;
	  //echo "<pre>";print_r($ProductsDetails);
		if(count($ProductsDetails)>0)
		{
					include "../admin/smtpmail/classes/class.phpmailer.php"; // include the class name
					$toEmailID = trim($ProductsDetails[0]->COMMUNICATION_EMAIL_ID);
					
					$message="";
	               
					$subject = 'Payment Successful: Order Detail Sinelec Tech';
					$message = '<strong>Dear ' . $ProductsDetails[0]->NAME . ',</strong><br/><br/>
								Kindly find your order details below:<br/><br/>
								<table rules="all" style="border-color: #666;" cellpadding="10">
								<tr  style="background: #eee;"> 
								<td><b>Order Id: '.$ProductsDetails[0]->ORDER_ID.'</b></td></tr>
								<tr  style="background: #eee;"> <td><b>Transaction Id: '.$ProductsDetails[0]->TRANSACTION_ID.'</b></td></tr>
								<tr  style="background: #eee;"> <td><b>Order Date: '.$ProductsDetails[0]->ORDER_DATE.'</b></td></tr>
								<tr  style="background: #eee;"> <td><b>Shipping Amt: '.$ProductsDetails[0]->SHIPING_AMT.'</b></td></tr>
								<tr  style="background: #eee;"> <td><b>Total Amt: '.$ProductsDetails[0]->ORDER_TOTAL_AMT.'</b></td></tr>
								<tr  style="background: #eee;"> <td><b>Order Status: '.$ProductsDetails[0]->ORDER_CURRENT_STATUS.'</b></td></tr>
								<tr  style="background: #eee;"> <td><b>Delivery Address: '.$ProductsDetails[0]->ADDRESS.', '.$ProductsDetails[0]->CITY.', '.$ProductsDetails[0]->STATE.'-'.$ProductsDetails[0]->ZIP.', '.$ProductsDetails[0]->COUNTRY.'</b></td></tr>
								<tr  style="background: #eee;"> <td><b>Delivery Ph. No.: '.$ProductsDetails[0]->DELIVERY_PHONE_NO.'</b></td>
								</tr>
								<tr  style="background: #eee;"> <td><b>Delivery Address: '.$billingAdress.', '.$billingCity.', '.$billingState.'-'.$billingZip.', '.$billingCountry.'</b></td></tr>
									<tr  style="background: #eee;"> <td><b>Delivery Ph. No.: '.$billingPhoneNo.'</b></td>
									</tr>
								
								</table>'.$message.
							'<br/><br/>
							<strong>Note:</strong> Kindly do not reply to this email as this is an auto generated email from Sinelec. For any query kindly contact sales@sinelec-tech.com';
	                //  echo "<pre>";print_r($message);die;
				  
						$host = "box5213.bluehost.com";
						$userName = "web@sinelec-tech.com";
						$password = "{Ge-[]sE(wq,";
						$fromname = "sales@sinelec-tech.com";
						$from = 'sales@sinelec-tech.com';
				   
						ob_clean();
						$mail = new PHPMailer(); // create a new object
						$mail->IsSMTP(); // enable SMTP
		             //  $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
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
						$mail->AddAddress($toEmailID);
						$mail->AddAddress($fromname); //send to mail id
						$messageCompleted = 1;
						$mail->Send();
						
		}
	
	
	header("location:http://www.sinelec-tech.com/website/OrderDetails.php?status=$status");

} 
 
if($type == 'cancel') {
	$orderId            = $_REQUEST['item_number'];
	$item_transaction   = $_REQUEST['item_name']; // Paypal transaction ID
	$status="Payment Failed";
	$objProductManager = new ProductManager(); 
	$ProductsDetails=$objProductManager->UpdateProductPaymentStatus($orderId,$item_transaction,$status);
	header("location:http://www.sinelec-tech.com/website/OrderDetails.php?status=$status");
}
?>