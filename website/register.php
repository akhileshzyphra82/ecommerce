<?php
ob_start();
ini_set('display_errors','0');
//error_reporting(E_ALL | E_STRICT);
require_once ('../admin/BO/User.php');
require_once ('../admin/BL/UserManager.php');
require_once("../admin/UI/Includes/Functions.php");

$objUserManager = new UserManager(); 
$countryList=$objUserManager->GetAllCountryList();

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
<?php include 'header.php';
$paramsArray = GetQueryStringParameters();
(isset($paramsArray['action']))? $action=$paramsArray['action'] : $action="";
(isset($paramsArray['msg']))? $msg=$paramsArray['msg'] : $msg="";


switch($action)
{	
case "profile":
	$userId=$paramsArray['CUSTOMER_ID'];
	$objUserManager = new UserManager(); 
	$result=$objUserManager->Updateuser($userId);
break;

}
	?>
	<style>
form select {
    font-family: "Roboto", sans-serif;
    outline: 0;
    background: #fff;
    width: 100%;
    margin: 0 0 15px;
    padding: 15px 15px;
    box-sizing: border-box;
    font-size: 14px;
    border: 1px solid #dae0e2;
	}
	</style>

    <!-- Breadcrumb -->
    <section class="row page_header section-spacing">
        <div class="container">
            <h3>Register</h3>
            <ol class="breadcrumb">
                <li><a href="index.php">home</a></li>
                <li class="active">Register</li>
            </ol>
        </div>
    </section>
  <?php
   //echo "<pre>";print_r($result);
	if(isset($paramsArray["parameters"]))
	{
	  list($Name,$lastName,$email,$mobile,$phoneNo,$companyName,$DesignationName)=explode("//",$paramsArray["parameters"]);
	}
  ?>
    <!-- Register -->
	
	
    <section class="row section-spacing2 bg-pattern">
        <div class="container">
		<?php
		if($paramsArray['msg']=='UpdatedProfile')
		{
		  ?>
		    <div class="row" style="text-align:center">
			  <p style="color:green;font-size:20px"><b>Profile has been updated successfully</b></p>
			</div> 
		  <?php
			$objUserManager = new UserManager(); 
			$result=$objUserManager->GetuserInfo($_SESSION['CUSTOMER_EMAIL'],'2');
		  
		}
		if($paramsArray['CUSTOMER_ID']!='')
		{
			$userId=$paramsArray['CUSTOMER_ID'];
			$objUserManager = new UserManager(); 
			$result=$objUserManager->Updateuser($userId);
		}
		?>
		
        	<div class="sectionTitle p-bottom40">
                <h2><?php if(isset($result)) echo "Update Account"; else echo "Account Register"; ?></h2>
            </div>
            <div class="row">
                <div class="col-sm-8 center-block register-form">
                    <div class="form"s style="white-space:normal">
                        <form class="login-form clearfix bg-gray border" action="login.php?urlstring=<?php echo EncryptURL('action=Register'); ?>" method="post" enctype="multipart/form-data">
						<?php if(isset($result)) list($fname,$lname)=explode(" ",$result[0]->NAME); ?>
                            <div class="col-sm-6">
                          <input placeholder="First Name *" type="text" name="firstName" id="firstName" value="<?php if(isset($result)) echo $fname; else echo $Name; ?>">
                          <span style="color:red;margin:0px;padding:0" id="first_name_msg"></span>
                            </div>
                            <div class="col-sm-6">
                                <input placeholder="Last Name" type="text" name="lastName" id="lastName" value="<?php if(isset($result)) echo $lname; else echo $lastName; ?>">
                            </div>
							
							
                           
                            <div class="col-sm-6"> 
                                <input placeholder="Email *" type="email" <?php if(isset($result)) echo "readonly='true'";?> name="Email" id="Email" value="<?php if(isset($result)) echo $result[0]->COMMUNICATION_EMAIL_ID; else echo $email; ?>"> 
								 <span style="color:red;margin:0px;padding:0" id="email_msg"></span>
																	<?php if($msg=="DuplicatId"){?>
									<span style="color:#FF0000;" ><?php echo "Email-Id already exist !!!";?></span>
									<?php }
									?>
                            </div>
                            
                             <div class="col-sm-2">
							    <select name="mobile_country_code" id="mobile_country_code" style="font-size:12px;text-align:left">
                                    <option data-countryCode="GB" value="44" Selected>UK (+44)</option>
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
                                            <option data-countryCode="DE" value="49">Germany (+49)</option>
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
							<div class="col-sm-4">
                                <input placeholder="Phone Number*" type="text" name="MobileNumber" id="MobileNumber" value="<?php if(isset($result)) echo $result[0]->COMMUNICATION_MOBILE_NUM; else echo $mobile ?>" onKeyPress="return validateNumber(event)"><span style="color:red;margin:0px;padding:0" id="mobile_msg"></span>
                            </div>
						  	<div class="clearfix"></div>
							<div class="col-sm-6">
                                <input placeholder="Company Name" type="text" name="companyName" id="companyName" value="<?php if(isset($result)) echo $result[0]->COMPANY_NAME; else echo $companyName;  ?>">
                            </div>
					<div class="col-sm-6">
                                <input placeholder="Designation" type="text" name="DesignationName" id="DesignationName" value="<?php if(isset($result)) echo $result[0]->DESIGNATION; else echo $DesignationName;?>">
                            </div>							
								
							<div class="clearfix"></div>
							
							
							<div class="col-sm-6" <?php if(isset($result)) {  $passwordFlag=1; echo "style='display:none;'"; }  else { $passwordFlag=0; } ?>>
                                <input placeholder="Password *" type="password" name="Password" id="Password">
                            </div>
                            <div class="col-sm-6" <?php if(isset($result)) echo "style='display:none;'"; ?> >
                               <input placeholder="Confirm Password *" type="password" name="ConformPassword" id="ConformPassword">
                            </div>
							<input type="hidden" id="pass_flag_applicable" value="<?php echo $passwordFlag; ?>">
							<div class="row" style="text-align:center">
							<span style="color:red;margin:0px;padding:0;text-align:center"  id="msg_password"></span>
							</div>
                            <div class="col-sm-12">
                                <input placeholder="Delivery Address*" type="text" name="deliveryaddress" id="deliveryaddress" value="<?php echo $fillDataArray['deliveryaddress'] ?>" required>					
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
							 <div class="col-md-6">
                                       
                                    <div class="g-recaptcha" data-sitekey="captcha_code_file"></div>
                                    	<img src="captcha_code_file.php?rand=<?php echo rand(); ?>" id='captchaimg' >
                                    <div >
										<?php if($msg=="Error"){?>
                                        <span style="color:#FF0000;"><?php echo "Either captcha or password does not match !!!";?></span>
                                        <?php }
                                        ?>  
                                        <input  type="hidden" name="captcha" id="captcha" value="<?php echo rand(); ?>">
                                        <label for='message'>Enter the code above here :</label><br>
                                        <input id="6_letters_code" name="6_letters_code" type="text"><br>
                                        <small>Can't read the image? click <a href='javascript: refreshCaptcha();'>here</a> to refresh</small>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
							
                            <div class="col-sm-12 text-center">
                                <input type="submit" class="btn btn-primary btn-xlg col-sm-8 col-xs-12 center-block m-top30" onClick=" return confirm ('Are You Sure you want to Save it?\n Click OK to Continue, Cancel to Stop'),ValidateForm();" value="<?php if(isset($result)) echo "Update"; else echo " Register"; ?>">
                                <p class="message p-top30 margin-bottom0">Already registered ? <a href="login.php">Account Login</a></p>
                            </div>
									<input  type="HIDDEN" name="userId" id="userId" value="<?php echo $result[0]->USER_ID; ?>">
                        </form>
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
	<script>
	function refreshCaptcha()
	{
		var img = document.images['captchaimg'];
		img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
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

function ValidateForm() 
{
	var x = document.getElementById("firstName").value;
	var string = /^[a-zA-Z ]+$/;
	if (x == "") 
	{
		document.getElementById("first_name_msg").innerHTML="Name is Mandatory";
		document.getElementById("firstName").focus();
		return false;
	}
	else
	{
		document.getElementById("first_name_msg").innerHTML="";
	}
	if(!x.match(string))   
	{  
		alert("plss use character");
		return false;  
	}  
	var email = document.getElementById("Email").value;
	if(email=="" || email.trim()=="")
	{
		 document.getElementById("email_msg").innerHTML="E-mail is Mandatory";
		 document.getElementById("Email").focus();
		return false;
	}
	else
	{
		var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
		if (reg.test(email) == false) 
		{
			document.getElementById("email_msg").innerHTML="Invalid E-mail";
			return false;
		}
		else
		{
			document.getElementById("email_msg").innerHTML="";
		}
	}
	var MobileNumber = document.getElementById("MobileNumber").value;
	if(MobileNumber=="" || MobileNumber.trim()=="")
	{
		 document.getElementById("mobile_msg").innerHTML="Mobile No. is Mandatory";
		 document.getElementById("MobileNumber").focus();
		 return false;
	}
	else
	{
		document.getElementById("MobileNumber").innerHTML="";
	}
  
	var Password=   document.getElementById("Password").value;
	if(document.getElementById("pass_flag_applicable").value==0)
	{
		var ConformPassword=   document.getElementById("ConformPassword").value;
		if((Password=="" || Password.trim()=="") || (ConformPassword=="" || ConformPassword.trim()=="") )	
		{
			document.getElementById("msg_password").innerHTML="Password or confirm password should not blank";
			return false;
		}
		else
		{
			if(Password!=ConformPassword)
			{
				document.getElementById("msg_password").innerHTML="Password & confirm password should be same";
				return false;
			}
		}
	}
	
	if(!x1.match(string))   
	{  
		alert("Please use character");
		return false;  
	}  
		
	if(!x2.match(string))   
	{  
		alert("Please use Numeric");
		return false;  
	} 
	
	var x3 = document.getElementById("MobileNumber").value;
	var string = !/^[0-9]+$/.test(z);
	if (x3 == "") 
	{
		alert("Phone can not be left blank.");
		return false;
	}
	if(!x3.match(string))   
	{  
		alert("Please use Numeric");
		return false;  
	} 
	
	
	if(document.getElementById("Password").value=="") 
	{
		alert("Password can not be left blank.");
		return false;
	} 
}
	  
function validateNumber(event) {
	var key = window.event ? event.keyCode : event.which;
	if (event.keyCode === 8 || event.keyCode === 46) {
	return true;
	} else if ( key < 48 || key > 57 ) {
	  document.getElementById("MobileNumber").innerHTML="Mobile no should numeric";
	return false;
	} else {
	return true;
	}
};
</script>


</body>
</html>