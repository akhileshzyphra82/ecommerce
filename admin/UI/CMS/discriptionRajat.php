<?php
require_once('../../BL/HomeManager.php');
$row=$_GET['row']+1;
$objHomeManager = new HomeManager(); 
$parentCategory=$objHomeManager->GetAndDisplayAllListProduct1();

//directSell
$flag=$_GET['flag'];
?>
<div id="RemoveAddMoreDiv<?php echo $row;?>">
    <div class="col-md-4 col-sm-12 col-xs-12" align="left">
        <label class="control-label">Product Category*</label>
        <div>
        <select class="form-control" name="product_category_id_<?php echo $row;?>" id="se_<?php echo $row;?>" onChange="getProductByCategoryId(this.value,'<?php echo $row;?>');" style="width:200px">
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
    </div>	
    <div class="col-md-4 col-sm-12 col-xs-12" align="left">
        <label class="control-label">Product *</label>
        <div id="productDiv_<?php echo $row;?>">
            <select class="form-control" name="product_id" id="product_id" style="width:200px">
                <option value="<?php echo $fillDataArray['product_id']; ?>">Select Product</option>
            </select>
        </div>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-12" align="left">
        <label class="control-label">Product Quantity *</label>
        <div>
        <input class="form-control" size="4" type="number" id="quantity_<?php echo $row;?>" name="quantity_<?php echo $row;?>" min="1" required>
        </div>
    </div>
</div>
<div id="addMore_<?php echo $row;?>"></div>
