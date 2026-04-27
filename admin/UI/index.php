<?php
include('indexPath.php');
//ini_set("display_errors",0);
set_time_limit(0);
////error_reporting(E_ALL & ~E_NOTICE);
//session_start();
$indexPage = 'index.php';
require_once ("Includes/Functions.php");
$responseURL = (isset($_GET['urlstring']) != "") ? DecryptURL($_GET['urlstring']) : "";
$responseString = isset($_GET['responsestring']);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<style>
#hero {
    position: relative;
    min-height: 100%;
	overflow:hidden;
    background: rgb(40, 70, 102) url(Images/es-bg.jpg) no-repeat center center;
    background-size: cover;
    color: #fff;
	}
</style>
<body class="hold-transition login-page"  id="hero">
<div class="login-box">
  <div class="login-logo">
     <b>Login</b>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your session</p>

    <form action="CommonIndex.php?responsestring=<?php echo $responseURL;?>&check" method="post"  >
			<input type="hidden" name="indexPage" value="<?php echo $indexPage; ?>"  />
		<div class="form-group has-feedback">
			<input type="text" class="form-control" name="txtUserName"  placeholder="Username" required="" />
			<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
		</div>
		<div class="form-group has-feedback">
			<input type="password" class="form-control" name="txtPassword" placeholder="Password" required="" />
			<span class="glyphicon glyphicon-lock form-control-feedback"></span>
		</div>
		<div class="row">
			<div class="col-xs-8">
		</div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
		</div>
    </form>
    <!-- /.social-auth-links -->
	<div>
		<h4><i class="fa fa-paw"></i>SINELEC Monitoring System</h4>
		<p>©2017 All Rights Reserved to <a href="https://www.sinelec-tech.com/" target="_blank">SINELIC</a>. Privacy and Terms</p>
	</div>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>
