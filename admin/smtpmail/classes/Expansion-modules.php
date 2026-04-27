
<?php
ob_start();
ini_set('display_errors','0');
error_reporting(E_ALL | E_STRICT);
require_once ('../admin/BL/HomeManager.php');
//echo "<pre>";print_r($_SESSION);die;
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
		(isset($paramsArray['action']))? $action=$paramsArray['action'] : $action="";
		$objHomeManager = new HomeManager(); 
		$ProductsDetails=$objHomeManager->GetProductData($paramsArray['product_id']);
		foreach($ProductsDetails as $val)
		{
		$ArrForSampleCode[$val->PRODUCT_SAMPLE_CODE_ID]=array("ext"=>$val->EXT,"LANGUAGE_TECHNOLOGY"=>$val->LANGUAGE_TECHNOLOGY,
		"IDE_COMPILER"=>$val->IDE_COMPILER,"TYPE"=>$val->TYPE,"OS"=>$val->OS,"DATE"=>$val->DATE);
		$ArrForImage[$val->IMAGE_ID]=array("ext"=>$val->IMAGE_EXT,"IMAGE_NAME"=>$val->IMAGE_NAME,"IMAGE_FOR"=>$val->IMAGE_FOR,
		"PRODUCT_MANUAL_TITLE"=>$val->PRODUCT_MANUAL_TITLE,"IMAGE_SIZE"=>$val->IMAGE_SIZE,"MANUAL_UPLOAD_DATE"=>$val->MANUAL_UPLOAD_DATE,"HYPER_LINK"=>$val->HYPER_LINK);
		}
		$ProductCategoryList=$objHomeManager->GetAndDisplayAllListProductById($ProductsDetails[0]->PRODUCT_CATEGORY_ID);
	?>

    <!--Breadcrumb-->
    <section class="row page_header section-spacing">
        <div class="container">
            <h3><?php if(isset($ProductCategoryList)) echo $ProductCategoryList[0]->PRODUCT_CATEGORY_NAME; ?></h3>
            <ol class="breadcrumb">
                <li><a href="/">home</a></li>
                <li><a href="products.php">Our Products</a></li>
                <li class="active"><?php if(isset($ProductCategoryList)) echo $ProductCategoryList[0]->PRODUCT_CATEGORY_NAME; ?> </li>
           </ol>
        </div>
    </section>


   <!--Shop Page-->
    <section class="row section-spacing shop-page" >
        <div class="container ">
            <div class="product-single">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="carousel slide m-bottom20" data-ride="carousel" id="gallery-post">
                            <!-- Wrapper for slides -->
                            <div class="carousel-inner" role="listbox">
							<?php
							$count=0;
							if(count($ProductsDetails)>0){
							 foreach($ArrForImage as $key=>$value)
							{
							if($value['IMAGE_FOR']=="Product" && $value['ext']!=""){
							$count++;
							 ?>
                                <div class="item <?php if($count==1) echo "active"; ?> "><img alt="..." class="img-responsive" src="<?php   echo "../admin/UI/Images/ProductImages/".$key."_productImages.".$value['ext']; ?>"></div>
                               
								
								<?php }} ?>
                            </div>

                            <!-- Controls -->
                            <a class="left carousel-control" data-slide="prev" href="#gallery-post" role="button">
                             	<span aria-hidden="true" class="glyphicon glyphicon-chevron-left"></span> <span class="sr-only">Previous</span>
                            </a>

                            <a class="right carousel-control" data-slide="next" href="#gallery-post" role="button">
                            	<span aria-hidden="true" class="glyphicon glyphicon-chevron-right"></span> <span class="sr-only">Next</span>
                            </a>
                        </div>
                          <div class="m-bottom20 ">
                                <span class="number price old-price"> <?php if($ProductsDetails[0]->PRODUCT_DISCOUNT!="0"){ ?><span class="glyphicon glyphicon-usd"></span><?php  echo number_format((float)$ProductsDetails[0]->PRODUCT_AMT, 2, '.', ''); }?></span> <span class="number price"> <span class="glyphicon glyphicon-usd"></span><?php if($ProductsDetails[0]->PRODUCT_DISCOUNT!="0") echo number_format((float)$ProductsDetails[0]->PRODUCT_AMT*(100-$ProductsDetails[0]->PRODUCT_DISCOUNT)/100, 2, '.', ''); else echo $ProductsDetails[0]->PRODUCT_AMT;   ?></span>
                            </div>
							
                            <form class="add-to-cart text-left" action="cart.php?urlstring=<?php echo EncryptURL('action=Add'); ?>" method="post" onSubmit="return Login('')" >
							<input type="hidden" id="productId" name="productId" value="<?php echo $paramsArray['product_id']; ?>">
                            <input class="btn btn-primary btn-xlg"  type="submit"  value="Add To Cart">
                       	 </form>
                        <!--end of image slider-->
                    </div>

                    <!--Product-->
                    <div class="col-sm-8">
                        <div class="description">
                          <h2> <?php echo $ProductsDetails[0]->PRODUCT_NAME; ?></h2>
                            <h3 class="m-bottom20">Description</h3>
                           <p><?php echo $ProductsDetails[0]->PRODUCT_DETAILS; ?></p>
							 <h5 class="m-bottom20">Category <span style="color:#3399FF;"><?php echo $ProductCategoryList[0]->PRODUCT_CATEGORY_NAME; ?></span></h5>
							 <h5 class="m-bottom20"> <?php echo $ProductsDetails[0]->PRODUCT_CODE; ?></h5>
                        </div>
                        <hr>
                        
                    </div>
                </div>

                <!--Product Description-->
                <div class="row">
                    <div class="col-sm-12">
                    
                    
       <ul class="nav nav-tabs text-uppercase" id="product-details-tabs">
	   <li class="active">
          <a data-toggle="tab" href="#Description">
            <i class="fa fa-bookmark-o"></i> Description
          </a>
        </li>
        <li >
          <a data-toggle="tab" href="#specification">Specification
          </a>
        </li>
		 <li >
          <a data-toggle="tab" href="#product-description">Downloads
          </a>
        </li>
       <li>
          <a data-toggle="tab" href="#SampleCode">
             Sample Code
          </a>
        </li>
		 
      </ul>
      <div class="tab-content">
       <div class="tab-pane  fade in" data-hook="description" id="product-description" itemprop="description">
       <h3 class="m-top20">Downloads</h3>
       			<table class="table table-striped table-bordered m-top20">
                            <tbody>
                                <tr>
                                    <th scope="row">Title</th>
                                    
                                    <th scope="row">Date	</th>
									<th scope="row">Download</th>
                                </tr>
								<?php
									$count=0;
									foreach($ArrForImage as $key=>$value)
									{
									if($value['IMAGE_FOR']=="Product Mannual" ){
									//echo "<pre>";print_r($value['HYPER_LINK']);
									$str = date('m-d-Y', strtotime($value['MANUAL_UPLOAD_DATE']));
									$dateObj = DateTime::createFromFormat('m-d-Y', $str);
								?>
                                <tr>
                                 <td><a href="<?php echo $value['HYPER_LINK']; ?>" target="_blank" >   <?php echo $value['PRODUCT_MANUAL_TITLE']; ?></a></td>
                                    
                                    <td><?php echo $dateObj->format('M d Y'); ?></td>
                                    
									<td><a href="<?php  if($value['ext']!=NULL) echo "../admin/UI/Images/ProductManuals/".$key."_productManual.".$value['ext']; else  echo $value['HYPER_LINK']; ?>" <?php  if($value['ext']!="") echo 'download='.$value['PRODUCT_MANUAL_TITLE'];?>>Download</a></td>
                                </tr>
                             <?php 
							 }
							 }
							
							 ?>  
                            </tbody>
                        </table>
       
       </div>
       <div class="tab-pane active fade in" data-hook="description" id="Description" itemprop="description">
       <h3 class="m-top20">Description</h3>
       			<?php echo $ProductsDetails[0]->PRODUCT_DESCRIPTION; ?>
                        
       
       </div>
	    <div class="tab-pane fade in" data-hook="specification" id="specification" itemprop="specification">
       <h3 class="m-top20">Additional information</h3>
	   &nbsp;
       			<?php echo $ProductsDetails[0]->PRODUCT_SPECIFICATION; ?>
                        
       
       </div>
	   <div class="tab-pane fade in" data-hook="SampleCode" id="SampleCode" itemprop="SampleCode">
       <h3 class="m-top20">Sample Code</h3>
	   &nbsp;
       			<table class="table table-striped table-bordered m-top20">
                            <tbody>
                                <tr>
								<th scope="row"> Language/Technology</th>
								
								<th scope="row">IDE/Compiler</th>
								
								<th scope="row">Type </th>
								
								<th scope="row">OS</th>
						<th scope="row">Download</th>
						 </tr>
						 
							   <?php
						
						 foreach($ArrForSampleCode as $key=>$value){ 
						// echo "<pre>";print_r($ArrForSampleCode);die;
						?>
					<tr>
                                 
							   <td>
								<?php  echo $value['LANGUAGE_TECHNOLOGY']; ?>
								</td>
								<td>
								<?php  echo $value['IDE_COMPILER']; ?>
								</td>
								<td>
								<?php  echo $value['TYPE']; ?>
								</td>
								<td>
								<?php  echo $value['OS']; ?>
								</td><td>
								<a href="<?php echo $value['ext']; ?>" target="_blank" ><span class="glyphicon glyphicon-file" style="width:150px;">Download</span></a>
								</td>
							   
					</tr>
							   <?php
						
						 }
						 
						
						 ?>
						</tbody>
						</table>
							   </div>
                        
       
       </div>
        <div class="tab-pane fade" id="resources">
         <h3 class="m-top20">Design kits & evaluation modules (16)</h3>
       				<table class="table table-striped table-bordered m-top20">
                            <tbody>
                                <tr>
                                    <th scope="row">Name</th>
                                    <th scope="row">Part#	</th>
                                    <th scope="row">Type</th>
                                </tr>
                                <tr>
                                    <td><a>DC Power Line Communication (PLC) Reference Design</a></td>
                                    <td>24VDCPLCEVM</td>
                                    <td>Evaluation Modules & Boards</td>
                                </tr>
                                
                                <tr>
                                    <td><a>Three Phase BLDC & PMSM Motor Kit with DRV8301 and Piccolo MCU	</a></td>
                                    <td>DRV8301-HC-C2-KIT	</td>
                                    <td>Evaluation Modules & Boards</td>
                                </tr>
                               
                            </tbody>
                        </table>
      </div>
                            
                        
            </div>
                
                </div>
            </div>
			<?php } ?>
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