<?php
//echo "<pre>";print_r($_SESSION);

require_once ('../admin/BL/HomeManager.php');
require_once("../admin/UI/Includes/Functions.php");
require_once ('../admin/BL/ProductManager.php');
$objBannerHomeManager = new HomeManager(); 
$ProductCategoryList=$objBannerHomeManager->GetAndDisplayAllListProduct();
$objProductManager = new ProductManager(); 
if(isset($_SESSION['CUSTOMER_ID']) && $_SESSION['CUSTOMER_ID']!=NULL)
$count=$objProductManager->countItemByUserId($_SESSION['CUSTOMER_ID']);
?>
 <section class="row top_header">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <ul class="nav nav-pills pull-left">
                        <li><i class="icon-call-out"></i> +91-8171452322</li>
                        <li><i class="icon-envelope"></i>contact@sinelec-tech.com</a></li>
                    </ul>
                </div>

                <div class="col-sm-6">
                    <ul class="nav nav-pills pull-right">
                        <li><div class="dropdown">
  		<button class="dropbtn"><a href="<?php if(isset($_SESSION['CUSTOMER_ID']) && $_SESSION['CUSTOMER_ID']!=NULL) echo '#'; else echo 'login.php';?>"><i class="fa fa-user-circle"></i><?php if(isset($_SESSION['CUSTOMER']) && $_SESSION['CUSTOMER']!=NULL) echo $_SESSION['CUSTOMER'];?></a></button>
			  <div class="top-dropdown-content">
				<ul>
				<li <?php if(!isset($_SESSION['CUSTOMER']) && $_SESSION['CUSTOMER']==NULL) echo "style='display:none;'";?>><a href="register.php?urlstring=<?php echo EncryptURL('action=profile&CUSTOMER_ID='.$_SESSION['CUSTOMER_ID']); ?>">Profile</a></li>
				<li <?php if(!isset($_SESSION['CUSTOMER']) && $_SESSION['CUSTOMER']==NULL) echo "style='display:none;'";?>><a href="login.php?urlstring=<?php echo EncryptURL('action=signOut&CUSTOMER_ID='.$_SESSION['CUSTOMER_ID']); ?>">Sign out</a></li>
				<li  <?php if(!isset($_SESSION['CUSTOMER']) && $_SESSION['CUSTOMER']==NULL) echo "style='display:none;'";?>><a href="resetpassword.php">Reset password</a></li>
				</ul>
			  </div>
			</div>
						</li>
                        <li><a href="contact.php"><i class="fa fa-address-book"></i></a></li>
                        <li class="cart-link"><a href="cart.php"><i class="fa fa-shopping-cart"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!--Navbar-->
    <nav class="navbar navbar-default navbar-static-top fluid_header centered">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <a class="navbar-brand" href="/">
                	<img src="images/Logo.png" alt="">
                </a>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main_navigation" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="main_navigation">
                <ul class="nav navbar-nav navbar-right">
                    <li class="active">
                        <a href="index.php">Home</a>
                    </li>
                    
                     <li class="dropdown">
                        <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="false" aria-expanded="false">COMPANY 
                        </a>
                         <ul class="dropdown-menu">
                          <li><a href="about-us.php">About Us</a></li>
                       	  <li><a href="latest-news.php">Latest News</a></li>
                       	  <li><a href="careers.php">Careers</a></li>
   						</ul>
                    </li>
                    
                     <li class="dropdown">
                        <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="false" aria-expanded="false">OUR SERVICES
                        </a>
                         <ul class="dropdown-menu">
                          <li><a href="engineering-services.php">Engineering Services</a></li>
                       	  <li><a href="manufacturing-services.php">Manufacturing Services</a></li>
                       	  <li><a href="distribution-services.php">Distrubution Services</a></li>
                       	  <li><a href="after-sale-services.php">After-Sale Services</a></li>
   						</ul>
                    </li>
					
					
					
					         	<li class="dropdown mega-drop">
                       <a href="products.php" class="dropdown-toggle"  role="button" aria-haspopup="false" aria-expanded="false">PRODUCT  </a>
                       <ul class="dropdown-menu mega-menu">
                           <li class="service_list">
						    <?php  getCategory('0'); ?>
                             </li>
                       </ul>
                   </li>                                    
							<?php
							
		function getChildCategory($catId,$margin)
		{
		
		$objHomeManager = new HomeManager(); 
		$CategoryList=$objHomeManager->GetAllSubProductDetailByProductCatId($catId);
		foreach($CategoryList as $row)
		{
		$rows1 = $objHomeManager->GetAllSubProductDetailByProductCatId($row->PRODUCT_CATEGORY_ID);//return all child of the category
		?>
		 <ul class="list-inline" style="margin-left:<?php echo $margin."px";  ?>">
		<li >
		<a href="products.php?urlstring=<?php echo EncryptURL('product_category_id='.$row->PRODUCT_CATEGORY_ID.'&parent_category_id='.$row->PARENT_CATEGORY_ID); ?>">
		<?php   echo $row->PRODUCT_CATEGORY_NAME;  ?></a>
		 </li>
		</ul>
		<?php
		getChildCategory($row->PRODUCT_CATEGORY_ID,$margin+10);
		}
	}
		function getCategory($catId)
	{
		$objHomeManager = new HomeManager(); 
		$CategoryList=$objHomeManager->GetAllSubProductDetailByProductCatId($catId);
		foreach($CategoryList as $row)
		{
		?>
		<div class="col-sm-4 col-xs-12">
             <div class="service-drop">
                <div class="media">
			<?php  if($row->PARENT_CATEGORY_ID== 0){ ?>
			 <a href="products.php?urlstring=<?php echo EncryptURL('product_category_id='.$row->PRODUCT_CATEGORY_ID.'&parent_category_id='.$row->PRODUCT_CATEGORY_ID); ?>" >
			 <h4 class="text-uppercase"><i class="icon-global"></i> <?php   echo $row->PRODUCT_CATEGORY_NAME;  ?>
			 </h4>
			 </a>
			 <?php } ?>
		 	 <div class="media-body">
			  <?php
			  getChildCategory($row->PRODUCT_CATEGORY_ID,10); 
				?>
             </div>
			</div>                                      
	   </div>
   </div>
		<?php 
		}
	}
		?>
                     <li class="dropdown">
                        <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="false" aria-expanded="false">MANUFACTURING
                        </a>
                         <ul class="dropdown-menu">
                          <li><a href="electronic-manufacturing.php">Electronic Manufacturing</a></li>
                       	  <li><a href="testing-programming.php">Testing &amp; Programming</a></li>
                       	  <li><a href="quality-control.php">Quality Control</a></li>
   						</ul>
                    </li>
                    

                    <li class="dropdown">
                        <a href="cart.php" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="false" aria-expanded="false">
                        	<i class="fa fa-shopping-cart"></i> Cart <span class="badge"><?php if(isset($count)) echo $count[0]->VAL; ?></span>
                        </a>
                        <ul class="dropdown-menu">
                          <li><a href="cart.php">Cart</a></li>
						   <li><a href="OrderDetails.php?urlstring=<?php echo EncryptURL('user_id='.$_SESSION['CUSTOMER_ID']); ?>">Order Details</a></li>
                      	</ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>