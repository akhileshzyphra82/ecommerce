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

    <!--Contact Banner-->
    <section class="row">
        <div class="row m0 sub_banner overly relative">
            <div class="container overly-content">
            	<div class="col-sm-12">
                    <!--<h4>Any Time Support </h4>
                    <h2><i class="icon icon-call-out"></i>456-246-34576</h2>-->
                </div>
            </div>
        </div>
    </section>
    <!--Contact Form-->
    <section class="row contact_content section-spacing bg-pattern">
        <div class="container">
            <div class="row form_row">
            <h3 class="part_title">CONTACT US</h3>
				<p class="part_subtitle">You may contact us at one of our addresses or write us an email and we will respond back.</p>
					<div class="col-sm-6 map_side">
					<div id="map"></div>
					</div>
					
					<div class="col-sm-6 detail_address">
					<div class="media">
						<div class="media-left"><i class="icon-streetsign fa-5x"></i></div>
						<div class="media-body media-middle">
							<span>INDIA</span><br>Sinelec Technologies (India) Pvt Ltd. <br>C44/D, Freedom Fighter Enclave,<br>New Delhi 110068, India
					</div>
					</div>
                    
                    <div class="media">
                        <div class="media-left"><i class="icon-streetsign fa-5x"></i></div>
                        <div class="media-body media-middle">
                            <span>EUROPE</span><br>Sinelec Technologies Deutschland.<br>Brachvogelweg 9, 85375 <br>Neufahrn bei Freising, Germany            </div>
                    </div>
                    
                    <div class="media">
                        <div class="media-left"><i class=" icon-phone fa-5x"></i></div>
                        <div class="media-body media-middle">
                             +91-8171452322 <span>(India)</span> <br>
                             +49-8165- 9906178 <span>(Germany)</span><br> +49-176- 22712256  <span>(Germany)</span>
                        </div>
                    </div>
                    <div class="media">
                        <div class="media-left"><i class="icon-flag fa-5x"></i></div>
                        <div class="media-body media-middle">
                            <a href="mailto:contact@sinelec-tech.com">contact@sinelec-tech.com</a>
                        </div>
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
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD37siSpIJBTSY9jte_ZWdfu2WyPHuwH3I&callback=initMap"></script>
<script>
function initMap() {
	
	var location1 = {
		info: '<strong>Sinelec Technologies (India) Pvt Ltd. </strong><br>C44/D,Freedom Fighter Enclave,<br>\
					New Delhi 110068, India, P: +91-8171452322<br>',
		lat: 28.509027,
		long: 77.199612
	};

	var location2 = {
		info: '<strong>Sinelec Technologies Deutschland.</strong><br>Brachvogelweg 9, 85375<br>\
					Neufahrn bei Freising, Germany, P: ++49-8165- 9906178<br>',
		lat: 48.323360,
		long: 11.665010
	};

	var locations = [
      [location1.info, location1.lat, location1.long, 0],
      [location2.info, location2.lat, location2.long, 1],
    ];

	var map = new google.maps.Map(document.getElementById('map'), {
		zoom: 3,
		 center: new google.maps.LatLng(35.268068,39.191221),
		mapTypeId: google.maps.MapTypeId.ROADMAP
	});

	var infowindow = new google.maps.InfoWindow({});

	var marker, i;

	for (i = 0; i < locations.length; i++) {
		marker = new google.maps.Marker({
			position: new google.maps.LatLng(locations[i][1], locations[i][2]),
			map: map
		});

		google.maps.event.addListener(marker, 'click', (function (marker, i) {
			return function () {
				infowindow.setContent(locations[i][0]);
				infowindow.open(map, marker);
			}
		})(marker, i));
	}
}
</script>

</body>
</html>