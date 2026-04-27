<?php
ob_start();
ini_set('display_errors',0);
include('../Common.php');
include('../Includes/Functions.php');
require_once ('../../UI/Config/inc_path.php');
require_once "../Includes/ConstantArray.php";
require_once ('../../BL/ProductManager.php');
$objProductManager = new ProductManager();
$date=date('Y-m-d');
$paramsArray = GetQueryStringParameters();
(isset($paramsArray['action']))? $action=$paramsArray['action'] : $action="";
isset($paramsArray["msg"]) ? $msg=$paramsArray["msg"] : $msg="";
switch($action)
{	
	case 'Search':
	   	//echo "<pre>";print_r($paramsArray);die;
		$limit="0";
		$maxRecord="100";
	    $enquiryStatus =(isset($_POST["table_search"])) ? $_POST["table_search"] : $paramsArray["reference"];
		header("location:Enquiry.php?urlstring=". EncryptURL("action=pagging&enquiryStatus=".$enquiryStatus.'&limit='.$limit.'&maxRecord='.$maxRecord));	
	break;

	case "UpdateOrderDetails":
		//echo "<pre>";print_r($_POST);die;
		$enquiryId=$_POST["enquiryId"];
		$enquiryStatus=$_POST["enquiryStatus"];
		$intSupplierNo=$_POST["intSupplierNo"];
		$intOrderNo=$_POST["intOrderNo"];
		$enquiryResultId=$objProductManager->UpdateEnquiryOrderDetail($enquiryId, $intSupplierNo, $intOrderNo);
		
		if($enquiryResultId!='' || $enquiryResultId=='0')
			header("location:Enquiry.php?urlstring=".EncryptURL("action=Search&msg=update&reference=".$enquiryStatus));
		else
			header("location:Enquiry.php?urlstring=".EncryptURL("msg=error"));
	
	break;
	
	case "UpdateStatus":
		//echo "<pre>";print_r($_POST);die;
		$enquiryId=$_POST["enquiryNo"];
		$enquiryChangedStatus=$_POST["enquiryChangedStatus"];
		$enquiryCurrentStatus=$_POST["enquiryCurrentStatus"];
		$enquiryResultId=$objProductManager->UpdateEnquiryStatusById($enquiryId, $enquiryChangedStatus, $enquiryCurrentStatus);
		
		if($enquiryResultId)
			header("location:Enquiry.php?urlstring=".EncryptURL("msg=update"));
		else
			header("location:Enquiry.php?urlstring=".EncryptURL("msg=error"));
	
	break;

	case "Delete":
		//echo "<pre>";print_r($paramsArray);die;
		$enquiryId=$paramsArray["enquiryId"];
		$enquiryResultId=$objProductManager->DeleteEnquiryQuoteById($enquiryId);

		if($enquiryResultId)
			header("location:Enquiry.php?urlstring=".EncryptURL("msg=delete"));
		else
			header("location:Enquiry.php?urlstring=".EncryptURL("msg=error"));
	break;

	case "SendQuotation":
		//echo "<pre>";print_r($paramsArray);
		$enquiryId=$paramsArray["enquiryId"];
		$flag = 'SendQuotation';
		$arrEnquiryProductData=$objProductManager->GetEnquiryDetailsById($enquiryId,$flag);
		
		//echo "<pre>";print_r($arrEnquiryProductData);
		if(count($arrEnquiryProductData)>0)
		{
			$arrEnquiryData=$arrEnquiryProductData[0][0];
			$arrEnqProductData=$arrEnquiryProductData[1];
			$password=$arrEnquiryProductData[2];
			//echo "<pre>";print_r($arrEnquiryData);die;
			if($arrEnquiryData->COMPANY_NAME=='')
			{
				$userName = $arrEnquiryData->USER_NAME;
			}
			else
			{
				$userName = $arrEnquiryData->COMPANY_NAME;
			}
			
			if($arrEnquiryData->USER_EMAIL!="")
			{

				$billing_address=$arrEnquiryData->BILLING_ADDRESS;
				$billing_city=$arrEnquiryData->BILLING_CITY;
				$billing_state=$arrEnquiryData->BILLING_STATE;
				$billing_zip=$arrEnquiryData->BILLING_ZIP;
				$billing_country=$arrEnquiryData->BILLING_COUNTRY;
									
				if($arrEnquiryData->BILLING_ADDRESS=='')
				{
					$billing_address=$arrEnquiryData->DELIVERY_ADDRESS;
					$billing_city=$arrEnquiryData->DELIVERY_CITY;
					$billing_state=$arrEnquiryData->DELIVERY_STATE;
					$billing_zip=$arrEnquiryData->DELIVERY_ZIP;
					$billing_country=$arrEnquiryData->DELIVERY_COUNTRY;
				}
				
				$address = $arrEnquiryData->DELIVERY_ADDRESS.', '.$arrEnquiryData->DELIVERY_CITY.', '.$arrEnquiryData->DELIVERY_STATE.', '.$arrEnquiryData->DELIVERY_COUNTRY.'. ZIP - '.$arrEnquiryData->DELIVERY_ZIP;
				
				list($enquiryDate,$enquiryTime)=explode(" ",$arrEnquiryData->ENQUIRY_DATE);
				
				include "../../smtpmail/classes/class.phpmailer.php"; // include the class name
				$subject = 'Requested Quotation';

				$userMessage='
					Hello '.$arrEnquiryData->USER_NAME.',<br/> <br/> 
					Thank you for your interest in our product(s). We hearby make you the following offer:<br/> <br/>
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
								<strong>City: </strong>'.$billing_city.', <strong>State: </strong>'.$billing_state.', <br/>
								<strong>Country: </strong>'.$billing_country.', <strong>Zip No: </strong> '.$billing_zip.'<br/> 
								<strong>VAT No: </strong>'.$arrEnquiryData->VAT_NUMBER.'    
								
								<br/> <br/>
								<strong>Phone Number:</strong>'.$arrEnquiryData->USER_PHONE_COUNTRY_CODE.' '.$arrEnquiryData->USER_PHONE.'<br/> 
	
								<br/> <br/>
								<strong><u>Quotation Details:</u></strong><br/>
								<strong>Enquiry No:</strong> '.$arrEnquiryData->ENQUIRY_QUOTE_ID.' <br/> 
								<strong>Customer Order No.: </strong>'.$arrEnquiryData->CUSTOMER_ORDER_NO.'
								
								<br/> <br/>
								<strong>Customer No.: </strong>'.$arrEnquiryData->USER_ID.'<br/>
								<strong>Customer Supplier No.: </strong>'.$arrEnquiryData->CUSTOMER_SUPPLIER_NO.'

								<br/> <br/>
								<strong>Date:</strong>'.$enquiryDate.'<br/>
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
					//$WithOutVat=0;
					$deliveryCountryShipping=$arrEnquiryData->ENQUIRY_SHIPPING_AMT;
					$vatAmount=$arrEnquiryData->ENQUIRY_VAT_AMT;
					
					foreach($arrEnqProductData as $productVal)
					{
						$productCategoryName=$productVal->PRODUCT_CATEGORY_NAME ;
						$productName=$productVal->PRODUCT_NAME ;
						$quantity=$productVal->PRODUCT_QUANTITY ;
						$productAmt=$productVal->PRODUCT_AMT ;

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
						Check the delivery & shipping time in our website <a href="https://sinelec-tech.com/website/Shipping-Payment.php" target="_blank">https://sinelec-tech.com/website/Shipping-Payment.php</a>

						</font>	
						</td>
					  </tr>
					</table>
					';
					
					
				//print_r($message);die;	
				$fileNamePdf = 'Quotation-'.$arrEnquiryData->ENQUIRY_QUOTE_ID;
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

				$mailArray=array("firstMail"=>$arrEnquiryData->USER_EMAIL,"secondMail"=>'contact@sinelec-tech.com',"3rdMail"=>'sales@sinelec-tech.com');
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
						//echo "<pre>";print_r('ssss');die;
						header("location:Enquiry.php?urlstring=".EncryptURL("msg=error"));
						
						$action='';
						
					} 
					else 
					{
						//echo "<pre>";print_r('VVVV');die;
						
						header("location:Enquiry.php?urlstring=".EncryptURL("msg=quotation"));	
						
						$action='';
					}
				}
			}
			
		}
		else
		{
			header("location:Enquiry.php?urlstring=".EncryptURL("msg=error"));
		}	
	break;
	
	case "GenerateOrder":
		//echo "<pre>";print_r($paramsArray);die;
		$enquiryId=$paramsArray["enquiryId"];
		$flag = 'GenerateOrder';
		$arrEnquiryProductData=$objProductManager->GetEnquiryDetailsById($enquiryId,$flag);
		//echo "<pre>";print_r($arrEnquiryData);die;
		if(count($arrEnquiryProductData)>0)
		{
			$arrEnquiryData=$arrEnquiryProductData[0][0];
			$arrEnqProductData=$arrEnquiryProductData[1];
			$password=$arrEnquiryProductData[2];
			
			if($arrEnquiryData->COMPANY_NAME=='')
			{
				$userName = $arrEnquiryData->USER_NAME;
			}
			else
			{
				$userName = $arrEnquiryData->COMPANY_NAME;
			}
			
			
			if($arrEnquiryData->USER_EMAIL!="")
			{
				list($orderDate,$orderTime)=explode(" ",$arrEnquiryData->O_ORDER_DATE);

				include "../../smtpmail/classes/class.phpmailer.php"; // include the class name
				$toEmailID = trim($arrEnquiryData->USER_EMAIL);
				$subject = 'Invoice: Order Detail Sinelec Tech';
				
				$address = $arrEnquiryData->DELIVERY_ADDRESS.', '.$arrEnquiryData->DELIVERY_CITY.', '.$arrEnquiryData->DELIVERY_STATE.', '.$arrEnquiryData->DELIVERY_COUNTRY.'. ZIP - '.$arrEnquiryData->DELIVERY_ZIP;
				
				
				$userMessage='
				Hello '.$arrEnquiryData->USER_NAME.', <br/> <br/> 
				Thanks for your purchase! <br/> <br/> 
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
                                <strong>'.$userName.'</strong>  <br/> 
								<strong>Address: </strong>'.$arrEnquiryData->BILLING_ADDRESS.'  <br/> 
								<strong>City: </strong>'.$arrEnquiryData->BILLING_CITY.', <strong>State: </strong>'.$arrEnquiryData->BILLING_STATE.', <br/>
								<strong>Country: </strong>'.$arrEnquiryData->BILLING_COUNTRY.', <strong>Zip No: </strong> '.$arrEnquiryData->BILLING_ZIP.'<br/> 
								<strong>VAT No: </strong>'.$arrEnquiryData->VAT_NUMBER.'    
								
								<br/> <br/>
								<strong>Phone Number:</strong>'.$arrEnquiryData->USER_PHONE_COUNTRY_CODE.' '.$arrEnquiryData->USER_PHONE.'<br/> 
	
								<br/> <br/>
								<strong><u>Invoice Details:</u></strong><br/>     
								<strong>Invoice No:</strong> '.$arrEnquiryData->ORDER_YEAR.' - '.$arrEnquiryData->ORDER_NUMBER.' <br/>
								<strong>Order Id:</strong> '.$arrEnquiryData->O_ORDER_ID.' <br/> 
								<strong>Customer Order No.: </strong>'.$arrEnquiryData->CUSTOMER_ORDER_NO.'
								
								<br/> <br/>
								<strong>Customer No.: </strong>'.$arrEnquiryData->USER_ID.'<br/>
								<strong>Customer Supplier No.: </strong>'.$arrEnquiryData->CUSTOMER_SUPPLIER_NO.'

								<br/> <br/>
								<strong>Date:</strong>'.$orderDate.'<br/>
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
							$deliveryCountryShipping=$arrEnquiryData->ENQUIRY_SHIPPING_AMT;
							$vatAmount=$arrEnquiryData->ENQUIRY_VAT_AMT;
							
							foreach($arrEnqProductData as $productVal)
							{
								$productCategoryName=$productVal->PRODUCT_CATEGORY_NAME ;
								$productName=$productVal->PRODUCT_NAME ;
								$quantity=$productVal->PRODUCT_QUANTITY ;
								$productAmt=$productVal->PRODUCT_AMT ;
		
								$message.='
								<tr>
									<td width="5%"> <font size="+1">' . $sno++ . '</font></td>
									<td width="55%"> <font size="+1">' . $productName . '</font></td>
									<td width="10%"> <font size="+1">' . $quantity . '</font></td>
									<td width="10%"> <font size="+1">' . $productAmt . '</font></td>
									<td width="20%" align="center"> <font size="+1">'  . round(($productAmt*$quantity),2) . '</font></td>
								</tr>';
								$totalAmount=$totalAmount+round(($productAmt*$quantity),2);
							}
					
							$message.='
							<tr>
								<td colspan="4"><font size="+1"><strong>Product Total Amount </strong></font></td>
								<td align="center"><font size="+1"><strong>'.round($totalAmount,2).'</strong></font></td>
							</tr>';
										
							$message.='
							<tr>
								<td colspan="4"><font size="+1"><strong>Packing & Shipping Amount </strong></font></td>
								<td align="center"><font size="+1"><strong>'.round($deliveryCountryShipping,2).'</strong></font></td>
							</tr>';

							$message.='
							<tr>
								<td colspan="4"><font size="+1"><strong>Net Total Amount </strong></font></td>
								<td align="center"><font size="+1"><strong>'.(round($totalAmount,2)+round($deliveryCountryShipping,2)).'</strong></font></td>
							</tr>';
							
							$message.='
							<tr>
								<td colspan="4"><font size="+1"><strong>VAT Amount (19%) </strong></font></td>
								<td align="center"><font size="+1"><strong>'.round($vatAmount,2).'</strong></font></td>
							</tr>';
							$message.='
							<tr bgcolor="#5B9BD5">
								<td colspan="4"><font color="#000000" size="+1"><strong>Invoice Total Amount </strong></font></td>
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
										Delivery Address: '.$address.'
									<br/><br/>
									</font>	
								
									<font size="+1">
									We thank you for your purchase. Please transfer the Invoice amount in above given German bank account within 30 days of receiving goods.
									</font>								
								</td>
									
							  </tr>
							</table>
						';
						//echo "<pre>";print_r($message);die;
					
					$fileNamePdf = 'Invoice-'.$arrEnquiryData->O_ORDER_ID;
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

					$emailArray=array($arrEnquiryData->USER_EMAIL,'sales@sinelec-tech.com');
					//echo"<pre>";print_r($emailArray); die;
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

						if (!$mail->Send()) 
						{
							header("location:Enquiry.php?urlstring=".EncryptURL("msg=error"));
							
							
						} 
						else 
						{
						header("location:Enquiry.php?urlstring=".EncryptURL("msg=invoice"));
							
						}
				  	}
					
			}	
		}				
		else
		{
			header("location:Enquiry.php?urlstring=".EncryptURL("msg=error"));
		}	
	
	break;
	
	case 'ExportExcel' :
		//print_r($_POST);
		//print_r($paramsArray);
		//die;
		$maxRecord=''; 
		$limit='';
		$enquiryStatus=$paramsArray['enquiryStatus'];
		$arrProductsEnquiryList=$objProductManager->GetProductsEnquiryList("", $enquiryStatus, "", "");
		//echo "<pre>";print_r($arrProductsEnquiryList);die;
		$body='';
		$body='<table width="100%" border="1" cellspacing="0" cellpadding="0" class="table"  style="background-color:#dff7f3">
				<tr style="background-color:#91afb8">
					<th rowspan="2" class="text_align_center">Sl</th>
					<th rowspan="2" class="text_align_center">Enquiry No</th>
					<th rowspan="2" class="text_align_center">Enquiry Date</th>
					<th rowspan="2" class="text_align_center">Customer</th>
					<th rowspan="2" class="text_align_center">Company</th>
					<th rowspan="2" class="text_align_center">Email</th>
					<th rowspan="2" class="text_align_center">Phone</th>

					<th rowspan="2" class="text_align_center">Total Amt</th>
					<th rowspan="2" class="text_align_center">Shipping Amt</th>
					<th rowspan="2" class="text_align_center">Vat Amt</th>

					<th rowspan="2" class="text_align_center">Order No</th>

					<th rowspan="2" class="text_align_center">Delivery Address</th>
					<th rowspan="2" class="text_align_center">Delivery City</th>
					<th rowspan="2" class="text_align_center">Delivery State</th>
					<th rowspan="2" class="text_align_center">Delivery Country</th>
					<th rowspan="2" class="text_align_center">Delivery ZIP</th>
					
					<th rowspan="2" class="text_align_center">Billing Address</th>
					<th rowspan="2" class="text_align_center">Billing City</th>
					<th rowspan="2" class="text_align_center">Billing State</th>
					<th rowspan="2" class="text_align_center">Billing Country</th>					
					<th rowspan="2" class="text_align_center">Billing ZIP</th>
					<th rowspan="2" class="text_align_center">EU VAT No</th>
					<th rowspan="2" class="text_align_center">Status</th>
					<th colspan="4" class="text_align_center">Product Details</th>
				</tr>
				
				<tr style="background-color:#91afb8">
					<th class="text_align_center">Category</th>
					<th class="text_align_center">Product</th>
					<th class="text_align_center">Quantity</th>
					<th class="text_align_center">Product Amt</th>
				</tr>

				';

		if(count($arrProductsEnquiryList)>0)
		{	
			$index=1;	
			foreach($arrProductsEnquiryList as $value)
			{
				list($enquiryDate,$enquiryTime)= explode(" ",$value->ENQUIRY_DATE);
				$body.='
					<tr>
						<td>'. $index++ .'</td>
						<td>'. $value->ENQUIRY_QUOTE_ID.'</td>
						<td>'. $enquiryDate .'</td>
						
						<td>'. $value->USER_NAME.'</td>
						<td>'. $value->COMPANY_NAME.'</td>
						<td>'. $value->USER_EMAIL.'</td>
						<td>'. $value->USER_PHONE_COUNTRY_CODE.' '.$value->USER_PHONE.'</td>
						
						<td>'. round($value->ENQUIRY_TOTAL_AMT,2).'</td>
						<td>'. round($value->ENQUIRY_SHIPPING_AMT,2).'</td>
						<td>'. round($value->ENQUIRY_TOTAL_AMT,2).'</td>
						<td>'. $value->ORDER_ID.'</td>

						<td>'. $value->DELIVERY_ADDRESS.'</td>
						<td>'. $value->DELIVERY_CITY.'</td>
						<td>'. $value->DELIVERY_STATE.'</td>
						<td>'. $value->DELIVERY_COUNTRY.'</td>
						<td>'. $value->DELIVERY_ZIP.'</td>

						<td>'. $value->BILLING_ADDRESS.'</td>
						<td>'. $value->BILLING_CITY.'</td>
						<td>'. $value->BILLING_STATE.'</td>
						<td>'. $value->BILLING_COUNTRY.'</td>
						<td>'. $value->BILLING_ZIP.'</td>
						
						<td>'. $value->VAT_NUMBER.'</td>
						<td>'. $value->ENQUIRY_STATUS.'</td>
						<td colspan="4">
							<table width="100%" border="1" cellspacing="0" cellpadding="0">
				 	';

				$arrEnquiryProductList=$objProductManager->GetEnquiryProductList($value->ENQUIRY_QUOTE_ID);
				//echo '<pre>'; print_r($arrEnquiryProductList);
				if(count($arrEnquiryProductList)>0)
				{
					foreach($arrEnquiryProductList as $enqProVal)
					{
					$body.='
								<tr>
									<td >'. $enqProVal->PRODUCT_CATEGORY_NAME .'</td>
									<td >'. $enqProVal->PRODUCT_NAME  .'</td>
									<td >'. $enqProVal->PRODUCT_QUANTITY .'</td>
									<td >'. round($enqProVal->PRODUCT_AMT,2) .'</td>
								</tr>';
					}
					$body.='</table>
						</td>
					</tr>';
				}				 
			}
		}
		else
		{
			$body.='
			<tr>
					<td colspan="27">No Data Found.</td>
			</tr>';
		}
		$body.='</table>';
	
		//print_r($body); die;
		$filename="EnquiryDataSheet.xls";
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
	<h1>  
		<a class="btn btn-danger btn-sm open" href="request_quote.php" target="_blank">Create Quotation</a>
	</h1>
	<ol class="breadcrumb">
		<li><a href="../User/Home.php"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Enquiry List</li>
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
	if($msg=='error')
	{	
	?>
		<div class="alert alert-danger alert-dismissible" style="height:50px;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
			<h4><i class="icon fa fa-ban"></i> Error in Process</h4>
		</div>
	<?php 
	}
	else if($msg=='delete')
	{
	?>
		<div class="alert alert-success alert-dismissible" style="height:50px;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
			<h4><i class="icon fa fa-check"></i> Enquiry has been deleted successfully</h4>
		</div>
	<?php
	}	
	else if($msg=='update')
	{
	?>
		<div class="alert alert-success alert-dismissible" style="height:50px;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
			<h4><i class="icon fa fa-check"></i> Enquiry Status has been updated successfully</h4>
		</div>
	<?php
	}	
	else if($msg=='quotation')
	{
	?>
		<div class="alert alert-success alert-dismissible" style="height:50px;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
			<h4><i class="icon fa fa-check"></i> Enquiry Status has been updated successfully and quotation has been sent to customer</h4>
		</div>
	<?php
	}	
	else if($msg=='invoice')
	{
	?>
		<div class="alert alert-success alert-dismissible" style="height:50px;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
			<h4><i class="icon fa fa-check"></i> Invoice Status has been updated successfully and invoice has been sent to customer</h4>
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
		<div class="col-xs-12">
			<div class="box" id="SibsSchool">
				
				<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
				<script src="../bootstrap/js/bootstrap.min.js"></script>
				<script src="../js/func_ajax.js"></script>
				<div class="box-tools">
				<form  action="Enquiry.php?urlstring=<?php echo EncryptURL('action=Search'); ?>" method="post">
				<div class="input-group input-group-sm" style="width: 250px;">
					<?php
					$enquiryStatus=$paramsArray['enquiryStatus'];
					?>
					<select  class="form-control pull-right" name="table_search">
						<option value=''> Select</option>
						<option value="Quotation Pending" <?php if(isset($enquiryStatus) && $enquiryStatus=='Quotation Pending') echo "selected"; ?>>Quotation Pending</option>
						<option value="Quotation Sent" <?php if(isset($enquiryStatus) && $enquiryStatus=='Quotation Sent') echo "selected"; ?>>
						Quotation Sent</option>
                        
						<option value="Order Generated" <?php if(isset($enquiryStatus) && $enquiryStatus=='Order Generated')
						echo "selected"; ?>>Order Generated</option>
                        <option value="Order Completed" <?php if(isset($enquiryStatus) && $enquiryStatus=='Order Completed')
						echo "selected"; ?>>Order Completed</option>
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

                    if(!isset($arrProductsEnquiryList))
                    {
						$enquiryStatus= (isset ($paramsArray['enquiryStatus'])) ? $paramsArray['enquiryStatus'] : '';
						$flag='count';
						$maxRecord="100";
						$limit="";
						$arrProductsEnquiryList=$objProductManager->GetProductsEnquiryList($flag,$enquiryStatus, $limit, "");

                        $pageCount=$arrProductsEnquiryList[0]->TOTAL/$maxRecord;
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
                                    <a href="Enquiry.php?urlstring=<?php echo EncryptURL('action=pagging&limit='.$limit.'&maxRecord='.$maxRecord);?>" 
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
                                 <a href="Enquiry.php?urlstring=<?php echo EncryptURL('action=ExportExcel&enquiryStatus='.$enquiryStatus); ?>">
                                 <button class="btn btn-success" onclick="exportToFileopen('excel')">Export to excel</button></a> 
                                </td> 
                            </tr> 	 
                        </table>
                        </div>
                    <?php 
                    }
                    else
                    {
                        $enquiryStatus=$enquiryStatus;
                    }	
                	?>
                	<div id="exportopenticket">
                    <table id="" class="table table-bordered table-striped" width="100%">
                    <thead>
                        <tr>
                            <th width="3%" rowspan="2" class="text_align_center">#</th>
                            <th width="6%" rowspan="2" class="text_align_center">Enq No/Date</th>
                            <th width="6%" rowspan="2" class="text_align_center">Order Details</th>
                            <th width="12%" rowspan="2" class="text_align_center">User Details</th>
                            <th width="15%" rowspan="2" class="text_align_center">Address/VAT</th>
                            <th width="10%" rowspan="2" class="text_align_center">Price Details</th>
                            <th width="6%" rowspan="2" class="text_align_center">Status</th>
                            <th width="36%" colspan="4" class="text_align_center">Product Details</th>
                            <th width="12%" rowspan="2" class="text_align_center">Action</th>
                        </tr>
                        <tr>
                            <th width="12%">Category</th>
                            <th width="12%">Product</th>
                            <th width="6%">Qua</th>
                            <th width="6%">Amt</th>
                        </tr>
                    </thead>
                    <?php
                    $limit= (isset ($paramsArray['limit'])) ? $paramsArray['limit'] : '';
                    $maxRecord= (isset ($paramsArray['maxRecord'])) ? $paramsArray['maxRecord'] : ''; 
					$arrProductsEnquiryList=$objProductManager->GetProductsEnquiryList("",$enquiryStatus, $limit, $maxRecord);

					//echo "<pre>"; print_r($arrProductsEnquiryList);die;
                    
					if(!empty($arrProductsEnquiryList))
                    {
                        $index=$limit+1;
                        //echo "<pre>"; print_r($AdminAllDetailsPaging);die;
                        foreach($arrProductsEnquiryList as $value)
                        {
							list($enquiryDate,$enquiryTime)= explode(" ",$value->ENQUIRY_DATE);

                        	?>
                            <tr class="common_table_header">
                                <td class="text_align_left" style="font-size:10px"><?php echo $index++; ?></td>
                                <td class="text_align_left" style="font-size:10px">
									<?php echo $value->ENQUIRY_QUOTE_ID; ?> <br />
                                	<?php echo date("Y-m-d",strtotime($enquiryDate)); ?>
                                </td>
                                <td class="text_align_left" style="font-size:10px">
                                    <?php echo "Order No. : ".$value->CUSTOMER_ORDER_NO; ?> <br />
                                    <?php echo "Supplier No. : ".$value->CUSTOMER_SUPPLIER_NO; ?> <br />
                                </td>
                                <td class="text_align_left" style="font-size:10px">
									<?php echo $value->COMPANY_NAME; ?><br />
									<?php echo $value->USER_NAME; ?><br />
									<?php echo $value->USER_EMAIL; ?><br />
									<?php echo $value->USER_PHONE; ?><br />
                                </td>
                                <td class="text_align_left" style="font-size:9px">
									<?php 
									if ($value->DELIVERY_ADDRESS!='')
									{
									?>
                                    	<strong><u>DELIVERY ADDRESS</u></strong><br />
										<?php echo $value->DELIVERY_ADDRESS; ?><br />
										<?php echo $value->DELIVERY_CITY; ?>, <?php echo $value->DELIVERY_STATE; ?>, <?php echo $value->DELIVERY_COUNTRY; ?><br /> 
										<?php echo $value->DELIVERY_ZIP; ?>
									<?php 
									}
									else
									{
									?>
										&nbsp;
									<?php
									}
									
									if ($value->BILLING_ADDRESS!='')
									{
									?>
                                    	<strong><u>BILLING ADDRESS</u></strong><br />
										<?php echo $value->BILLING_ADDRESS; ?><br />
										<?php echo $value->BILLING_CITY; ?>, <?php echo $value->BILLING_STATE; ?>, <?php echo $value->BILLING_COUNTRY; ?><br /> 
										<?php echo $value->BILLING_ZIP; ?><br /><br />
									<?php 
									}
									elseif($value->BILLING_ADDRESS=='' && $value->DELIVERY_ADDRESS!='')
									{
									?>
                                    	<strong><u>BILLING ADDRESS</u></strong><br />
										<?php echo $value->DELIVERY_ADDRESS; ?><br />
										<?php echo $value->DELIVERY_CITY; ?>, <?php echo $value->DELIVERY_STATE; ?>, <?php echo $value->DELIVERY_COUNTRY; ?><br /> 
										<?php echo $value->DELIVERY_ZIP; ?><br /><br />
									<?php
									}
									else
									{
									?>
                                    	&nbsp;
                                    <?php
									}
									?>
                                    <strong><u>VAT</u></strong><br />
									<?php echo $value->VAT_NUMBER; ?>
                                    
                                </td>
                                <td class="text_align_left" style="font-size:10px">
									<strong>VAT Amt: </strong><?php echo round($value->ENQUIRY_VAT_AMT,2); ?><br />
									<strong>Ship Amt: </strong><?php echo round($value->ENQUIRY_SHIPPING_AMT,2); ?><br />
									<strong>Total Amt: </strong><?php echo round($value->ENQUIRY_TOTAL_AMT,2); ?>
                                </td>
		
                                <td class="text_align_left" style="font-size:9px"><?php echo $value->ENQUIRY_STATUS; ?></td>
                                <td class="text_align_left" colspan="4">
                                	<table id="" class="table table-bordered table-striped">
										<?php
                                            $arrEnquiryProductList=$objProductManager->GetEnquiryProductList($value->ENQUIRY_QUOTE_ID);
                                            //echo '<pre>'; print_r($arrEnquiryProductList);
                                            if(count($arrEnquiryProductList)>0)
                                            {
                                                foreach($arrEnquiryProductList as $enqProVal)
												{
												?>
                                                <tr>
                                                    <td width="12%" style="font-size:9px"><?php echo $enqProVal->PRODUCT_CATEGORY_NAME  ;?></td>
                                                    <td width="12%" style="font-size:9px"><?php echo $enqProVal->PRODUCT_NAME  ;?></td>
                                                    <td width="6%" style="font-size:9px"><?php echo $enqProVal->PRODUCT_QUANTITY ;?></td>
                                                    <td width="6%" style="font-size:9px"><?php echo round($enqProVal->PRODUCT_AMT,2) ;?></td>
                                                </tr>
                                                <?php
												}
                                            }
                                            else
                                            {
                                            ?>
                                            <tr>
                                            	<td colspan="3">No Product</td>
                                            </tr>
                                            <?php
                                            }
                                        ?>
                                	</table>
                                </td>
                                <td class="text_align_center">
                                	<a href="Enquiry.php?urlstring=<?php echo EncryptURL('action=EditOrderDetails&enquiryId='.$value->ENQUIRY_QUOTE_ID.'&enquiryStatus='.$enquiryStatus.'&intOrderId='.$value->CUSTOMER_ORDER_NO.'&intSupplierId='.$value->CUSTOMER_SUPPLIER_NO) ;?>">
                                 	<button class="btn btn-success">Edit Order Details</button></a>
                                <?php  
                                if($value->ENQUIRY_STATUS == 'Quotation Pending')
                                {
                                ?>
                                    <button class="btn btn-danger btn-sm open" data-Id="<?php echo $value->ENQUIRY_QUOTE_ID."_".$value->ENQUIRY_STATUS ?>">
                                    Status Update</button>
                                	<a href="Enquiry.php?urlstring=<?php echo EncryptURL('action=SendQuotation&enquiryId='.$value->ENQUIRY_QUOTE_ID) ;?>">
                                 	<button class="btn btn-success">Send Quot</button></a>
                                <?php
                                }
                                elseif($value->ENQUIRY_STATUS == 'Quotation Sent')
                                {
                                ?>
                                    <button class="btn btn-danger btn-sm open" data-Id="<?php echo $value->ENQUIRY_QUOTE_ID."_".$value->ENQUIRY_STATUS ?>">
                                    Status Update</button>
                                	<a href="Enquiry.php?urlstring=<?php echo EncryptURL('action=SendQuotation&enquiryId='.$value->ENQUIRY_QUOTE_ID) ;?>">
                                 	<button class="btn btn-success">Re-Send Quot</button></a>
                                	<a href="Enquiry.php?urlstring=<?php echo EncryptURL('action=GenerateOrder&enquiryId='.$value->ENQUIRY_QUOTE_ID) ;?>">
                                 	<button class="btn btn-success">Gen Order</button></a>

                                <?php
                                }
								elseif($value->ENQUIRY_STATUS == 'Order Generated')
								{
								?>
                                    <button class="btn btn-danger btn-sm open" data-Id="<?php echo $value->ENQUIRY_QUOTE_ID."_".$value->ENQUIRY_STATUS ?>">
                                    Status Update</button>

                                    <a href="Enquiry.php?urlstring=<?php echo EncryptURL('action=GenerateOrder&enquiryId='.$value->ENQUIRY_QUOTE_ID) ;?>">
                                 	<button class="btn btn-success">Re-Gen Order</button></a>                               
								<?php
								}
								elseif($value->ENQUIRY_STATUS == 'Order Completed')
								{
								?>
                                   &nbsp;                                
								<?php
								}
                                ?>

                                <a href="Enquiry.php?urlstring=<?php echo EncryptURL('action=Delete&enquiryId='.$value->ENQUIRY_QUOTE_ID) ;?>" onClick="return confirm('Are You Sure you want to Delete Enquiry? \n Click OK to Continue, Cancel to Stop');">
                                <button class="btn btn-danger btn-sm open1">Del</button></a>
								<br />
								<?php
								$pdf_file_src = '../Attachments/Quotation-'.$value->ENQUIRY_QUOTE_ID.'.pdf';
								$pdf_file_path = 'https://www.sinelec-tech.com/admin/UI/Attachments/Quotation-'.$value->ENQUIRY_QUOTE_ID.'.pdf';
								$fileNamePdf = 'Quotation-'.$value->ENQUIRY_QUOTE_ID;
								$pdf_file_path='https://www.sinelec-tech.com/website/view.php?urlstring='.EncryptURL('fileName='.$fileNamePdf.'.pdf');
								//echo $pdf_file_path;
								if(!@file_exists($pdf_file_src))
								{
								
								}
								else
								{
								?>
									<a href="<?php echo $pdf_file_path; ?>" target="_blank">
                                 	<button class="btn btn-success">PDF File</button></a>
								<?php
								}
								?>
                                </td>	
                            </tr>
                        <?php 
                        }
                    }
                    else
                    {
                    ?>
                        <tr><td colspan="16" style="color:red;font-weight:bold;text-align:center">No Record found</td></tr>
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
                            
                            $modal.load('UpdateEnquiryStatus.php',{'val': val},
                            function(){
                            //alert(val);
                            $modal.modal('show');
                            });
                        });
                    });
                    </script>
				<?php
				}
				if($action=="EditOrderDetails")
				{
					//echo "<pre>";print_r($paramsArray);

				?>
                	<div>
                        <form action="Enquiry.php?urlstring=<?php echo EncryptURL('action=UpdateOrderDetails'); ?>" method="post">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <td colspan="4">
                                    	Update Order Details
                                    </td>
                                </tr>
                                <tr>
                                    <td>Order No.</td>
                                    <td><input type="text"  class="form-control"  name="intOrderNo" id="intOrderNo" value="<?php echo $paramsArray['intOrderId']; ?>"></td>
                                    <td>Supplier No.</td>
                                    <td><input type="text"  class="form-control"  name="intSupplierNo" id="intSupplierNo" value="<?php echo $paramsArray['intSupplierId']; ?>"></td>
                                    <input type="hidden" name="enquiryStatus" value="<?php echo $paramsArray['enquiryStatus']; ?>">
                                    <input type="hidden" name="enquiryId" value="<?php echo $paramsArray['enquiryId']; ?>">
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <button type="submit" class="btn btn-success pull-right">Update</button>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
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
$pagetitle = "Enquiry List :: sinelec-tech.com";
//Apply the template
include('../MasterTemplatePage.php');
?>