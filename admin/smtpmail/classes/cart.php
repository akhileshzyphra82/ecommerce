<?php
ob_start();
ini_set('display_errors','0');
error_reporting(E_ALL | E_STRICT);
require_once ('../admin/BL/HomeManager.php');
require_once ('../admin/BL/ProductManager.php');
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
$Products=array();
$objProductManager = new ProductManager(); 
$ProductsDetails=$objProductManager->GetProductToCart($user_id);
foreach($ProductsDetails as $value) {
if($value->IMAGE_EXT!="" && $value->IMAGE_FOR=='Product')
$ProductsDetails1[$value->PRODUCT_ID] = $value;

$Products[$value->PRODUCT_ID] = $value;
}
//echo "<pre>";print_r($ProductsDetails1);die;
(isset($paramsArray['action']))? $action=$paramsArray['action'] : $action="";
isset($paramsArray["msg"]) ? $msg=$paramsArray["msg"] : $msg="";
$price=0;
switch($action)
	{	
	case "Add":
	(isset($paramsArray['productId']))? $productId=$paramsArray['productId'] : $productId=$_POST['productId'];
	$objHomeManager = new HomeManager(); 
	$ProductsDetails=$objHomeManager->GetProductData($productId);
	
	$productArray=array();
	$transaction_id=generateRandomString();
	//echo "<pre>";print_r($transaction_id);die;
	$quantity=1;
	$product_code=$ProductsDetails[0]->PRODUCT_CODE;
	$product_amt=$ProductsDetails[0]->PRODUCT_AMT;
	$product_tax=$ProductsDetails[0]->PRODUCT_TAX;
	$product_discount=$ProductsDetails[0]->PRODUCT_DISCOUNT;
	$order_total_amt=$ProductsDetails[0]->PRODUCT_AMT*(100-$ProductsDetails[0]->PRODUCT_DISCOUNT)/100;
	$productArray=array("user_id"=>$user_id,"productId"=>$productId,"transection_id"=>$transaction_id,"order_current_status"=>'Cart',"order_total_amt"=>$order_total_amt,
	"quantity"=>$quantity,"product_code"=>$product_code,"product_amt"=>$product_amt,"product_tax"=>$product_tax,"product_discount"=>$product_discount);
	$objProductManager = new ProductManager(); 
	foreach($Products as $val){
	$order_id=$val->ORDER_ID;
	$ORDER_CURRENT_STATUS=$val->ORDER_CURRENT_STATUS;
	}
	if(count($Products[$productId])>0 && $Products[$productId]->ORDER_CURRENT_STATUS=='Cart')
	$ProductsDetails=$objProductManager->UpdateProductFromCart('+',$productId,$user_id,$order_total_amt,$Products[$productId]->ORDER_ID);
	else{
	if(count($Products)>0 && $ORDER_CURRENT_STATUS=='Cart')
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
	$objHomeManager = new HomeManager(); 
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
	$objHomeManager = new HomeManager(); 
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

    <!--Cart Details-->
    <section class="row shopping-cart section-spacing2">
        <div class="container">
        	<div class="sectionTitle p-bottom40">
                <h2>Shopping Cart</h2>
            </div>
            <div class="row">
            	<!--Order Details-->
                <div class="col-sm-8">
                    <div class="table-responsive">
                        <table class="table cart m-bottom40">
						<?php if(count($ProductsDetails1)>0) { ?>
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
                                    <td><span class="number price"> <span class="glyphicon glyphicon-usd"></span><?php  echo number_format((float)$value->QUANTITY*$value->PRODUCT_AMT*(100-$value->PRODUCT_DISCOUNT)/100, 2, '.', '');   ?></span></td>
									<?php   $price=$price+number_format((float)$value->QUANTITY*$value->PRODUCT_AMT*(100-$value->PRODUCT_DISCOUNT)/100, 2, '.', ''); ?>
                                    <td scope="row">
                                        <a class="btn btn-red btn-sm" data-original-title="Remove from cart" data-placement="top" data-toggle="tooltip" href="cart.php?urlstring=<?php echo EncryptURL('action=Delete&productId='.$value->PRODUCT_ID.'&ORDER_ID='.$value->ORDER_ID); ?>" title=""><i class="ti-close"></i></a> 
                                    </td>
                                </tr>
                                
								
                                    
                            </tbody>
							<?php } } } ?>
							
                        </table>
                     </div>
                 <?php /*   <form class="thirds text-center">
                        <h3 class="text-left m-bottom30">Add a coupon code</h3>
                        <input class="col-sm-3 " type="text" placeholder="Coupon Code" />
                        <input class="btn btn-primary btn-xlg col-sm-3" type="submit" value="Apply" />
                    </form>
					*/?>
                </div>

                <!--Order Summary-->
				<?php if(count($ProductsDetails1)>0) { ?>
                <div class="col-sm-4">
                    <div class="summary-box m-bottom30 bg-gray">
                        <h3 class="text-center padding20 summary-boxtitle">Order Summary</h3>
                        <table class="table m-bottom0">
                            <tbody>
                                <tr class="">
                                    <th scope="row">Cart Subtotal</th>
                                    <td class="text-right"><span class="glyphicon glyphicon-usd"></span><?php echo $price; ?></td>
                                </tr>
                                <tr>
                                    <th scope="row"><small class="text-muted">Totals</small></th>
                                    <td class="text-right text-muted"><small><span class="glyphicon glyphicon-usd"></span><?php echo $price; ?></small></td>
                                </tr>
                                <tr>
                                    <th scope="row">Order Total</th>
                                    <td>
                                        <h2 class="price text-right"><span class="glyphicon glyphicon-usd"></span><?php echo $price; ?></h2>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                     <form action="cart-checkout.php?urlstring=<?php echo EncryptURL('action=checkout'); ?>">
                       <a href="cart-checkout.php"> <input class="btn btn-primary btn-xlg m-bottom20" type="button" value="Place Order" /></a>
                       <a href="products.php"> <input class="btn btn-border btn-xlg"  type="button" value="Continue Shopping" /></a>
                     </form>
                </div>
				<?php }  ?>
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