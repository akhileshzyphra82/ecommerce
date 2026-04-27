<?php
ob_start();
//ini_set('display_errors',0);
//error_reporting(E_ALL | E_STRICT);
require_once ('../admin/BL/HomeManager.php');
require_once ('../admin/BL/ProductManager.php');
$userId=$_SESSION['CUSTOMER_ID'];
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
						<form  action="cart.php?urlstring=<?php echo EncryptURL('action=AddMultiple'); ?>" method="post"  onSubmit="return checkItems()" name="ReOrder" >
                        <div class="col-sm-10 col-md-10 center-block">
                            <div class="sectionTitle p-bottom10">
                                <h4 align="left">ORDER DETAILS</h4>
                            </div>
                            <div class="table-responsive">
                                <table class="table cart">
                                    <thead>
                                        <tr>
											 <th style="text-align:center">Add Cart</th>
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
													<input type="checkbox" name="products[]" id="products<?php echo $value->PRODUCT_ID;?>" value="<?php echo $value->PRODUCT_ID;?>" checked="checked" style="width: 20px; height:20px;">
													
													<input type="hidden" name="productsQty[<?php echo $value->PRODUCT_ID;?>]" value="<?php echo $value->QUANTITY;?>" >
 												
												</td>
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
								<button  class="btn btn-danger btn-sm"   type="submit" >Add To Cart</button>
								
								<hr/>
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
						</form>
						
						<script>
							function checkItems()
							{
								var totItems=document.getElementsByName('products[]');
								
								
								var checkFlag='No';
								
								for(var i=0; i<totItems.length; i++)
								{
									//console.log(totItems[i].value);
									var id=totItems[i].value;
									if(document.getElementById('products'+id).checked==true)
										checkFlag='Yes';
									
								}
								
								if(checkFlag=='No')
								{
									alert('Please choose at least one product.');
									return false;
								}
								
							}
						
						</script>
						
			<?php 
					}
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