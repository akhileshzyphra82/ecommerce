<?php
$row=$_GET['row']+1;
?>
<div class="form-group" class="col-md-12">
<div class="col-md-2 col-sm-12 col-xs-12" align="left">
								<label class="control-label" for="last-name">Download Title</label>
								<input type="text" id="manual_title_<?php echo $row; ?>" name="manual_title_<?php echo $row; ?>"   class="form-control col-md-6">
								</div>
								<div class="col-md-2 col-sm-12 col-xs-12" align="left">
								<label class="control-label" for="last-name">Download File</label>
<input type="file" id="manual_file_<?php echo $row; ?>" name="manual_file_<?php echo $row; ?>"   class="form-control col-md-6" onchange="ValidateSize(this)">
</div>
<div class="col-md-2 col-sm-12 col-xs-12" align="left"></div>
<div class="col-md-4 col-sm-12 col-xs-12" align="left">
								<label class="control-label" for="last-name">Url</label>
<input type="text" id="Url_<?php echo $row; ?>" name="Url_<?php echo $row; ?>"   class="form-control col-md-6" onchange="ValidateSize(this)">
</div>
<div class="col-md-2 col-sm-12 col-xs-12" align="left">
								<label class="control-label" for="last-name">Upload Date</label>
								<input type="text" id="file_upload_date_<?php echo $row; ?>" name="file_upload_date_<?php echo $row; ?>"   class="form-control col-md-6 date2" value="<?php if(isset($result)) echo $result[0]->MANUAL_UPLOAD_DATE; else echo date('Y-m-d', time()); ?>"  >
								</div>
</div>
<div id="addMoreUploadManual_<?php echo $row;?>"></div>