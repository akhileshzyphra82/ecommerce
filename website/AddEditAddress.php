<?php
ob_start();
ini_set('display_errors','0');
//error_reporting(E_ALL | E_STRICT);
require_once ('../admin/BO/User.php');
require_once ('../admin/BL/UserManager.php');
require_once("../admin/UI/Includes/Functions.php");
$objUserManager = new UserManager(); 
$countryList=$objUserManager->GetAllCountryList();
$strUserAddId=$_GET['addId'];
list($intUserId,$intUserAddId)=explode("@_@",$strUserAddId);
if($intUserAddId!="")
{
	$arrAddData=$objUserManager->UpdateuserAddress($intUserAddId);
	$arrAddDetails=$arrAddData[0];
}
//echo "<pre>"; print_r($arrAddDetails);die;
?>
<div class="row">
    <div class="col-sm-11 center-block register-form">
        <div class="sectionTitle p-bottom10 p-top30">
            <h4><?php if($intUserAddId=="") echo "Add Address"; else echo "Update Address" ?></h4>
        </div>
        <div class="form" style="white-space:normal">
            <form class="login-form clearfix bg-gray border" action="YourAccount.php?urlstring=<?php echo EncryptURL('action=InsertUpdate'); ?>" method="post" enctype="multipart/form-data">
            	<input type="hidden" name="intUserId" id="intUserId" value="<?php echo $intUserId;?>">
            	<input type="hidden" name="intUserAddId" id="intUserAddId" value="<?php echo $intUserAddId;?>">
                <div class="col-sm-6">
                    <input  placeholder="Name *"  value="<?php echo $arrAddDetails->USER_NAME; ?>" type="text" name="strName" id="strName" required>
                </div>
                <div class="col-sm-6">
                    <input  placeholder="Company Name *"  value="<?php echo $arrAddDetails->COMPANY_NAME; ?>" type="text" name="strCompName" id="strCompName" required>
                </div>
                <div class="col-sm-12">
                    <input placeholder="Delivery Address *" type="text" name="strAddress" id="strAddress" value="<?php echo $arrAddDetails->ADDRESS; ?>" required>					
                </div>	
                    <!---------------------------------------------------------->
                <div class="col-sm-6">
                    <input  placeholder="City/District/Town *"  value="<?php echo $arrAddDetails->CITY; ?>" type="text" name="strCity" id="strCity" required>
                </div>
                <div class="col-sm-6">
                    <input  type="text" placeholder="State *" value="<?php echo $arrAddDetails->STATE; ?>"  id="strState" name="strState" required>
                </div>
                <div class="col-sm-6">
                    <input type="text"  id="strZipCode" placeholder="Zip *" value="<?php echo $arrAddDetails->ZIP; ?>" name="strZipCode" required>
                </div>
                <div class="col-sm-6">
                    <select  placeholder="Country *"  type="text"  id="strCountry" onChange="showHideVatNo(this.value)" name="strCountry" required>
                        <option value=''>Select Country</option>
                        <?php 
                        if(count($countryList)>0)
                        {
                            foreach($countryList as $country)
                            {
                            ?> 
                                <option value="<?php echo $country->COUNTRY_ID.'@_@'.$country->COUNTRY.'@_@'.$country->SHIPPING_AMT; ?>" <?php if($arrAddDetails->COUNTRY_ID==$country->COUNTRY_ID) echo "selected"; ?> >
                                <?php echo $country->COUNTRY; ?></option>
                            <?php
                            } 
                        }
                        ?>
                    </select>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-12">
                    <input id="example_check1" <?php if($arrAddDetails->EU_VAT!="") echo "checked"; ?> onChange="YourVatBoxEnable();" type="checkbox" style="float:left; width:20px">
                    <label for="example_check1"  style="float:left; font-weight:400">If you are a company situated outside Germany but in EU and want a 
                    VAT free quote</label>
                    <input placeholder="VAT Number" <?php if($arrAddDetails->EU_VAT=="") echo "disabled"; ?> type="text" value="<?php echo $arrAddDetails->EU_VAT; ?>" id="strVatNo" name="strVatNo">
                </div>
                <div class="clearfix"></div>
                <div class="clearfix"></div>
                <div class="col-sm-12 text-center">
                    <button type="button" class="btn btn-primary btn-sm m-top10" onClick="window.location.reload();">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm m-top10" onClick=" return confirm ('Are You Sure you want to Save it?\n Click OK to Continue, Cancel to Stop')">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
