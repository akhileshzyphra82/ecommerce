<?php 
require_once ('../../BL/HomeManager.php');

//echo "<pre>";print_r($_GET);die;
$ProducCatId=$_GET['val'];

$objProductHomeManager = new HomeManager(); 
$ProductDetailArray=$objProductHomeManager->GetAllSubProductDetailByProductCatId($ProducCatId);
//echo "<pre>";print_r($ProductDetailArray);die;
?>
<select name="product_sub_category_1" id="product_sub_category_1"   class="form-control col-md-12" >
<option value="">Select Sub Category</option>
<?php
 if(count($ProductDetailArray)>0)
 {
   foreach($ProductDetailArray as $subCat)
   {
     ?>
	   <option value="<?php echo $subCat->PRODUCT_CATEGORY_ID; ?>" <?php if( isset($result) && $subCat->PRODUCT_CATEGORY_ID == $Category->PRODUCT_CATEGORY_ID ) echo " selected"; ?>><?php echo $subCat->PRODUCT_CATEGORY_NAME; ?></option>
	 <?php
   }
 }

?>
 </select>
