<?php 
ob_start();
ini_set('display_errors','1');
//error_reporting(E_ALL | E_STRICT);
require_once ('../admin/BL/HomeManager.php');

//echo '<pre>'; print_r($_SESSION);
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
	<script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
      ga('create', 'UA-114376380-1', 'auto');
      ga('send', 'pageview');
    </script>
</head>

<body>
	<!--Top Header-->
   <?php include 'header.php';

$paramsArray = GetQueryStringParameters();
if($paramsArray['msg']=='inserted')
echo "<script type='text/javascript'>alert('Registered successfully');</script>";				   
 $objBannerHomeManager = new HomeManager(); 
					$bannerDetailArray=$objBannerHomeManager->GetAndDisplayAllBannerDetails();
					//echo "<pre>";print_r($bannerDetailArray);die;
					
				?>
	<!--Slider-->
    <section class="row slider">
        <div id="" class="owl-carousel home_slider ">
		<?php foreach($bannerDetailArray as $bannerDetail){ ?>
            <div class="item overly">
                <img src="<?php echo "../admin/UI/Images/Banner/".$bannerDetail->BANNER_ID.".".$bannerDetail->BANNER_IMG_EXT;?>" alt="">
                <div class="slide_caption row m0 ">
                    <div class="container overly-content text-center">
                        <div class="row" >
                            <h2><?php echo $bannerDetail->BANNER_NAME; ?></h2>
                            <p  class="center-block" ><?php echo $bannerDetail->BANNER_DESCRIPTION; ?></p>
                            <a href="<?php echo $bannerDetail->HYPERLINK; ?>" class="btn btn-primary btn-xlg"><i class="fa fa-external-link"></i> Read More</a>
                        </div>
                    </div>
                </div>
            </div>
<?php } ?>
           

        </div>
    </section>
    
    
     <section class="section-spacing bg-pattern">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="sectionTitle text-left">
                                        <h2>Sinelec Technologies</h2>
                                    </div>
                                    <h4 class="m-bottom15">India based Sinelec Technologies is a pioneer in electronics and semiconductor development in India as well as streamlines corporation between Europe and India in Electronics System Design & Manufacturing (ESDM) sector.</h4>
                                   <!-- <p>We offer a wide range of products and services across various industry segments. We have continuously invested in strengthening our design &amp; product development capabilities while developing deep domain knowledge in the segments we operate in. Sinelec also partners with world-class manufacturing facilities in India with an infrastructure as well as a global supply chain capable of delivering high quality and reliable products to our customer.</p> -->
                                    <a href="about-us.php" class="btn btn-primary btn-xlg"><i class="fa fa-external-link"></i> Read More</a>
                                </div>
                                <div class="col-sm-6">
                                    <img src="images/1.jpg" class="img-responsive shadow radius border" />
                                </div>
                            </div>
                        </div>
                    </div>
</section>

       <section class="section-spacing-sm">            
       <div class="domain hidden-sm hidden-xs">
            <div class="container">
                <ul class="menu">
                    <li class="heading">OUR SEGMENTS</li>
                    <li>
                        <a href="#" class="box"> 
                            <div class="domaincontent">
                                <img src="images/aerospace.png">
                                <p class="description">
                                    Aerospace
                                </p>

                            </div>
                        </a>
                        <img src="images/aerospace.jpg" class="img-responsive"  alt="Image">
                    </li>
                    
                    <li></li>
                    
                    <li>
                        <a href="#" class="box"> 
                            <div class="domaincontent">
                                <img src="images/transportation.png">
                                <p class="description">
                                    Transportation
                                </p>

                            </div>
                        </a>
                        <img src="images/transportation.jpg" class="img-responsive" alt="Image">
                    </li>
                    
                    <li>
                        <a href="#" class="box"> 
                            <div class="domaincontent">
                                <img src="images/medical.png">
                                <p class="description">
                                    medical
                                </p>

                            </div>
                        </a>
                        <img src="images/medical.jpg" class="img-responsive" alt="Image">
                    </li>
                    
                    <li>
                        <a href="#" class="box"> 
                            <div class="domaincontent">
                                <img src="images/space.png">
                                <p class="description">
                                    Space
                                </p>

                            </div>
                        </a>
                        <img src="images/space.jpg" class="img-responsive" alt="Image">
                    </li>
                    
                    <li></li>
                    
                     <li>
                        <a href="#" class="box"> 
                            <div class="domaincontent">
                                <img src="images/automotive.png">
                                <p class="description">
                                    Automotive
                                </p>

                            </div>
                        </a>
                        <img src="images/automotive.jpg" class="img-responsive" alt="Image">
                    </li>
                    
                    <li>
                        <a href="#" class="box"> 
                            <div class="domaincontent">
                                <img src="images/communications.png">
                                <p class="description">
                                    communications
                                </p>

                            </div>
                        </a>
                        <img src="images/communications.jpg" class="img-responsive" alt="Image">
                    </li>
                    
                    <li>
                        <a href="#" class="box"> 
                            <div class="domaincontent">
                                <img src="images/defense.png">
                                <p class="description">
                                    Defence 
                                </p>

                            </div>
                        </a>
                        <img src="images/defense.jpg" class="img-responsive" alt="Image">
                    </li>
                    
                    <li></li>
                    
                    
                    <li>
                        <a href="#" class="box"> 
                            <div class="domaincontent">
                                <img src="images/industry.png">
                                <p class="description">
                                    Industry & Energy
                                </p>

                            </div>
                        </a>
                        <img src="images/industry.jpg" class="img-responsive" alt="Image">
                    </li>
                    
                </ul>
            </div>

        </div>
        
     
        
<!-- Our domains E...-->
        
<!-- Our domains mobi S...-->
        
        <div class="domain hidden-md hidden-lg">
            <div class="container">
                
                <div class="heading">OUR SEGMENTS</div>
                
                <ul class="menu">
                    <li>
                        <a href="#" class="box"> 
                            <div class="domaincontent">
                                <img src="images/aerospace.png">
                                <p class="description">
                                    Aerospace
                                </p>

                            </div>
                        </a>
                      <img src="images/aerospace.jpg" class="sectors img-responsive"  alt="Image">
                    </li>
                    
                    <li>
                        <a href="#" class="box"> 
                            <div class="domaincontent">
                                <img src="images/space.png">
                                <p class="description">
                                    Space
                                </p>

                            </div>
                        </a>
                        <img src="images/space.jpg" class="img-responsive" alt="Image">
                    </li>
                    
                    <li>
                        <a href="#" class="box"> 
                            <div class="domaincontent">
                                <img src="images/defense.png">
                                <p class="description">
                                    Defence 
                                </p>

                            </div>
                        </a>
                        <img src="images/defense.jpg" class="img-responsive" alt="Image">
                    </li>
                    
                    <li>
                        <a href="#" class="box"> 
                            <div class="domaincontent">
                                <img src="images/transportation.png">
                                <p class="description">
                                    Transportation
                                </p>

                            </div>
                        </a>
                        <img src="images/transportation.jpg" class="img-responsive" alt="Image">
                    </li>
                    
                    <li>
                        <a href="#" class="box"> 
                            <div class="domaincontent">
                                <img src="images/automotive.png">
                                <p class="description">
                                    Automotive
                                </p>

                            </div>
                        </a>
                        <img src="images/automotive.jpg" class="img-responsive" alt="Image">
                    </li>
                    
                    <li>
                        <a href="#" class="box"> 
                            <div class="domaincontent">
                                <img src="images/industry.png">
                                <p class="description">
                                    Industry & Energy
                                </p>

                            </div>
                        </a>
                        <img src="images/industry.jpg" class="img-responsive" alt="Image">
                    </li>
                    
                    <li>
                        <a href="#" class="box"> 
                            <div class="domaincontent">
                                <img src="images/medical.png">
                                <p class="description">
                                    medical
                                </p>

                            </div>
                        </a>
                        <img src="images/medical.jpg" class="img-responsive" alt="Image">
                    </li>
                    
                    <li>
                        <a href="#" class="box"> 
                            <div class="domaincontent">
                                <img src="images/communications.png">
                                <p class="description">
                                    communications
                                </p>

                            </div>
                        </a>
                        <img src="images/communications.jpg" class="img-responsive" alt="Image">
                    </li>
                    
                    <li></li>
    
                </ul>
            </div>

        </div>     
            
                   
        </div>
    </section>
   
    <!-- Facts -->
    <section class="row section-spacing facts">
    <div class="container">
            <div class="row">
    		 <div class="col-xs-12">
                 <div class="sectionTitle text-left">
                    <h2>Leading All Electronic Design Service</h2>
                    <p>Sinelec offer complete electronics design service to  original equipment manufacturer (OEM) customer from design concept till final electronic product.</p>
                </div>
        
                <div class="col-sm-3 fact"><i class="icon-happy fa-5x"></i><strong class="counter">12</strong>Happy customers</div>
                <div class="col-sm-3 fact"><i class="icon-target fa-5x"></i><strong class="counter">23</strong>Project Delivered</div>
                <div class="col-sm-3 fact"><i class="icon-map fa-5x"></i><strong class="counter">2</strong>Locations</div>
                <div class="col-sm-3 fact"><i class="icon-strategy fa-5x"></i><strong class="counter">16</strong>Employess</div>
            </div>
        </div>
    </section>
    
    <div class="section-spacing offer_area section-parallax cover-image2">
      <div class="container parallax-content">
			<div class="row pop-video-container">
				<div class="col-md-6 col-sm-6 col-xs-12">
                    <a class="popup-youtube video-button text-center" href="https://www.youtube.com/watch?v=7YR-DPQWlG8">
                    <i class="fa fa-play-circle-o fa-4x text_white"></i>
                    </a>
                    
				</div>
				<div class="col-md-6 col-sm-6  col-xs-12">
					<div class="title">
						<h3 class="offer-title">
							
							<span class="m-top15"> Our Services Explained!</span>
						</h3>
					</div>
                     <div class="offer_content">
                       <a href="contact.php" class="btn btn-primary btn-xlg m-top30">Contact Us</a>
                    </div> 
				</div>
			</div>
		</div>
	</div>
	<!-- Blog Posts -->
    <section class="section-spacing bg-gray">
        <div class="container">
          <div class="row">
            <div class="col-sm-12">
              <div class="row sectionTitle text-center p-bottom80">
                <h2>Latest News & Events</h2>
                <!--<p class="lead">Prepare for a full day of discussion from some of the web's best and brightest.</p> -->
              </div>

              <div class="latest_news row m0">
			<?php 
			 $objNewsAndEvent = new HomeManager(); 
			 $NewsAndEventDetailArray=$objNewsAndEvent->GetAndDisplayAllNewsAndEventDetails();
			if(count($NewsAndEventDetailArray)>0)
			{  
				foreach($NewsAndEventDetailArray as $NewsAndEventDetails)
				{ 
					//$NewsEventimg=$objNewsAndEvent->GetAndDisplayAllInsertedImages($NewsAndEventDetails->NEWS_EVENT_ID);
			
				?>

                <div class="col-sm-4">
                  <div class="post media border shadow radius">
                    <div class="news-image">
                      <?php 
					  if ($NewsAndEventDetails->IMG_EXT!='')
					  {
					  ?>
                      	<a href="#"><img alt="" class="img-responsive" src="<?php echo "../admin/UI/Images/NewsAndEventPic/".$NewsAndEventDetails->NEWS_EVENT_ID.".".$NewsAndEventDetails->IMG_EXT;?>"></a>
                      <?php
					  }
					  else
					  {
					  ?>
					  	<a href="#"><img alt="" class="img-responsive" src="<?php echo "../admin/UI/Images/NewsAndEventPic/default.jpg" ; ?>"></a>
					  <?php
					  }
					  ?>
                    </div>
                    <div class="media-body">
                      <?php 
					  $docURL="#";
					  if ($NewsAndEventDetails->DOC_EXT!='')
					  {
					  	$docURL = "https://sinelec-tech.com/admin/UI/Images/NewAndEventDocs/".$NewsAndEventDetails->NEWS_EVENT_ID.".".$NewsAndEventDetails->DOC_EXT;
					  }
					  ?>

                      <h5><a href="<?php echo $docURL; ?>" target="_blank"><?php echo $NewsAndEventDetails->TITLE; ?></a></h5>
                      <div class="row m0 timeAgo">
                       <?php //$str = $NewsAndEventDetails->CREATED_DATE;
					    $str = date('m-d-Y', strtotime($NewsAndEventDetails->CREATED_DATE));
						$dateObj = DateTime::createFromFormat('m-d-Y', $str);
						echo $dateObj->format('M d Y'); ?>
                      </div>
                      <p><?php echo $NewsAndEventDetails->DESCRIPTION; ?></p>
                    </div>
                  </div>
                </div>
	<?php 
	} 
	}
	?>
                

                
              </div>
            </div>
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