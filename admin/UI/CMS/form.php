<?php
ob_start();
//ini_set("display_errors",0);
//error_reporting(E_ALL | E_STRICT);
include('../Common.php');
include('../Includes/Functions.php');
require_once ('../../UI/Config/inc_path.php');
require_once "../Includes/ConstantArray.php";
require_once ('../../BL/HomeManager.php');

{
?>
	<div class="row">
		<div class="col-md-12 text-center">
		<h2>Banner</h2>
		<div class="box box-primary">
				<div class="box-body" id="div1">
				<div class="box-border" style="height:400px">
					<div class="form-group" align="left">
					<div class="col-md-6 col-sm-12 col-xs-12">
						<label class="control-label" for="last-name"> Title</label><br />
							<input type="text" name="spring_eng_title_name" id="spring_eng_title_name"  value="" class="form-control col-md-12">
						<div class="form-group">
						<div class="col-md-12 col-sm-12 col-xs-12" align="left">
						<label class="control-label" for="last-name"> Banner Image</label><br />
							<input type="file" name="spring_eng_file_name" id="spring_eng_file_name"  value=""  class="form-control col-md-12" onchange="readURL(this);">
						</div>
						</div>
							
					<div class="form-group">
					<div class="col-md-3 col-sm-12 col-xs-12" align="left">
						<label class="control-label " for="last-name">Priority</label><br/>
						<input type="text" id="spring_eng_priority" name="spring_eng_priority"   class="form-control col-md-12">
						</div>
				</div>
				<div class="form-group">
					<div class="col-md-12 col-sm-12 col-xs-12" align="left">
						<label class="control-label " for="last-name">Discription</label><br/>
						<textarea class="form-control" rows="5"id="spring_eng_priority" name="spring_eng_priority"   class="form-control col-md-12"></textarea>
						</div>
				</div>
				</div>		
					<div class="col-md-6 col-sm-col-xs-12">
						<img src="../Images/blankimg.jpg" id="imageShow" class="col-md-8 col-sm-12 col-xs-12" style="float:right;"/>
						</div>
						
					</div>
					
						</div>
					</div>
					<div class="form-group">
					<div class="col-md-4 col-sm-12 col-xs-12" align="left">
					    <a href="#row" class="btn btn-info">Add More</a>
					</div>
				    </div>
				</div>
				</div>
			
		
		
		<div class="form-group text-center">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<a href="AddHomePageBanner.php"><button   type="button"  class="btn btn-danger" >Cancel</button></a>
				<button type="submit" class="btn btn-success" onclick="return confirm('Are You Sure you want to Save it?\n Click OK to Continue, Cancel to Stop'),checkFileSize()">Submit</button>
			</div>
		</div>
		</div>
		</div>

</form>
</section>
</div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#imageShow').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script>
$(document).ready(function(){
    $("button").click(function(){
        $.ajax({url: "demo_test.txt", success: function(result){
            $("#div1").html(result);
        }});
    });
});
</script>

<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>tinymce.init({ 
	selector:'#description', 
	height: 300,
	
	theme: 'modern',
	plugins: [
		'advlist autolink lists link image charmap print preview hr anchor pagebreak',
		'searchreplace wordcount visualblocks visualchars code fullscreen',
		'insertdatetime media nonbreaking save table contextmenu directionality',
		'emoticons template paste textcolor colorpicker textpattern imagetools'
	],
	toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
	toolbar2: 'print preview media | forecolor backcolor emoticons',
	image_advtab: true,
	templates: [
	{ title: 'Test template 1', content: 'Test 1' },
	{ title: 'Test template 2', content: 'Test 2' }
	],
	content_css: [
		'//www.tinymce.com/css/codepen.min.css'
	]
});</script>
<script>tinymce.init({ 
	selector:'#description1', 
	height: 300,
	
	theme: 'modern',
	plugins: [
		'advlist autolink lists link image charmap print preview hr anchor pagebreak',
		'searchreplace wordcount visualblocks visualchars code fullscreen',
		'insertdatetime media nonbreaking save table contextmenu directionality',
		'emoticons template paste textcolor colorpicker textpattern imagetools'
	],
	toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
	toolbar2: 'print preview media | forecolor backcolor emoticons',
	image_advtab: true,
	templates: [
	{ title: 'Test template 1', content: 'Test 1' },
	{ title: 'Test template 2', content: 'Test 2' }
	],
	content_css: [
		'//www.tinymce.com/css/codepen.min.css'
	]
});</script>
<?php
}
?>