<?php
require_once('../admin/BL/HomeManager.php');
$row=$_GET['row']+1;
$objHomeManager = new HomeManager(); 
$parentCategory=$objHomeManager->GetAndDisplayAllListProduct1();

//directSell
$flag=$_GET['flag'];
?>
<div class="col-sm-5 form-group ">
	<label class="control-label">Product Category*</label>
	<select class="form-control" name="product_category_id_<?php echo $row;?>" id="se_<?php echo $row;?>" onChange="getProductByCategoryId(this.value,'<?php echo $row;?>');">
		<option value="">Select Product Category</option>
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
<div class="col-sm-4 form-group ">
	<label class="control-label">Product *</label>
	<div id="productDiv_<?php echo $row;?>">
		<select class="form-control" name="product_id" id="product_id" >
			<option value="<?php echo $fillDataArray['product_id']; ?>">Select Product</option>
		</select>
	</div>
</div>
 <div class="col-sm-3 form-group ">
 	<label class="control-label">Product Quantity</label>
  	<input size="4" type="number" id="quantity_<?php echo $row;?>" name="quantity_<?php echo $row;?>" min="1" required>
</div>
<?php 
if($flag=='directSell')
{
?>
<div id="productAmtDiv_<?php echo $row;?>" >
<div  class="col-sm-12">
		<div class="col-sm-5 form-group ">
			 <label class="control-label">Product Amount *</label>
			  <input size="4" type="text" id="product_amt_<?php echo $row;?>" name="product_amt_<?php echo $row;?>"  required >
		</div>
		
		
		<div class="col-sm-4 form-group ">
			 <label class="control-label">Product Tax *</label>
			  <input size="4" type="text" id="product_tax_<?php echo $row;?>" name="product_tax_<?php echo $row;?>"   required
			 >
		</div>
		
		<div class="col-sm-3 form-group ">
			 <label class="control-label">Product Discount *</label>
			  <input size="4" type="text" id="product_discount_<?php echo $row;?>" name="product_discount_<?php echo $row;?>"   required
			  >
		</div>
	</div>
</div>

<?php
}
?>
<div id="addMore_<?php echo $row;?>"></div>
