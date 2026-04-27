<?php
ob_start();
ini_set('display_errors',0);
////error_reporting(E_ALL | E_STRICT);
require_once ('../admin/BL/HomeManager.php');
require_once ('../admin/BL/ProductManager.php');
require_once ('Common.php');
if(isset($_SESSION))
$user_id=$_SESSION['CUSTOMER_ID'];
$objHomeManager = new HomeManager(); 
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
	
function generateRandomString($length = 10) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
$paramsArray = GetQueryStringParameters();
$ProductsDetails1=array();
$objProductManager = new ProductManager(); 
$ProductsDetails=$objProductManager->GetProductToCart($user_id);
foreach($ProductsDetails as $value) {
if($value->IMAGE_EXT!="" && $value->IMAGE_FOR=='Product' && $value->ORDER_CURRENT_STATUS=="Cart")
$ProductsDetails1[$value->PRODUCT_ID] = $value;
}
if(isset($_POST['productId']))
$_SESSION['productId'] = $_POST['productId'];
(isset($paramsArray['action']))? $action=$paramsArray['action'] : $action="";
isset($paramsArray["msg"]) ? $msg=$paramsArray["msg"] : $msg="";
$price=0;
switch($action)
	{	
	
	case "AddMultiple":  ///////////////////////this is comming from ReOrder page 
		$products=$_POST['products'];
		$productsQty=$_POST['productsQty'];
		if(count($products)>0)
		{
			foreach($products as $productId)
			{
				if($productsQty[$productId]>0)
					$quantity=$productsQty[$productId];
				else
					$quantity=1;
					
				
					$ProductsDetails=$objHomeManager->GetProductData($productId);
					$productArray=array();
					$transaction_id=generateRandomString();
					$product_code=$ProductsDetails[0]->PRODUCT_CODE;
					$product_amt=$ProductsDetails[0]->PRODUCT_AMT;
					$product_tax=$ProductsDetails[0]->PRODUCT_TAX;
					$product_discount=$ProductsDetails[0]->PRODUCT_DISCOUNT;
					$order_total_amt=$ProductsDetails[0]->PRODUCT_AMT*(100-$ProductsDetails[0]->PRODUCT_DISCOUNT)/100;
					$productArray=array("user_id"=>$user_id,"productId"=>$productId,"transection_id"=>$transaction_id,"order_current_status"=>'Cart',"order_total_amt"=>$order_total_amt,
					"quantity"=>$quantity,"product_code"=>$product_code,"product_amt"=>$product_amt,"product_tax"=>$product_tax,"product_discount"=>$product_discount);
					//echo '<pre>'; print_r($productArray); 
					//die;
					$objProductManager = new ProductManager(); 
					foreach($ProductsDetails1 as $val)
					{
						if($val->ORDER_CURRENT_STATUS=='Cart')
						{
							$order_id=$val->ORDER_ID;
							$ORDER_CURRENT_STATUS=$val->ORDER_CURRENT_STATUS;
						}
					}
					if(isset($ProductsDetails1[$productId]) && $ProductsDetails1[$productId]->ORDER_CURRENT_STATUS=='Cart')
						echo '';
					else
					{
					if(count($ProductsDetails1)>0 && $ORDER_CURRENT_STATUS=='Cart')
						$ProductsDetails=$objProductManager->InsertOtherProductToCart($productArray,$order_id);
					else
						$ProductsDetails=$objProductManager->InsertProductToCart($productArray);
						
					}
			}
		}
		header("location:cart.php?urlstring=".EncryptURL("action=&msg=added"));
	break;
	
	
	
	case "Add":
		if($productId=='')
			(isset($paramsArray['productId']))? $productId=$paramsArray['productId'] : $productId=$_SESSION['productId'];
		if($productId=='')
			(isset($_POST['productId']))? $productId=$_POST['productId'] : $productId=$_SESSION['productId'];
		
		
		$ProductsDetails=$objHomeManager->GetProductData($productId);
		
		$productArray=array();
		$transaction_id=generateRandomString();
		$quantity=1;
		$product_code=$ProductsDetails[0]->PRODUCT_CODE;
		$product_amt=$ProductsDetails[0]->PRODUCT_AMT;
		$product_tax=$ProductsDetails[0]->PRODUCT_TAX;
		$product_discount=$ProductsDetails[0]->PRODUCT_DISCOUNT;
		$order_total_amt=$ProductsDetails[0]->PRODUCT_AMT*(100-$ProductsDetails[0]->PRODUCT_DISCOUNT)/100;
		$productArray=array("user_id"=>$user_id,"productId"=>$productId,"transection_id"=>$transaction_id,"order_current_status"=>'Cart',"order_total_amt"=>$order_total_amt,
		"quantity"=>$quantity,"product_code"=>$product_code,"product_amt"=>$product_amt,"product_tax"=>$product_tax,"product_discount"=>$product_discount);
		
		
		//echo '<pre>'; print_r($productArray); 
		//die;
		
		$objProductManager = new ProductManager(); 
		foreach($ProductsDetails1 as $val)
		{
			if($val->ORDER_CURRENT_STATUS=='Cart')
			{
				$order_id=$val->ORDER_ID;
				$ORDER_CURRENT_STATUS=$val->ORDER_CURRENT_STATUS;
			}
		}
		if(isset($ProductsDetails1[$productId]) && $ProductsDetails1[$productId]->ORDER_CURRENT_STATUS=='Cart')
			$ProductsDetails=$objProductManager->UpdateProductFromCart('+',$productId,$user_id,$order_total_amt,$ProductsDetails1[$productId]->ORDER_ID);
		else
		{
		if(count($ProductsDetails1)>0 && $ORDER_CURRENT_STATUS=='Cart')
			$ProductsDetails=$objProductManager->InsertOtherProductToCart($productArray,$order_id);
		else
			$ProductsDetails=$objProductManager->InsertProductToCart($productArray);
			header("location:cart.php?urlstring=".EncryptURL("action=&msg=added"));
		}
	break;
	
	case "Remove":
		$productId=$paramsArray['productId'];
		$quantity=$paramsArray['quantity'];
		$order_id=$paramsArray['ORDER_ID'];
		$ProductsDetails=$objHomeManager->GetProductData($productId);
		$order_total_amt=$ProductsDetails[0]->PRODUCT_AMT*(100-$ProductsDetails[0]->PRODUCT_DISCOUNT)/100;
		$objProductManager = new ProductManager(); 
		$ProductsDetails=$objProductManager->UpdateProductFromCart($quantity,$productId,$user_id,$order_total_amt,$order_id);
		header("location:cart.php?urlstring=".EncryptURL("action=&msg=added"));
	break;
	case "Delete":
		$productId=$paramsArray['productId'];
		$quantity=$paramsArray['quantity'];
		$order_id=$paramsArray['ORDER_ID'];
		$ProductsDetails=$objHomeManager->GetProductData($productId);
		$order_total_amt=$ProductsDetails[0]->PRODUCT_AMT*(100-$ProductsDetails[0]->PRODUCT_DISCOUNT)/100;
		$objProductManager = new ProductManager(); 
		$ProductsDetails=$objProductManager->DeleteProductFromCart($quantity,$productId,$user_id,$order_total_amt,$order_id);
		header("location:cart.php?urlstring=".EncryptURL("action=&msg=added"));
	break;
	}
	?>
    
    <!--Breadcrumb-->
    <section class="row page_header section-spacing">
        <div class="container">
            <h3>Shopping Cart</h3>
            <ol class="breadcrumb">
                <li><a href="index.php">home</a></li>
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
    <!--Cart Details-->
    <section class="row shopping-cart section-spacing2">
        <div class="container">
        	<div class="sectionTitle p-bottom40">
                <h2>Shopping Cart</h2>
            </div>
			<div class="stepwizard">
				<div class="stepwizard-row">
					<div class="stepwizard-step" style="width:5%">
						<a href="https://sinelec-tech.com/website/cart.php"><button type="button" class="btn-success button-circle">1</button></a>
						<p>Cart</p>
					</div>
					<div class="stepwizard-step" style="width:95%">
						<?php if($_SESSION['AddressStatus']!="") {?><a href="cart-checkout.php?urlstring=<?php echo EncryptURL('action=&OrderTotalAmt='.$_POST['total_order_amt']); ?>"><button type="button" class="btn-danger button-circle">2</button></a><?php } else { ?><button type="button" class="btn-danger button-circle">2</button><?php }?>
						<p>Address</p>
					</div>
					<div class="stepwizard-step" style="width:0%">
						<button type="button" class="btn-danger button-circle">3</button>
						<p>Payment</p>
					</div>
				</div>
			</div>		
		<?php
		// echo "<pre>";print_r($ProductsDetails);die;
		 if(count($ProductsDetails1)>0) { ?>
            <div class="row">
            	<!--Order Details-->
                <div class="col-sm-8">
                    <div class="table-responsive">
                        <table class="table cart m-bottom40">
                            <tbody>
                                <tr>
                                    <th>Product / Options</th>
                                    <th>Description</th>
									 <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
							<?php 
							foreach($ProductsDetails1 as $value)
							 { 
							if($value->ORDER_CURRENT_STATUS=="Cart"){
							?>
                                <tr>
                                    <td style="float:left;"><a href="Expansion-modules.php?urlstring=<?php echo EncryptURL('product_id='.$value->PRODUCT_ID); ?>"><img alt="..." class="img-responsive" style="width:100px;height:75px;" src="<?php   echo "../admin/UI/Images/ProductImages/".$value->IMAGE_ID."_productImages.".$value->IMAGE_EXT; ?>"></a>
                                        
                                    </td>
                                    <td>
									<a href="Expansion-modules.php?urlstring=<?php echo EncryptURL('product_id='.$value->PRODUCT_ID); ?>"><?php echo $value->PRODUCT_NAME; ?></a>
                                </td>
								<td><a class="btn btn-sm"  href="cart.php?urlstring=<?php if($value->QUANTITY!=1) echo EncryptURL('action=Remove&productId='.$value->PRODUCT_ID.'&quantity=-&ORDER_ID='.$value->ORDER_ID); ?>" title="" <?php if($value->QUANTITY==1) echo "disabled"; ?>><i class="ti-minus"></i></a><?php echo $value->QUANTITY; ?><a class="btn  btn-sm" href="cart.php?urlstring=<?php echo EncryptURL('action=Remove&productId='.$value->PRODUCT_ID.'&quantity=+&ORDER_ID='.$value->ORDER_ID); ?>" title=""><i class="ti-plus"></i></a> </td>
                                    <td><span class="number price"> <span class="glyphicon glyphicon-euro"></span><?php  echo number_format((float)$value->QUANTITY*$value->PRODUCT_AMT*(100-$value->PRODUCT_DISCOUNT)/100, 2, '.', '');   ?></span></td>
									<?php   $price=$price+number_format((float)$value->QUANTITY*$value->PRODUCT_AMT*(100-$value->PRODUCT_DISCOUNT)/100, 3, '.', ''); ?>
                                    <td scope="row">
                                        <a class="btn btn-red btn-sm" data-original-title="Remove from cart" data-placement="top" data-toggle="tooltip" href="cart.php?urlstring=<?php echo EncryptURL('action=Delete&productId='.$value->PRODUCT_ID.'&ORDER_ID='.$value->ORDER_ID); ?>" title="" onClick=""><i class="ti-close"></i></a> 
                                    </td>
                                </tr>
                            </tbody>
							<?php } }  ?>
							
                        </table>
                     </div>
<!--                  <form class="thirds text-center">
                        <h3 class="text-left m-bottom30">Add a coupon code</h3>
                        <input class="col-sm-3 " type="text" placeholder="Coupon Code" />
                        <input class="btn btn-primary btn-xlg col-sm-3" type="submit" value="Apply" />
                    </form>
-->
                </div>

                <!--Order Summary-->
				<?php if(count($ProductsDetails1)>0) {
				$objProductManager = new ProductManager(); 
				//$ShipingProductAmt=$objProductManager->getShipingAmt();
				 ?>
                <div class="col-sm-4">
                    <div class="summary-box m-bottom30 bg-gray">
                        <h3 class="text-center padding20 summary-boxtitle">Order Summary</h3>
                        <table class="table m-bottom0">
                            <tbody>
                                <tr class="">
                                    <th scope="row">Cart Subtotal</th>
                                    <td class="text-right"><span class="glyphicon glyphicon-euro"></span><?php echo number_format($price,2); ?></td>
                                </tr>
<!--                                <tr>
                                    <th scope="row"><small class="text-muted">Shipping Amt</small></th>
                                    <td class="text-right text-muted"><small><span class="glyphicon glyphicon-euro"></span><?php if(isset($ShipingProductAmt)) echo $ShipingProductAmt[0]->SHIPING_AMT; ?></small></td>
                                </tr>
-->                                <tr>
                                    <th scope="row">Grand Total</th>
                                    <td>
                  <h2 class="price text-right"><span class="glyphicon glyphicon-euro"></span><?php if(isset($price)) echo number_format($price, 2) ; ?></h2>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                     <form action="cart-checkout.php?urlstring=<?php echo EncryptURL('action=PlaceOrder'); ?>">
                       <a href="cart-checkout.php?urlstring=<?php echo EncryptURL('action=&OrderTotalAmt='.$price); ?>"> <input class="btn btn-primary btn-xlg m-bottom20" type="button" value="Place Order" /></a>
                       <a href="products.php"> <input class="btn btn-border btn-xlg"  type="button" value="Continue Shopping" /></a>
                     </form>
                </div>
				<?php }  ?>
            </div>
			<?php  } else { ?>
			<div class="row" align="center">
			<span class="_61Ylla">Your Shopping Cart is empty</span><br>
			 <a href="products.php"> <input class="btn btn-border btn-xlg"  type="button" value="Continue Shopping" /></a>
			 </div>
			<?php }  ?>
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
<style>
._61Ylla {

    display: block;
    font-size: 18px;

}
</style>