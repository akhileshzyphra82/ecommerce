<?php
$intEmployeeTypeId= $_SESSION['EMPLOYEETYPEID'];
$intEmployeeId= $_SESSION['EMPLOYEEID'];
$strEmpName=$_SESSION['EMPLOYEE_NAME'];
$intUserId=$_SESSION['USER_ID'];
$folder='../UserImages/user-photo/'.$intUserId.'.png';

if(!file_exists($folder))
{
	$folder='../UserImages/user-photo/default.png';
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $pagetitle; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../plugins/iCheck/flat/blue.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="../plugins/morris/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="../plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="../plugins/datepicker/datepicker3.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="../plugins/timepicker/bootstrap-timepicker.min.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->

  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

  <!--[if lt IE 9]>

  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>

  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

  <![endif]-->

</head>
<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">
  <header class="main-header">
    <!-- Logo -->
    <a href="../User/Home.php" target="_blank" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"> <img src="../UserImages/sinelec.png" width="170px" > </span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>		
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
		  <li class="dropdown user user-menu"> 
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo $folder; ?>" class="user-image" alt="User Image">
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo $folder;?>" class="img-circle" alt="User Image">
                <p>
                 <?php echo $strEmpName; ?>
                  <small></small>
                </p>
              </li>
              <!-- Menu Body -->
             <?php /*?> <li class="user-body">

                <div class="row">

                  <div class="col-xs-4 text-center">

                    <a href="#">Followers</a>

                  </div>

                  <div class="col-xs-4 text-center">

                    <a href="#">Sales</a>

                  </div>

                  <div class="col-xs-4 text-center">

                    <a href="#">Friends</a>

                  </div>

                </div>

                <!-- /.row -->

              </li><?php */?>
              <!-- Menu Footer-->
              <li class="user-footer">

                <div class="pull-left">

                  <a href="#" class="btn btn-default btn-flat">Profile</a>

                </div>

                <div class="pull-right">

                  <a href="../Logout.php" class="btn btn-default btn-flat">Sign out</a>

                </div>

              </li>

            </ul>

          </li>
          <!-- Control Sidebar Toggle Button -->

          <?php /*?><li>

            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>

          </li><?php */?>

        </ul>

      </div>

    </nav>

  </header>

  <!-- Left side column. contains the logo and sidebar -->

  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->

    <section class="sidebar">

      <!-- Sidebar user panel -->

     <!-- <div class="user-panel">

        <div class="pull-left image">

          <img src="<?php echo $folder;?>" class="img-circle" alt="User Image">

        </div>

        <div class="pull-left info">

          <p><?php echo $strEmpName; ?></p>

       <a href="#"><i class="fa fa-circle text-success"></i> Online</a>

        </div>

      </div>-->

      <!-- search form -->

     <?php /*?> <form action="#" method="get" class="sidebar-form">

        <div class="input-group">

          <input type="text" name="q" class="form-control" placeholder="Search...">

              <span class="input-group-btn">

                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>

                </button>

              </span>

        </div>

      </form><?php */?>

      <!-- /.search form -->

      <!-- sidebar menu: : style can be found in sidebar.less -->
		<?php
		if($intEmployeeTypeId=='3')
		{
			include("UserMenu/Menu3.html");
		}
		else
		{
			include("UserMenu/Menu.html");
		}
		?>
	
    </section>

    <!-- /.sidebar -->

  </aside>
  <!-- Content Wrapper. Contains page content -->

  <?php echo $pageMainContent; ?>

  <!-- /.content-wrapper -->

  <footer class="main-footer">

    <div class="pull-right hidden-xs">
    </div>

    <strong>Copyright &copy; 2017 <a href="http://www.sibs.com.cn" target="_blank">Sinelec</a></strong> All rights

    reserved.

  </footer>
  <!-- Control Sidebar -->
  <?php /*?><aside class="control-sidebar control-sidebar-dark">

    <!-- Create the tabs -->

    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">

      <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>

      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>

    </ul>

    <!-- Tab panes -->

    <div class="tab-content">

      <!-- Home tab content -->

       <label class="control-sidebar-subheading">

	  <a href="../Logout.php"><i class="fa fa-circle-o"></i>&nbsp;&nbsp;Logout</a> 

	   </label>
      <!-- /.tab-pane -->

    </div>

  </aside><?php */?>

  <!-- /.control-sidebar -->

  <!-- Add the sidebar's background. This div must be placed

       immediately after the control sidebar -->

  <div class="control-sidebar-bg"></div>

</div>
<!-- ./wrapper -->
<!-- jQuery 2.2.3 -->

<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>

<!-- jQuery UI 1.11.4 -->

<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>

<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->

<script>

  $.widget.bridge('uibutton', $.ui.button);

</script>

<!-- Bootstrap 3.3.6 -->

<script src="../bootstrap/js/bootstrap.min.js"></script>

<!-- Morris.js charts -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>

<script src="../plugins/morris/morris.min.js"></script>

<!-- Sparkline -->

<script src="../plugins/sparkline/jquery.sparkline.min.js"></script>

<!-- jvectormap -->

<script src="../plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>

<script src="../plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>

<!-- jQuery Knob Chart -->

<script src="../plugins/knob/jquery.knob.js"></script>

<!-- daterangepicker -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>

<script src="../plugins/daterangepicker/daterangepicker.js"></script>

<!-- datepicker -->

<script src="../plugins/datepicker/bootstrap-datepicker.js"></script>

<!-- Bootstrap WYSIHTML5 -->

<script src="../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>

<!-- Slimscroll -->

<script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>

<!-- FastClick -->

<script src="../plugins/fastclick/fastclick.js"></script>

<!-- AdminLTE App -->

<script src="../dist/js/app.min.js"></script>

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!-- AdminLTE for demo purposes -->

<script src="../dist/js/demo.js"></script>

<script>
  $(function () {
    //Date picker
    $('#datepicker').datepicker({
      autoclose: true,
      todayHighlight: true,
      format: 'yyyy-mm-dd' 
    })
  })
</script>
</body>
</html>

