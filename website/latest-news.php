<?php
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
     <?php include 'header.php';?>

    <!--Breadcrumb-->
    <section class="row page_header section-spacing">
        <div class="container">
            <h3>Latest News</h3>
            <ol class="breadcrumb">
                <li><a href="/">home</a></li>
                <li><a href="#">Company</a></li>
                <li class="active">Latest News</li>
            </ol>
        </div>
    </section>

    <!--Blog Content-->
    <section class="row blog_content bg-gray section-spacing">
        <div class="container">
            <div class="row">
            	<!--Left Body-->
                <div class="col-sm-12 blogs">
                    <div class="latest_news row m0">
						<?php
                        $objNewsAndEvent = new HomeManager(); 
                        $NewsAndEventDetailArray=$objNewsAndEvent->GetAndDisplayAllNewsAndEventDetails(); 
                        if(count($NewsAndEventDetailArray)>0)
                        {
                            foreach($NewsAndEventDetailArray as $NewsAndEventDetails)
                            { 
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
                
			   		<br>
               		<hr class="blog_bottom_line">
                    <nav class="pagination_nav">
                        <ul class="pagination">
                            <li><a href="" aria-label="Previous">previous</a></li>
                            <li class="active"><a href="">1</a></li>
                            <li><a href="">2</a></li>
                            <li><a href="">3</a></li>
                            <li><a href="" aria-label="Next">next</a></li>
                        </ul>
                    </nav> 
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