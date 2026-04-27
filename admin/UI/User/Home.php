<?php
ini_set("display_errors",0);
ob_start();
date_default_timezone_set('Asia/Kolkata');
include_once('../Common.php');
include('../Includes/Functions.php');
require_once "../Includes/ConstantArray.php";

require_once "../../BL/HRManager.php";
require_once "../../BL/DashboardManager.php";
  //----------------------
$manageState =  $_SESSION['MANAGE_STATE'];
$empTypeId =  $_SESSION['EMPLOYEETYPEID'];
$intEmployeeId= $_SESSION['EMPLOYEEID'];
$paramsArray = GetQueryStringParameters();
(isset($paramsArray['action']))? $action = $paramsArray['action'] : $action="";

$objDashboardManager = new DashboardManager();
$userDetail = $objDashboardManager->GetUserDetailByUserId($intEmployeeId);
//echo"<pre>";print_r($_SESSION);die;
$folder='../UserImages/user-photo/'.$intEmployeeId.'.jpg';
if(!file_exists($folder))
{
	$folder='../UserImages/user-photo/default.png';
}
$folder=$folder."?t=".time();



$arrGraphData = $objDashboardManager->GetDashBoardGraphData();

$arrEnquryStatusData=$arrGraphData[0];
$arrOrderStatusData=$arrGraphData[1];

//echo"<pre>";print_r($arrGraphData);die;
?>
<link rel="stylesheet" href="../css/praveen_template.css">
	<script src="../js/3D_Donuts/amcharts.js"></script>
    <script src="../js/3D_Donuts/light.js"></script>
    <script src="../js/3D_Donuts/serial.js"></script>
    <script src="../js/3D_Donuts/pie.js"></script>
    <link rel="stylesheet" href="../../css/tinystyle.css" />
    <script src="../js/jquery.min.js"></script>

	<div class="content-wrapper">

    <!-- Main content -->
    <section class="content">
	
		<div class="row">
	
			<div style="border:1px solid #cccccc; padding:1% 0%; margin:10px 5px 10px 15px; width:97.5%; display:block; float:left">
				<div class="col-md-1" align="left">
				<img src="<?php echo $folder; ?>" class="user-image" alt="User Image" width="90" height="90">
				</div>
				<div class="col-md-4">
				<div class="name"><strong>Name</strong>&nbsp;&nbsp;:&nbsp;&nbsp; <?php echo $userDetail[0]->NAME; ?></div>
				<div class="name">&nbsp;</div>
				</div>
				
				<div class="col-md-4">
				<div class="name"><strong>Mobile No</strong>&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $userDetail[0]->COMMUNICATION_MOBILE_NUM; ?></div>
				<div class="name">&nbsp;</div>
				</div>
				
				<div class="col-md-3">
				<div class="name"><strong>Email Id</strong>&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $userDetail[0]->COMMUNICATION_EMAIL_ID; ?></div>
				<div class="name">&nbsp;</div>
				</div>
	</div>
	</div>
	
      <!-- Small boxes (Stat box) -->
	  <div class="row">
		  <div class="col-lg-12" style="height:280px">
						 <?php 
						 //	$arrFinalClaim=array
						// echo "<pre>"; print_r($arrStatusWiseClaimData); die;
							$enquryStatusWiseClaim="";
							$flag=false;
							if(count($arrEnquryStatusData)>0)
							{
								foreach($arrEnquryStatusData as $arrEnquryStatusDataVal)
								{
	
									if($arrEnquryStatusDataVal >0)
										$flag=true;
										
								   $enquryStatusWiseClaim.="{'Status':'".$arrEnquryStatusDataVal->ENQUIRY_STATUS."','Total Enquiry':".$arrEnquryStatusDataVal->TOTAL."},";
	
								}
								$enquryStatusWiseClaim=rtrim($enquryStatusWiseClaim,",");    
							}
							?>
								<script>
								var chart = AmCharts.makeChart("enquryStatusWiseClaims", {
									"theme": "none",
									"titles": [ {
									"text": "Status Wise Enquiry Count",
									"size": 13,
									"margin": 1
									} ],
									"type": "serial",
									
									"dataProvider": [<?php echo $enquryStatusWiseClaim;?>],
									"startDuration": 1,
									"graphs": [{
										"balloonText": "[[category]] (Total Enquiry): <b>[[value]]</b>",
										"fillAlphas": 0.9,
										"lineAlpha": 0.2,
										"title": "Total Enquiry",
										"type": "column",
										"valueField": "Total Enquiry"
									}],
									"plotAreaFillAlphas": 0.1,
									"depth3D": 20,
									"angle": 45,
									"categoryField": "Status",
									"categoryAxis": {
										"gridPosition": "start"
									},
									"export": {
										"enabled": true
									 }
								});
								jQuery('.chart-input').off().on('input change',function() {
								  var property	= jQuery(this).data('property');
								  var target		= chart;
								  chart.startDuration = 0;
								
								  if ( property == 'topRadius') {
									target = chart.graphs[0];
										if ( this.value == 0 ) {
										  this.value = undefined;
										}
								  }
								
								  target[property] = this.value;
								  chart.validateNow();
								});
								</script>
									<?php
									if($flag==true)
									{
									?>
									  <div id="enquryStatusWiseClaims" style="height:100%; width:100%; color:#FF0000; font-weight:bold; text-align:center; vertical-align:middle"></div>									<?php
									}
									else
									{
									?>
									  <div id="enquryStatusWiseClaims1" style="height:100%; width:100%; color:#FF0000;"><p style="line-height:240px;font-weight:bold; text-align:center; margin-right:130px;" id="nodata1">No data found.</p></div>	
									<?php
									}
									?>
						</div>
						<div class="col-lg-12" style="height:400px">
						<br/>
						<br/>
						<br/>
							<div class="box">
							<div class="box-header with-border" style="background:red">						
								<h4 class="box-title" ><font color="#FFFFFF" ">Status Wise Order Count</h4>
							</div>
							<div class="box-body">
								<div class="col-md-12" style="height:400px;">
									<?php 
									$orderStatusWiseClaim="";
									$flag=false;
									if(count($arrOrderStatusData)>0)
									{
										foreach($arrOrderStatusData as $arrOrderStatusDataVal)
										{
					
											if($arrEnquryStatusDataVal >0)
												$flag=true;
												
										   $orderStatusWiseClaim.="{'orderStatus':'".$arrOrderStatusDataVal->ORDER_CURRENT_STATUS."','Total Order':".$arrOrderStatusDataVal->TOTAL."},";
					
										}
										$orderStatusWiseClaim=rtrim($orderStatusWiseClaim,",");    
									}
									?>
									<script>
									var chart = AmCharts.makeChart("orderStatusWiseClaims", {
									"theme": "none",
									"type": "serial",
									
									"dataProvider": [<?php echo $orderStatusWiseClaim;?>],
									"startDuration": 1,
									"graphs": [{
										"balloonText": "[[category]] (Total Order): <b>[[value]]</b>",
										"fillAlphas": 0.9,
										"lineAlpha": 0.2,
										"title": "Total Order",
										"type": "column",
										"valueField": "Total Order"
									}],
									"plotAreaFillAlphas": 0.1,
									"depth3D": 20,
									"angle": 45,
									"categoryField": "orderStatus",
									"categoryAxis": {
										"gridPosition": "start",
										"labelRotation": 30
									},
									"export": {
										"enabled": true
									 }
								});
								jQuery('.chart-input').off().on('input change',function() {
								  var property	= jQuery(this).data('property');
								  var target		= chart;
								  chart.startDuration = 0;
								
								  if ( property == 'topRadius') {
									target = chart.graphs[0];
										if ( this.value == 0 ) {
										  this.value = undefined;
										}
								  }
								
								  target[property] = this.value;
								  chart.validateNow();
								});
								</script>
								
								<?php
								if($flag==true)
								{
								?>
								  <div id="orderStatusWiseClaims" style="height:100%; width:100%; color:#FF0000; font-weight:bold; text-align:center; vertical-align:middle"></div>									<?php
								}
								else
								{
								?>
								  <div id="orderStatusWiseClaims1" style="height:100%; width:100%; color:#FF0000;"><p style="line-height:240px;font-weight:bold; text-align:center; margin-right:130px;" id="nodata1">No data found.</p></div>	
								<?php
								}
								?>
								
								</div>
							</div>
						</div>
						</div>			
	  </div>
	  
    </section>
    <!-- /.content -->
  </div>	
<?php
$pageMainContent = ob_get_contents();
ob_end_clean();
$pagetitle = "Sinelec :: Home";
//Apply the template
include('../MasterTemplatePage.php');
?>