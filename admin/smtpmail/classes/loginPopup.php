<?php
require_once("../admin/UI/Includes/Functions.php");
//echo "<pre>";print_r($_REQUEST);die();
list($val,$message)=explode("_",$_REQUEST['val']);
?>
        <div class="container">
        	
            <div class="row">
            	<div class="col-sm-6 col-md-5 col-xs-12 center-block">
                      <div class="form bg-gray clearfix login-form border">
					  <div class="sectionTitle p-bottom40">
                <h2>Account Login</h2>
				
            </div>
			
                            <form class="login-form clearfix" action="login.php?urlstring=<?php echo EncryptURL('action=LoginPopUp'); ?>" method="post" enctype="multipart/form-data">
                                <div class="col-sm-12">
                                	<input type="text" placeholder="Email" name="Email" id="Email"/>
                                </div>
									<input type="hidden" id="productId" name="productId" value="<?php echo $val; ?>">
                                <div class="col-sm-12">
                                    <input type="password" placeholder="Password" name="password" id="password"/>
									<span style="color:#FF0000;"><?php echo $message; ?></span>
                                    <div class="checkbox text-left">
                                        <input type="checkbox" name="example_check" id="example_check">
                                        <label for="example_check1">Remember Me</label>
                                    </div>
                                    <button class="btn btn-primary btn-xlg btn-block">login</button>
                                </div>

                                <div class="col-sm-12">
                                    <p class="message p-top30 margin-bottom0"><a href="resetpassword.php">Forgot password?</a></p>
                                    <p class="message p-top10 margin-bottom0">Not registered? <a href="register.php">Create an account</a></p>
                                </div>
                            </form>
                      </div>
                </div>
            </div>
        </div>
  