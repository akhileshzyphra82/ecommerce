<?php 
require_once ('../../BL/ProductManager.php');

$objProductManager = new ProductManager(); 
//print_r($_GET); die;
$flag=$_GET["flag"];

if($flag==1)
{
$getCategoryId = $_GET['CategoryId'];
//$getProductId = $_GET['product_id'];
$count=$_GET["count"];
$usedProArra=array();
$usedProArra=explode(",",$_GET["res"]);
 //print_r($usedProArra);
//echo 'THIS IS ';print_r($getCategoryId); die;
if($getCategoryId!='')
{
	list($catId,$catName)=explode("_",$getCategoryId);
	$ProductDetailArray=$objProductManager->GetProductsNameIdByProductCategoryID($catId);
}

?>
<select name="product_id_<?php echo $count; ?>" id="product_id_<?php echo $count; ?>"   class="form-control col-md-12" required onChange="getProductByDesId(this.value,'<?php echo $count; ?>');" style="width:200px">
<option value="">Select Product</option>
<?php
 if(count($ProductDetailArray)>0)
 {
   foreach($ProductDetailArray as $product)
   {
     ?>
	   <option value="<?php echo $product->PRODUCT_ID."@_@".$product->PRODUCT_NAME."@_@".$product->PRODUCT_AMT; ?>"><?php echo $product->PRODUCT_NAME;  ?></option>

	 <?php
   }
 }
}
?>
 </select>
