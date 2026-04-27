<?php
$row=$_GET['row']+1;
?>
<div class="form-group" class="col-md-12">
							<div class="col-md-2 col-sm-12 col-xs-12" align="left">
								<label class="control-label" for="last-name">Language/Technology</label>
								<input type="text" id="Language_<?php echo $row; ?>" name="Language_<?php echo $row; ?>"   class="form-control col-md-6" >
								</div>
								<div class="col-md-2 col-sm-12 col-xs-12" align="left">
								<label class="control-label" for="last-name">IDE/Compiler</label>
								<input type="text" id="IDE_<?php echo $row; ?>" name="IDE_<?php echo $row; ?>"   class="form-control col-md-6" >
								</div>
								<div class="col-md-2 col-sm-12 col-xs-12" align="left">
								<label class="control-label" for="last-name">Type</label>
								<input type="text" id="Type_<?php echo $row; ?>" name="Type_<?php echo $row; ?>"   class="form-control col-md-6" >
								</div>
								<div class="col-md-2 col-sm-12 col-xs-12" align="left">
								<label class="control-label" for="last-name">OS</label>
								<input type="text" id="OS_<?php echo $row; ?>" name="OS_<?php echo $row; ?>"   class="form-control col-md-6" >
								</div>
							<div class="col-md-2 col-sm-12 col-xs-12" align="left">
								<label class="control-label" >URL</label>
								<input type="text" id="Sample_code_file_<?php echo $row; ?>" name="Sample_code_file_<?php echo $row; ?>"   class="form-control " onchange="ValidateSize(this)">
							</div>
							<div class="col-md-2 col-sm-12 col-xs-12" align="left">
								<label class="control-label" for="last-name">Upload Date</label>
								<input type="text" id="sample_code_upload_date_<?php echo $row; ?>" name="sample_code_upload_date_<?php echo $row; ?>"   class="form-control col-md-6 date2" value="<?php if(isset($result)) echo $result[0]->MANUAL_UPLOAD_DATE; else echo date('Y-m-d', time()); ?>"  >
								</div>
								
					       </div>
<div id="addMoreUploadSample_<?php echo $row;?>"></div>


