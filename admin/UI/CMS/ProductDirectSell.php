<?php
ob_start();
ini_set('display_errors',0);
//error_reporting(E_ALL | E_STRICT);
include('../Common.php');
include('../Includes/Functions.php');
require_once ('../../UI/Config/inc_path.php');
require_once "../Includes/ConstantArray.php";
require_once ('../../BL/HomeManager.php');
require_once ('../../BL/UserManager.php');


require_once ('../../BL/ProductManager.php');
$objHomeManager=new HomeManager();
$objUserManager = new UserManager(); 

$objProductManager=new ProductManager();
$currentDate=date('Y-m-d');
$parentCategory=$objHomeManager->GetAndDisplayAllListProduct1();
$countryList=$objUserManager->GetAllCountryList();
$date=date('Y-m-d');
$paramsArray = GetQueryStringParameters();
(isset($paramsArray['action']))? $action=$paramsArray['action'] : $action="";
isset($paramsArray["msg"]) ? $msg=$paramsArray["msg"] : $msg="";
switch($action)
{
   
	case "Insert":
		//echo "<pre>";print_r($_POST);
		$totalProduct=$_POST['addMore'];
		$arrProductOrderCartDetails = array();
		$arrProductOrderDetails=array();
		$order_date=$_POST['order_date'];
		$order_total_amt='';
		$tax_total_amount='';
		for($x=1; $x<=$totalProduct; $x++)
		{
			$order_total_amt+=$_POST['product_amt_'.$x];
			$tax_total_amount+=$_POST['product_tax_'.$x];
		
			if($_POST['product_category_id_'.$x]!='' || $_POST['product_id_'.$x]!='' || $_POST['quantity_'.$x]!='')
			{
				list($productId,$name,$amt)=explode('@_@',$_POST['product_id_'.$x]);
				$arrProductOrderCartDetails[] = array("productCategoryId"=>$_POST['product_category_id_'.$x],"proudctId"=>$productId,
				"productQuantity"=>$_POST['quantity_'.$x],'order_date'=>$order_date,'product_code'=>$_POST['product_code_'.$x],'product_amt'=>$_POST['product_amt_'.$x],'product_tax'=>$_POST['product_tax_'.$x],'product_discount'=>$_POST['product_discount_'.$x]);
			}
		}
		
		list($year,$m,$d)=explode('-',$order_date);
		$arrProductOrderDetails=array('order_date'=>$order_date,'order_current_status'=>'Other Channel Sell Successful','order_total_amt'=>$order_total_amt,'tax_total_amount'=>$tax_total_amount,'dispatch_courier_company'=>'Online','dispatch_courier_tracking_id'=>'Online','dispatch_courier_tracking_url'=>'Online','customer_order_no'=>$_POST['customerOrderNo'],'customer_supplier_no'=>$_POST['customerSupplierNo'],'order_number'=>$_POST['order_number'],'order_year'=>$year);
		
		//echo "<pre>"; print_r($arrProductOrderDetails); 
		//die;
		
	   $user_name=$_POST['name'];
	   $company_name=$_POST['companyname'];
	   $user_email=$_POST['email'];
	   $user_phone=$_POST['phonenumber'];
	   $phone_country_code=$_POST['phone_country_code'];
	   $vat_number=$_POST['vatnumber'];
	   $delivery_address=$_POST['deliveryaddress'];
	   $city=$_POST['city'];
	   $state=$_POST['state'];
	   $zip=$_POST['zip'];

	   $customerOrderNo=$_POST['customerOrderNo'];
	   $customerSupplierNo=$_POST['customerSupplierNo'];
	   
	   list($deliveryCountryId,$country,$deliveryCountryShipping)=explode('@_@',$_POST['country']);
	   
	   $billing_address=$_POST['billingAddress'];
	   $billing_city=$_POST['cityName'];
	   $billing_state=$_POST['stateName'];
	   $billing_zip=$_POST['zipName'];
	   list($billingCountryId,$billing_country,$billingCountryShipping)=explode('@_@',$_POST['countryName']);

		if($billing_address=='')
		{
			$billing_address=$delivery_address;
			$billing_city=$city;
			$billing_state=$state;
			$billing_zip=$zip;
			$billing_country=$country;
			$billingCountryId=$deliveryCountryId;
			$billingCountryShipping=$deliveryCountryShipping;
		}
	   
	    $vatCountryList = array("11","18","28","45","48","50","59","62","63","71","80","86","88","99","105","106","113","120","128","143","144","148","162","163","169","174",
		"190");

	   $arrUserDetails=array('user_name'=>$user_name, 'company_name'=>$company_name, 'user_email'=>$user_email,'user_phone'=>$user_phone,'phone_country_code'=>$phone_country_code,
	   'vat_number'=>$vat_number, 'delivery_address'=>$delivery_address, 'delivery_city'=>$city, 'delivery_state'=>$state, 'delivery_country'=>$country, 
	   'delivery_country_id'=>$deliveryCountryId, 'delivery_zip'=>$zip, 
	   'billing_address'=>$billing_address, 'billing_city'=>$billing_city, 'billing_state'=>$billing_state, 'billing_zip'=>$billing_zip, 'billing_country'=>$billing_country,
	   'customerOrderNo'=>$customerOrderNo, 'customerSupplierNo'=>$customerSupplierNo  );
	   
	  // echo "<pre>";print_r($arrProductOrderCartDetails); 
	   //echo "<pre>";print_r($arrUserDetails);
	  // echo "<pre>";print_r($arrProductOrderDetails);
	 // die;
	   
	   $enquiryData=$objProductManager->InsertProductOrderDetails($arrUserDetails,$arrProductOrderDetails,$arrProductOrderCartDetails);
	   if($enquiryData!='')
		{
			
			header("location:ProductDirectSell.php?urlstring=".EncryptURL("action=&msg=insert"));
		}
		else
		{
		
			header("location:ProductDirectSell.php?urlstring=".EncryptURL("action=&msg=error"));
		
		}
	   
	   
	  
	break;
	
	case "Delete":
	   $intOrderId=$paramsArray['intOrderId'];
	  	$intId=$objProductManager->DeleteDirectSellProductByOrderId($intOrderId);
	   if($intId!='')
		{
			
			header("location:ProductDirectSell.php?urlstring=".EncryptURL("action=&msg=delete"));
		}
		else
		{
		
			header("location:ProductDirectSell.php?urlstring=".EncryptURL("action=&msg=error"));
		
		}
	   
	   
	  
	break;
	
	
}

?>
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
<link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css">
<link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
<link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
<link rel="stylesheet" href="../css/praveen_template.css">

    <link rel="stylesheet" href="../../../website/vendors/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../../../website/vendors/bootstrap/bootstrap-theme.min.css">

    <!-- Vendors -->
    <link rel="stylesheet" href="../../../website/vendors/owl.carousel/owl.carousel.css">
    <link rel="stylesheet" href="../../../website/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="../../../website/vendors/fontawesome/font-awesome.min.css">
    <link rel="stylesheet" href="../../../website/vendors/et-line-icons/et-line-icons.css">
    <link rel="stylesheet" href="../../../website/vendors/themify-icons/themify-icons.css">
    <link rel="stylesheet" href="../../../website/vendors/lineariconsFree/style.css">
    <link rel="stylesheet" href="../../../website/vendors/magnificpopup/magnific-popup.css">

    <!--Fonts-->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700" rel="stylesheet">


    <!--Theme Styles-->
    <link rel="stylesheet" href="../../../website/css/style.css">
    <link rel="stylesheet" href="../../../website/css/responsive.css">
    
	
	<link rel="stylesheet" href="../../../website/css/tinystyle.css" />

<div class="content-wrapper">
	<section class="content-header">
		<ol class="breadcrumb">
			<li><a href="../User/Home.php"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Product Purchase Details</li>
		</ol>
	</section>
	<br/>
	<?php	
	if(isset($paramsArray['msg']))
	{
		$msg=$paramsArray['msg'];	
	?>
		<div class="col-md-12 col-sm-12 col-xs-12 ">
		<?php	
		if($msg=='insert')
		{
		?>
			<div class="alert alert-success alert-dismissible" style="height:50px;">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
				<h4><i class="icon fa fa-check"></i> Order has been added successfully</h4>
			</div>	
		<?php	 
		}
		else if($msg=='error')
		{	
		?>
			<div class="alert alert-danger alert-dismissible" style="height:50px;">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
				<h4><i class="icon fa fa-ban"></i> Error in Process</h4>
			</div>
		<?php	
		}
		else if($msg=='update')
		{
		?>
			<div class="alert alert-success alert-dismissible" style="height:50px;">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
				<h4><i class="icon fa fa-check"></i>Order has been updated successfully</h4>
			</div>
		<?php 
		}
		else if($msg=='delete')
		{
		?>
			<div class="alert alert-success alert-dismissible" style="height:50px;">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
				<h4><i class="icon fa fa-check"></i> Order has been deleted successfully</h4>
			</div>
		<?php
		}	
		?>
	</div>
	<?php	
	}	
	?>

	<script src="../js/jquery-1.11.2.min.js"></script>
	<script language="javascript" type="text/javascript" src="../js/jquery.coolfieldset.js"></script>
	<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
	<script src="../bootstrap/js/bootstrap.min.js"></script>
	
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/jquery.coolfieldset.css" />
	
	<?php
	if($action=='')
	{
	?>
	<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box" id="SibsSchool">
				
				<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
				<script src="../bootstrap/js/bootstrap.min.js"></script>
				<script src="../js/func_ajax.js"></script>
                <div >
                <table>
                    <tr>
                        <td> 
                         <a href="ProductDirectSell.php?urlstring=<?php echo EncryptURL('action=Add')?>"  class="btn btn-success"> Add Other Channel Sell</a>
                         
                        </td> 
                    </tr> 	 
                </table>
				<div class="box-header">
				<h3 class="box-title"> Other Channel Sell Details</h3>
				</div>
                </div>
				<div class="box-body">
				<?php
				
				$arrProductData=$objProductManager->GetDirectSellProductData();
				//echo '<pre>';print_r($arrProductData); die;
				if(!empty($arrProductData))
				{
				?>
					<table id="" class="table table-bordered table-striped">
						<thead>
							<tr>
							<th >Order Details</th>
							<th >Product Details</th>
							<th >Action</th>
							</tr>
						</thead>
						<?php
						foreach($arrProductData as $key=>$arrProductDataVal)
						{
							$orderDetails=$val->ORDER_DATE.'@@'.$val->ORDER_ID.'@@'.$val->ORDER_ID.'@@'.$val->ORDER_TOTAL_AMT.'@@'.$val->ORDER_NUMBER.'@@'.$val->ORDER_YEAR;
							list($orderDate,$orderId,$orderTotalAmt,$orderNo,$orderYear)=explode('@@',$key);
							?>
							
								<tr class="common_table_header">
									<td ><strong>ORDER ID : <?php echo $orderId; ?><br/>
										ORDER DATE : <?php echo $orderDate; ?><br/>
										ORDER TOTAL AMT : <?php echo $orderTotalAmt; ?><br/>
										ORDER NO : <?php echo $orderNo; ?><br/>
										ORDER YEAR : <?php echo $orderYear; ?></strong>
									</td>
									<td>
										<table id="" class="table table-bordered table-striped">
										<tr class="common_table_header" style="background-color:#00CC66;color:white;">
											<td > Product Name</td>
											<td > Code</td>
											<td > Quantity</td>
											<td > Amt</td>
											<td > Tax</td>
											<td > Discount</td>
										</tr>
											<?php
											foreach($arrProductDataVal as $val)
											{
												?>
												<tr class="common_table_header">
													<td > <?php echo $val->PRODUCT_NAME;?></td>
													<td > <?php echo $val->PRODUCT_CODE;?></td>
													<td > <?php echo $val->QUANTITY;?></td>
													<td > <?php echo $val->PRODUCT_AMT;?></td>
													<td > <?php echo $val->PRODUCT_TAX;?></td>
													<td > <?php echo $val->PRODUCT_DISCOUNT;?></td>
												</tr>
												<?php
											}
											?>
										</table>
									</td>
									<td style="vertical-align:middle">    <a href="ProductDirectSell.php?urlstring=<?php echo EncryptURL('action=Delete&intOrderId='.$orderId)?>"  class="btn btn-danger "> Del </a></td>
								</tr>
							
							<?php
						}
						?>
				 </table>
				<?php 			
				}
				else
				{
				?>		
				<table>
					<thead>
					<tr>
					<th style="color:#FF0000">No data found</th>
					</tr>
					</thead>
				</table>
				<?php
				}
				?>				
					
				</div>
			</div>
		</div>
	</div>
</section>

	<?php
	}
	?>
	<section class="content">
		<div class="row">
			<?php
			if($action=='Add')
			{
			?>	

			<div class="col-xs-12">
				<div class="box" id="SibsSchool">
					<div class="box-body">
						<div class="box-body table-responsive no-padding">
							<form class="login-form clearfix bg-gray border shadow radius"  method="post" action="ProductDirectSell.php?urlstring=<?php echo EncryptURL('action=Insert')?>" onSubmit="return validation();">
					
							<div  class="col-sm-12">
								
								<button type="button" class="btn btn-danger btn-xs pull-right" onClick="javascript:getNextRow()"><i class="glyphicon glyphicon-plus"></i> Add More</button>
								
							</div><br/>
						
                           <div class="col-sm-4 form-group">
                             <label class="control-label">Product Category*</label>
                              <select class="form-control" name="product_category_id_1" id="se_1" onChange="getProductByCategoryId(this.value,'1');" required>
								<option value="<?php echo $fillDataArray['product_category_id'];?>">Select Product Category</option>
								<?php
								foreach($parentCategory as $parentCategory1)
								{ 
								?>
								 <option value="<?= $parentCategory1->PRODUCT_CATEGORY_ID."_".$parentCategory1->PRODUCT_CATEGORY_NAME; ?>">
								 <?= $parentCategory1->PRODUCT_CATEGORY_NAME; ?></option>
								<?php  
								
								}
								?>
								</select>
                            </div>
                            <div class="col-sm-4 form-group">
                             <label class="control-label">Product *</label>
							 <div id="productDiv_1">
								<select class="form-control" name="product_id" id="product_id_1"  onChange="getProductByDesId(this.value,'1');" required>
								  <option value="<?php echo $fillDataArray['product_id']; ?>">Select Product</option>
								</select>
							</div>
                            </div>
                            
                            <div class="col-sm-4 form-group ">
								 <label class="control-label">Product Quantity *</label>
								  <input size="4" type="text" id="quantity_1" name="quantity_1" min="1" max="20" value="<?php echo $fillDataArray['quantity']; ?>" required>
								  <input size="4" type="hidden" id="product_amt_1" name="product_amt_1" value="<?php echo $fillDataArray['product_amt']; ?>" required>
                            </div>
							
                           	<div id="productAmtDiv_1">
								<div  class="col-sm-12">
									<div class="col-sm-4 form-group">
										 <label class="control-label">Product Amount *</label>
										  <input size="4" type="text" id="product_amt_1" name="product_amt_1"   required>
									</div>
									
									<div class="col-sm-4 form-group">
										 <label class="control-label">Product Tax * </label>
										  <input size="4" type="text" id="product_tax_1" name="product_tax_1"   required>
									</div>
									
									<div class="col-sm-4 form-group">
										 <label class="control-label">Product Discount *</label>
										  <input size="4" type="text" id="product_discount_1" name="product_discount_1"   required>
									</div>
								</div>
							</div>

							<div id="addMore_1"></div>
							
							<input type="hidden" name="addMore" id="total" value="1" class="form-control select2 input-sm">
							
							<div  class="col-sm-12">
									<div class="col-sm-4 form-group">
										 <label class="control-label">Order Date *</label>
										  <input size="4" type="date" id="order_date" name="order_date" required>
									</div>
									
									<div class="col-sm-4 form-group">
										 <label class="control-label">Shipping Amt *</label>
										  <input size="4" type="number" id="shiping_amt" name="shiping_amt"  required>
									</div>
									
									<div class="col-sm-4 form-group">
										  <label class="control-label">Order Number *</label>
										  <input size="4" type="number" id="order_number" name="order_number"   required>
									</div>
								</div>
							<div  class="col-sm-12">
							<div class="sectionTitle m-top30 m-bottom20">
                          	<h4>Personal Information</h4>
                         	</div>
							</div>
							<div class="col-sm-6">
								<label class="control-label">Name *</label>
                                <input placeholder="Name" type="text" id="name" name="name" value="<?php echo $fillDataArray['name']; ?>" required>
                            </div>
                            
                            <div class="col-sm-6">
								<label class="control-label">Company Name *</label>
                                <input placeholder="Company Name" type="text"id="companyname" name="companyname" value="<?php echo $fillDataArray['companyname']; ?>" required>
                            </div>
                            
                            <div class="col-sm-6">
								<label class="control-label">Email Address *</label>
                                <input placeholder="Email Address" type="email" id="email" name="email" value="<?php echo $fillDataArray['email']; ?>" required>
                            </div>
							
							<div class="col-sm-6">
								<div class="row">
									
									<div class='col-sm-5 form-group'>
									<label class="control-label">Code</label>
									<select name="phone_country_code" id="phone_country_code"  style="font-size:12px;text-align:left; width:100%" >
										<option data-countryCode="GB" value="44" Selected style="font-size:12px;text-align:left">UK (+44)</option>
										<option data-countryCode="US" value="1">USA (+1)</option>
										<optgroup label="Other countries">
											<option data-countryCode="DZ" value="213">Algeria (+213)</option>
											<option data-countryCode="AD" value="376">Andorra (+376)</option>
											<option data-countryCode="AO" value="244">Angola (+244)</option>
											<option data-countryCode="AI" value="1264">Anguilla (+1264)</option>
											<option data-countryCode="AG" value="1268">Antigua &amp; Barbuda (+1268)</option>
											<option data-countryCode="AR" value="54">Argentina (+54)</option>
											<option data-countryCode="AM" value="374">Armenia (+374)</option>
											<option data-countryCode="AW" value="297">Aruba (+297)</option>
											<option data-countryCode="AU" value="61">Australia (+61)</option>
											<option data-countryCode="AT" value="43">Austria (+43)</option>
											<option data-countryCode="AZ" value="994">Azerbaijan (+994)</option>
											<option data-countryCode="BS" value="1242">Bahamas (+1242)</option>
											<option data-countryCode="BH" value="973">Bahrain (+973)</option>
											<option data-countryCode="BD" value="880">Bangladesh (+880)</option>
											<option data-countryCode="BB" value="1246">Barbados (+1246)</option>
											<option data-countryCode="BY" value="375">Belarus (+375)</option>
											<option data-countryCode="BE" value="32">Belgium (+32)</option>
											<option data-countryCode="BZ" value="501">Belize (+501)</option>
											<option data-countryCode="BJ" value="229">Benin (+229)</option>
											<option data-countryCode="BM" value="1441">Bermuda (+1441)</option>
											<option data-countryCode="BT" value="975">Bhutan (+975)</option>
											<option data-countryCode="BO" value="591">Bolivia (+591)</option>
											<option data-countryCode="BA" value="387">Bosnia Herzegovina (+387)</option>
											<option data-countryCode="BW" value="267">Botswana (+267)</option>
											<option data-countryCode="BR" value="55">Brazil (+55)</option>
											<option data-countryCode="BN" value="673">Brunei (+673)</option>
											<option data-countryCode="BG" value="359">Bulgaria (+359)</option>
											<option data-countryCode="BF" value="226">Burkina Faso (+226)</option>
											<option data-countryCode="BI" value="257">Burundi (+257)</option>
											<option data-countryCode="KH" value="855">Cambodia (+855)</option>
											<option data-countryCode="CM" value="237">Cameroon (+237)</option>
											<option data-countryCode="CA" value="1">Canada (+1)</option>
											<option data-countryCode="CV" value="238">Cape Verde Islands (+238)</option>
											<option data-countryCode="KY" value="1345">Cayman Islands (+1345)</option>
											<option data-countryCode="CF" value="236">Central African Republic (+236)</option>
											<option data-countryCode="CL" value="56">Chile (+56)</option>
											<option data-countryCode="CN" value="86">China (+86)</option>
											<option data-countryCode="CO" value="57">Colombia (+57)</option>
											<option data-countryCode="KM" value="269">Comoros (+269)</option>
											<option data-countryCode="CG" value="242">Congo (+242)</option>
											<option data-countryCode="CK" value="682">Cook Islands (+682)</option>
											<option data-countryCode="CR" value="506">Costa Rica (+506)</option>
											<option data-countryCode="HR" value="385">Croatia (+385)</option>
											<option data-countryCode="CU" value="53">Cuba (+53)</option>
											<option data-countryCode="CY" value="90392">Cyprus North (+90392)</option>
											<option data-countryCode="CY" value="357">Cyprus South (+357)</option>
											<option data-countryCode="CZ" value="42">Czech Republic (+42)</option>
											<option data-countryCode="DK" value="45">Denmark (+45)</option>
											<option data-countryCode="DJ" value="253">Djibouti (+253)</option>
											<option data-countryCode="DM" value="1809">Dominica (+1809)</option>
											<option data-countryCode="DO" value="1809">Dominican Republic (+1809)</option>
											<option data-countryCode="EC" value="593">Ecuador (+593)</option>
											<option data-countryCode="EG" value="20">Egypt (+20)</option>
											<option data-countryCode="SV" value="503">El Salvador (+503)</option>
											<option data-countryCode="GQ" value="240">Equatorial Guinea (+240)</option>
											<option data-countryCode="ER" value="291">Eritrea (+291)</option>
											<option data-countryCode="EE" value="372">Estonia (+372)</option>
											<option data-countryCode="ET" value="251">Ethiopia (+251)</option>
											<option data-countryCode="FK" value="500">Falkland Islands (+500)</option>
											<option data-countryCode="FO" value="298">Faroe Islands (+298)</option>
											<option data-countryCode="FJ" value="679">Fiji (+679)</option>
											<option data-countryCode="FI" value="358">Finland (+358)</option>
											<option data-countryCode="FR" value="33">France (+33)</option>
											<option data-countryCode="GF" value="594">French Guiana (+594)</option>
											<option data-countryCode="PF" value="689">French Polynesia (+689)</option>
											<option data-countryCode="GA" value="241">Gabon (+241)</option>
											<option data-countryCode="GM" value="220">Gambia (+220)</option>
											<option data-countryCode="GE" value="7880">Georgia (+7880)</option>
											<option data-countryCode="DE" value="49" selected>Germany (+49)</option>
											<option data-countryCode="GH" value="233">Ghana (+233)</option>
											<option data-countryCode="GI" value="350">Gibraltar (+350)</option>
											<option data-countryCode="GR" value="30">Greece (+30)</option>
											<option data-countryCode="GL" value="299">Greenland (+299)</option>
											<option data-countryCode="GD" value="1473">Grenada (+1473)</option>
											<option data-countryCode="GP" value="590">Guadeloupe (+590)</option>
											<option data-countryCode="GU" value="671">Guam (+671)</option>
											<option data-countryCode="GT" value="502">Guatemala (+502)</option>
											<option data-countryCode="GN" value="224">Guinea (+224)</option>
											<option data-countryCode="GW" value="245">Guinea - Bissau (+245)</option>
											<option data-countryCode="GY" value="592">Guyana (+592)</option>
											<option data-countryCode="HT" value="509">Haiti (+509)</option>
											<option data-countryCode="HN" value="504">Honduras (+504)</option>
											<option data-countryCode="HK" value="852">Hong Kong (+852)</option>
											<option data-countryCode="HU" value="36">Hungary (+36)</option>
											<option data-countryCode="IS" value="354">Iceland (+354)</option>
											<option data-countryCode="IN" value="91">India (+91)</option>
											<option data-countryCode="ID" value="62">Indonesia (+62)</option>
											<option data-countryCode="IR" value="98">Iran (+98)</option>
											<option data-countryCode="IQ" value="964">Iraq (+964)</option>
											<option data-countryCode="IE" value="353">Ireland (+353)</option>
											<option data-countryCode="IL" value="972">Israel (+972)</option>
											<option data-countryCode="IT" value="39">Italy (+39)</option>
											<option data-countryCode="JM" value="1876">Jamaica (+1876)</option>
											<option data-countryCode="JP" value="81">Japan (+81)</option>
											<option data-countryCode="JO" value="962">Jordan (+962)</option>
											<option data-countryCode="KZ" value="7">Kazakhstan (+7)</option>
											<option data-countryCode="KE" value="254">Kenya (+254)</option>
											<option data-countryCode="KI" value="686">Kiribati (+686)</option>
											<option data-countryCode="KP" value="850">Korea North (+850)</option>
											<option data-countryCode="KR" value="82">Korea South (+82)</option>
											<option data-countryCode="KW" value="965">Kuwait (+965)</option>
											<option data-countryCode="KG" value="996">Kyrgyzstan (+996)</option>
											<option data-countryCode="LA" value="856">Laos (+856)</option>
											<option data-countryCode="LV" value="371">Latvia (+371)</option>
											<option data-countryCode="LB" value="961">Lebanon (+961)</option>
											<option data-countryCode="LS" value="266">Lesotho (+266)</option>
											<option data-countryCode="LR" value="231">Liberia (+231)</option>
											<option data-countryCode="LY" value="218">Libya (+218)</option>
											<option data-countryCode="LI" value="417">Liechtenstein (+417)</option>
											<option data-countryCode="LT" value="370">Lithuania (+370)</option>
											<option data-countryCode="LU" value="352">Luxembourg (+352)</option>
											<option data-countryCode="MO" value="853">Macao (+853)</option>
											<option data-countryCode="MK" value="389">Macedonia (+389)</option>
											<option data-countryCode="MG" value="261">Madagascar (+261)</option>
											<option data-countryCode="MW" value="265">Malawi (+265)</option>
											<option data-countryCode="MY" value="60">Malaysia (+60)</option>
											<option data-countryCode="MV" value="960">Maldives (+960)</option>
											<option data-countryCode="ML" value="223">Mali (+223)</option>
											<option data-countryCode="MT" value="356">Malta (+356)</option>
											<option data-countryCode="MH" value="692">Marshall Islands (+692)</option>
											<option data-countryCode="MQ" value="596">Martinique (+596)</option>
											<option data-countryCode="MR" value="222">Mauritania (+222)</option>
											<option data-countryCode="YT" value="269">Mayotte (+269)</option>
											<option data-countryCode="MX" value="52">Mexico (+52)</option>
											<option data-countryCode="FM" value="691">Micronesia (+691)</option>
											<option data-countryCode="MD" value="373">Moldova (+373)</option>
											<option data-countryCode="MC" value="377">Monaco (+377)</option>
											<option data-countryCode="MN" value="976">Mongolia (+976)</option>
											<option data-countryCode="MS" value="1664">Montserrat (+1664)</option>
											<option data-countryCode="MA" value="212">Morocco (+212)</option>
											<option data-countryCode="MZ" value="258">Mozambique (+258)</option>
											<option data-countryCode="MN" value="95">Myanmar (+95)</option>
											<option data-countryCode="NA" value="264">Namibia (+264)</option>
											<option data-countryCode="NR" value="674">Nauru (+674)</option>
											<option data-countryCode="NP" value="977">Nepal (+977)</option>
											<option data-countryCode="NL" value="31">Netherlands (+31)</option>
											<option data-countryCode="NC" value="687">New Caledonia (+687)</option>
											<option data-countryCode="NZ" value="64">New Zealand (+64)</option>
											<option data-countryCode="NI" value="505">Nicaragua (+505)</option>
											<option data-countryCode="NE" value="227">Niger (+227)</option>
											<option data-countryCode="NG" value="234">Nigeria (+234)</option>
											<option data-countryCode="NU" value="683">Niue (+683)</option>
											<option data-countryCode="NF" value="672">Norfolk Islands (+672)</option>
											<option data-countryCode="NP" value="670">Northern Marianas (+670)</option>
											<option data-countryCode="NO" value="47">Norway (+47)</option>
											<option data-countryCode="OM" value="968">Oman (+968)</option>
											<option data-countryCode="PW" value="680">Palau (+680)</option>
											<option data-countryCode="PA" value="507">Panama (+507)</option>
											<option data-countryCode="PG" value="675">Papua New Guinea (+675)</option>
											<option data-countryCode="PY" value="595">Paraguay (+595)</option>
											<option data-countryCode="PE" value="51">Peru (+51)</option>
											<option data-countryCode="PH" value="63">Philippines (+63)</option>
											<option data-countryCode="PL" value="48">Poland (+48)</option>
											<option data-countryCode="PT" value="351">Portugal (+351)</option>
											<option data-countryCode="PR" value="1787">Puerto Rico (+1787)</option>
		
											<option data-countryCode="QA" value="974">Qatar (+974)</option>
											<option data-countryCode="RE" value="262">Reunion (+262)</option>
											<option data-countryCode="RO" value="40">Romania (+40)</option>
											<option data-countryCode="RU" value="7">Russia (+7)</option>
											<option data-countryCode="RW" value="250">Rwanda (+250)</option>
											<option data-countryCode="SM" value="378">San Marino (+378)</option>
											<option data-countryCode="ST" value="239">Sao Tome &amp; Principe (+239)</option>
											<option data-countryCode="SA" value="966">Saudi Arabia (+966)</option>
											<option data-countryCode="SN" value="221">Senegal (+221)</option>
											<option data-countryCode="CS" value="381">Serbia (+381)</option>
											<option data-countryCode="SC" value="248">Seychelles (+248)</option>
											<option data-countryCode="SL" value="232">Sierra Leone (+232)</option>
											<option data-countryCode="SG" value="65">Singapore (+65)</option>
											<option data-countryCode="SK" value="421">Slovak Republic (+421)</option>
											<option data-countryCode="SI" value="386">Slovenia (+386)</option>
											<option data-countryCode="SB" value="677">Solomon Islands (+677)</option>
											<option data-countryCode="SO" value="252">Somalia (+252)</option>
											<option data-countryCode="ZA" value="27">South Africa (+27)</option>
											<option data-countryCode="ES" value="34">Spain (+34)</option>
											<option data-countryCode="LK" value="94">Sri Lanka (+94)</option>
											<option data-countryCode="SH" value="290">St. Helena (+290)</option>
											<option data-countryCode="KN" value="1869">St. Kitts (+1869)</option>
											<option data-countryCode="SC" value="1758">St. Lucia (+1758)</option>
											<option data-countryCode="SD" value="249">Sudan (+249)</option>
											<option data-countryCode="SR" value="597">Suriname (+597)</option>
											<option data-countryCode="SZ" value="268">Swaziland (+268)</option>
											<option data-countryCode="SE" value="46">Sweden (+46)</option>
											<option data-countryCode="CH" value="41">Switzerland (+41)</option>
											<option data-countryCode="SI" value="963">Syria (+963)</option>
											<option data-countryCode="TW" value="886">Taiwan (+886)</option>
											<option data-countryCode="TJ" value="7">Tajikstan (+7)</option>
											<option data-countryCode="TH" value="66">Thailand (+66)</option>
											<option data-countryCode="TG" value="228">Togo (+228)</option>
											<option data-countryCode="TO" value="676">Tonga (+676)</option>
											<option data-countryCode="TT" value="1868">Trinidad &amp; Tobago (+1868)</option>
											<option data-countryCode="TN" value="216">Tunisia (+216)</option>
											<option data-countryCode="TR" value="90">Turkey (+90)</option>
											<option data-countryCode="TM" value="7">Turkmenistan (+7)</option>
											<option data-countryCode="TM" value="993">Turkmenistan (+993)</option>
											<option data-countryCode="TC" value="1649">Turks &amp; Caicos Islands (+1649)</option>
											<option data-countryCode="TV" value="688">Tuvalu (+688)</option>
											<option data-countryCode="UG" value="256">Uganda (+256)</option>
											<!-- <option data-countryCode="GB" value="44">UK (+44)</option> -->
											<option data-countryCode="UA" value="380">Ukraine (+380)</option>
											<option data-countryCode="AE" value="971">United Arab Emirates (+971)</option>
											<option data-countryCode="UY" value="598">Uruguay (+598)</option>
											<!-- <option data-countryCode="US" value="1">USA (+1)</option> -->
											<option data-countryCode="UZ" value="7">Uzbekistan (+7)</option>
											<option data-countryCode="VU" value="678">Vanuatu (+678)</option>
											<option data-countryCode="VA" value="379">Vatican City (+379)</option>
											<option data-countryCode="VE" value="58">Venezuela (+58)</option>
											<option data-countryCode="VN" value="84">Vietnam (+84)</option>
											<option data-countryCode="VG" value="84">Virgin Islands - British (+1284)</option>
											<option data-countryCode="VI" value="84">Virgin Islands - US (+1340)</option>
											<option data-countryCode="WF" value="681">Wallis &amp; Futuna (+681)</option>
											<option data-countryCode="YE" value="969">Yemen (North)(+969)</option>
											<option data-countryCode="YE" value="967">Yemen (South)(+967)</option>
											<option data-countryCode="ZM" value="260">Zambia (+260)</option>
											<option data-countryCode="ZW" value="263">Zimbabwe (+263)</option>
										</optgroup>								
									</select>
		
									</div>
									 <div class="col-sm-7">
									 	<label class="control-label">Ph Number *</label>
										<input placeholder="Phone Number" type="text" id="phonenumber" name="phonenumber" maxlength="15" 
										value="<?php echo $fillDataArray['phonenumber']; ?>" required>
									</div>
								</div>
							</div>
                            
                            <div class="col-sm-6">
                                <input placeholder="Customer Order No" type="text" id="customerOrderNo" name="customerOrderNo" value="">
                            </div>
                            
                            <div class="col-sm-6">
                                <input placeholder="Customer Supplier No" type="text"id="customerSupplierNo" name="customerSupplierNo" value="">
                            </div>

                            <div class="col-sm-12">
                                <input placeholder="Delivery Address *" type="text" name="deliveryaddress" id="deliveryaddress" value="<?php echo $fillDataArray['deliveryaddress'] ?>" required>					
                            </div>	
								<!---------------------------------------------------------->
							
							  <div class="col-sm-6">
								<input  placeholder="City/District/Town"  type="text" name="city" id="city" required>
							  </div>
							  <div class="col-sm-6">
								<input  type="text" placeholder="State"  id="state" name="state" required>
							  </div>
							  <div class="col-sm-6">
								<input type="text"  id="ZIP" placeholder="zip" name="zip" required>
							  </div>
							  <div class="col-sm-6">
								<select  placeholder="Country"  type="text"  id="country" onChange="showHideVatNo(this.value)" name="country" required>
								<option value=''>Select Country</option>
								<?php 
								if(count($countryList)>0)
								{
									foreach($countryList as $country)
									{
								 ?> 
									<option  value="<?php echo $country->COUNTRY_ID.'@_@'.$country->COUNTRY.'@_@'.$country->SHIPPING_AMT; ?>" >
									<?php echo $country->COUNTRY; ?></option>
								<?php
									 } 
								 }
								 ?>
								</select>
							  </div>
							
							<div class="col-sm-12">
                                <input type="checkbox"  id="yourBox" style="float:left; width:20px">
                              	<label for="example_check1" style=" font-weight:400">Billing Address Different from Delivery address </label>
							</div>
								
							<div class="col-sm-12">
						<input placeholder="Billing Address" type="text" id="billingAddress" name="billingAddress" disabled  value="<?php echo $fillDataArray['example_check1']?>">

							</div>

							
							 <div class="col-sm-6">
								<input  placeholder="City/District/Town"  type="text" name="cityName" id="cityName" disabled>
							  </div>
							  <div class="col-sm-6">
								<input  placeholder="State" name="stateName" type="text" id="stateName" disabled>
							  </div>
							  <div class="col-sm-6">
								<input type="text" name="zipName"  placeholder="ZIP" id="zipName" disabled >
							  </div>
							  <div class="col-sm-6">
								<select  placeholder="Country"  type="text" name="countryName" onChange="showHideVatNo(this.value)"  id="countryName" disabled>
									<option value=''>Select Country</option>
									<?php 
									if(count($countryList)>0)
									{
										foreach($countryList as $countryBill)
										{
									 ?> 
                                        <option  value="<?php echo $countryBill->COUNTRY_ID.'@_@'.$countryBill->COUNTRY.'@_@'.$countryBill->SHIPPING_AMT; ?>" >
                                        <?php echo $countryBill->COUNTRY; ?></option>
									<?php
										 } 
									 }
									 ?>
								</select>
							  </div>
							  
                            <div class="col-sm-12">
								<input id="example_check1" type="checkbox" style="float:left; width:20px">
								<label for="example_check1"  style="float:left; font-weight:400">If you are a company situated outside Germany but in EU and want a 
								VAT free quote</label>
                                <input placeholder="VAT Number" type="text" id="vatnumber" name="vatnumber" disabled value="<?php echo $fillDataArray['vatnumber']; ?>">
                            </div>
							
							<div class="col-sm-12 text-center">
								<button class="btn btn-primary btn-lg col-sm-4 col-xs-12 center-block m-top30" >Submit</button>
							</div>
						</form>
							<script src="../js/func_ajax.js"></script>
							<script>
							function getProductByCategoryId(CategoryId,count)
							{
								var res=0;
								for(var j=1; j<count;j++)
								{
									var res=res+','+String(document.getElementById('product_id_'+j).value);				
								}
								var div_id="productDiv_"+count;
								callAjax(div_id, "../Ajax/getProductByCategoryId.php", {
								params:"CategoryId="+CategoryId+"&count="+count+"&res="+res+"&flag=1",
								meth:"get",
								async:true,
								errorfunc:"ajaxError()" }
								);
							}
							
							function getProductByDesId(ProductId,count)
							{
								console.log(ProductId);
								var div_id="productAmtDiv_"+count;
								var flag='DirectSell';
								callAjax(div_id, "../Ajax/getProductByDesId.php", {
								params:"ProductId="+ProductId+"&count="+count+"&flag="+flag,
								meth:"get",
								async:true,
								errorfunc:"ajaxError()" 
							}
							
								);
							}
							
							function validation()
							{
								var Category=document.getElementById('se').value;
								var getProduct=document.getElementById('product_id').value;
								var qua=document.getElementById('quantity').value;
							   // var Descrip=document.getElementById('Description').value;
								var username=document.getElementById('name').value;
								var companyname=document.getElementById('companyname').value;
								var phone=document.getElementById('phonenumber').value;
								var Email=document.getElementById('email').value;
								var deliveryaddress=document.getElementById('deliveryaddress').value;
								// document.getElementById("example_check").disabled = true;
										if(Category=='')
											{
												alert("Plaese select product category");
												return false;
											}
										 if(getProduct=='')
											 {
												 alert("Plaese select a product");
												 return false;
										   }  
										  if(qua=='')
										   {
											alert("Plaese enter quantity");
											document.getElementById('quantity').focus();
											return false;
										   } 
							
										   //  if(Descrip=='')
										   // {
										   //  alert("Plaese fill the Description");
										   //  return false;
										   // } 
										   if(username==''){
							
											alert("Plaese enter your Name");
											document.getElementById('name').focus();
											document.getElementById('name').value='';
											return false;
										   }
							
										   if(!isNaN(username))
										   {
											  alert("Enter only Alphabhate in Name");
											  document.getElementById('name').focus();
											  return false;
										   }
										   if(companyname==''){
							
											alert("Plaese enter Company Name");
											document.getElementById('companyname').focus();
											return false;
											}
							
										   /*if(!isNaN(companyname))
										   {
											  alert("Enter only Alphabhate in Conpany Name");
											 document.getElementById('companyname').focus();
											 return false;
										   }*/
										   if(phone==''){
							
											alert("Plaese enter Phone Number");
											document.getElementById('phonenumber').focus();
											return false;
										   }
										  if(isNaN(phone))
										   {
											  alert("Enter only number in Phone Number");
											  document.getElementById('phonenumber').focus();
											  return false;
										   }
										   /*if((phone).length<15)
										   {
											alert("Enter only maximum 15 digit mobile number");
											document.getElementById('phonenumber').focus();
											return false;
										   }*/
										   if(Email==''){
							
											alert("Please enter Email Id");
											document.getElementById('email').focus();
											return false;
										   }
										   if(deliveryaddress=='')
										   {
											 alert("Plaese enter Delivery Address");
											 document.getElementById('deliveryaddress').focus();
											 return false;
										   }
								}
							
								document.getElementById('yourBox').onchange = function() 
								{
									document.getElementById('billingAddress').disabled = !this.checked;
									document.getElementById('cityName').disabled = !this.checked;
									
									document.getElementById('stateName').disabled = !this.checked;
									
									document.getElementById('zipName').disabled = !this.checked;
									document.getElementById('countryName').disabled = !this.checked;
							
								};
								document.getElementById('example_check1').onchange = function() 
								{
								document.getElementById('vatnumber').disabled = !this.checked;
								};
								
							function refreshCaptcha()
							{
								var img = document.images['captchaimg'];
								img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
							}
							function myFunction()
							{
							  var x = document.getElementById("productdescription");
							  if (x.style.display === "block")
							  {
								x.style.display = "none";
							  }
							  else 
							  {
								x.style.display = "block";
							  }
							}
							/*
							This script is identical to the above JavaScript function.
							*/
							function getNextRow()
							{
								var r = document.getElementById('total').value;
								//alert(r);
								if(document.getElementById('se_'+r).value==""){
									alert('Please select category.');
									return false;
								}
								var div_id = "addMore_"+r;
								var flag='directSell';
								callAjax(div_id, "../../../website/discriptionRajat.php", {
								params:"row="+r+"&catId="+document.getElementById('se_'+r).value+"&flag="+flag,
								
								meth:"get",
								async:true,
								errorfunc:"ajaxError()" }
								);
								 document.getElementById('total').value = Number(r)+1;
							}
							</script>
						</div>
					</div>
				</div>
			</div>
			
			<?php
			}
			?>

		</div>
	</section>
	
</div> 
<?php
$pageMainContent = ob_get_contents();
ob_end_clean();
$pagetitle = "Other Channel Sell";
//Apply the template
include('../MasterTemplatePage.php');
?>