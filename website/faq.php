<?php 
ob_start();
//ini_set('display_errors',0);
//error_reporting(E_ALL | E_STRICT);


include('../admin/UI/Includes/Functions.php');
require_once('../admin/BL/HomeManager.php');
require_once('../admin/BL/UserManager.php');
$objHomeManager = new HomeManager(); 
$parentCategory=$objHomeManager->GetAndDisplayAllListProduct1();
$paramsArray = GetQueryStringParameters();
(isset($paramsArray['action']))? $action=$paramsArray['action'] : $action="";
isset($paramsArray["msg"]) ? $msg=$paramsArray["msg"] : $msg="";

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


    <!--Bread Crumb-->
    <section class="row page_header section-spacing">
        <div class="container">
            <h3>Frequently Asked Questions </h3>
            <ol class="breadcrumb">
                <li><a href="/">home</a></li>
                <li><a href="#">Company</a></li>
                <li class="active">Frequently Asked Questions</li>
            </ol>
        </div>
    </section>

    <section class="row faqs_section section-spacing bg-gray">
        <div class="container">
            <div class="sectionTitle p-bottom80">
                <h2>Frequently Asked Questions</h2>
            </div>
            
				<?php 
				$objUserManager = new UserManager(); 
				$FqaResult=$objUserManager->GetAllFqadata();
				if(count($FqaResult)>0)
				{
					foreach($FqaResult as $FqaResultValue)
					{
					?>
					<div class="panel">
						<div class="panel-heading">
							<h4 class="panel-title"> <a data-parent="#accordion" data-toggle="collapse" class="panel-toggle" href="#faq1_<?php echo $FqaResultValue->FAQ_ID; ?>">
							<i class="fa fa-plus-square"></i> <?php echo $FqaResultValue->FAQ_QUESTION; ?></a></h4>
							</div>
							<div class="panel-collapse collapse" id="faq1_<?php echo $FqaResultValue->FAQ_ID; ?>">
							<div class="panel-body"> <?php echo $FqaResultValue->FAQ_ANSWER; ?></div>
						</div>
					</div><!--//panel-->
				<?php 
					}
				}
				?>    
</div>
</section>

     <!-- Footer -->
    <footer class="row">
    	 <?php include 'footer.php';?>

    </footer>
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

<script>
	 /* ======= FAQ accordion ======= */
    function toggleIcon(e) 
	{
    $(e.target)
        .prev('.panel-heading')
        .find('.panel-title a')
        .toggleClass('active')
        .find("i.fa")
        .toggleClass('fa-plus-square fa-minus-square');
    }
    $('.panel').on('hidden.bs.collapse', toggleIcon);
    $('.panel').on('shown.bs.collapse', toggleIcon);    
	</script>

</body>
</html>