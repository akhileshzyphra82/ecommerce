<?php
ini_set('display_errors',0);
//error_reporting(E_ALL | E_STRICT);
//require_once ('Common.php');
require_once("../admin/UI/Includes/Functions.php");
require_once ('../admin/BL/ProductManager.php');
//echo "AkkiPaypall<pre>";print_r($_REQUEST);
if($_REQUEST['PayerID']!= '') 
{
	$paymentId= $_REQUEST['paymentId'];
	$token = $_REQUEST['token']; // Paypal token ID
	$PayerID= $_REQUEST['PayerID']; // Paypal PayerID
	$access_token= $_REQUEST['access_token']; // Paypal access_token
	$orderId= $_REQUEST['OrderId']; // Paypal access_token
	//$currency='USD';
	$url='https://api.paypal.com/v1/payments/payment/'.$paymentId.'/execute';
	$JSONrequest='{ "payer_id" : "'.$PayerID.'" }'; 
	//var_dump($JSONrequest);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	//curl_setopt($ch, CURLOPT_SSLCERT, $sslcertpath);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Authorization: Bearer '.$access_token
		));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $JSONrequest);
	
	$result = curl_exec($ch); 
	$result1 = json_decode($result,true);
	//echo "<pre>";print_r($statusCode);
	$statusCode=$result1['state'];
	$item_transaction=$result1['id'];
	if($statusCode=='approved')
	{
		$status="Payment Successful"; 
	}	
	else
	{
		$status="Payment Failed";
		$objProductManager = new ProductManager(); 
		$ProductsDetails=$objProductManager->UpdateProductPaymentStatus($orderId,$item_transaction,$status);
		header("Location:../website/OrderDetails.php?status=$status");
	}
		
	curl_close ($ch);
	//print_r($result1);die;
	$objProductManager = new ProductManager(); 
	$objProductManager->UpdateProductPaymentStatus($orderId,$item_transaction,$status);
	$orderDetails= $objProductManager->GetOrderDetails($orderId);
	//echo "<pre>";print_r($orderDetails); die;
	if(count($orderDetails)>0)
	{
		
		if(empty($_SESSION))
		{
		
			///////after session set///
			//echo "<pre>";print_r($_SESSION); 
			//echo "after session is not set.."; 
			
			session_start();
			$_SESSION["CUSTOMER_EMAIL"] = $orderDetails[0]->COMMUNICATION_EMAIL_ID;
			$_SESSION["CUSTOMER"] = $orderDetails[0]->NAME;
			$_SESSION["CUSTOMER_ID"] = $orderDetails[0]->USER_ID;
			$_SESSION["USER_TYPE_ID_WEBSITE"] = 2;
			
			
		}
		
		
	
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

		include "../admin/smtpmail/classes/class.phpmailer.php"; // include the class name
		$toEmailID = trim($orderDetails[0]->COMMUNICATION_EMAIL_ID);
		
		$subject = 'Payment Successful: Order Detail Sinelec Tech';
		
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
						<font size="+1"><strong><u>Customer Billing Address:</u></strong> <br/>
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
						<strong>BIC:</strong> BYLADEM1FSI </font>

					</td>
				  </tr>
				</table>
				<br/><br/>

		';

		$message.='
				<table width="100%" border="1" cellpadding="5">
					<tr bgcolor="#5B9BD5">
						<td width="5%">
							<font color="#000000"><strong>Sl</strong></font>
						</td>
						<td width="65%">
							<font color="#000000"><strong>Product</strong></font>
						</td>
						<td width="8%">
							<font color="#000000"><strong>Quantity</strong></font>
						</td>
						<td width="8%">
							<font color="#000000"><strong>Unit Price</strong></font>
						</td>
						<td width="14%" align="center">
							<font color="#000000"><strong>Total in EURO</strong></font>
						</td>
					</tr>
			';
		$sno=1;
		$totalAmount=0;
		$WithOutVat=0;
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
					<br/><br/>
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
							We thank you for your purchase. We will dispatch the goods immediately.
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
			
			//$emailArray=array($orderDetails[0]->COMMUNICATION_EMAIL_ID,'sales@sinelec-tech.com');
			
			//foreach($emailArray as $email)
			//{
				$host = "box5213.bluehost.com";
				$userName = "web@sinelec-tech.com";
				$password = "{Ge-[]sE(wq,";
				$fromname = "sales@sinelec-tech.com";
				$from = 'sales@sinelec-tech.com';
		   
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
				$mail->Body = $userMessage.$message.$fotter;
				$mail->AddAddress($toEmailID);
				$mail->AddAddress($fromname); //send to mail id
				$mail->Send();
			//}	
			//ob_clean();
			header("Location:../website/OrderDetails.php?status=$status");
	}
	else
	{
		//ob_clean();
		header("Location:../website/OrderDetails.php?status=$status");
	}
}
?>