<?php
ob_start();
ini_set('display_errors',0);
//error_reporting(E_ALL | E_STRICT);
include('../Common.php');
include('../Includes/Functions.php');
require_once ('../../UI/Config/inc_path.php');
require_once ('../../BL/HomeManager.php');
require_once ('../../BL/ProductManager.php');
require_once ("../../smtpmail/classes/class.phpmailer.php"); // include the class name

$date=date('Y-m-d');
$objHomeManager=new HomeManager();
$objProductManager = new ProductManager(); 

$type = 'Active';

$paramsArray = GetQueryStringParameters();
(isset($paramsArray['action']))? $action=$paramsArray['action'] : $action="";
isset($paramsArray["msg"]) ? $msg=$paramsArray["msg"] : $msg="";
isset($paramsArray["page"]) ? $page=$paramsArray["page"] : $page="1";
isset($paramsArray["start_from"]) ? $msg=$paramsArray["start_from"] : $start_from="";
isset($paramsArray["limit"]) ? $msg=$paramsArray["limit"] : $limit="";
switch($action)
{
	case 'UpdateStatus':
		//echo "<pre>";print_r($_POST); die;
		$order_status =$_POST["table_search"];
		$order_id =$_POST["order_id"];
		$referenceStatus=$_POST["referenceStatus"];
		$dispatch_courier_company=$_POST["dispatch_courier_company"];
		$dispatch_courier_tracking_id=$_POST["dispatch_courier_tracking_id"];
		$dispatch_courier_tracking_url=$_POST["dispatch_courier_tracking_url"];
		//UpdateOrderStatusByOrderId($order_status,$order_id,$dispatch_courier_company, $dispatch_courier_tracking_id, $dispatch_courier__tracking_url)

		if($order_status=='Delete Order')
		{
			if($objHomeManager->DeleteOrderByOrderId($order_id))
			{
				$fileInvoice = glob("../Attachments/*".$order_id.".pdf");
				//echo '<pre>'; print_r($fileInvoice[0]); die;
				unlink($fileInvoice[0]);
				header("Location:ViewOrderDetailsNew.php?urlstring=".EncryptURL("action=&msg=delete&OrderNo=".$order_id));
			}
			else
			{
				header("Location:ViewOrderDetailsNew.php?urlstring=".EncryptURL("action=&msg=error"));
			}	
				
		}
		else
		{
			$arrResult = $objHomeManager->UpdateOrderStatusByOrderId($order_status,$order_id,$dispatch_courier_company, $dispatch_courier_tracking_id, $dispatch_courier_tracking_url);
			//echo '<pre>'; print_r($arrResult); die;
			if(count($arrResult)>0)
			{
				$in=1;
				foreach($arrResult as $arrResultVal)
				{
					$mailOb = $mail.$in;
					$host = "box5213.bluehost.com";
					$userName = "web@sinelec-tech.com";
					$password = "{Ge-[]sE(wq,";
					$fromname = "alert@sinelec-tech.com";
					$from = 'alert@sinelec-tech.com';
					$email = 'sales@sinelec-tech.com';
					
					$mailOb = new PHPMailer(); // create a new object
					$mailOb->IsSMTP(); // enable SMTP
					$mailOb->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
					$mailOb->SMTPAuth = true; // authentication enabled
					$mailOb->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
					$mailOb->Host = $host;
					$mailOb->Port = 465; // 465 or 587
					$mailOb->IsHTML(true);
					$mailOb->Username = $userName;
					$mailOb->Password = $password;
					$mailOb->FromName = $fromname;
					$mailOb->From = $from;         
					$subject = 'Alert: Threshold of product '.$arrResultVal['product_name'].' has fallen';    
					$mailOb->Subject = $subject;
				
					$messageBody = 
					'Product ID: '.$arrResultVal['product_id'].'<br>'.
					'Product Name: '.$arrResultVal['product_name'].'<br>'.
					'Threshold Value: '.$arrResultVal['product_threshold'].'<br>'.
					'Remaining Product: '.$arrResultVal['product_remaining'];
				
					$mailOb->Body = $messageBody;
				
					$mailOb->AddAddress($email); //send to mail id
					//echo "<pre>";print_r($mail);
					if (!$mailOb->Send()) 
					{
						//header("Location:ViewOrderDetailsNew.php?urlstring=".EncryptURL("action=&msg=update&OrderNo=".$order_id));
					} 
					$in++;
				}
			}
			
			if(($order_status=='Dispatched' || $order_status=='Dispatched Invoice Payment Pending') && $dispatch_courier_company!='' && $dispatch_courier_tracking_id!='' && $dispatch_courier_tracking_url!='')
			{
				$fileInvoice = glob("../Attachments/*".$order_id.".pdf");
				$fileDir = $fileInvoice[0];
				list($aaa,$bbb,$fileName)=explode("/",$fileDir);
				$attachFile='https://sinelec-tech.com/admin/UI/Attachments/'.$fileName;
				$attachFile='https://www.sinelec-tech.com/website/view.php?urlstring='.EncryptURL('fileName='.$fileName);
				$orderDetails=$objProductManager->GetOrderDetails($order_id);
				
				if(count($orderDetails)>0)
				{
					if($orderDetails[0]->COMMUNICATION_EMAIL_ID!='')
					{
						
						$toEmailID = trim($orderDetails[0]->COMMUNICATION_EMAIL_ID);
						$subject = 'Dispatch Details: Sinelec Tech';
						
						if($orderDetails[0]->COMPANY_NAME=='')
						{
							$userName = $orderDetails[0]->USER_NAME;
						}
						else
						{
							$userName = $orderDetails[0]->COMPANY_NAME;
						}
	
						$userMessage='
							Dear '.$orderDetails[0]->USER_NAME.',<br/> <br/> 
							A package has just been sent to you from our German warehouse with <strong>'.$dispatch_courier_company.'</strong> parcel service.<br/><br/>
							The following information is available for this shipment:<br/><br/>
							
							Your Order and Invoice number : '.$orderDetails[0]->ORDER_ID.'<br/>
							The shipment is made to <br/>
							<strong>'.$userName.'</strong>  <br/> 
							<strong>Address: </strong>'.$orderDetails[0]->ADDRESS.'  <br/> 
							<strong>City: </strong>'.$orderDetails[0]->CITY.', <strong>State: </strong>'.$orderDetails[0]->STATE.'  <br/>
							<strong>Country: </strong>'.$orderDetails[0]->COUNTRY.', <strong>Zip No: </strong>'.$orderDetails[0]->ZIP.'  <br/>
							<strong>VAT No: </strong>'.$orderDetails[0]->EU_VAT.'    
							
							<br/> <br/>
							<strong>Mobile Number:</strong>'.$orderDetails[0]->COMMUNICATION_MOBILE_NUM_ISD.' '.$orderDetails[0]->COMMUNICATION_MOBILE_NUM.'<br/> 
							<strong>Email Id: </strong>'.$orderDetails[0]->COMMUNICATION_EMAIL_ID.'
							
							<br/> <br/> 
							You can track the delivery status online using the following link:<br/> <br/> 
							Your parcel tracking number: <strong>'.$dispatch_courier_tracking_id.'</strong><br/> 
							Parcel Tracking Link : '.$dispatch_courier_tracking_url.'
							
							<br/> <br/> 
							You can find more information on Sinelec Technologies Group at www.sinelec-tech.com
							
							<br/> <br/> 
							We are happy to fulfil your order and look forward to your next order.<br/><br/>
							
							<strong>Invoice</strong><br/>
							<strong>Attachment:</strong> <a href='.$attachFile.' target="_blank">'.rtrim($fileName,')').'</a>
							
							<br/> <br/> 
							Yours sincerely <br/> 
							Your Sinelec Technologies Team					
						';
						
						//echo "<pre>";print_r($userMessage);die;
						
						$emailArray=array($orderDetails[0]->COMMUNICATION_EMAIL_ID,'sales@sinelec-tech.com');
					
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
							
							$mail->Body = $userMessage;
		
							$mail->AddAddress($email); //send to mail id
							//echo "<pre>";print_r($mail);
							if (!$mail->Send()) 
							{
								header("Location:ViewOrderDetailsNew.php?urlstring=".EncryptURL("action=&msg=update&OrderNo=".$order_id));
							} 
							else 
							{
								header("Location:ViewOrderDetailsNew.php?urlstring=".EncryptURL("action=&msg=error"));
							}
						}
					}
				}
				
				header("Location:ViewOrderDetailsNew.php?urlstring=".EncryptURL("action=&msg=update&OrderNo=".$order_id));
			}
			elseif($order_status=='Cancel Order')
			{
				$orderDetails=$objProductManager->GetOrderDetails($order_id);
				//echo "<pre>";print_r($paramsArray); die;
				
				if(count($orderDetails)>0)
				{
				
					if($orderDetails[0]->COMPANY_NAME=='')
					{
						$userName = $orderDetails[0]->USER_NAME;
					}
					else
					{
						$userName = $orderDetails[0]->COMPANY_NAME;
					}
					
					$invoiceNo='';
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
					
					if($orderDetails[0]->COMMUNICATION_EMAIL_ID!='')
					{
						$toEmailID = trim($orderDetails[0]->COMMUNICATION_EMAIL_ID);
						$subject = 'Order Canceled - Sinelec Tech - Order Id '.$orderDetails[0]->ORDER_ID;
						
						$userMessage='
							Hello '.$orderDetails[0]->USER_NAME.',<br/> <br/> 
							Order Id '.$orderDetails[0]->ORDER_ID.'. has been canceled as requested. <br/> <br/> 
							Kindly find the order details below:<br/> <br/> 
							
						';
						$message='
							<table width="100%" border="0" cellpadding="5">
							  <tr>
								<td width="100%">
									<img src="https://sinelec-tech.com/website/images/Logo.png" alt="logo" width="137" height="39"><br/>
									Sinelec Technologies Deutschland GmbH, Brachvogelweg 9, 85375 Neufahrn, Germany
								</td>
							  </tr>
							  <tr>
								<td width="100%" align="center">
									<font size="+2"><strong><u>Canceled Order</u></strong></font>
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
							//$WithOutVat=0;
							foreach($orderDetails as $order)
							{
								$message.='
								<tr>
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
							<br/><br/><br/>
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
									</td></font>
							  </tr> 							
							  <tr>
								<td>
								<font size="+1">
								We thank you for your purchase. Please transfer the Invoice amount in the above given German bank account. Once amount is received we will dispatch the goods immediately.
								</font>
								</td>
							  </tr>
							</table>
							';
						
						//echo "<pre>";print_r($message);die;
						$fileNamePdf = $invoicePreString.'Invoice-'.$orderDetails[0]->ORDER_ID;
						require_once "../../../website/tcpdf/tcpdf.php";
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
						$filePath='../Attachments/';
						$uploadFile= $filePath.$fileNamePdf.'.pdf';
							
						if(file_exists($uploadFile))
						{
							@unlink($uploadFile);
						}
						$pdf->Output($uploadFile, 'F');
						
						$attachFile='https://sinelec-tech.com/admin/UI/Attachments/'.$fileNamePdf.'.pdf';
						$attachFile='https://www.sinelec-tech.com/website/view.php?urlstring='.EncryptURL('fileName='.$fileNamePdf.'.pdf');
						$fotter = '<br/><br/><strong>Attachment:</strong> <a href='.$attachFile.' target="_blank">'.rtrim($fileNamePdf,')').'</a>';
		
						$emailArray=array($orderDetails[0]->COMMUNICATION_EMAIL_ID,'sales@sinelec-tech.com');
						//echo "<pre>";print_r($emailArray);die;
						
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
							if (!$mail->Send()) 
							{
							   //header("location:ViewOrderDetailsNew.php?urlstring=".EncryptURL("action=&msg=error"));
							} 
							else 
							{
								//
							}
						}
						header("Location:ViewOrderDetailsNew.php?urlstring=".EncryptURL("action=&msg=update&OrderNo=".$order_id));
					}	
				}			
			}
			else
			{
				header("Location:ViewOrderDetailsNew.php?urlstring=".EncryptURL("action=&msg=update&OrderNo=".$order_id));
			}
		}
	break;

	case 'ResendInvoice':
		//echo "<pre>";print_r($paramsArray); die;
		$orderId=$paramsArray['orderId'];
		$orderStatus=$paramsArray['orderStatus'];

		$fileInvoice = glob("../Attachments/*".$orderId.".pdf");
		$fileDir = $fileInvoice[0];
		list($aaa,$bbb,$fileName)=explode("/",$fileDir);
					
		$orderDetails=$objProductManager->GetOrderDetails($orderId);
		//echo "<pre>";print_r($orderDetails); die;
		
		if(count($orderDetails)>0)
		{
		
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
				if($orderDetails[0]->ORDER_NUMBER=='' || $orderDetails[0]->ORDER_NUMBER==NULL || $orderDetails[0]->ORDER_NUMBER==0)
				{
					$invoiceNo = 'Not Generated';
				}
				else
				{
					$invoiceNo = $orderDetails[0]->ORDER_YEAR.' - '.$orderDetails[0]->ORDER_NUMBER ;
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
			
			if($orderDetails[0]->COMMUNICATION_EMAIL_ID!='')
			{
				$toEmailID = trim($orderDetails[0]->COMMUNICATION_EMAIL_ID);
				$subject = 'Invoice Sinelec Tech';
				
				$userMessage='
					Hello '.$orderDetails[0]->USER_NAME.',<br/> <br/> 
					Kindly find the copy of the invoice with order id'.$orderDetails[0]->ORDER_ID.'. <br/> <br/> 
					Kindly find the order details below:<br/> <br/> 
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
					//$WithOutVat=0;
					foreach($orderDetails as $order)
					{
						$message.='
						<tr>
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
					<br/><br/><br/>
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
							</td></font>
					  </tr> 							
					  <tr>
						<td>
						<font size="+1">
						We thank you for your purchase.
						</font>
						</td>
					  </tr>
					</table>
					';
					//echo 	$message; die;		
				//$attachFile='https://sinelec-tech.com/admin/UI/Attachments/'.$fileName;
				$attachFile='https://www.sinelec-tech.com/website/view.php?urlstring='.EncryptURL('fileName='.$fileName);
				$fotter = '<br/><br/><strong>Attachment:</strong> <a href='.$attachFile.' target="_blank">'.rtrim($fileName,')').'</a>';

				$emailArray=array($orderDetails[0]->COMMUNICATION_EMAIL_ID,'sales@sinelec-tech.com');
				//echo "<pre>";print_r($emailArray);die;
				
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
					if (!$mail->Send()) 
					{
					   //header("location:ViewOrderDetailsNew.php?urlstring=".EncryptURL("action=&msg=error"));
					} 
					else 
					{
						//
					}
				}
				header("location:ViewOrderDetailsNew.php?urlstring=".EncryptURL("action=&msg=resend"));
			}	
		}
		else
		{
			header("location:ViewOrderDetailsNew.php?urlstring=".EncryptURL("action=&msg=error"));
		}	
	break;


	case 'PaymentRemainder':
		//echo "<pre>";print_r($paramsArray); die;
		$orderId=$paramsArray['orderId'];
		$orderStatus=$paramsArray['orderStatus'];
		
		if($orderStatus=='Invoice Payment Pending')
		{
			$invoicePreString = '';
		}
		elseif($orderStatus=='Bank Transfer Payment Pending')
		{
			$invoicePreString = 'PB-';
		}
				
		$orderDetails=$objProductManager->GetOrderDetails($orderId);
		//echo "<pre>";print_r($paramsArray); die;
		
		if(count($orderDetails)>0)
		{
		
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
				if($orderDetails[0]->ORDER_NUMBER=='' || $orderDetails[0]->ORDER_NUMBER==NULL || $orderDetails[0]->ORDER_NUMBER==0)
				{
					$invoiceNo = 'Not Generated';
				}
				else
				{
					$invoiceNo = $orderDetails[0]->ORDER_YEAR.' - '.$orderDetails[0]->ORDER_NUMBER ;
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
				
				
			if($orderDetails[0]->COMMUNICATION_EMAIL_ID!='')
			{
				$toEmailID = trim($orderDetails[0]->COMMUNICATION_EMAIL_ID);
				$subject = 'Payment Remainder and Invoice Sinelec Tech';
				
				$userMessage='
					Hello '.$orderDetails[0]->USER_NAME.',<br/> <br/> 
					We have not received your payment for the order id '.$orderDetails[0]->ORDER_ID.'. 
					It will be great if you can kindly make the payment. <br/> <br/> 

					Kindly find the order details below:<br/> <br/> 
					
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
					//$WithOutVat=0;
					foreach($orderDetails as $order)
					{
						$message.='
						<tr>
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
					<br/><br/><br/>
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
							</td></font>
					  </tr>								
					  <tr>
						<td>
						<font size="+1">
						We thank you for your purchase. Please transfer the Invoice amount in the above given German bank account. Once amount is received we will dispatch the goods immediately.
						</font>
						</td>
					  </tr>
					</table>
					';
				
				//echo "<pre>";print_r($message);die;
				$fileNamePdf = $invoicePreString.'Invoice-'.$orderDetails[0]->ORDER_ID;
				require_once "../../../website/tcpdf/tcpdf.php";
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
				$filePath='../Attachments/';
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
				//echo "<pre>";print_r($emailArray);die;
				
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
					if (!$mail->Send()) 
					{
					   //header("location:ViewOrderDetailsNew.php?urlstring=".EncryptURL("action=&msg=error"));
					} 
					else 
					{
						//
					}
				}
				header("location:ViewOrderDetailsNew.php?urlstring=".EncryptURL("action=&msg=remainder"));
			}	
		}
		else
		{
			header("location:ViewOrderDetailsNew.php?urlstring=".EncryptURL("action=&msg=error"));
		}	
	break;
	
	case 'Delete':
		//echo "<pre>";print_r($paramsArray);die;
		$orderId=$paramsArray['orderId'];
		if($objHomeManager->DeleteOrderByOrderId($orderId))
		{
			$fileInvoice = glob("../Attachments/*".$orderId.".pdf");
			unlink($fileInvoice[0]);

		  	header("location:ViewOrderDetailsNew.php?urlstring=".EncryptURL("action=&msg=delete"));
		}
		else
		{
			header("location:ViewOrderDetailsNew.php?urlstring=".EncryptURL("action=&msg=error"));
		}	
	break;
	
	case 'Search':
	   	//echo "<pre>";print_r($paramsArray);die;
		$limit="0";
		$maxRecord="100";
	    
		$OrderStatus =(isset($_POST["table_search"])) ? $_POST["table_search"] : $paramsArray["reference"];
		if($OrderStatus=='')
		{
			if($type=='Active')
			{
				$OrderStatus = "'Invoice Payment Pending','Dispatched Invoice Payment Pending','Bank Transfer Payment Pending','Payment Successful','Invoice Payment Successful', 'Bank Transfer Payment Successful'";
			}
			elseif($type=='Passive')
			{
				$OrderStatus = "'Payment Failed', 'Dispatched', 'Delivered', 'Cancel Order'";
			}
		}
		
		header("location:ViewOrderDetailsNew.php?urlstring=". EncryptURL("action=pagging&OrderStatus=".$OrderStatus.'&limit='.$limit.'&maxRecord='.$maxRecord));	
	break;
	
	case 'ExportExcel' :
		//print_r($_POST);
		//print_r($paramsArray);
		//die;
		$maxRecord=''; 
		$limit='';
		$OrderStatus=$paramsArray['Status'];
		if($OrderStatus=='')
		{
			if($type=='Active')
			{
				$OrderStatus = "'Invoice Payment Pending','Dispatched Invoice Payment Pending','Bank Transfer Payment Pending','Payment Successful','Invoice Payment Successful', 'Bank Transfer Payment Successful'";
			}
			elseif($type=='Passive')
			{
				$OrderStatus = "'Payment Failed', 'Dispatched', 'Delivered', 'Cancel Order'";
			}
		}
		$AdminAllDetailsPaging=$objHomeManager->GetAllProductOrderedDetails($OrderStatus,$limit,$maxRecord);
		$body='';
		$body='<table width="100%" border="1" cellspacing="0" cellpadding="0" class="table"  style="background-color:#dff7f3">
				<tr style="background-color:#91afb8">
					<th class="text_align_center">Order No</th>
					<th class="text_align_center">Invoice No</th>
					<th class="text_align_center">Customer</th>
					<th class="text_align_center">Order Date</th>
					<th class="text_align_center">Order Amt</th>
					<th class="text_align_center">Shipping Amt</th>
					<th class="text_align_center">Tax Amt</th>
					<th class="text_align_center">Delivery Address</th>
					<th class="text_align_center">City</th>
					<th class="text_align_center">State</th>
					<th class="text_align_center">Country</th>
					<th class="text_align_center">EU_VAT</th>
					<th class="text_align_center">Transaction No</th>
					<th class="text_align_center">Paypal Transaction No</th>
					<th class="text_align_center">Status</th>
				</tr>';

		if(count($AdminAllDetailsPaging)>0)
		{		
			foreach($AdminAllDetailsPaging as $value)
			{
				list($orderDate,$oderTime)= explode(" ",$value->ORDER_DATE);
				$body.='<tr>
						<td>'. $value->ORDER_ID.'</td>
						<td>'. $value->ORDER_YEAR.'-'.$value->ORDER_NUMBER .'</td> 
						<td>'. $value->NAME.'</td>
						<td>'. date("Y-m-d",strtotime($orderDate)).'</td>
						<td>'. round($value->ORDER_TOTAL_AMT,2).'</td>
						<td>'. round($value->SHIPING_AMT,2).'</td>
						<td>'. round($value->TAX_TOTAL_AMOUNT,2).'</td>
						<td>'. $value->ADDRESS.'</td>
						<td>'. $value->CITY.'</td>
						<td>'. $value->STATE.'</td>
						<td>'. $value->COUNTRY_NAME.'</td>
						<td>'. $value->EU_VAT.'</td>
						<td>'. $value->TRANSACTION_ID.'</td>
						<td>'. $value->PAY_PAL_TX_ID.'</td>
						<td>'. $value->ORDER_CURRENT_STATUS.'</td>
				 </tr>';
			}
		}
		else
		{
			$body.='<tr>
					<td colspan="14">No Data Found.</td>
			 </tr>';
		}
		$body.='</table>';
	
		$filename="OrderDataSheet.xls";
		excelReport($body, $filename);
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
<li class="active">Order Details</li>
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
			<h4><i class="icon fa fa-check"></i> Order has been added successfully</h4>
		</div>	
	<?php
	}
	else if($msg=='error')
	{
	?>
		<div class="alert alert-danger alert-dismissible" style="height:50px;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
			<h4><i class="icon fa fa-ban"></i>Order No - <?php echo $paramsArray["OrderNo"]; ?> Error in Process</h4>
		</div>
	<?php
	}
	
	else if($msg=='resend')
	{
	?>
		<div class="alert alert-success alert-dismissible" style="height:50px;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
			<h4><i class="icon fa fa-check"></i>Invoice email has been sent successfully</h4>
		</div>
	<?php 
	}

	else if($msg=='remainder')
	{
	?>
		<div class="alert alert-success alert-dismissible" style="height:50px;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
			<h4><i class="icon fa fa-check"></i>Payment remainder email has been sent successfully</h4>
		</div>
	<?php 
	}

	else if($msg=='update')
	{
	?>
		<div class="alert alert-success alert-dismissible" style="height:50px;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
			<h4><i class="icon fa fa-check"></i>Order No - <?php echo $paramsArray["OrderNo"]; ?> status has been updated successfully</h4>
		</div>
	<?php 
	}
	else if($msg=='delete')
	{
	?>
		<div class="alert alert-success alert-dismissible" style="height:50px;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
			<h4><i class="icon fa fa-check"></i> Order No - <?php echo $paramsArray["OrderNo"]; ?> has been deleted successfully</h4>
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
<div class="col-md-12 col-sm-12 col-xs-12 ">
</div>
<!-- Main content -->
<section class="content"> 
	<div class="row">
		<div class="col-xs-12">
			<div class="box" id="SibsSchool">
				<div class="box-header">
				<h3 class="box-title">Order Details</h3>
				</div>
				<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
				<script src="../bootstrap/js/bootstrap.min.js"></script>
				<script src="../js/func_ajax.js"></script>
                
                <script type="text/javascript">
					function getCourierDetails(status, curStatus, orderNo)
					{
						alert(status);
						if(status=='Dispatched')
						{
							callAjax("courierDiv", "getCourierDetails.php", {
							params:"status="+status,
							params:"status="+status+"&curStatus="+curStatus+"&orderNo="+orderNo,
							meth:"get",
							async:true,
							startfunc:"s_function('courierDiv')",
							endfunc:"e_function('courierDiv')",
							errorfunc:"ajaxError()" }
							);
						}
					}
				</script>	
				<div class="box-tools">
				<form  action="ViewOrderDetailsNew.php?urlstring=<?php echo EncryptURL('action=Search'); ?>" method="post">
				<div class="input-group input-group-sm" style="width: 250px;">
					<?php
					$order_status=$paramsArray['OrderStatus'];
					?>
					<select  class="form-control pull-right" name="table_search">
						<option value=''> Select</option>
						<?php 
						if($type=='Active')
						{
						?>
							<option value="Invoice Payment Pending" <?php if(isset($order_status) && $order_status!="" && $order_status=='Invoice Payment Pending')
							echo "selected"; ?>>Invoice Payment Pending</option>
							
							<option value="Dispatched Invoice Payment Pending" <?php if(isset($order_status) && $order_status!="" && 
							$order_status=='Dispatched Invoice Payment Pending') echo "selected"; ?>>Dispatched Invoice Payment Pending</option>
							
							<option value="Bank Transfer Payment Pending" <?php if(isset($order_status) && $order_status!="" && $order_status=='Bank Transfer Payment Pending')
							echo "selected"; ?>>Bank Transfer Payment Pending</option>
							
							<option value="Payment Successful" <?php if(isset($order_status) && $order_status!="" && $order_status=='Payment Successful') echo "selected"; ?>>
							Payment Successful</option>

							<option value="Invoice Payment Successful" <?php if(isset($order_status) && $order_status!="" && $order_status=='Invoice Payment Successful')
							echo "selected"; ?>>Invoice Payment Successful</option>
	
							<option value="Bank Transfer Payment Successful" 
							<?php if(isset($order_status) && $order_status!="" && $order_status=='Bank Transfer Payment Successful')
							echo "selected"; ?>>Bank Transfer Payment Successful</option>
						<?php
						}
						elseif($type=='Passive')
						{
						?>
							<option value="Cart" <?php if(isset($order_status) && $order_status!="" && $order_status=='Cart') echo "selected"; ?>>Cart</option>

							<option value="Payment Failed" <?php if(isset($order_status) && $order_status!="" && $order_status=='Payment Failed') echo "selected"; ?>>
							Payment Failed</option>
							<option value="Dispatched" <?php if(isset($order_status) && $order_status!="" && $order_status=='Dispatched') echo "selected"; ?>>Dispatched</option>
							<option value="Delivered" <?php if(isset($order_status) && $order_status!="" && $order_status=='Delivered') echo "selected"; ?>>Delivered</option>
							<option value="Cancel Order" <?php if(isset($order_status) && $order_status!="" && $order_status=='Cancel Order') echo "selected"; ?>>
							Cancel Order</option>
						<?php
						}
						?>
					</select>                    
					<div class="input-group-btn">
						<button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
					</div>
				</div>
				 </form>	
				</div>
                <div class="box-body">
                <?php
                if($action=="pagging")
                {
                    //echo "<pre>"; print_r($paramsArray);die;	
                        //$OrderStatus=$paramsArray['OrderStatus'];
                    if(!isset($AdminAllDetailsPaging))
                    {
						$OrderStatus = (isset($paramsArray['OrderStatus'])) ? $paramsArray['OrderStatus'] : '';
						
						if($OrderStatus=='')
						{
							if($type=='Active')
							{
								$OrderStatus = "'Invoice Payment Pending','Dispatched Invoice Payment Pending','Bank Transfer Payment Pending','Payment Successful','Invoice Payment Successful', 'Bank Transfer Payment Successful'";
							}
							elseif($type=='Passive')
							{
								$OrderStatus = "'Payment Failed', 'Dispatched', 'Delivered', 'Cancel Order'";
							}
						}
						
						$flag='count';
						$maxRecord="100";
						$limit=0;
						
						$AdminAllDetailsPaging=$objHomeManager->GetAllProductOrderedCount($flag,$OrderStatus);

                        $pageCount=$AdminAllDetailsPaging[0]->TOTAL/$maxRecord;
                        $pageCount=ceil($pageCount); 
                        for($page=1;$page<=$pageCount;$page++)
                        {
                        
                            if($page=='' || $page=='1')
                            {
                                $limit="0";
                                
                            }
                            else
                            {
                                $limit=($page*100)-100;
                            }
                            ?>
                            <tr>
                                <td>
                                    <a href="ViewOrderDetailsNew.php?urlstring=<?php echo EncryptURL('action=pagging&limit='.$limit.'&maxRecord='.$maxRecord);?>" 
                                    style=" text-decoration:none;" > <button> <?php echo $page ; ?> </button> </a>
                                </td>
                            </tr>
                            <?php
                         }
                         
                        ?>
                        <div style="float:right">
                        <table>
                            <tr>
                                <td> 
                                 <a href="ViewOrderDetailsNew.php?urlstring=<?php echo EncryptURL('action=ExportExcel&Status='.$OrderStatus); ?>">
                                 <button class="btn btn-success" onclick="exportToFileopen('excel')">Export to excel</button></a> 
                                </td> 
                            </tr> 	 
                        </table>
                        </div>
                    <?php 
                    }
                    else
                    {
                        $OrderStatus=$OrderStatus;
                    }	
                	?>
                	<div id="exportopenticket">
                    <table id="" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text_align_center">S.No</th>
                            <th class="text_align_center">Order No</th>
                            <th class="text_align_center">Invoice No</th>
                            <th class="text_align_center">Order Date</th>
                            <th class="text_align_center">Order Amt</th>
                            <th class="text_align_center">Shipping Amt</th>
                            <th class="text_align_center">Tax Amt</th>
                            <th class="text_align_center">Customer</th>
                            <th class="text_align_center" >Delivery Address</th>
                            <th class="text_align_center" >EU VAT</th>
                            <th class="text_align_center">Trans ID</th>
                            <th class="text_align_center">Status</th>
                            <th class="text_align_center">Action</th>
                        </tr>
                    </thead>
                    <?php
                    $limit= (isset ($paramsArray['limit'])) ? $paramsArray['limit'] : '';
                    $maxRecord= (isset ($paramsArray['maxRecord'])) ? $paramsArray['maxRecord'] : ''; 
					if($OrderStatus=='')
					{
						if($type=='Active')
						{
							$OrderStatus = "'Invoice Payment Pending','Dispatched Invoice Payment Pending','Bank Transfer Payment Pending','Payment Successful','Invoice Payment Successful', 'Bank Transfer Payment Successful'";
						}
						elseif($type=='Passive')
						{
							$OrderStatus = "'Payment Failed', 'Dispatched', 'Delivered', 'Cancel Order'";
						}
					}					
                    $AdminAllDetailsPaging=$objHomeManager->GetAllProductOrderedDetails($OrderStatus,$limit,$maxRecord);
					//echo $OrderStatus;
                    if(!empty($AdminAllDetailsPaging))
                    {
                        $index=$limit+1;
                        //echo "<pre>"; print_r($AdminAllDetailsPaging);die;
                        foreach($AdminAllDetailsPaging as $value)
                        {
                            list($orderDate,$oderTime)= explode(" ",$value->ORDER_DATE);
                        	?>
                            <tr class="common_table_header">
                                <td class="text_align_left"><?php echo $index++; ?></td>
                                <td class="text_align_left"><?php echo $value->ORDER_ID; ?></td>
                                <td class="text_align_left"><?php echo $value->ORDER_YEAR.'-'.$value->ORDER_NUMBER; ?></td>
                                <td class="text_align_left"><?php echo date("Y-m-d",strtotime($orderDate)); ?></td>
                                <td class="text_align_left"><?php echo round($value->ORDER_TOTAL_AMT,2); ?></td>
                                <td class="text_align_left"><?php echo round($value->SHIPING_AMT,2); ?></td>
                                <td class="text_align_left"><?php echo round($value->TAX_TOTAL_AMOUNT,2); ?></td>
        
                                <td class="text_align_left"><?php echo $value->NAME; ?></td>
                                <td class="text_align_left">
                                <?php 
                                if ($value->ADDRESS!='')
                                {
                                ?>
                                    <?php echo $value->ADDRESS; ?><br />
                                    <?php echo $value->CITY; ?>, <?php echo $value->STATE; ?>, <?php echo $value->COUNTRY_NAME; ?><br /> 
                                    Zip :<?php echo $value->ZIP; ?>
                                <?php 
                                }
                                else
                                {
                                ?>
                                &nbsp;
                                <?php
                                }
                                ?>
                                
                                </td>
                                <td class="text_align_left"><?php echo $value->EU_VAT; ?></td>
                                
                                <td class="text_align_left"><?php echo $value->TRANSACTION_ID; ?></td>
                                <td class="text_align_left"><?php echo $value->ORDER_CURRENT_STATUS; ?></td>
                                <td class="text_align_center">
        						
                                <?php ?>
                                <button class="btn btn-danger btn-sm open" data-Id="<?php echo $value->ORDER_ID."@_@".$value->ORDER_CURRENT_STATUS ?>">
                                Status Update</button>
                                
                                <?php  
                                if($value->ORDER_CURRENT_STATUS == 'Invoice Payment Pending' ||  $value->ORDER_CURRENT_STATUS == 'Bank Transfer Payment Pending'  ||  $value->ORDER_CURRENT_STATUS == 'Dispatched Invoice Payment Pending')
                                {
                                ?>
                                <a href="ViewOrderDetailsNew.php?urlstring=<?php echo EncryptURL('action=PaymentRemainder&orderId='.$value->ORDER_ID.'&orderStatus='.$value->ORDER_CURRENT_STATUS) ;?>">
                                 <button class="btn btn-success">Send Payment Reminder</button></a>
                                <?php
                                }
								elseif($value->ORDER_CURRENT_STATUS == 'Payment Successful' ||  $value->ORDER_CURRENT_STATUS == 'Invoice Payment Successful'  ||  $value->ORDER_CURRENT_STATUS == 'Bank Transfer Payment Successful'  ||  $value->ORDER_CURRENT_STATUS == 'Dispatched'  ||  $value->ORDER_CURRENT_STATUS == 'Delivered')
                                {
                                ?>
                                <a href="ViewOrderDetailsNew.php?urlstring=<?php echo EncryptURL('action=ResendInvoice&orderId='.$value->ORDER_ID.'&orderStatus='.$value->ORDER_CURRENT_STATUS) ;?>">
                                 <button class="btn btn-success">Re-Send Invoice</button></a>
                                <?php
                                }
                                elseif($value->ORDER_CURRENT_STATUS == 'Cart' || $value->ORDER_CURRENT_STATUS == 'Checkout'  || $value->ORDER_CURRENT_STATUS == 'Payment Failed')
                                {
                                ?>
                                <a href="ViewOrderDetailsNew.php?urlstring=<?php echo EncryptURL('action=Delete&orderId='.$value->ORDER_ID) ;?>" onClick="return confirm('Are You Sure you want to Delete Order? \n Click OK to Continue, Cancel to Stop');">
                                 <button class="btn btn-danger btn-sm">Delete</button></a>
                                <?php
                                }
                                ?>
                                <button class="btn btn-success open1" data-Id-1="<?php echo $value->ORDER_ID."@_@".$value->USER_ID; ?>">
                                View details</button>
								
								<br />
								<?php
								$pdf_invoice_file_src = '../Attachments/Invoice-'.$value->ORDER_ID.'.pdf';
								$pdf_pb_invoice_file_src = '../Attachments/PB-Invoice-'.$value->ORDER_ID.'.pdf';
								$pdf_online_invoice_file_src = '../Attachments/Online-Invoice-'.$value->ORDER_ID.'.pdf';

								//echo $pdf_file_path;
								if(file_exists($pdf_invoice_file_src))
								{
									$pdf_file_path = 'https://www.sinelec-tech.com/admin/UI/Attachments/Invoice-'.$value->ORDER_ID.'.pdf';
									$fileNamePdf='Invoice-'.$value->ORDER_ID.'.pdf';
									$pdf_file_path='https://www.sinelec-tech.com/website/view.php?urlstring='.EncryptURL('fileName='.$fileNamePdf);
									?>
									<a href="<?php echo $pdf_file_path; ?>" target="_blank">
                                 	<button class="btn btn-success">Invoice PDF</button></a>
								
								<?php
								}
								elseif(file_exists($pdf_pb_invoice_file_src))
								{
									//$pdf_file_path = 'https://www.sinelec-tech.com/admin/UI/Attachments/PB-Invoice-'.$value->ORDER_ID.'.pdf';
									$fileNamePdf='PB-Invoice-'.$value->ORDER_ID.'.pdf';
									$pdf_file_path='https://www.sinelec-tech.com/website/view.php?urlstring='.EncryptURL('fileName='.$fileNamePdf);
									?>
									<a href="<?php echo $pdf_file_path; ?>" target="_blank">
                                 	<button class="btn btn-success">PB Invoice PDF</button></a>
								
								<?php
								}
								elseif(file_exists($pdf_online_invoice_file_src))
								{
									$pdf_file_path = 'https://www.sinelec-tech.com/admin/UI/Attachments/Online-Invoice-'.$value->ORDER_ID.'.pdf';
									$fileNamePdf='Online-Invoice-'.$value->ORDER_ID.'.pdf';
									$pdf_file_path='https://www.sinelec-tech.com/website/view.php?urlstring='.EncryptURL('fileName='.$fileNamePdf);
									?>
									<a href="<?php echo $pdf_file_path; ?>" target="_blank">
                                 	<button class="btn btn-success">Online Invoice PDF</button></a>
								<?php
								}
								
								?>

        
                                <!--<a href="ViewOrderDetailsNew.php?urlstring=" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to Delete this record ?\n Click OK to Continue, Cancel to Stop')" ><span class="glyphicon glyphicon"  ></span> View details</a>-->
                                </td>	
                            </tr>
                        <?php 
                        }
                    }
                    else
                    {
                    ?>
                        <tr><td colspan="13" style="color:red;font-weight:bold;text-align:center">No Record found</td></tr>
                    <?php
                    } 
                    ?>
                    </table>
                	</div>

                    <div id="Open_popup_modal_show_id" class="modal fade" tabindex="-1"></div>
                    <script src="../js/jquery-1.11.2.min.js"></script>
                    <script type="text/javascript">
                    $(document).ready(function(){
                    var $modal = $('#Open_popup_modal_show_id');
                    $('.open').on('click', function(){
                            var val=$(this).attr('data-Id');
                            
                            $modal.load('UpdateOrderStatus.php',{'val': val},
                            function(){
                            //alert(val);
                            $modal.modal('show');
                            });
                        });
                    });
                    </script>
	
                    <div id="Open1_popup_modal_show_id" class="modal fade" tabindex="-1"></div>
                    <script src="../js/jquery-1.11.2.min.js"></script>
                    <script type="text/javascript">
                    $(document).ready(function(){
                    var $modal = $('#Open1_popup_modal_show_id');
                    $('.open1').on('click', function(){
                            var val=$(this).attr('data-Id-1');
                            
                            $modal.load('ViewOrderDetailsModal.php',{'val': val},
                            function(){
                            //alert(val);
                            $modal.modal('show');
                            });
                        });
                    });
                    </script>
				<?php
				}
				?>
            	</div>
        	</div>
    	</div>     
    </div>       
</section> 
</div>
<?php
$pageMainContent = ob_get_contents();
ob_end_clean();
$pagetitle = "View Order Details ::";
//Apply the template
include('../MasterTemplatePage.php');
?>