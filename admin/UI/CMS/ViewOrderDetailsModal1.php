<?php
ob_start();
//ini_set('display_errors','0');
//error_reporting(E_ALL | E_STRICT);
require_once ('../../BL/HomeManager.php');
require_once ('../../BL/ProductManager.php');
require_once ('../Common.php');
$user_id=$_SESSION['CUSTOMER_ID'];
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
$ProductsDetails1=array();
$objProductManager = new ProductManager(); 
$ProductsDetails=$objProductManager->GetProductToCart($user_id);
$OrderDetails=$objProductManager->GetOrderByUserId($user_id);
$OrderDetailsNew=array();

foreach($OrderDetails as $value) 
{
	$OrderDetailsNew[$value->ORDER_ID] = $value;
}

//echo "<pre>";print_r($OrderDetails);die;
(isset($paramsArray['action']))? $action=$paramsArray['action'] : $action="";
isset($paramsArray["msg"]) ? $msg=$paramsArray["msg"] : $msg="";
switch($action)
{	
	case "Details":
	//echo "<pre>";print_r($_POST);die;
		$objProductManager = new ProductManager(); 
		$ProductsDetails=$objProductManager->GetOrderDetailsByOrderId($_POST['order_id']);
		$OrderAddressDetails1=array();
		foreach($ProductsDetails as $value)
		{
			if($value->IMAGE_EXT!="" && $value->IMAGE_FOR=='Product')
			$ProductsDetails1[$value->PRODUCT_ID] = $value;
			}
				foreach($OrderDetails as $value) 
				{
					if($value->ORDER_ID==$_POST['order_id']){
					$OrderAddressDetails = $value;
				$OrderHistory[$value->ORDER_HISTORY_ID] = $value;
			}
		}
		$action="DetailsProduct";
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
			?> 
				<div class="col-sm-12  p-bottom10 "  style="border:2px solid #f1f1f1">
				<?php
				if(count($ProductsDetails1)>0)
				{ 
					$str = date('m-d-Y', strtotime($OrderAddressDetails->ORDER_DATE));
					$dateObj = DateTime::createFromFormat('m-d-Y', $str);?>
					<div class="col-sm-6 col-md-6 text-left" style="border-right:2px solid #f1f1f1">
						<div class="sectionTitle p-bottom10">
							<h3 align="left">Order Details</h3>
						</div>
						<div class="col-sm-12 col-md-12 p-20" >
							Order ID &nbsp;<label><?php echo $OrderAddressDetails->ORDER_ID;?></label><br>
							Order Date &nbsp; <label><?php echo $dateObj->format('M d Y');?></label><br>
							Total Amount &nbsp;<label><span class="glyphicon glyphicon-euro"></span><?php echo $OrderAddressDetails->ORDER_TOTAL_AMT;?></label><br>
							Transaction ID  &nbsp;<label><?php echo $OrderAddressDetails->TRANSACTION_ID;?></label>
						</div>
					</div>	
					<div class="col-sm-6 col-md-6" >
						<div class="sectionTitle p-bottom10">
							<h3 align="left">Address</h3>
						</div>
						<div class="col-sm-12 col-md-12"  >
							<label><?php echo $OrderAddressDetails->USER_NAME;?></label><br>
							Delivery Address &nbsp; <label> 
							<?php echo $OrderAddressDetails->ADDRESS.','.$OrderAddressDetails->CITY.' '.$OrderAddressDetails->STATE.'-'.$OrderAddressDetails->ZIP;?></label><br>
							Phone &nbsp;<label><?php echo $OrderAddressDetails->DELIVERY_PHONE_NO;?></label>
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
								<td><a href="Expansion-modules.php?urlstring=<?php echo EncryptURL('product_id='.$value->PRODUCT_ID); ?>">
								<img alt="..." class="img-responsive" style="width:100px;height:75px;" src="<?php  
								 echo "../admin/UI/Images/ProductImages/".$value->IMAGE_ID."_productImages.".$value->IMAGE_EXT; ?>"></a></td>
								<td class="pull-left">
								Product Code &nbsp;<label><?php echo $value->PRODUCT_CODE;?></label><br>
								Description &nbsp;<label><a href="Expansion-modules.php?urlstring=<?php echo EncryptURL('product_id='.$value->PRODUCT_ID); ?>">
								<?php echo $value->PRODUCT_NAME; ?></a></label><br>
								Quantity &nbsp;<label><?php echo $value->QUANTITY;?></label><br>
								Price &nbsp;<label><span class="number price"> <span class="glyphicon glyphicon-euro"></span>
								<?php  echo number_format((float)$value->QUANTITY*$value->PRODUCT_AMT*(100-$value->PRODUCT_DISCOUNT)/100, 2, '.', '');   ?></span></label></td>
							</tr>
						
						<?php 
							}
						} 
						?>
						</tbody>
						</table>
						</div>
						<div class="col-sm-6 col-md-6" >
						
						<table class="table cart ">
						<tbody>
							<tr>
								<th>Order status history</th>
							<th> Date</th>
						</tr>
						<?php 
						foreach($OrderHistory as $value)
						{ 
							if($value->ORDER_CURRENT_STATUS!="Cart")
							{
								$str = date('m-d-Y', strtotime($value->ORDER_STATUS_DATE));
								$dateObj = DateTime::createFromFormat('m-d-Y', $str);
						?>
							<tr>
								<td><?php echo $value->ORDER_STATUS;?></td>
								<td> <?php echo $dateObj->format('M d Y');?></td>
							</tr>
				<?php 
							}
						}
				}
				?>
						</tbody>
						</table>
					</div>
				 </div>
				</div>
			<?php 
			}
			if($action=='')
			{ 
			?>
			<div class="col-sm-12 col-md-12">
				<div class="table-responsive">
					<form  action="OrderDetails.php?urlstring=<?php echo EncryptURL('action=Details'); ?>" method="post" enctype="multipart/form-data">
						<table class="table cart ">
						<?php 
						if(count($OrderDetailsNew)>0) 
						{
						?>
							<tbody>
								<tr>
									 <td>Name</td>
									 <td>Mobile No</td>
									 <td>Order Id</td>
									 <td>Transaction ID</td>
									 <td>Order Date</td>
									 <td> Current Status</td>
									 <td>Order Total Amount</td>
									 <td>Shipping Address</td>
									 <td>View Details</td>
								 </tr>
							<?php 
							foreach($OrderDetailsNew as $value)
							{
								if($value->ORDER_CURRENT_STATUS!="Cart")
								{
							  ?>
								<tr>
									<td><?php echo $value->USER_NAME;?></td>
									<td><?php echo $value->DELIVERY_PHONE_NO;?></td>
									<td><?php echo $value->ORDER_ID;?></td>
									<td><?php echo $value->TRANSACTION_ID;?></td>
							<?php 	$str = date('m-d-Y', strtotime($value->ORDER_DATE));
									$dateObj = DateTime::createFromFormat('m-d-Y', $str);?>
									 <td><?php echo $dateObj->format('M d Y');?></td>
									
									<td><?php echo $value->ORDER_CURRENT_STATUS;?></td>
									
									<td><?php echo $value->ORDER_TOTAL_AMT;?></td>
									
									<td><?php echo $value->ADDRESS.'<br>'.$value->CITY.' '.$value->STATE;?></td>
									<input class="col-sm-3 " type="hidden" name="order_id" id="order_id" value="<?php echo $OrderDetails[0]->ORDER_ID;?>" />
									<td ><input class="btn btn-primary btn-xlg " type="submit" value="View Details" /></td></tr>
							<?php  
								} 
							}
							?>
							</tbody>
						</table>
						</form>
						<?php 
						}
						else
						{
						?>
						<table class="table cart ">
						<tbody>
							<tr>
								<td style="color:#FF0000;text-align:center">No Order History</td>
							</tr>
						</tbody>
						</table>
						<?php 
						}
						?>
				 </div>
			 <?php /*   <form class="thirds text-center">
					<h3 class="text-left m-bottom30">Add a coupon code</h3>
					<input class="col-sm-3 " type="text" placeholder="Coupon Code" />
					<input class="btn btn-primary btn-xlg col-sm-3" type="submit" value="Apply" />
				</form>
				*/?>
			</div>
			<?php 
			} 
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

    <!--Infinite Scroll-->
    <script src="vendors/infinitescrol/jquery.infinitescroll.min.js"></script>

    <!--Theme JS-->
    <script src="js/theme.js"></script>
   

</body>
</html>