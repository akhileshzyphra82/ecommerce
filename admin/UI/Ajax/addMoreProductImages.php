<?php
$row=$_GET['row']+1;
?>

<input type="file" name="product_image_<?php echo $row; ?>" id="product_image_<?php echo $row; ?>"  class="form-control col-md-12" onchange="ValidateSize(this)">
<div id="addMore_<?php echo $row;?>"></div>
