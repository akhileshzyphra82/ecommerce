<?php 
require_once ('../../BL/ProductManager.php');

//echo "<pre>";print_r ($_GET);die;
//DirectSell

$flag=$_GET['flag'];
$count=$_GET['count'];
list($pId,$pName,$productAmt)=explode("@_@",$_GET['ProductId']);
$objProductManager = new ProductManager(); 
$ProductDetailArray=$objProductManager->getProductByDesId($pId);
//echo "<pre>";print_r ($ProductDetailArray);die;
?>

<?php 
if($flag=='DirectSell')
{
	?>
	<div  class="col-sm-12">
		<div class="col-sm-5 form-group required">
			 <label class="control-label">Product Amount *</label>
			  <input size="4" type="text" id="product_amt_<?php echo $count;?>" name="product_amt_<?php echo $count;?>" min="1" max="20"  required value="<?php echo $ProductDetailArray[0]->PRODUCT_AMT; ?>">
			  <input size="4" type="hidden" id="product_code_<?php echo $count;?>" name="product_code_<?php echo $count;?>"    value="<?php echo $ProductDetailArray[0]->PRODUCT_CODE; ?>">
		</div>
		
		
		<div class="col-sm-4 form-group required">
			 <label class="control-label">Product Tax *</label>
			  <input size="4" type="text" id="product_tax_<?php echo $count;?>" name="product_tax_<?php echo $count;?>" min="1" max="20"  required
			  value="<?php echo $ProductDetailArray[0]->PRODUCT_TAX; ?>">
		</div>
		
		<div class="col-sm-3 form-group required">
			 <label class="control-label">Product Discount *</label>
			  <input size="4" type="text" id="product_discount_<?php echo $count;?>" name="product_discount_<?php echo $count;?>" min="1" max="20"  required
			  value="<?php echo $ProductDetailArray[0]->PRODUCT_DISCOUNT; ?>">
		</div>
	</div>
	<?php
}
else
{
	 if($ProductDetailArray[0]->PRODUCT_DESCRIPTION!='')
	 {
		echo $ProductDetailArray[0]->PRODUCT_DESCRIPTION;
	  
	 }
	 
	 else 
	 echo "No Description !!!";
} 
 ?>
 
