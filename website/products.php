<?php
ini_set('display_errors','0');
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
	
	function getCategory1($categoryId,$count)
	{
		if($count==1)
		$body="";
		
		$objHomeManager = new HomeManager(); 
		$CategoryName=$objHomeManager->GetParentNameByCatId($categoryId);
		
		if(count($CategoryName)>0)	
		{
			$body = '<li><a href="#0">'.$CategoryName[0]->PRODUCT_CATEGORY_NAME.'</a></li>' . $body;
			getCategory1($CategoryName[0]->PARENT_CATEGORY_ID,$count++);
		}
		echo $body;
	}
	
	function getCategoryIds($categoryId,$count)
	{
		$categoryIds="";
		$objHomeManager = new HomeManager(); 
		$CategoryName=$objHomeManager->GetAllSubProductDetailByProductCatId($categoryId);
		if(count($CategoryName)>0)	{
		foreach($CategoryName as $val){
		$rows1 = $objHomeManager->GetAllSubProductDetailByProductCatId($val->PRODUCT_CATEGORY_ID);//return all child of the category
		$ids="";
		if(count($rows1)>0)	{
		foreach($rows1 as $values){
		$ids=$ids.','.$values->PRODUCT_CATEGORY_ID;
		}
		}
		if($ids!="")
		$categoryIds=$categoryIds.','.$val->PRODUCT_CATEGORY_ID.','.$ids;
		else
		$categoryIds=$categoryIds.','.$val->PRODUCT_CATEGORY_ID;
		getCategoryIds($val->PRODUCT_CATEGORY_ID,$count++);
		}
		}
		return $categoryIds;
	}
		
		$paramsArray = GetQueryStringParameters();
		$objHomeManager = new HomeManager(); 
		 $CategoryIds= $paramsArray['product_category_id'].getCategoryIds($paramsArray['product_category_id'],$count);
		$ProductCategoryList=$objHomeManager->GetAndDisplayAllListProductById($paramsArray['product_category_id']);
		$CategoryIds=str_replace(",,",",",$CategoryIds);
		$CategoryIds=rtrim($CategoryIds,",");
		$CategoryIds=ltrim($CategoryIds,",");
		$ProductsDetails=$objHomeManager->GetProductsDetailsByProductCategoryID($CategoryIds);
		$productArray=array();
		$categoryArray=array();
		foreach($ProductsDetails as $value) 
		{
		if($value->IMAGE_EXT!="" && $value->IMAGE_FOR=='Product')
		$productArray[$value->PRODUCT_ID] = $value;
		$categoryArray[$value->PRODUCT_CATEGORY_ID] = $value;
		}					
	 ?>
    <!--Breadcrumb-->
    <section class="row page_header section-spacing">
        <div class="container">
            <h3>Our Products</h3>
            <ol class="breadcrumb">
                <li><a href="/">home</a></li>
                <li class="active">Our Products</li>
            </ol>
        </div>
    </section>


    <!--Blog Content-->
    <section class="row blog_content bg-gray section-spacing bg-pattern">
        <div class="container">
            <div class="row">
				<div class="col-md-8 col-md-offset-2">
				 <div class="row sectionTitle text-center p-bottom20">
						<h2>Our Products</h2>
						<p class="lead">We offer a wide range of products and services across various industry segments. We have continuously invested in strengthening our design & product development capabilities while developing deep domain knowledge in the segments we operate in.</p>
				</div>
				</div>
				<!--Left Body-->
				<?php if(!empty($productArray))
				{ 
				?> 
				<div class="col-md-12">
					<?php 
					foreach($categoryArray as $key=>$val)
					{
						//echo '<pre>'; print_r($key);
						//echo '<pre>'; print_r($val);
						?>
                        <div class="row" style="display:flex !important; flex-wrap:wrap !important;">
						<div class="col-sm-12">
							<ol class="cd-breadcrumb custom-separator">
								<?php 
								getCategory1($key,'1'); 
								?>
							</ol>
						</div>
						<?php
							foreach($productArray as $value)
							{
								if($val->PRODUCT_CATEGORY_ID==$value->PRODUCT_CATEGORY_ID)
								{
							
									?>
									<div class="col-md-3 col-sm-3 col-xs-12 m-top30 m-bot30">
										<div class="products-section">
											<div class="row m0 image"> 
												<a href="Expansion-modules.php?urlstring=<?php echo EncryptURL('product_id='.$value->PRODUCT_ID); ?>">
												<img  class="img-responsive" src="<?php   echo "../admin/UI/Images/ProductImages/".$value->IMAGE_ID."_productImages.".$value->IMAGE_EXT; ?>">
												</a>
											</div>
											<div class="padding15">
												<h3><a href="#"><?php echo $value->PRODUCT_NAME; ?></a></h3>
												<p> <span class="glyphicon glyphicon-euro"></span>
													<?php echo number_format((float)$value->PRODUCT_AMT*(100-$value->PRODUCT_DISCOUNT)/100, 2, '.', ''); ?>
												</p>
												<form  action="cart.php?urlstring=<?php echo EncryptURL('action=Add&product_id='.$value->PRODUCT_ID); ?>" 
												method="post" onSubmit="return Login('')" >
													<input type="hidden" id="productId" name="productId" value="<?php echo $value->PRODUCT_ID; ?>">
													<input class="btn btn-danger btn-block"  type="submit"  value="Add To Cart">
												</form>
												<a class="btn btn-primary btn-block" href="Expansion-modules.php?urlstring=<?php 
												echo EncryptURL('product_id='.$value->PRODUCT_ID); ?>">More Information</a>
											</div>
										</div>
									</div>
									<?php 
								}
							}
						?>
                        </div>
                        <?php
					}
					?>
				 </div>
				<?php		
				}
				?>
            </div>
        </div>
    </section>
	<div id="Open_popup_modal_show_id" class="modal fade" tabindex="-1"></div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="../js/jquery-1.11.2.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
<?php if($action=='ErrorLogin'){ ?>
Login( "User Id or password doesn't match!!!");
<?php 
}?>
	});
	
function Login(message){
var modal = $('#Open_popup_modal_show_id');
var val ="<?php echo $ProductsDetails[0]->PRODUCT_ID; ?>"+"_"+message;
	<?php if(!isset($_SESSION['CUSTOMER_ID']) && $_SESSION['CUSTOMER_ID']==NULL ){ ?>
		modal.load('loginPopup.php',{'val': val},
		function(){
		modal.modal('show');
		});
		return false;
		<?php }  ?>
	}
	</script>

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