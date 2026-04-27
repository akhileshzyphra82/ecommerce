<?php
ob_start();
//ini_set('display_errors',0);
//error_reporting(E_ALL | E_STRICT);
require_once ('../admin/BL/HomeManager.php');
require_once ('../admin/BL/ProductManager.php');
$userId=$_SESSION['CUSTOMER_ID'];
require_once ('Common.php');
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
isset($paramsArray["page"]) ? $page=$paramsArray["page"] : $page=1;
//echo "<pre>";print_r($paramsArray);die;
$limit=10;
$startFrom=($page-1)*$limit;
$ProductsDetails1=array();
$objProductManager = new ProductManager(); 
$OrderDetails=$objProductManager->GetOrderByUserId($userId);
$OrderDetailsNew=$objProductManager->GetTotalOrderByUserId($userId);
$OrderDetailsNewPaging=$objProductManager->GetTotalOrderByUserIdPaging($userId,$startFrom,$limit);
//echo "<pre>";print_r($OrderDetailsNewPaging);die;
$PagingCount=count($OrderDetailsNew)/$limit;
(isset($paramsArray['action']))? $action=$paramsArray['action'] : $action="";
isset($paramsArray["msg"]) ? $msg=$paramsArray["msg"] : $msg="";
	switch($action)
	{	
	case "Details":
		//echo "<pre>";print_r($_POST);die;
		$intOrderId=(isset($_POST['order_id'])) ? $_POST['order_id'] : $paramsArray['order_id'];
		$objProductManager = new ProductManager(); 
		$ProductsDetails=$objProductManager->GetOrderDetailsByOrderId($intOrderId);
		$OrderAddressDetails1=array();
		foreach($ProductsDetails as $value) 
		{
			if($value->IMAGE_EXT!="" && $value->IMAGE_FOR=='Product')
			$ProductsDetails1[$value->PRODUCT_ID] = $value;
		}
		foreach($OrderDetails as $value) 
		{
			if($value->ORDER_ID==$intOrderId)
			{
				$OrderAddressDetails = $value;
				$OrderHistory[$value->ORDER_HISTORY_ID] = $value;
			}
		}
		$action="DetailsProduct";
	break;
	case "TrackOrder":
		//echo "<pre>";print_r($_POST);die;
		$intOrderId=(isset($_POST['order_id'])) ? $_POST['order_id'] : $paramsArray['order_id'];
		$objProductManager = new ProductManager(); 
		$ProductsDetails=$objProductManager->GetOrderDetailsByOrderId($intOrderId);
		$OrderAddressDetails1=array();
		foreach($ProductsDetails as $value) 
		{
			if($value->IMAGE_EXT!="" && $value->IMAGE_FOR=='Product')
			$ProductsDetails1[$value->PRODUCT_ID] = $value;
		}
		foreach($OrderDetails as $value) 
		{
			if($value->ORDER_ID==$intOrderId)
			{
				$OrderAddressDetails = $value;
				$OrderHistory[$value->ORDER_HISTORY_ID] = $value;
			}
		}
		$action="Track";
	break;
	}
	?>
    <!--Breadcrumb-->
    <section class="row page_header section-spacing">
        <div class="container">
            <h3>Order Details</h3>
            <ol class="breadcrumb">
                <li><a href="index.php">home</a></li>
                <li class="active">Order Details</li>
            </ol>
        </div>
    </section>

    <!--Cart Details-->
    <section class="row shopping-cart section-spacing2">
        <div class="container">
            <div class="row">
            	<!--Order Details-->
               <?php 
				if($action=='DetailsProduct')
				{
					if(count($OrderAddressDetails)>0) 
					{ 
						//echo "<pre>";print_r($OrderAddressDetails);
					?>  <style>
							.table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td
							{
								padding:10px 10px;
							}
					 	</style>
                        <div class="col-sm-10 col-md-10 center-block">
                            <div class="sectionTitle p-bottom10">
                                <h4 align="left">ORDER DETAILS</h4>
                            </div>
                            <div class="table-responsive">
                                <table class="table cart">
                                    <thead>
                                        <tr>
                                            <th style="text-align:center">PRODUCT</th>
                                            <th style="text-align:center">QUANTITY</th>
                                            <th style="text-align:center">TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    foreach($ProductsDetails1 as $value)
                                    { 
                                        if($value->ORDER_CURRENT_STATUS!="Cart")
                                        {
                                        ?>
                                            <tr>
                                                <td>
                                                    <div class="col-sm-2 col-md-2">
                                                        <a href="Expansion-modules.php?urlstring=<?php echo EncryptURL('product_id='.$value->PRODUCT_ID); ?>"><img alt="..." class="img-responsive" style="width:100px;height:75px;" src="<?php   echo "../admin/UI/Images/ProductImages/".$value->IMAGE_ID."_productImages.".$value->IMAGE_EXT; ?>"></a>
                                                    </div>
                                                    <div class="col-sm-10 col-md-10">
                                                        <?php echo $value->PRODUCT_NAME; ?><br>
                                                        Product Code &nbsp;<label><?php echo $value->PRODUCT_CODE;?></label><br>
                                                        Description &nbsp;<label><a target="_blank" href="Expansion-modules.php?urlstring=<?php echo EncryptURL('product_id='.$value->PRODUCT_ID); ?>"><?php echo $value->PRODUCT_NAME; ?></a></label>                                               
                                                     </div>
                                                </td>
                                                <td style="text-align:center"><?php echo $value->QUANTITY;?></td>
                                                <td style="text-align:center"><span class="glyphicon glyphicon-euro"></span><?php  echo number_format((float)$value->QUANTITY*$value->PRODUCT_AMT*(100-$value->PRODUCT_DISCOUNT)/100, 2, '.', '');   ?></td>
                                            </tr>
                                        <?php
                                        }
                                    }
									//echo "<pre>";print_r($ProductsDetails);
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive">
                                <table class="table cart">
                                    <tbody>
                                        <tr>
                                            <td>Subtotal</td>
                                            <td style="text-align:right"><span class="glyphicon glyphicon-euro"></span><?php echo number_format($ProductsDetails[0]->ORDER_TOTAL_AMT,2);?></td>
                                        </tr>
                                        <tr>
                                            <td>Shipping</td>
                                            <td style="text-align:right"><span class="glyphicon glyphicon-euro"></span><?php echo number_format($ProductsDetails[0]->SHIPING_AMT,2);?></td>
                                        </tr>
                                        <tr>
                                            <td>Tax</td>
                                            <td style="text-align:right"><span class="glyphicon glyphicon-euro"></span><?php echo number_format($ProductsDetails[0]->TAX_TOTAL_AMOUNT,2);?></td>
                                        </tr>
                                        <tr>
                                            <td>Payment Mode/Transaction Number</td>
                                            <td style="text-align:right"><?php echo $OrderAddressDetails->TRANSACTION_ID;?></td>
                                        </tr>
                                        <tr>
                                            <td>Total</td>
                                            <td style="text-align:right"><span class="glyphicon glyphicon-euro"></span><?php echo number_format($ProductsDetails[0]->ORDER_TOTAL_AMT+$ProductsDetails[0]->SHIPING_AMT+$ProductsDetails[0]->TAX_TOTAL_AMOUNT,2);?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <div class="sectionTitle p-bottom10">
                                        <h4 align="left">CUSTOMER DETAILS</h4>
                                    </div>
                                    <p><strong>Email: </strong><?php echo $OrderAddressDetails->COMMUNICATION_EMAIL_ID;?><br>
                                    <strong>Telephone: </strong><?php echo $OrderAddressDetails->COMMUNICATION_MOBILE_NUM;?></p>
                                    <div class="sectionTitle p-bottom5 p-top10">
                                        <h5 align="left">SHIPPING ADDRESS</h5>
                                    </div>
                                    <p><?php echo $OrderAddressDetails->COMPANY_NAME;?><br><?php echo $OrderAddressDetails->USER_NAME;?><br>
									<?php echo $OrderAddressDetails->ADDRESS.' '.$OrderAddressDetails->LANDMARK.' '.$OrderAddressDetails->CITY.' '.$OrderAddressDetails->STATE.'<br>'.$OrderAddressDetails->COUNTRY."<br>".$OrderAddressDetails->ZIP;?></p>
                                    <div class="sectionTitle p-bottom5 p-top10">
                                        <h5 align="left">BILLING ADDRESS</h5>
                                    </div>
                                    <p><?php echo $OrderAddressDetails->COMPANY_NAME;?><br><?php echo $OrderAddressDetails->USER_NAME;?><br>
									<?php echo $OrderAddressDetails->ADDRESS.' '.$OrderAddressDetails->LANDMARK.' '.$OrderAddressDetails->CITY.' '.$OrderAddressDetails->STATE.'<br>'.$OrderAddressDetails->COUNTRY."<br>".$OrderAddressDetails->ZIP;?></p>
                                </div>
                            </div>
                        </div>
<?php /*?>                        <div class="col-sm-12  p-bottom10 "  style="border:2px solid #f1f1f1">
                            <?php 
                            $str = date('m-d-Y', strtotime($OrderAddressDetails->ORDER_DATE));
                            $dateObj = DateTime::createFromFormat('m-d-Y', $str);?>
                            <div class="col-sm-6 col-md-6 text-left" style="border-right:2px solid #f1f1f1">
                                <div class="sectionTitle p-bottom10">
                                    <h3 align="left">Order Details</h3>
                                </div>
                                <div class="col-sm-12 col-md-12 p-20" >
                                
                                    Order ID &nbsp;<label><?php echo $OrderAddressDetails->ORDER_ID;?></label><br>
                                    Order Date &nbsp; <label><?php echo $dateObj->format('F d, Y');?></label><br>
                                    Total Amount &nbsp;<label><span class="glyphicon glyphicon-euro"></span><?php echo number_format($ProductsDetails[0]->ORDER_TOTAL_AMT+$ProductsDetails[0]->SHIPING_AMT,2);?></label><br>
                                    Transaction ID  &nbsp;<label><?php echo $OrderAddressDetails->TRANSACTION_ID;?></label>
                                </div>
                            </div>	
                            <div class="col-sm-6 col-md-6" >
                                <div class="sectionTitle p-bottom10">
                                    <h3 align="left">Address</h3>
                                </div>
                                <div class="col-sm-12 col-md-12"  >
                                    <label><?php echo $OrderAddressDetails->USER_NAME;?></label><br>
                                    Delivery Address &nbsp; <label> <?php echo $OrderAddressDetails->ADDRESS.','.$OrderAddressDetails->CITY.' '.$OrderAddressDetails->STATE.'-'.$OrderAddressDetails->ZIP;?></label><br>
                                     Phone &nbsp;<label><?php echo $OrderAddressDetails->DELIVERY_PHONE_NO;?></label><br>
                                     EU VAT Number :&nbsp;<label><?php echo $OrderAddressDetails->EU_VAT;?></label>
    							</div>
                            </div>
                        </div>	
                        <div class="col-sm-12  p-bottom10"  style="border:2px solid #f1f1f1">
                            <div class="col-sm-6 col-md-6" >
                                <table class="table cart ">
                                <?php 
                                foreach($ProductsDetails1 as $value)
                                { 
                                    if($value->ORDER_CURRENT_STATUS!="Cart")
                                    {
                                    ?>
                                        <tbody>
                                            <tr>
                                                <td><a href="Expansion-modules.php?urlstring=<?php echo EncryptURL('product_id='.$value->PRODUCT_ID); ?>"><img alt="..." class="img-responsive" style="width:100px;height:75px;" src="<?php   echo "../admin/UI/Images/ProductImages/".$value->IMAGE_ID."_productImages.".$value->IMAGE_EXT; ?>"></a></td>
                                                <td class="pull-left">
                                                Product Code &nbsp;<label><?php echo $value->PRODUCT_CODE;?></label><br>
                                                Description &nbsp;<label><a href="Expansion-modules.php?urlstring=<?php echo EncryptURL('product_id='.$value->PRODUCT_ID); ?>">
                                                <?php echo $value->PRODUCT_NAME; ?></a></label><br>
                                                Quantity &nbsp;<label><?php echo $value->QUANTITY;?></label><br>
                                                Price &nbsp;<label><span class="number price"> <span class="glyphicon glyphicon-euro"></span><?php  echo number_format((float)$value->QUANTITY*$value->PRODUCT_AMT*(100-$value->PRODUCT_DISCOUNT)/100, 2, '.', '');   ?></span></label></td>
                                            </tr>
                                        </tbody>
                                    <?php 
                                    }
                                }  
                                ?>
                                </table>
                            </div>
                            <div class="col-sm-6 col-md-6" >
                            <table class="table cart ">
                                <tbody>
                                    <tr><th>Order status history</th><th> Date</th></tr>
                                    <?php 
                                    foreach($OrderHistory as $value)
                                    { 
                                        if($value->ORDER_CURRENT_STATUS!="Cart")
                                        {
                                            $str = date('m-d-Y', strtotime($value->ORDER_STATUS_DATE));
                                            $dateObj = DateTime::createFromFormat('m-d-Y', $str);
                                            ?>
                                            <tr><td><?php echo $value->ORDER_STATUS;?></td><td> <?php echo $dateObj->format('M d Y');?></td></tr>
                                        <?php
                                        }
                                    }
                                     ?>
                                </tbody>
                            </table>
                            </div>
                         </div>
<?php */?>					<?php 
					}
				}
				if($action=='Track')
				{
					if(count($OrderAddressDetails)>0) 
					{ 
						//echo "<pre>";print_r($OrderAddressDetails);
					?>  <style>
							.table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td
							{
								padding:10px 10px;
							}
					 	</style>
                        <div class="col-sm-10 col-md-10 center-block">
                            <div class="sectionTitle p-bottom10">
                                <h4 align="left">ORDER HISTORY</h4>
                            </div>
                            <div class="table-responsive">
                                <table class="table cart">
                                    <thead>
                                        <tr>
                                            <th>DATE</th>
                                            <th>STATUS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    foreach($OrderHistory as $value)
                                    { 
                                        if($value->ORDER_CURRENT_STATUS!="Cart")
                                        {
                                            $str = date('m-d-Y', strtotime($value->ORDER_STATUS_DATE));
                                            $dateObj = DateTime::createFromFormat('m-d-Y', $str);
                                        ?>
                                            <tr>
                                                <td><?php echo $dateObj->format('M d Y');?></td>
                                                <td><?php echo $value->ORDER_STATUS;?></td>
                                            </tr>
                                        <?php
                                        }
                                    }
									//echo "<pre>";print_r($ProductsDetails);
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
					<?php 
					}
				}
				if($action=='')
				{?>
					<style>
                    table.cart
                    {
                        border:0px;
                    }
                    .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td
                    {
                        border-top:0px;
                    }
                    table.cart tr:nth-child(even) {
                        background: #ffffff
                    }
                    table.cart tr:nth-child(odd) {
                        background: #ffffff; 
                    }
                    .cart th
                    {
                        background-color:#ffffff; 
                    }
                    </style>
				   <div class="col-sm-12 col-md-12">
						<div class="table-responsive">
							<?php
							if(isset($_REQUEST['status']))
							{?>
								<table class="table cart ">
									<tbody>
										<tr>
											<td colspan="8"><font color="#009933"><strong><?php echo $_REQUEST['status'];?></strong></font></td>
										</tr>
									</tbody>
								</table>
							<?php
							}
							?>
							<?php 
							if(count($OrderDetailsNewPaging)>0) 
							{ 
							?>
								<table class="table cart ">
									<tbody>
										<tr>
											<th>Order</th>
											<th>Date</th>
											<th>Status</th>
											<th>Total</th>
											<th>Actions</th>
										</tr>
										<?php
										 $checkFlag=1;
										 foreach($OrderDetailsNewPaging as $value)
										 {
											$ProductsHistoryDetails=$objProductManager->GetProductHistoryByHistoryId($value->ORDER_ID);
											$ProductsDetails=$objProductManager->GetOrderDetailsByOrderId($value->ORDER_ID);
											
											$ProductsDetails1=array();
											foreach($ProductsDetails as $val) 
											{
												$ProductsDetails1[$val->PRODUCT_ID] = $val;
											}
											$TotalProdCount=0;
										
											foreach($ProductsDetails1 as $prodVal)
											{
												//echo $prodVal->QUANTITY."<br>";
												$TotalProdCount+=$prodVal->QUANTITY;
											}
											//echo "<pre>";print_r($value);
											
											$trackingUrl=$value->DISPATCH_COURIER_TRACKING_URL;
											if (strpos($trackingUrl, 'http') == false) 
											{
												$trackingUrlAct = 'http://'.$trackingUrl;
											}
											else
											{
												$trackingUrlAct = $trackingUrl;
											}
																						
										  	?>
											<tr>
												<td><a href="OrderDetails.php?urlstring=<?php echo EncryptURL('action=Details&order_id='.$value->ORDER_ID); ?>">#<?php echo $value->ORDER_ID;?></a></td>
												<?php 	$str = date('m-d-Y', strtotime($ProductsHistoryDetails[0]->ORDER_STATUS_DATE));
												$dateObj = DateTime::createFromFormat('m-d-Y', $str);?>
												<td><?php echo $dateObj->format('F d, Y');?></td>
												<td><?php echo $value->ORDER_CURRENT_STATUS;?></td>
												
												<td><span class="glyphicon glyphicon-euro"></span><?php echo number_format($value->ORDER_TOTAL_AMT+$value->SHIPING_AMT,2)." for ".$TotalProdCount;?> items</td>
												<td>
												<?php
												if($trackingUrl!='' && $value->ORDER_CURRENT_STATUS!='Delivered')
												{
												?>
													<a style="padding-left:10px;" href="<?php echo $trackingUrlAct; ?>" class="btn btn-secondary btn-sm" target="_blank">Track</a>
												<?php
												}
												?>	
												<a style="padding-right:10px;" class="btn btn-secondary btn-sm"  href="OrderDetails.php?urlstring=<?php echo EncryptURL('action=TrackOrder&order_id='.$value->ORDER_ID); ?>">History</a>
												<a style="padding-left:10px;" class="btn btn-secondary btn-sm" href="ReOrder.php?urlstring=<?php echo EncryptURL('action=Details&order_id='.$value->ORDER_ID); ?>">Order Again</a>
												</td>
											</tr>
										<?php  
										} 
										?>  
								  </tbody>
								</table>
								Page :
								<?php
								//echo "<pre>";print_r($PagingCount);
								for($indexPage=1;$indexPage<=ceil($PagingCount);$indexPage++)
								{
								 ?>
								  <a href="OrderDetails.php?urlstring=<?php echo EncryptURL("page=".$indexPage) ?>"><?php echo $indexPage; ?></a>
								 <?php
								}
								?>
							<?php 
							 }
							 else
							 {?>
								<table class="table cart ">
									<tbody>
										<tr>
											<td style="color:#FF0000;text-align:center">No order details available</td>
										</tr>
									</tbody>
								</table>
							<?php 
							 }?>
						 </div>
					</div>
				<?php 
				} ?>
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
    <!--Infinite Scroll-->
    <script src="vendors/infinitescrol/jquery.infinitescroll.min.js"></script>
    <!--Theme JS-->
    <script src="js/theme.js"></script>
</body>
</html>