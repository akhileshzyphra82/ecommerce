<?php
ob_start();
ini_set('display_errors',0);
//error_reporting(E_ALL | E_STRICT);
include('../Common.php');
include('../Includes/Functions.php');
require_once ('../../UI/Config/inc_path.php');
require_once "../Includes/ConstantArray.php";
require_once ('../../BL/HomeManager.php');

$objHomeManager=new HomeManager();

$date=date('Y-m-d');
$paramsArray = GetQueryStringParameters();
(isset($paramsArray['action']))? $action=$paramsArray['action'] : $action="";
isset($paramsArray["msg"]) ? $msg=$paramsArray["msg"] : $msg="";


switch($action)
{
case 'ExportExcel' :

		$body='';
		$arrSrchData=array('productCategory'=>'','productName'=>'','productCode'=>'');
		$arrProductsDetails=$objHomeManager->GetAndDisplayAllAddProductsDetails($arrSrchData);
		
		$body.='<table id="" class="table table-bordered table-striped">
						<thead>
							<tr style="background-color:red;color:white">
								<th class="text_align_center" colspan="7">Product Stock Details</th>
							</tr>
							<tr style="background-color:red;color:white">
								<th class="text_align_center" width="3%">#</th>
								<th class="text_align_center" width="50%">Product Name/Code</th>
								<th class="text_align_center" width="8%">Price</th>
								<th class="text_align_center" width="8%">Total </th>
								<th class="text_align_center" width="8%">Sold</th>
								<th class="text_align_center" width="8%">Remaining</th>
								<th class="text_align_center" width="8%">Threshold</th>
							</tr>
						</thead>
						<tbody>';
							$index=1; 
							foreach($arrProductsDetails as $arrProductsDetailsVal)
							{ 
							
									$body.='<td>'.$index++.'</td>
									<td ><font size="-1">'. $arrProductsDetailsVal->PRODUCT_NAME.'('.$arrProductsDetailsVal->PRODUCT_CODE.')'.'</font></td>
									<td >
										'.$arrProductsDetailsVal->PRODUCT_AMT.'
									</td>
									<td>
										'.$arrProductsDetailsVal->TOTAL_PRODUCT.'
									</td>
									<td>
										
										'.$arrProductsDetailsVal->TOTAL_SOLD.' 
										
									</td>
									<td>
										 '. $arrProductsDetailsVal->TOTAL_REMAINING.'
									</td>
									<td>
										 '.$arrProductsDetailsVal->PRODUCT_THRESHOLD.'
									</td>
								</tr>';
							
							}
						
				 		$body.='</tbody>
						</table>';
		//print_r($body); die;
		$filename="StockList.xls";
		excelReport($body, $filename);
		exit();	
	break;

}

if($action=="")
{
?>  

<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
<link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css">
<link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
<link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
<link rel="stylesheet" href="../css/praveen_template.css">		  
		 
<div class="content-wrapper">
	<section class="content-header">
		<ol class="breadcrumb">
			<li><a href="../User/Home.php"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Product Stock Record</li>
		</ol>
	</section>
	<br/>
	<!-- Content Header (Page header) -->
	<script src="../js/jquery-1.11.2.min.js"></script>
	<script language="javascript" type="text/javascript" src="../js/jquery.coolfieldset.js"></script>
	<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
	<script src="../bootstrap/js/bootstrap.min.js"></script>
	
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/jquery.coolfieldset.css" />

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box" id="SibsSchool">
                <div class="box-body">
					<div class="box-body table-responsive no-padding">
						<div class="col-md-12 col-sm-12 col-xs-12 ">
						
							<div class="input-group">
						
							</div>
						</div>
						<div >
							<table>
								<tr>
									<td> 
									 <a href="ProductStockRecord.php?urlstring=<?php echo EncryptURL('action=ExportExcel'); ?>">
									 <button class="btn btn-success" onclick="exportToFileopen('excel')">Export to excel</button></a> 
									</td> 
								</tr> 	 
							</table>
						</div>
						<table id="" class="table table-bordered table-striped">
						<?php
						$arrSrchData=array('productCategory'=>'','productName'=>'','productCode'=>'');
						$arrProductsDetails=$objHomeManager->GetAndDisplayAllAddProductsDetails($arrSrchData);
		
						//echo '<pre>';print_r($arrProductsDetails); die;
						?>
						<thead>
						<tr>
							<th class="text_align_center" colspan="9">Product Stock Details</th>
						</tr>
							<tr>
							<th class="text_align_center" width="3%">#</th>
							<th class="text_align_center" width="50%">Product Name/Code</th>
							<th class="text_align_center" width="8%">Price</th>
							<th class="text_align_center" width="8%">Total</th>
							<th class="text_align_center" width="8%">Sold</th>
							<th class="text_align_center" width="8%">Remaining</th>
							<th class="text_align_center" width="8%">Threshold</th>
							<th class="text_align_center" width="25%">Purchase Details</th>
							</tr>
						</thead>
						<tbody>
						<?php 
						if(count($arrProductsDetails)>0)
						{
							$index=1; 
							foreach($arrProductsDetails as $arrProductsDetailsVal)
							{ 
								?>
									<td><?php echo $index++; ?></td>
									<td ><font size="-1"><?php echo $arrProductsDetailsVal->PRODUCT_NAME.' ('.$arrProductsDetailsVal->PRODUCT_CODE.')'; ?></font></td>
									<td ><?php echo $arrProductsDetailsVal->PRODUCT_AMT; ?></td>
									
									<td>
										<?php echo $arrProductsDetailsVal->TOTAL_PRODUCT;?>
									</td>
									<td>
										
										<?php echo $arrProductsDetailsVal->TOTAL_SOLD;?> 
										
									</td>
									<td>
										 <?php echo $arrProductsDetailsVal->TOTAL_REMAINING;?>
									</td>
									<td>
										 <?php echo $arrProductsDetailsVal->PRODUCT_THRESHOLD;?>
									</td>
									<td>
										<a href="ProductStockRecord.php?urlstring=<?php echo EncryptURL('action=PurchaseDetails&intProductId='.$arrProductsDetailsVal->PRODUCT_ID); ?>" class="btn btn-success btn-xs edit" style="padding-top:0%;" target="_blank">Details</a>
									</td>

								</tr>
							<?php 
							}
						}
						else
						{
						?>
								<tr>
									<td style="color:#FF0000">No data found</td>
								</tr>
						<?php
						}
						?>
				 		</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
</div> 

<?php
}
elseif($action='PurchaseDetails')
{
	//echo "<pre>"; print_r($paramsArray); die;
	
	$intProductId = $paramsArray["intProductId"];
	$arrProPurchaseDetails=$objHomeManager->GetProPurchaseDetailsByProId($intProductId);
	//echo "<pre>"; print_r($arrProPurchaseDetails); die;
	?>
	<div class="content-wrapper">
	<section class="content">
	<div class="row">
	<div class="col-xs-12">
	<div class="box">
	<div class="box-body">
	<div class="box-body table-responsive no-padding">
		<table id="" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th class="text_align_center" colspan="9">Purchase Details</th>
				</tr>
				<tr>
					<th class="text_align_center" width="3%">#</th>
					<th class="text_align_center" width="10%">Category</th>
					<th class="text_align_center" width="28%">Name</th>
					<th class="text_align_center" width="10%">Code</th>
					<th class="text_align_center" width="12%">From</th>
					<th class="text_align_center" width="8%">Receipt No</th>
					<th class="text_align_center" width="8%">Date</th>
					<th class="text_align_center" width="8%">Amount</th>
					<th class="text_align_center" width="7%">Quantity</th>
				</tr>
			</thead>
			<tbody>
			<?php
			if(count($arrProPurchaseDetails)>0)
			{
				$indexPurcahe=1;
				$totQuantity=0;
				$totAmt=0;
				foreach($arrProPurchaseDetails as $arrProPurchaseVal)
				{
				?>
			    
					<tr bordercolor="#FFFFFF">
						<td><?php echo $indexPurcahe++; ?></td>
						<td><font size="-1"><?php echo $arrProPurchaseVal->PRODUCT_CATEGORY_NAME; ?></font></td>
						<td><font size="-1"><?php echo $arrProPurchaseVal->PRODUCT_NAME; ?></font></td>
						<td><font size="-1"><?php echo $arrProPurchaseVal->PRODUCT_CODE; ?></font></td>
						<td><font size="-1"><?php echo $arrProPurchaseVal->PURCHASED_FROM; ?></font></td>
						<td><?php echo $arrProPurchaseVal->RECEIPT_NO; ?></td>
						<td><?php echo $arrProPurchaseVal->DATE_OF_PURCHASE; ?></td>
						<td><?php echo $arrProPurchaseVal->PURCHASE_AMT; ?></td>
						<td><?php echo $arrProPurchaseVal->QUANTITY_PURCHASED; ?></td>
						
					</tr>	
					<?php
					$totQuantity=$totQuantity+$arrProPurchaseVal->QUANTITY_PURCHASED;
					$totAmt=$totQuantity+$arrProPurchaseVal->PURCHASE_AMT;
				}
				?>
				<tr>
					<td colspan="7">Total</td>
					<td><?php echo $totAmt;?></td>
					<td><?php echo $totQuantity;?></td>
					
				</tr>
				
				
			<?php	
			}
			else
			{
			?>
				<tr>
					<td colspan="9">No Purchase Record Found</td>
				</tr>

			<?php
			}
			?>
			</tbody>
		</table>
	</div>
	</div>
	</div>
	</div>
	</div>
	</section>
	</div>
<?php	
}
$pageMainContent = ob_get_contents();
ob_end_clean();
$pagetitle = "Product Stock Record";
//Apply the template
include('../MasterTemplatePage.php');
?>