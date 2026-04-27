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
$arrSrchData=array();
$date=date('Y-m-d');
$paramsArray = GetQueryStringParameters();
(isset($paramsArray['action']))? $action=$paramsArray['action'] : $action="";
isset($paramsArray["msg"]) ? $msg=$paramsArray["msg"] : $msg="";
$arrProductsDetails=$objHomeManager->GetAndDisplayAllAddProductsDetails($arrSrchData);
switch($action)
{	
	case 'Search':
	  
		$productCategory=$_POST['productCategory'];
		$productName=$_POST['productName'];
		$productCode=$_POST['productCode'];
		$arrSrchData=array('productCategory'=>$productCategory,'productName'=>$productName,'productCode'=>$productCode);
		$arrProductsDetails=$objHomeManager->GetAndDisplayAllAddProductsDetails($arrSrchData);
		
		$action='';
	break;

	case "Insert":
		//echo '<pre>'; print_r($_POST); die;
		$arrProductIdsData=$_POST['intProductId'];
		$arrProductPurchase=array();
		
		if(count($arrProductIdsData)>0)
		{
			foreach($arrProductIdsData as $arrProductIdsVal)
			{
				if($_POST['product_'.$arrProductIdsVal] == 'Yes')
				{
					if($_POST['strPurchasedFrom_'.$arrProductIdsVal]=='New')
					{
						$strPurchaseFrom=$_POST['strPurchasedFromNew_'.$arrProductIdsVal];
					}
					else
					{
						$strPurchaseFrom=$_POST['strPurchasedFrom_'.$arrProductIdsVal];
					}
					
					$arrProductPurchase[]=array('intProductId'=>$arrProductIdsVal,'intProductThreshold'=>$_POST['intProductThreshold_'.$arrProductIdsVal],
					'intQuantityPurchased'=>$_POST['intQuantityPurchased_'.$arrProductIdsVal],'dtDateOfPurchased'=>$_POST['dtDateOfPurchased_'.$arrProductIdsVal],
					'strPurchasedFrom'=>$strPurchaseFrom, 'strReceiptNo'=>$_POST['strReceiptNo_'.$arrProductIdsVal],
					'intPurchaseAmt'=>$_POST['intPurchaseAmt_'.$arrProductIdsVal]);
				}
				
			}
		}	
		//echo "<pre>"; print_r($arrProductPurchase); die;
		$insertProductPurchase=$objHomeManager->InsertProductPurchaseData($arrProductPurchase);
		header("location:ProductPurchase.php?urlstring=".EncryptURL("action=&msg=".$insertProductPurchase));
	
	break;
	case "DeletePurchase":
		//echo '<pre>'; print_r($paramsArray); die;
		$intProductPurchaseId=$paramsArray['intProductPurchaseId'];
		$intProductId=$paramsArray['intProductId'];
		$intProductQuantity=$paramsArray['intProductQuantity'];
		
		$delProductPurchase=$objHomeManager->DelProductPurchaseData($intProductPurchaseId, $intProductId, $intProductQuantity);
		//echo $delProductPurchase;die;
		header("location:ProductPurchase.php?urlstring=".EncryptURL("action=PurchaseDetails&intProductId=".$intProductId."&msg1=".$delProductPurchase));
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
			<li class="active">Product Purchase Details</li>
		</ol>
	</section>
	<br/>
	<!-- Content Header (Page header) -->
	<?php	
	
	if(isset($paramsArray['msg']))
	{
		$msg=$paramsArray['msg'];	
		?>
		<div class="col-md-12 col-sm-12 col-xs-12 ">
		<?php	
		if($msg=='success')
		{
		?>
			<div class="alert alert-success alert-dismissible" style="height:50px;">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
				<h4><i class="icon fa fa-check"></i> Products purchase details has been added successfully</h4>
			</div>	
		<?php	 
		}
		else if($msg=='error')
		{	
		?>
			<div class="alert alert-danger alert-dismissible" style="height:50px;">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
				<h4><i class="icon fa fa-ban"></i> Error in Process</h4>
			</div>
		<?php	
		}
		?>
	</div>
	<?php	
	}	
	?>
	<script src="../js/jquery-1.11.2.min.js"></script>
	<script language="javascript" type="text/javascript" src="../js/jquery.coolfieldset.js"></script>
	<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
	<script src="../bootstrap/js/bootstrap.min.js"></script>
	
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/jquery.coolfieldset.css" />

<!-- Main content -->


<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<form  action="ProductPurchase.php?urlstring=<?php echo EncryptURL('action=Search'); ?>" method="post">
				<?php
				$CategoryDetailArray=$objHomeManager->GetAndDisplayAllListProduct();
				
				//echo '<pre>'; print_r($CategoryDetailArray)
				?>
				<div class="col-md-3 col-sm-3 col-xs-3 ">
					Product Category: 
					<select  class="form-control" name="productCategory">
						<option value="">Select Category</option>
						<?php 
						if(count($CategoryDetailArray)>0)
						{
							foreach($CategoryDetailArray as $subCat)
							{
								if($subCat->PARENT_CATEGORY_ID!=0)
								{
								?>
								<option value="<?php echo $subCat->PRODUCT_CATEGORY_ID; ?>" <?php if($_POST['productCategory']==$subCat->PRODUCT_CATEGORY_ID) echo 'selected';?>> <?php echo $subCat->PRODUCT_CATEGORY_NAME; ?></option>
								<?php
								}
							}
						}
						?>
					</select>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-3 ">
					Product Name:
					<input type="text" name="productName" id="productName"  class="form-control"  value="<?php echo  $_POST['productName'];?>"/> 
				</div>  
				<div class="col-md-3 col-sm-3 col-xs-3 ">
					Product Code:
					<input type="text" name="productCode" id="productCode" class="form-control"  value="<?php echo  $_POST['productCode'];?>" /> 
				</div> 
				<br/>						 
				<div class="input-group-btn">
					
					<button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
				</div>
		 </form>	
		</div>
	</div>
	<br/>
	<div class="row">
		<div class="col-xs-12">
			<div  id="SibsSchool">
                <div class="box-body">
					<div class="box-body table-responsive no-padding">
						<form action="ProductPurchase.php?urlstring=<?php echo EncryptURL("action=Insert") ?>" method="post" >
						<div class="col-md-12 col-sm-12 col-xs-12 ">
							<div class="input-group">
								<button type="submit" class="btn btn-success" onclick="return confirm('Are You Sure you want to Save it?\n Click OK to Continue, Cancel to Stop')">Submit</button>
							</div>
						</div>

						<table id="" class="table table-bordered table-striped">
						<?php
						
						//echo '<pre>';print_r($arrProductsDetails); die;
						?>
						<thead>
							<tr>
							<th class="text_align_center" width="4%">&nbsp;</th>
							<th class="text_align_center" width="3%">#</th>
							<th class="text_align_center" width="8%">Category</th>
							<th class="text_align_center" width="15%">Name</th>
							<th class="text_align_center" width="8%">Code</th>
							<th class="text_align_center" width="6%">Price</th>
							<th class="text_align_center" width="8%">Quantity</th>
							<th class="text_align_center" width="8%">Threshold</th>
							
							<th class="text_align_center" width="33%">Purchase Details</th>
							<th class="text_align_center" width="7%">Details</th>
							</tr>
						</thead>
						<tbody>
						<?php 
						if(count($arrProductsDetails)>0)
						{
							$index=1; 
							foreach($arrProductsDetails as $arrProductsDetailsVal)
							{ 
								$arrPurchaseFrom=$objHomeManager->GetPurchaseFromDistictDataByProductId($arrProductsDetailsVal->PRODUCT_ID);
								//echo "<pre>"; print_r($arrPurchaseFrom); 
								?>
								<input type="hidden" name="intProductId[]" value="<?php echo $arrProductsDetailsVal->PRODUCT_ID;?>" />
								<tr class="common_table_header">
									<td><input type="checkbox" name="product_<?php echo $arrProductsDetailsVal->PRODUCT_ID;?>" id="product_<?php echo $arrProductsDetailsVal->PRODUCT_ID;?>" value="Yes"  onclick="enableAllBox(this,'<?php echo $arrProductsDetailsVal->PRODUCT_ID;?>')"/>
									</td>
									<td><?php echo $index++; ?></td>
									<td><font size="-1"><?php echo $arrProductsDetailsVal->PRODUCT_CATEGORY_NAME; ?></font></td>
									<td><font size="-1"><?php echo $arrProductsDetailsVal->PRODUCT_NAME; ?></font></td>
									<td><font size="-1"><?php echo $arrProductsDetailsVal->PRODUCT_CODE; ?></font></td>
									<td><?php echo $arrProductsDetailsVal->PRODUCT_AMT; ?></td>
									<td>
										TP: <?php echo $arrProductsDetailsVal->TOTAL_PRODUCT;?> <br />
										TS: <?php echo $arrProductsDetailsVal->TOTAL_SOLD;?> <br />
										TR: <?php echo $arrProductsDetailsVal->TOTAL_REMAINING;?>
									</td>
									<td>
										<input type="number" name="intProductThreshold_<?php echo $arrProductsDetailsVal->PRODUCT_ID;?>" id="intProductThreshold_<?php echo $arrProductsDetailsVal->PRODUCT_ID;?>"  value="<?php echo $arrProductsDetailsVal->PRODUCT_THRESHOLD;?>" style="width:50px"  disabled/>
									</td>
									<td>
										<table id="" class="table table-bordered table-striped"> 
											<tr>
												<td width="50%">Purchased Quantity</td>
												<td width="50%"><input type="number" name="intQuantityPurchased_<?php echo $arrProductsDetailsVal->PRODUCT_ID;?>" id="intQuantityPurchased_<?php echo $arrProductsDetailsVal->PRODUCT_ID;?>"  style="width:150px" disabled /></td>
											</tr>
											<tr>
												<td width="50%">Date of Purchase</td>
												<td width="50%"><input type="date" name="dtDateOfPurchased_<?php echo $arrProductsDetailsVal->PRODUCT_ID;?>" id="dtDateOfPurchased_<?php echo $arrProductsDetailsVal->PRODUCT_ID;?>" value="<?php echo date('Y-m-d');?>"  style="width:150px" disabled /></td>
											</tr>
											<tr>
												<td width="50%">Purchased From</td>
												<td width="50%">
												<select name="strPurchasedFrom_<?php echo $arrProductsDetailsVal->PRODUCT_ID;?>" id="strPurchasedFrom_<?php echo $arrProductsDetailsVal->PRODUCT_ID;?>"  style="width:150px" disabled onchange="getNewInput(this.value,'<?php echo $arrProductsDetailsVal->PRODUCT_ID;?>')">
													<option value="">Select</option>
													<option value="New">Add New</option>
													<?php 
													foreach($arrPurchaseFrom as $arrPurchaseFromVal)
													{
													?>
														<option value="<?php echo $arrPurchaseFromVal->PURCHASED_FROM; ?>"><?php echo $arrPurchaseFromVal->PURCHASED_FROM; ?></option>
													<?php
													}
													?>
												</select>
												<input type="text" name="strPurchasedFromNew_<?php echo $arrProductsDetailsVal->PRODUCT_ID;?>" id="strPurchasedFromNew_<?php echo $arrProductsDetailsVal->PRODUCT_ID;?>"  style="width:150px;display:none" placeholder="Enter New" />
												</td>
											</tr>
											<tr>
												<td width="50%">Receipt No</td>
												<td width="50%"><input type="text" name="strReceiptNo_<?php echo $arrProductsDetailsVal->PRODUCT_ID;?>" id="strReceiptNo_<?php echo $arrProductsDetailsVal->PRODUCT_ID;?>"  style="width:150px" disabled/></td>
											</tr>
											<tr>
												<td width="50%">Purchase Price Per Unit</td>
												<td width="50%"><input type="number" name="intPurchaseAmt_<?php echo $arrProductsDetailsVal->PRODUCT_ID;?>" id="intPurchaseAmt_<?php echo $arrProductsDetailsVal->PRODUCT_ID;?>"  style="width:150px" disabled/></td>
											</tr>
										</table>	
									</td>
									<td>
										<a href="ProductPurchase.php?urlstring=<?php echo EncryptURL('action=PurchaseDetails&intProductId='.$arrProductsDetailsVal->PRODUCT_ID); ?>" class="btn btn-success btn-xs edit" style="padding-top:0%;" target="_blank">Details</a>
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
						<div class="col-md-12 col-sm-12 col-xs-12 ">
							<div class="input-group">
								<button type="submit" class="btn btn-success" onclick="return confirm('Are You Sure you want to Save it?\n Click OK to Continue, Cancel to Stop')">Submit</button>
							</div>
						</div>
						</form>
						<script>
						function enableAllBox(val,productId)
						{
							//document.getElementById('intProductThreshold_'+productId).disabled=false
							//console.log(productId)
							if(document.getElementById('product_'+productId).checked==true)
							{
								document.getElementById('intProductThreshold_'+productId).disabled=false;
								document.getElementById('intQuantityPurchased_'+productId).disabled=false;
								document.getElementById('dtDateOfPurchased_'+productId).disabled=false;
								document.getElementById('strPurchasedFrom_'+productId).disabled=false;
								document.getElementById('strReceiptNo_'+productId).disabled=false;
								document.getElementById('intPurchaseAmt_'+productId).disabled=false;
							}
							else
							{
								document.getElementById('intProductThreshold_'+productId).disabled=true;
								document.getElementById('intQuantityPurchased_'+productId).disabled=true;
								document.getElementById('dtDateOfPurchased_'+productId).disabled=true;
								document.getElementById('strPurchasedFrom_'+productId).disabled=true;
								document.getElementById('strReceiptNo_'+productId).disabled=true;
								document.getElementById('intPurchaseAmt_'+productId).disabled=true;

							
							}
						}
						function getNewInput(val,productId)
						{
							console.log(val)
							if(val=='New')
							{
								document.getElementById('strPurchasedFromNew_'+productId).style.display='block';
							
							}
							else
							{
							
								document.getElementById('strPurchasedFromNew_'+productId).style.display='none';
							}
						
						
						}
						
						
						</script>
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
	<?php
	if(isset($paramsArray['msg1']))
	{
		$msg=$paramsArray['msg1'];	
		?>
		<div class="col-md-12 col-sm-12 col-xs-12 ">
		<?php	
		if($msg=='success')
		{
		?>
			<div class="alert alert-success alert-dismissible" style="height:50px;">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
				<h4><i class="icon fa fa-check"></i> Products purchase record deleted successfully</h4>
			</div>	
		<?php	 
		}
		else if($msg=='error')
		{	
		?>
			<div class="alert alert-danger alert-dismissible" style="height:50px;">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
				<h4><i class="icon fa fa-ban"></i> Error in Process</h4>
			</div>
		<?php	
		}
		?>
	</div>
	<?php	
	}	
	?>
	<div class="box-body table-responsive no-padding">
		<table id="" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th class="text_align_center" colspan="10">Purchase Details</th>
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
					<th class="text_align_center" width="6%">Action</th>
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
						<td>
							<a href="ProductPurchase.php?urlstring=<?php echo EncryptURL('action=DeletePurchase&intProductPurchaseId='.$arrProPurchaseVal->PRODUCT_PURCHASE_ID.'&intProductId='.$intProductId.'&intProductQuantity='.$arrProPurchaseVal->QUANTITY_PURCHASED); ?>" class="btn btn-danger btn-xs edit" style="padding-top:0%;" onclick="return confirm('Are You Sure you want to delete it?\n Click OK to Continue, Cancel to Stop')">Del</a>

						</td>
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
					<td>&nbsp;</td>
				</tr>
				
				
			<?php	
			}
			else
			{
			?>
				<tr>
					<td colspan="10">No Purchase Record Found</td>
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
$pagetitle = "Product Purchase Details";
//Apply the template
include('../MasterTemplatePage.php');
?>