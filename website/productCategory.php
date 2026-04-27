<?php
ob_start();
////ini_set('display_errors',0);
require_once ('../admin/BL/HomeManager.php');
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
		$objHomeManager = new HomeManager(); 
		$ProductCategoryList=$objHomeManager->GetAndDisplayAllListProductById($paramsArray['product_category_id']);
		$ProductsDetails=$objHomeManager->GetProductsDetailsByProductCategoryID($paramsArray['product_category_id']);
		
		$productArray=array();
		foreach($ProductsDetails as $key=>$values){
		if(!array_key_exists($values->PRODUCT_ID, $productArray))
		$productArray[$values->PRODUCT_ID]=$values;
}
	?>

    <!--Breadcrumb-->
    <section class="row page_header section-spacing">
        <div class="container">
            <h3><?php echo $ProductCategoryList[0]->PRODUCT_CATEGORY_NAME; ?></h3>
            <ol class="breadcrumb">
                <li><a href="/">home</a></li>
                <li><a href="products.php">Our Products</a></li>
                <li class="active"><?php echo $ProductCategoryList[0]->PRODUCT_CATEGORY_NAME; ?></li>
           </ol>
        </div>
    </section>


   <!--Shop Page-->
    <section class="row section-spacing shop-page">
        <div class="container">
            <div class="product-single">
             <h2 class="text-uppercase m-bottom20"><?php echo $ProductCategoryList[0]->PRODUCT_CATEGORY_NAME; ?></h2>
                <!--Product Description-->
               <div class="row masonry">
			   <?php
			    foreach($productArray as $value)
				{
				?>
                <div class="col-md-3 col-sm-3 col-xs-12 m-top30 m-bot30">
                    <div class=" text-center shop-product border">
                        <a href="#"><img  class="img-responsive" src="<?php   echo "../admin/UI/Images/ProductImages/".$value->IMAGE_ID."_productImages.".$value->IMAGE_EXT; ?>"></a>
                        <div class="title">
                            <h3><?php echo $value->PRODUCT_NAME; ?></h3>
                            <h5 style="height:120px; overflow:hidden"><?php echo $value->PRODUCT_DESCRIPTION_PARAMETRICS; ?></h5> <a class="btn btn-primary" href="Expansion-modules.php?urlstring=<?php echo EncryptURL('product_id='.$value->PRODUCT_ID); ?>">More Information</a>
                        </div>
                    </div>
                </div>
				<?php 
				}
				 ?>
				 </div>
              <!--Pagination-->
         <?php /*   <nav class="pagination_nav ">
                <ul class="pagination btn-block">
                    <li>
                        <a aria-label="Previous" href="#">previous</a>
                    </li>
                    <li class="active">
                        <a href="#">1</a>
                    </li>
                    <li>
                        <a href="#">2</a>
                    </li>
                    <li>
                        <a href="#">3</a>
                    </li>
                    <li>
                        <a aria-label="Next" href="#">next</a>
                    </li>
                </ul>
            </nav>*/?>
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
   
</body>
</html>