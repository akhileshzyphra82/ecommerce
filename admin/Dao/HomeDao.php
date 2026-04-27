<?php
include 'pathDao.php';
require_once ("BaseDao.php");
require_once ($path."/BL/CryptedPassword.inc.php");
/*
if (!class_exists('PHPMailer', false)) 
{
	require_once ($path."/smtpmail/classes/class.phpmailer.php");
}
*/

class HomeDao extends BaseDao
{
	private $dbConn;
	private $_PASS_LENGTH = 8;
	
	private function Encrypt($strData)
	{
		$objEncryptManager = new CryptedPassword();
		return $objEncryptManager->LinEncrypt($strData);
	}
	
	private function Decrypt($strData)
	{
		$objEncryptManager = new CryptedPassword();
		return $objEncryptManager->LinDecrypt($strData);
	}

	public function InsertBannerData($title,$imagExt,$priority,$discription,$hyperlink)
	{
		try
		{
			$this->dbConn = parent::BeginTransaction();
			$strQuery = "INSERT INTO tbl_banner (banner_name,banner_img_ext ,priority ,banner_description,hyperlink) VALUES('".$title."','".$imagExt."','".$priority."',
			'".$discription."','".$hyperlink."')"; 
			$intNewsTitleId = parent::insert($strQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $intNewsTitleId;
		}
		catch(ADODB_Exception $ae)
		{
			parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae );
		}
		catch(Exception $e)
		{
			parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	
	public function GetAllJobData()
	{
		try
		{
			$strQuery = "SELECT * FROM tbl_job_career";
			$this->dbConn = parent::BeginTransaction();
			$arraData = parent::executeQuery($strQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arraData;
		}
		catch(ADODB_Exception $ae)
		{
			parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
			parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	
	public function InsertJobPost($JobPosition,$JobPriority,$Location,$Description,$Status)
	{
		try
		{
			$this->dbConn = parent::BeginTransaction();
			$strQuery = "INSERT INTO tbl_job_career(job_position ,job_priority ,job_location,job_discription,job_status) VALUES('".$JobPosition."','".$JobPriority."',
			'".$Location."','".$Description."','".$Status."')";
			$JobId = parent::insert($strQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $JobId;
			
		}
		catch(ADODB_Exception $ae)
		{
			parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae );
		}
		catch(Exception $e)
		{
			parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	
	public function InsertProduct($InsertProductDetailArray,$imageArray,$manualArray,$SampleCodeArray)
	{
	   try
	   {	
			$this->dbConn = parent::BeginTransaction();
			$strQuery = "INSERT INTO tbl_product (product_name, product_entry_date ,product_category_id,display_flag,product_status, product_description,            							            product_specification,priorty,product_details,product_amt ,product_tax  ,product_discount,product_code  ) VALUES('".$InsertProductDetailArray['product_name']."',
			'".$InsertProductDetailArray['product_entry_date']."','".$InsertProductDetailArray['product_category_id']."','".$InsertProductDetailArray['product_display']."','".            $InsertProductDetailArray['product_status']."','".$InsertProductDetailArray['product_description']."','".$InsertProductDetailArray['product_specification']."',
			'".$InsertProductDetailArray['Priority']."','".$InsertProductDetailArray['product_Details']."','".$InsertProductDetailArray['product_amt']."',
			'".$InsertProductDetailArray['product_tax']."','".$InsertProductDetailArray['product_discount']."','".$InsertProductDetailArray['product_code']."')"; 
			$intProductId = parent::insert($strQuery,false, $this->dbConn);
			if(count($manualArray)>0 )
			{
				foreach($manualArray as $val)
				{
					$strQuery = "INSERT INTO tbl_product_img (product_id,image_ext,priorty,display_flag,image_for,product_manual_title,image_size,manual_upload_date,hyper_link) 		                    VALUES('".$intProductId."','".$val['image_ext']."','".$InsertProductDetailArray['Priority']."','".$InsertProductDetailArray['product_display']."',
					'".$val['image_for']."','".$val['product_manual_title']."','".$val['image_size']."','".$val['manual_upload_date']."','".$val['URL']."')";
					$intManualProductId = parent::insert($strQuery,false, $this->dbConn);
					$target_file="../../UI/Images/ProductManuals/".$intManualProductId."_productManual.".$val['image_ext'];
					move_uploaded_file($val['tmp_name'], $target_file);
				}
			}
			if(count($imageArray)>0)
			{
				foreach($imageArray as $val)
				{
					$strQuery = "INSERT INTO tbl_product_img (product_id,image_ext,priorty,display_flag,image_for,image_name,image_size,manual_upload_date)
					VALUES('".$intProductId."','".$val['image_ext']."','".$InsertProductDetailArray['Priority']."','".$InsertProductDetailArray['product_display']."',
					'".$val['image_for']."','".$val['image_name']."','".$val['image_size']."','".$val['manual_upload_date']."')"; 
					$intImageProductId = parent::insert($strQuery,false, $this->dbConn);
					$target_file="../../UI/Images/ProductImages/".$intImageProductId."_productImages.".$val['image_ext'];
					move_uploaded_file($val['tmp_name'], $target_file);
				}
			}
			if(count($SampleCodeArray)>0)
			{
				foreach($SampleCodeArray as $val)
				{
					$strQuery = "INSERT INTO tbl_product_sample_code (product_id,language_technology,ide_compiler,type,os,ext,date) VALUES('".$intProductId."',
					'".$val['Language']."','".$val['IDE']."','".$val['Type']."','".$val['OS']."','".$val['image_ext']."','".$val['upload_date']."')"; 
					$intSampleCodeId = parent::insert($strQuery,false, $this->dbConn);
					$target_file="../../UI/Images/SampleCode/".$intSampleCodeId."_SampleCode.".$val['image_ext'];
					move_uploaded_file($val['tmp_name'], $target_file);
				}
			}
			parent::EndTransaction($this->dbConn);
			return $intProductId;
			
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae );
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}

	public function UpdateProduct($InsertProductDetailArray,$imageArray,$manualArray,$AddProductId,$SampleCodeArray)
	{
	   try
	   {
			$this->dbConn = parent::BeginTransaction();
			
			$strQuery = "UPDATE tbl_product set product_name ='".$InsertProductDetailArray['product_name']."', 
			product_entry_date =  '".$InsertProductDetailArray['product_entry_date']."',product_category_id = '".$InsertProductDetailArray['product_category_id']."',
			display_flag = '".$InsertProductDetailArray['product_display']."',product_status = '".$InsertProductDetailArray['product_status']."',
		    product_description = '".$InsertProductDetailArray['product_description']."',  product_specification = '".$InsertProductDetailArray['product_specification']."',
			priorty = '".$InsertProductDetailArray['Priority']."',product_Details = '".$InsertProductDetailArray['product_Details']."',
			product_amt = '".$InsertProductDetailArray['product_amt']."', product_tax = '".$InsertProductDetailArray['product_tax']."' ,
			product_discount = '".$InsertProductDetailArray['product_discount']."',product_code='".$InsertProductDetailArray['product_code']."' Where product_id='".$AddProductId	            ."'";
			
			$intProductId = parent::insert($strQuery,false, $this->dbConn);
			if(count($manualArray)>0)
			{
				foreach($manualArray as $val)
				{
					$strQuery = "INSERT INTO tbl_product_img (product_id,image_ext,priorty,display_flag,image_for,product_manual_title,image_size,manual_upload_date,hyper_link) 		                    VALUES('".$AddProductId."','".$val['image_ext']."','".$InsertProductDetailArray['Priority']."','".$InsertProductDetailArray['product_display']."',
					'".$val['image_for']."','".$val['product_manual_title']."','".$val['image_size']."','".$val['manual_upload_date']."','".$val['URL']."')"; 
					$intManualProductId = parent::insert($strQuery,false, $this->dbConn);
					$target_file="../../UI/Images/ProductManuals/".$intManualProductId."_productManual.".$val['image_ext'];
					move_uploaded_file($val['tmp_name'], $target_file);
				}
			}
			if(count($imageArray)>0)
			{
				foreach($imageArray as $val)
				{
				    $strQuery = "INSERT INTO tbl_product_img (product_id,image_ext,priorty,display_flag,image_for,image_name,image_size,manual_upload_date)
				    VALUES('".$AddProductId."','".$val['image_ext']."','".$InsertProductDetailArray['Priority']."','".$InsertProductDetailArray['product_display']."',
					'".$val['image_for']."','".$val['image_name']."','".$val['image_size']."','".$val['manual_upload_date']."')"; 
					$intImageProductId = parent::insert($strQuery,false, $this->dbConn);
					$target_file="../../UI/Images/ProductImages/".$intImageProductId."_productImages.".$val['image_ext'];
					move_uploaded_file($val['tmp_name'], $target_file);
				}
			}
			if(count($SampleCodeArray)>0)
			{
				foreach($SampleCodeArray as $val)
				{
				
					$strQuery = "INSERT INTO tbl_product_sample_code (product_id,language_technology,ide_compiler,type,os,ext,date) VALUES('".$AddProductId."',
					'".$val['Language']."','".$val['IDE']."','".$val['Type']."','".$val['OS']."','".$val['image_ext']."','".$val['upload_date']."')";
					$intSampleCodeId = parent::insert($strQuery,false, $this->dbConn);
					$target_file="../../UI/Images/SampleCode/".$intSampleCodeId."_SampleCode.".$val['image_ext'];
					move_uploaded_file($val['tmp_name'], $target_file);
				}
			}
			parent::EndTransaction($this->dbConn);
			return $intProductId;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae );
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	
	public function GetProductsDetailsByProductCategoryID($productCategoryId)
	{
		try
		{	
			$whereClause="";
		if($productCategoryId !="")
		{
			$whereClause="WHERE  p.product_category_id IN ($productCategoryId)";	
			$strQuery = "SELECT p.*,pi.* FROM tbl_product p
			LEFT JOIN tbl_product_img pi ON p.product_id=pi.product_id ".$whereClause;
			$this->dbConn = parent::BeginTransaction();
			$arraData = parent::executeQuery($strQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arraData;
		}
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	
	public function DeleteAddedProductData($AddProductId)
	{
		try
		{
		
			$strQuery1= "SELECT COUNT(*) AS TOTAL_COUNT FROM `tbl_enquiry_quote_product` WHERE product_id='".$AddProductId."'";
			$strQuery2= "SELECT COUNT(*) AS TOTAL_COUNT FROM `tbl_product_purchase` WHERE product_id='".$AddProductId."'";
			$this->dbConn = parent::BeginTransaction();
			$arrCheckData1 = parent::executeQuery($strQuery1, false, $this->dbConn);
			$arrCheckData2 = parent::executeQuery($strQuery2, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			
		
			//echo '<pre>'; print_r($strQuery1);
			//echo '<pre>'; print_r($strQuery2);
			//die;
			if($arrCheckData1[0]->TOTAL_COUNT>0 || $arrCheckData2[0]->TOTAL_COUNT>0)
			{
				return 'Product will not be deleted because it is used';
			}
			else
			{
				$strQuery = "DELETE FROM tbl_product WHERE  product_id='".$AddProductId."'";
				$this->dbConn = parent::BeginTransaction();
				$result = parent::execute($strQuery, false, $this->dbConn);
				parent::EndTransaction($this->dbConn);
				return 'delete';
			}
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	
	public function DeleteProductImage($ImageId,$ext,$imageFor)
	{
		//$data=array($ImageId,$ext,$imageFor);
		//echo "<pre>"; print_r($data);die;
		try
		{
			$strQuery = "DELETE FROM tbl_product_img WHERE  image_id='".$ImageId."'";
			$this->dbConn = parent::BeginTransaction();
			$result = parent::execute($strQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			if($imageFor=='Product')
			{
				$path="ProductImages";
				$for="productImages";
			}
			if($imageFor=='ProductMannual')
			{
				$path="ProductManuals";
				$for="productManual";
			}
			$targetFile="../../UI/Images/".$path."/".$ImageId."_".$for.".".$ext;
			unlink($targetFile);
			return $result;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
		
	public function DeleteSampleCode($SampleCodeID,$ext)
	{
		try
		{
			$strQuery = "DELETE FROM tbl_product_sample_code WHERE  product_sample_code_id='".$SampleCodeID."'";
			$this->dbConn = parent::BeginTransaction();
			$result = parent::execute($strQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			$target_file="../../UI/Images/SampleCode/".$SampleCodeID."_SampleCode.".$ext;
			unlink($target_file);
			return $result;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
		
	public function DeleteImageById($ProductId,$ext)
	{
		try
		{
			$strQuery = "UPDATE tbl_product_category set ext='' WHERE product_category_id='".$ProductId."'";
			$this->dbConn = parent::BeginTransaction();
			$result = parent::execute($strQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			$target_file="../UI/Images/ProductCategory/".$ProductId.".".$ext;
			unlink($target_file);
			return $result;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
		
	public function GetProductData($AddProductId)
	{
		try
		{
			 $strQuery = "SELECT p.*,pi.*,psc.*,p.product_id FROM tbl_product p
			 LEFT JOIN tbl_product_img pi ON p.product_id=pi.product_id
			 LEFT JOIN tbl_product_sample_code psc ON psc.product_id=pi.product_id
			 WHERE  p.product_id='".$AddProductId."'";

			$this->dbConn = parent::BeginTransaction();
			$arraData = parent::executeQuery($strQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arraData;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	
	public function GetAndDisplayAllAddProductsDetails($arrSrchData)
	{
		try
		{
			///echo '<pre>'; print_r($arrSrchData); die;
			$productCategory=$arrSrchData['productCategory'];
			$productName=$arrSrchData['productName'];
			$productCode=$arrSrchData['productCode'];
			
			$strProductCategoryWhereClause='';
			$strProductNameWhereClause='';
			$productCodeWhereClause='';
			
			if($productCategory!='')
			{
				
				$strCheckParentQuery = "SELECT GROUP_CONCAT(DISTINCT PRODUCT_CATEGORY_ID) AS PRO_CAT_IDS FROM tbl_product_category WHERE parent_category_id='".$productCategory."' "; 
				$this->dbConn = parent::BeginTransaction();
				$arrCheckParentData = parent::executeQuery($strCheckParentQuery, false, $this->dbConn);
				parent::EndTransaction($this->dbConn);
				
				if($arrCheckParentData[0]->PRO_CAT_IDS!=NULL)
					$strProductCategoryWhereClause=" AND p.product_category_id IN (".$arrCheckParentData[0]->PRO_CAT_IDS.") ";
				else	
					$strProductCategoryWhereClause=" AND p.product_category_id='".$productCategory."' ";
				
				
			}
			if($productName!='')
				$strProductNameWhereClause=" AND p.product_name LIKE '%".$productName."%' ";

			if($productCode!='')
				$productCodeWhereClause=" AND p.product_code LIKE '%".$productCode."%' ";
	


			$strQuery = "SELECT p.*, pc.product_category_name
			FROM tbl_product p
			LEFT JOIN tbl_product_category pc ON pc.product_category_id = p.product_category_id
			WHERE p.product_id>0 ".$strProductCategoryWhereClause.$strProductNameWhereClause.$productCodeWhereClause."
			ORDER BY pc.priority, p.product_category_id, p.priorty, p.product_name";
			$this->dbConn = parent::BeginTransaction();
			$arraData = parent::executeQuery($strQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			
			return $arraData;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	
	public function GetAllSubProductDetailByProductCatId($id)
	{
		try
		{
			if($id!="")
			$whereClause="WHERE parent_category_id='".$id."'";
			$strQuery = "SELECT * FROM tbl_product_category ".$whereClause;
			$this->dbConn = parent::BeginTransaction();
			$arraData = parent::executeQuery($strQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arraData;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	
	public function GetParentNameByCatId($CatId)
	{
		try
		{
			$strQuery = "SELECT * FROM tbl_product_category WHERE product_category_id='".$CatId."'";
			$this->dbConn = parent::BeginTransaction();
			$arraData = parent::executeQuery($strQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arraData;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
		
	public function GetAllJobById($JobId)
	{
		try
		{ 
			$strQuery = "SELECT * FROM tbl_job_career WHERE job_post_id='".$JobId."'";
			$this->dbConn = parent::BeginTransaction();
			$arraData = parent::executeQuery($strQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arraData;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	
	public function InsertProductData($ProductCategoryName,$ParentCategoryId,$priority,$Description,$imagExt)
	{
	   try
	   {
			$this->dbConn = parent::BeginTransaction();
			$strQuery = "INSERT INTO tbl_product_category(product_category_name ,parent_category_id ,priority,description,ext) VALUES
			('".$ProductCategoryName."','".$ParentCategoryId."','".$priority."','".$Description."','".$imagExt."')"; 
			$ProductCategoryId = parent::insert($strQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $ProductCategoryId;
			
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae );
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	
	public function GetAndDisplayAllBannerDetails()
	{
		try
		{
			$strQuery = "SELECT * FROM tbl_banner";
			$this->dbConn = parent::BeginTransaction();
			$arraData = parent::executeQuery($strQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arraData;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	
	public function GetAndDisplayAllListProduct()
	{
		try
		{
			$strQuery = "SELECT * FROM tbl_product_category ORDER BY parent_category_id, priority, product_category_name";
			$this->dbConn = parent::BeginTransaction();
			$arraData = parent::executeQuery($strQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arraData;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	
	public function GetAndDisplayAllListProduct1()
	{
		try
		{
			$strQuery = "SELECT * FROM tbl_product_category WHERE parent_category_id=0";
			$this->dbConn = parent::BeginTransaction();
			$arraData = parent::executeQuery($strQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arraData;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	
	public function GetAndDisplayAllListProductById($ProductId)
	{
		try
		{
			$whereClause="";
			if($ProductId !="")
			$whereClause="WHERE product_category_id ='".$ProductId."' order by product_category_id";	
			$strQuery = "SELECT * FROM tbl_product_category ".$whereClause;
			
			$this->dbConn = parent::BeginTransaction();
			$arraData = parent::executeQuery($strQuery, false, $this->dbConn);
			//echo $intNewsEventId;die;
			parent::EndTransaction($this->dbConn);
			return $arraData;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	
	public function DeleteBannerData($bannerId)
	{
		try
		{
			$strQuery = "DELETE FROM tbl_banner WHERE  banner_id='".$bannerId."'";
			$this->dbConn = parent::BeginTransaction();
			$arraData = parent::execute($strQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arraData;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
		
	public function DeleteProductCategory($ProductId)
	{
		try
		{ 
		
			$strQuery1= "SELECT COUNT(*) AS TOTAL_COUNT FROM `tbl_enquiry_quote_product` WHERE product_category_id='".$ProductId."'";
			$strQuery2= "SELECT COUNT(*) AS TOTAL_COUNT FROM `tbl_product` WHERE product_category_id='".$ProductId."'";
			$this->dbConn = parent::BeginTransaction();
			$arrCheckData1 = parent::executeQuery($strQuery1, false, $this->dbConn);
			$arrCheckData2 = parent::executeQuery($strQuery2, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			
		
			//echo '<pre>'; print_r($strQuery1);
			//echo '<pre>'; print_r($strQuery2);
			//die;
			if($arrCheckData1[0]->TOTAL_COUNT>0 || $arrCheckData2[0]->TOTAL_COUNT>0)
			{
				return 'Product Category will not be deleted because it is used';
			}
			else
			{
				$strQuery = "DELETE FROM tbl_product_category WHERE product_category_id='".$ProductId."' AND product_category_id NOT IN(SELECT product_category_id 
				FROM tbl_product)";
				$this->dbConn = parent::BeginTransaction();
				$arraData = parent::execute($strQuery, false, $this->dbConn);
				parent::EndTransaction($this->dbConn);
				return 'delete';
			}
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	
	public function InsertNewsAndEventData($flag,$title,$date,$discription,$empId,$imgExt,$docExt)
	{
		 try
		 {
			$this->dbConn = parent::BeginTransaction();
			$strQuery = "INSERT INTO tbl_news_event (flag,title,created_date,description,created_by,img_ext,doc_ext) 
			VALUES('".$flag."','".$title."','".$date."','".$discription."','".$empId."','".$imgExt."','".$docExt."')";
			$intNewsTitleId = parent::insert($strQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $intNewsTitleId;
					
		}
		catch(ADODB_Exception $ae)
		{
			parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae );
		}
		catch(Exception $e)
		{
			parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}

	public function DeleteNewsAndEventData($intNewsAndEventId)
	{
	   try{
			$this->dbConn = parent::BeginTransaction();
			$strQuery = "DELETE FROM tbl_news_event WHERE news_event_id = '".$intNewsAndEventId."'";
			$intNewsTitleId = parent::insert($strQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $intNewsTitleId;
			
		}
		catch(ADODB_Exception $ae)
		{
			parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae );
		}
		catch(Exception $e)
		{
			parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}

	public function UpdateNewsAndEventData($flag,$title,$date,$discription,$empId,$intNewsEventId,$imgExt,$docExt)
	{
	   try{
			$this->dbConn = parent::BeginTransaction();
			$strQuery = "UPDATE tbl_news_event set title='".$title."', flag='".$flag."',description='".$discription."', created_date='".$date."',
			created_by='".$empId."',img_ext='".$imgExt."',doc_ext='".$docExt."'	WHERE news_event_id = '".$intNewsEventId."'";
			$intNewsTitleId = parent::insert($strQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $intNewsTitleId;
			
		}
		catch(ADODB_Exception $ae)
		{
			parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae );
		}
		catch(Exception $e)
		{
			parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	
	
	
	public function  GetAndDisplayAllNewsAndEventDetails()
	{
		try
		{
			$strQuery = "SELECT * FROM tbl_news_event";
			$this->dbConn = parent::BeginTransaction();
			$arraData = parent::executeQuery($strQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arraData;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	
	public function GetNewsAndEventById($NewsEventId)
	{
		try 
		{
			$strQuery =" SELECT * FROM tbl_news_event WHERE news_event_id='".$NewsEventId."' ";
			$this->dbConn = parent::BeginTransaction();
			$arraData = parent::executeQuery($strQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arraData;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	
	public function  GetAndDisplayAllInsertedImages($NewsEventId)
	{
		try
		{
			$strQuery = "SELECT * FROM  tbl_news_event_images WHERE NEWS_EVENT_ID='".$NewsEventId."'";
			$this->dbConn = parent::BeginTransaction();
			$arraData = parent::executeQuery($strQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arraData;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	
	public function DeleteJobPost($JobId)
	{
		try
		{
			$strQuery = "DELETE FROM tbl_job_career WHERE job_post_id='".$JobId."'";
			$this->dbConn = parent::BeginTransaction();
			$arraData = parent::execute($strQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arraData;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
		
	public function UpdateProductData($ProductId,$ProductCategoryName,$ParentCategoryId,$priority,$Description,$imagExt)
	{
		try
		{ 
			$strQuery = "UPDATE tbl_product_category
			SET product_category_name='".$ProductCategoryName."',parent_category_id ='".$ParentCategoryId."',priority='".$priority."',description='".$Description."',
			ext='".$imagExt."'
			WHERE product_category_id='".$ProductId."'";
			$this->dbConn = parent::BeginTransaction();
			$arrData = parent::insert($strQuery, false,$this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	
	public function UpdateJobPost($JobId,$JobPosition,$JobPriority,$Location,$Description,$Status)
	{
		try
		{
			$strQuery = "UPDATE tbl_job_career
			SET job_position='".$JobPosition."',job_priority ='".$JobPriority."',job_location='".$Location."',job_discription='".$Description."',job_status='".$Status."'
			WHERE job_post_id='".$JobId."'";
			$this->dbConn = parent::BeginTransaction();
			$arrData = parent::insert($strQuery, false,$this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
			
	public function DeleteNewsImageData($ImageId)
	{
		try
		{
			$strQuery = "DELETE FROM tbl_news_event_images WHERE  news_event_img_id='".$ImageId."'"; 
			$this->dbConn = parent::BeginTransaction();
			$arraData = parent::execute($strQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arraData;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
		
	public function GetAllProductOrderedDetails($OrderStatus,$limit,$maxRecord)
	{
		try
		{ 
		
			$limitClause='';
			if($limit!='')
			$limitClause = " LIMIT $limit,$maxRecord ";

			if($OrderStatus!='')
			{
				$arrOrderStatus = explode(',', $OrderStatus);
				if(count($arrOrderStatus)>1)
				{
					$strOrderCartClaus= " AND o.order_current_status IN (".$OrderStatus.") ";
				}
				else
				{
					$strOrderCartClaus= " AND o.order_current_status = '".$OrderStatus."' " ;
				}	
			}
								
			$strQuery = "SELECT o.*,tbl_user_address.*,u.*,country.country as country_name 
			FROM tbl_order o
			LEFT JOIN tbl_user u ON u.user_id=o.user_id
			LEFT JOIN tbl_user_address ON o.user_address_id=tbl_user_address.user_address_id
			LEFT JOIN tbl_country country ON country.country_id = tbl_user_address.country_id 
			WHERE o.order_id>0 ".$strOrderCartClaus.' ORDER BY o.order_id DESC '.$limitClause;
			
			$this->dbConn = parent::BeginTransaction();
			$arraData = parent::executeQuery($strQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arraData;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
		
	public function UpdateOrderStatusByOrderId($orderStatus,$orderId, $dispatch_courier_company, $dispatch_courier_tracking_id, $dispatch_courier_tracking_url)
	{
		try
		{
			$arrProductListBelowThreshold = array();
			if($orderStatus=='Dispatched' || $orderStatus=='Dispatched Invoice Payment Pending')
			{
				$strOrderProductCountQuery = "SELECT product_id, quantity, order_id FROM tbl_add_cart WHERE order_id='".$orderId."'";
				
				$this->dbConn = parent::BeginTransaction();
				$arrOrderProductCountData = parent::executeQuery($strOrderProductCountQuery, false, $this->dbConn);
				parent::EndTransaction($this->dbConn);
				
				if(count($arrOrderProductCountData)>0)
				{
					foreach($arrOrderProductCountData as $arrOrderProductCountVal)
					{
						$strProductQtyQuery = "SELECT product_id, total_product, total_sold, total_remaining, product_threshold, product_name FROM tbl_product 
						WHERE product_id = '".$arrOrderProductCountVal->PRODUCT_ID."' ";
						$this->dbConn = parent::BeginTransaction();
						$arrProductQtyData = parent::executeQuery($strProductQtyQuery, false, $this->dbConn);
						parent::EndTransaction($this->dbConn);
						
						$totProduct = $arrProductQtyData[0]->TOTAL_PRODUCT;
						$totProductSold = $arrProductQtyData[0]->TOTAL_SOLD + $arrOrderProductCountVal->QUANTITY;
						$totRemProduct = $totProduct - $totProductSold;
						
						//echo $arrProductQtyData[0]->TOTAL_PRODUCT.' -- '.$intQuantityPurchased.' -- '.$totProduct.' -- '.$arrProductQtyData[0]->TOTAL_SOLD.' -- '.$totRemProduct;

						$strUpdateProductQtyQuery = "UPDATE tbl_product SET total_product='".$totProduct."', total_remaining='".$totRemProduct."', 
						total_sold='".$totProductSold."' WHERE product_id='".$arrOrderProductCountVal->PRODUCT_ID."'";

						$this->dbConn = parent::BeginTransaction();
						$intUpProductId = parent::insert($strUpdateProductQtyQuery,false, $this->dbConn);
						parent::EndTransaction($this->dbConn);
						
						if($arrProductQtyData[0]->PRODUCT_THRESHOLD >= $totRemProduct)
						{
						
							$arrProductListBelowThreshold[] = array('product_id'=>$arrOrderProductCountVal->PRODUCT_ID, 'product_name'=>$arrProductQtyData[0]->PRODUCT_NAME, 'product_threshold'=>$arrProductQtyData[0]->PRODUCT_THRESHOLD, 'product_remaining'=>$totRemProduct) ;
						}
					}
				}
				
				$strQuery = "UPDATE tbl_order SET order_current_status='".$orderStatus."', dispatch_courier_company='".$dispatch_courier_company."',
				dispatch_courier_tracking_id='".$dispatch_courier_tracking_id."', dispatch_courier_tracking_url='".$dispatch_courier_tracking_url."'
				WHERE order_id='".$orderId."'";
			}
			else
			{	
				$strQuery = "UPDATE tbl_order SET order_current_status='".$orderStatus."' 
				WHERE order_id='".$orderId."'";
			}	
			
			$this->dbConn = parent::BeginTransaction();
			$arrData = parent::insert($strQuery, false,$this->dbConn);
			parent::EndTransaction($this->dbConn);
			
			$strQuery1="INSERT INTO tbl_order_history(order_id,order_status) VALUES('".$orderId."','".$orderStatus."')";
			$this->dbConn = parent::BeginTransaction();
			$result = parent::insert($strQuery1, false,$this->dbConn);
			parent::EndTransaction($this->dbConn);
			
			//echo '<pre>'; print_r($arrProductListBelowThreshold); die;
			return $arrProductListBelowThreshold;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}

	public function DeleteOrderByOrderId($orderId)
	{
		try
		{
			$strQuery = "DELETE FROM tbl_order WHERE order_id='".$orderId."'";

			$this->dbConn = parent::BeginTransaction();
			$orderId = parent::execute($strQuery, false,$this->dbConn);
			parent::EndTransaction($this->dbConn);
			return true;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	
	
	public function GetAllUserDetails($limit,$maxRecord)
	{
		try
		{
		
			$limitClause='';
			if($limit!='')
			$limitClause = " LIMIT $limit, $maxRecord ";

		
			$strQuery = "SELECT `user_id` as clinet_id,`name` as client_name,`communication_phone_num` as Phone_No,`communication_mobile_num` 
			as Mobile_NO,`communication_email_id` as Email_Id,`company_name` as company_Name,`designation` as Designation,  
			CASE 
			WHEN account_activation_flag=0 THEN 'Inactive'
			WHEN account_activation_flag=1 THEN 'Active'
			END as status
			FROM tbl_user WHERE user_type_id='2' ORDER BY user_id DESC ".$limitClause;
			$this->dbConn = parent::BeginTransaction();
			$arraData = parent::executeQuery($strQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arraData;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	
	public function GetExcelUserDetails($name,$emil)
	{
		try
		{
			$strQuery = "SELECT * FROM tbl_user WHERE user_type_id='2' ORDER BY user_id DESC ";
			$this->dbConn = parent::BeginTransaction();
			$arraData = parent::executeQuery($strQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arraData;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
		
	public function DeleteCustomerData($CustomerId)
	{
		try
		{
			$strQuery = "DELETE FROM tbl_user WHERE  user_id='".$CustomerId."'"; 
			$this->dbConn = parent::BeginTransaction();
			$arraData = parent::execute($strQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arraData;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
		
	public function getUserAddressByUserId($userId)
	{
		try
		{
			$strQuery = "SELECT * FROM tbl_user_address WHERE user_id='".$userId."'";
			$this->dbConn = parent::BeginTransaction();
			$arraData = parent::executeQuery($strQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arraData;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	
		
	public function  GetAllUserDetailsForPaging($startFrom,$limit,$customerName)
	{
		try
		{
			//$strQuery = "SELECT * FROM tbl_user  LIMIT $startFrom,$limit";
			$strQuery="SELECT * FROM tbl_user WHERE name LIKE '".$customerName."%' and user_type_id=2 LIMIT $startFrom,$limit";
			$this->dbConn = parent::BeginTransaction();
			$arraData = parent::executeQuery($strQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arraData;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
		
	public function InsertCandidateDetails($name,$positionId,$email,$phone,$experience,$FileExt,$FileTempName)
	{
		try
		{
		
		
		
			$strQuery = "INSERT INTO tbl_candidate_applied_for_job (job_post_id,candidate_name,candidate_email,candidate_phone,applied_date,candidate_experience,resume_file_ext) 			VALUES('".$positionId."','".$name."','".$email."','".$phone."','".date("Y-m-d")."','".$experience."','".$FileExt."') ";
			$this->dbConn = parent::BeginTransaction();
			$appliedId = parent::insert($strQuery, false,$this->dbConn);
			if($appliedId!="")
			{
			    $path="../admin/UI/Images/CandResume/".$appliedId.".".$FileExt;
			    move_uploaded_file($FileTempName,$path);
			  
			}
			parent::EndTransaction($this->dbConn);
			return $appliedId;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	
	public function  GetAllAppliedJobCandidate($arrSrchData)
	{
		try
		{
				
			$intPosition=$arrSrchData['intPosition'];
			$fromDate=$arrSrchData['fromDate'];
			$toDate=$arrSrchData['toDate'];
			
			$strJobPostIdWhereClause='';
			$strDateWhereClause='';
			
			if($intPosition!='')
				$strJobPostIdWhereClause=" AND  cafj.job_post_id='".$intPosition."' ";
			
			if($fromDate!='' && $toDate!='')
				$strDateWhereClause=" AND cafj.applied_date BETWEEN '".$fromDate."' AND '".$toDate."' ";
			
			

			$strQuery = "SELECT * FROM tbl_candidate_applied_for_job cafj 
			 			LEFT JOIN tbl_job_career jc ON cafj.job_post_id=jc.job_post_id
			  			WHERE jc.job_status='Active' ".$strJobPostIdWhereClause.$strDateWhereClause." ORDER BY cafj.applied_date DESC";
				//echo '<pre>';print_r($strQuery);
			$this->dbConn = parent::BeginTransaction();
			$arraData = parent::executeQuery($strQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arraData;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
		
     public function InsertProductsEnquiry($arrUserDetails,$arrProductOrderDetails)
	 {
		try
		{
			$strUserQuery = "SELECT user_id, erp_password, random_activation_key FROM tbl_user WHERE communication_email_id='".$arrUserDetails['user_email']."'";
			$this->dbConn = parent::BeginTransaction();
			$arrUserData = parent::executeQuery($strUserQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);

			if(count($arrUserData)>0)
			{
				$userId=$arrUserData[0]->USER_ID;
				$randomKeyward='';
				$password = $arrUserData[0]->ERP_PASSWORD;
				$decriptPassword = $this->Decrypt($password);
				
				$strUserAddressQuery = "SELECT user_address_id FROM tbl_user_address WHERE user_id ='".$userId."' 
				AND address LIKE '%".$arrUserDetails['delivery_address']."%' AND city LIKE '%".$arrUserDetails['delivery_city']."%'
				AND state LIKE '%".$arrUserDetails['delivery_state']."%' AND zip LIKE '%".$arrUserDetails['delivery_zip']."%'
				AND country_id ='".$arrUserDetails['delivery_country_id']."' ";
	
				$this->dbConn = parent::BeginTransaction();
				$arrUserAddressData = parent::executeQuery($strUserAddressQuery, false, $this->dbConn);
				parent::EndTransaction($this->dbConn);
				
				if(count($arrUserAddressData)>0)
				{
					$userAddressId = $arrUserAddressData[0]->USER_ADDRESS_ID;
				}
				else
				{
					$strUserAddQuery = "INSERT INTO tbl_user_address (user_id, address, city, state, zip, country, landmark, delivery_phone_no, user_name, 
					eu_vat, company_name, mobile_country_code, country_id) VALUES('".$userId."','".$arrUserDetails['delivery_address']."','".$arrUserDetails['delivery_city']."',
					'".$arrUserDetails['delivery_state']."','".$arrUserDetails['delivery_zip']."','".$arrUserDetails['delivery_country']."','','".$arrUserDetails['user_phone']."',
					'".$arrUserDetails['user_name']."','".$arrUserDetails['vat_number']."','".$arrUserDetails['company_name']."','".$arrUserDetails['phone_country_code']."'
					,'".$arrUserDetails['delivery_country_id']."')"; 

					$this->dbConn = parent::BeginTransaction();
					$userAddressId = parent::insert($strUserAddQuery,false,$this->dbConn);
					parent::EndTransaction($this->dbConn);
					//echo '<pre>userAddressId'; print_r($userAddressId); die;
				}
			}
			else
			{
				$randomKeyward=rand(100,10000);
				$decriptPassword=rand(100,1000000000);
				$encrypePassword = $this->Encrypt($decriptPassword);

				$strInsertQuery="INSERT INTO tbl_user (user_type_id, name, communication_phone_num_isd, communication_phone_num, communication_mobile_num_isd,
 				communication_mobile_num, communication_email_id, erp_password, company_name, designation, account_activation_flag, random_activation_key,verified_flag) 
				VALUES('2','".$arrUserDetails['user_name']."','".$arrUserDetails['phone_country_code']."','".$arrUserDetails['user_phone']."'
				,'".$arrUserDetails['phone_country_code']."','".$arrUserDetails['user_phone']."','".$arrUserDetails['user_email']."','".$encrypePassword."'
				,'".$arrUserDetails['company_name']."','','1','".$randomKeyward."','Yes')";
			
				$this->dbConn = parent::BeginTransaction();
				$userId = parent::insert($strInsertQuery,false,$this->dbConn);
				parent::EndTransaction($this->dbConn);
				
				if($arrUserDetails['billing_address']!='')
				{
					$strUserAddQuery = "INSERT INTO tbl_user_address (user_id, address, city, state, zip, country, landmark, delivery_phone_no, user_name, 
					eu_vat, company_name, mobile_country_code, country_id) VALUES('".$userId."','".$arrUserDetails['delivery_address']."','".$arrUserDetails['delivery_city']."',
					'".$arrUserDetails['delivery_state']."','".$arrUserDetails['delivery_zip']."','".$arrUserDetails['delivery_country']."','','".$arrUserDetails['user_phone']."',
					'".$arrUserDetails['user_name']."','".$arrUserDetails['vat_number']."','".$arrUserDetails['company_name']."','".$arrUserDetails['phone_country_code']."'
					,'".$arrUserDetails['delivery_country_id']."')"; 

					//echo '<pre>'; print_r($strUserAddQuery); die;
					$this->dbConn = parent::BeginTransaction();
					$userAddressId = parent::insert($strUserAddQuery,false,$this->dbConn);
					parent::EndTransaction($this->dbConn);
				}
			}
	   			
			$strEnqQuoteInsQuery="INSERT INTO tbl_enquiry_quote(user_name, company_name, user_email, user_phone, delivery_address, billing_address, vat_number,
			user_phone_country_code, delivery_city, delivery_state, delivery_zip, delivery_country, billing_city, billing_state, billing_country, billing_zip, 
			user_id, user_address_id, customer_order_no, customer_supplier_no) 
			VALUES('".$arrUserDetails['user_name']."','".$arrUserDetails['company_name']."','".$arrUserDetails['user_email']."','".$arrUserDetails['user_phone']."',
			'".$arrUserDetails['delivery_address']."','".$arrUserDetails['billing_address']."','".$arrUserDetails['vat_number']."','".$arrUserDetails['phone_country_code']."',
			'".$arrUserDetails['delivery_city']."','".$arrUserDetails['delivery_state']."','".$arrUserDetails['delivery_zip']."','".$arrUserDetails['delivery_country']."',
			'".$arrUserDetails['billing_city']."','".$arrUserDetails['billing_state']."','".$arrUserDetails['billing_country']."','".$arrUserDetails['billing_zip']."',
			'".$userId."','".$userAddressId."','".$arrUserDetails['customerOrderNo']."','".$arrUserDetails['customerSupplierNo']."')";
			
			$this->dbConn = parent::BeginTransaction();
			$enquiry_quote_id = parent::insert($strEnqQuoteInsQuery,false,$this->dbConn);
			parent::EndTransaction($this->dbConn);
			
			if(count($arrProductOrderDetails)>0)
			{
				foreach($arrProductOrderDetails as $productVal)
				{ 
					list($productCategoryId,$productCategoryName)=explode('_',$productVal['productCategoryId']);
					list($productId,$productName,$productAmt)=explode('@_@',$productVal['proudctId']);
					$quantity=$productVal['productQuantity'];
	
					$strQueryRelation="INSERT INTO tbl_enquiry_quote_product(enquiry_quote_id, product_category_id, product_id, product_quantity, product_amt) 
							VALUES('".$enquiry_quote_id."','".$productCategoryId."', '".$productId."','".$quantity."','".$productAmt."')";
					
					$this->dbConn = parent::BeginTransaction();
					$productRelationId=parent::execute($strQueryRelation,false,$this->dbConn);
					parent::EndTransaction($this->dbConn);
				}
			}
			$arrData=array($enquiry_quote_id, $decriptPassword, $userId, $randomKeyward);
			return $arrData;
			
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	
	public function UpdateEnquiryStatus($enquiryId,$enquiryStatus,$vatAmount,$deliveryCountryShipping,$totalAmount)
	{
		try
		{
			$strQuery = "UPDATE tbl_enquiry_quote SET enquiry_status ='".$enquiryStatus."', enquiry_vat_amt = '".$vatAmount."', 
			enquiry_shipping_amt='".$deliveryCountryShipping."', enquiry_total_amt = '".$totalAmount."'
			WHERE enquiry_quote_id='".$enquiryId."' AND enquiry_status='Quotation Pending' "; 
			
			$this->dbConn = parent::BeginTransaction();
			$intEnquiryId = parent::insert($strQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $intEnquiryId;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}


	public function SelectUser($flag)
	{
		try
		{
			if($flag=='count')	
			$selectCondition = " COUNT(*) AS TOTAL ";
			else
			$selectCondition = "`user_id` as clinet_id,`name` as client_name,`communication_phone_num` as Phone_No,`communication_mobile_num` 
			as Mobile_NO,`communication_email_id` as Email_Id,`company_name` as company_Name,`designation` as Designation";
			
			$strQuery = "SELECT ".$selectCondition."FROM tbl_user";
			$this->dbConn = parent::BeginTransaction();
			$arraData = parent::executeQuery($strQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arraData;
		}
		catch(ADODB_Exception $ae)
		{
			parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
			parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
		
	public function GetAllProductOrderedCount($flag,$OrderStatus)
	{
		try
		{ 
			if($flag=="count")
			{
			 	$selectCondition="COUNT(*) AS TOTAL";
			}	
			else
			{
				$selectCondition="o.*,tbl_user_address.*,u.*,country.country as country_name";
			}
			$strOrderCartClaus = '';
			$arrOrderStatus = array();
			if($OrderStatus!='')
			{
				$arrOrderStatus = explode(',', $OrderStatus);
				if(count($arrOrderStatus)>1)
				{
					$strOrderCartClaus= " AND o.order_current_status IN (".$OrderStatus.") ";
				}
				else
				{
					$strOrderCartClaus= " AND o.order_current_status = '".$OrderStatus."' " ;
				}	
			}
			
			$strQuery = "SELECT ".$selectCondition." FROM tbl_order o
			LEFT JOIN tbl_user u ON u.user_id=o.user_id
			LEFT JOIN tbl_user_address ON o.user_address_id=tbl_user_address.user_address_id
			LEFT JOIN tbl_country country ON country.country_id = tbl_user_address.country_id 
			WHERE o.order_id>0 ".$strOrderCartClaus;
			
			$this->dbConn = parent::BeginTransaction();
			$arraData = parent::executeQuery($strQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arraData;
		}
		catch(ADODB_Exception $ae)
		{
			parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
			parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}

	public function InsertProductPurchaseData($arrProductPurchase)
	{
		try
		{
			if(count($arrProductPurchase)>0)
			{
				foreach($arrProductPurchase as $arrProductPurchaseVal)
				{
					$intProductId = $arrProductPurchaseVal['intProductId'];
					$intProductThreshold = $arrProductPurchaseVal['intProductThreshold'];
					$intQuantityPurchased = $arrProductPurchaseVal['intQuantityPurchased'];
					$dtDateOfPurchased = $arrProductPurchaseVal['dtDateOfPurchased'];
					$strPurchasedFrom = $arrProductPurchaseVal['strPurchasedFrom'];
					$strReceiptNo = $arrProductPurchaseVal['strReceiptNo'];
					$intPurchaseAmt = $arrProductPurchaseVal['intPurchaseAmt'];
					
					$strProductQtyQuery = "SELECT product_id, total_product, total_sold, total_remaining FROM tbl_product WHERE product_id = '".$intProductId."' ";
					$this->dbConn = parent::BeginTransaction();
					$arrProductQtyData = parent::executeQuery($strProductQtyQuery, false, $this->dbConn);
					parent::EndTransaction($this->dbConn);
					
					$totProduct = $arrProductQtyData[0]->TOTAL_PRODUCT + $intQuantityPurchased;
					$totRemProduct = $totProduct - $arrProductQtyData[0]->TOTAL_SOLD;
					
					//echo $arrProductQtyData[0]->TOTAL_PRODUCT.' -- '.$intQuantityPurchased.' -- '.$totProduct.' -- '.$arrProductQtyData[0]->TOTAL_SOLD.' -- '.$totRemProduct;
					if($intQuantityPurchased=='')
					{
						$strUpdateProductQtyQuery = "UPDATE tbl_product SET product_threshold='".$intProductThreshold."' WHERE product_id='".$intProductId."'";

					}
					else
					{
						$strUpdateProductQtyQuery = "UPDATE tbl_product SET total_product='".$totProduct."', total_remaining='".$totRemProduct."', 
						product_threshold='".$intProductThreshold."' WHERE product_id='".$intProductId."'";
					}
					$this->dbConn = parent::BeginTransaction();
					$intUpProductId = parent::insert($strUpdateProductQtyQuery,false, $this->dbConn);
					parent::EndTransaction($this->dbConn);
					
					$strInsProPurQuery = "INSERT INTO tbl_product_purchase (product_id, quantity_purchased, date_of_purchase, purchased_from, receipt_no, purchase_amt) 
					VALUES(
					'".$intProductId."',
					'".$intQuantityPurchased."',
					'".$dtDateOfPurchased."',
					'".$strPurchasedFrom."',
					'".$strReceiptNo."',
					'".$intPurchaseAmt."')"; 
					$this->dbConn = parent::BeginTransaction();
					$intProPurId = parent::insert($strInsProPurQuery,false, $this->dbConn);
					parent::EndTransaction($this->dbConn);
			
					$msg = 'success';
					
				}
			}
			else
			{
				$msg = 'failed';
			}
			
			return $msg;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	
	public function GetProPurchaseDetailsByProId($intProductId)	
	{
		try
		{
			$strProPurchaseQuery = "SELECT pp.*, p.product_name, p.product_code, pc.product_category_name
			FROM tbl_product_purchase pp 
			LEFT JOIN tbl_product p ON p.product_id=pp.product_id
			LEFT JOIN tbl_product_category pc ON p.product_category_id=pc.product_category_id
			WHERE pp.product_id='".$intProductId."' ORDER BY date_of_purchase DESC";

			$this->dbConn = parent::BeginTransaction();
			$arrData = parent::executeQuery($strProPurchaseQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arrData;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}

	public function DelProductPurchaseData($intProductPurchaseId, $intProductId, $intProductQuantity)
	{
		try
		{
			
			$strProductQtyQuery = "SELECT product_id, total_product, total_sold, total_remaining FROM tbl_product WHERE product_id = '".$intProductId."' ";
			$this->dbConn = parent::BeginTransaction();
			$arrProductQtyData = parent::executeQuery($strProductQtyQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			
			if($intProductQuantity!='' && $intProductQuantity>0)
			{
				$totProduct = $arrProductQtyData[0]->TOTAL_PRODUCT - $intProductQuantity;
				$totRemProduct = $totProduct - $arrProductQtyData[0]->TOTAL_SOLD;
				
				//echo $arrProductQtyData[0]->TOTAL_PRODUCT.' -- '.$intQuantityPurchased.' -- '.$totProduct.' -- '.$arrProductQtyData[0]->TOTAL_SOLD.' -- '.$totRemProduct;
				$strUpdateProductQtyQuery = "UPDATE tbl_product SET total_product='".$totProduct."', total_remaining='".$totRemProduct."' WHERE product_id='".$intProductId."'";
				$this->dbConn = parent::BeginTransaction();
				$intUpProductId = parent::insert($strUpdateProductQtyQuery,false, $this->dbConn);
				parent::EndTransaction($this->dbConn);
				
				$strDelProPurchaseQuery = "DELETE FROM tbl_product_purchase WHERE product_purchase_id='".$intProductPurchaseId."' "; 
				
				$this->dbConn = parent::BeginTransaction();
				$result = parent::execute($strDelProPurchaseQuery, false, $this->dbConn);
				parent::EndTransaction($this->dbConn);
				
				$msg = 'success';
				return $msg;
			}
			else
			{
				$msg = 'failed';
				return $msg;
			}

		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}

	public function GetPurchaseFromDistictDataByProductId($intProductId)
	{
		try
		{
			$strProPurchaseQuery = "SELECT DISTINCT(purchased_from) FROM tbl_product_purchase WHERE product_id='".$intProductId."'";
			$this->dbConn = parent::BeginTransaction();
			$arrData = parent::executeQuery($strProPurchaseQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arrData;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}

	public function GetAlreadyApply($arrSrch)
	{
		try
		{
			$strQuery = "SELECT COUNT(*) AS TOTA_APPLY FROM tbl_candidate_applied_for_job
			 WHERE job_post_id='".$arrSrch['positionId']."' AND candidate_email='".$arrSrch['email']."'";
			$this->dbConn = parent::BeginTransaction();
			$arrData = parent::executeQuery($strQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arrData;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	public function DeleteMultiCandidate($arrDelData)
	{
		try
		{
			if($arrDelData['strAppliedId']!='')
			{
				$strQuery = "DELETE  FROM tbl_candidate_applied_for_job WHERE candidate_applied_job_id IN(".$arrDelData['strAppliedId'].")";
				$this->dbConn = parent::BeginTransaction();
				$intId = parent::execute($strQuery, false, $this->dbConn);
				parent::EndTransaction($this->dbConn);
			}
			return $intId;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae);
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
		
}
?>