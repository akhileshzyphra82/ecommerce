<?php
include 'pathDao.php';
require_once ("BaseDao.php");
require_once ($path."BL/CryptedPassword.inc.php");

class ProductDao extends BaseDao{

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
	
	public function InsertProductToCart($productArray)
		{
		//echo "<pre>";print_r($productArray);die;
	   	try{
	        $this->dbConn = parent::BeginTransaction();
			$strQuery1 = "INSERT INTO tbl_order (user_id, transaction_id, order_current_status, order_total_amt) VALUES (".$productArray['user_id'].",
			".$productArray['transection_id'].",'".$productArray['order_current_status']."','".$productArray['order_total_amt']."')"; 
			$orderId = parent::insert($strQuery1,false, $this->dbConn);
			
			$strQuery2 = "INSERT INTO `tbl_order_history` (`order_id` ,`order_status`) VALUES('".$orderId."','".$productArray['order_current_status']."')"; 
			$orderHistoryId = parent::insert($strQuery2,false, $this->dbConn);
			
			$strQuery3 = "INSERT INTO `tbl_add_cart` (quantity,product_id ,user_id,`product_code` ,`product_amt` ,`product_tax` ,`product_discount` ,`order_id` ) 
			VALUES('".$productArray['quantity']."','".$productArray['productId']."','".$productArray['user_id']."','".$productArray['product_code']."',
			'".$productArray['product_amt']."','".$productArray['product_tax']."','".$productArray['product_discount']."','".$orderId."')"; 
			$addCartId = parent::insert($strQuery3,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $addCartId;
			
		}catch(ADODB_Exception $ae){
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae );
		}catch(Exception $e){
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	public function InsertOtherProductToCart($productArray,$order_id)
		{
		//echo "<pre>";print_r($productArray);die;
	   	try{
	        $this->dbConn = parent::BeginTransaction();
			 $strQuery1 = "UPDATE tbl_order SET order_total_amt=order_total_amt+(".$productArray['quantity'].'*'.$productArray['order_total_amt']. ") WHERE  `order_id` ='".$order_id."' AND user_id ='".$productArray['user_id']."'";
			  $orderId = parent::insert($strQuery1,false, $this->dbConn);
			  $strQuery3 = "INSERT INTO `tbl_add_cart` (quantity,product_id ,user_id,`product_code` ,`product_amt` ,`product_tax` ,`product_discount` ,`order_id` ) VALUES('".$productArray['quantity']."','".$productArray['productId']."','".$productArray['user_id']."','".$productArray['product_code']."','".$productArray['product_amt']."','".$productArray['product_tax']."','".$productArray['product_discount']."','".$order_id."')"; 
			$addCartId = parent::insert($strQuery3,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $addCartId;
			
		}catch(ADODB_Exception $ae){
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae );
		}catch(Exception $e){
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	
	public function GetProductToCart($user_id)
	 {
	   try{
	        $this->dbConn = parent::BeginTransaction();
			  $strQuery = "SELECT AC.*,P.* ,pi.image_id,pi.image_ext,pi.image_for,O.* FROM tbl_add_cart AC
				LEFT JOIN tbl_product P ON AC.product_id = P.product_id 
				LEFT JOIN tbl_order O ON O.order_id = AC.order_id 
				LEFT JOIN tbl_product_img pi ON pi.product_id=P.product_id WHERE  AC.user_id ='".$user_id."'"; 
			$arrData = parent::executeQuery($strQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			
			return $arrData;
			
		}catch(ADODB_Exception $ae){
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae );
		}catch(Exception $e){
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	public function GetProductByUserId($user_id)
	 {
	   try{
	        $this->dbConn = parent::BeginTransaction();
			 $strQuery = "SELECT ac.* FROM tbl_add_cart ac 
			  LEFT JOIN tbl_order o on ac.user_id=o.user_id
			  WHERE  ac.user_id  ='".$user_id."'";
			$arrData = parent::executeQuery($strQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			
			return $arrData;
			
		}catch(ADODB_Exception $ae){
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae );
		}catch(Exception $e){
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	public function GetProductToCartById($product_id,$user_id)
	 {
	   try{
	        $this->dbConn = parent::BeginTransaction();
			  $strQuery = "SELECT * FROM tbl_add_cart WHERE product_id='".$product_id."' AND user_id ='".$user_id."' AND  quantity !=  0"; 
			$arrData = parent::executeQuery($strQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arrData;
			
		}catch(ADODB_Exception $ae){
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae );
		}catch(Exception $e){
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	public function countItemByUserId($user_id)
	 {
	   try{
	        $this->dbConn = parent::BeginTransaction();
			$whereClause="";
			if($user_id!="")
			$whereClause="WHERE AC.user_id='".$user_id."' AND O.order_current_status='Cart'";
			 $strQuery = "SELECT count(AC.quantity) as val FROM tbl_add_cart AC LEFT JOIN tbl_order O on O.order_id=AC.order_id ".$whereClause; 
			$arrData = parent::executeQuery($strQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arrData;
			
		}catch(ADODB_Exception $ae){
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae );
		}catch(Exception $e){
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	public function UpdateProductFromCart($quantity,$product_id,$user_id,$product_amt,$order_id)
	{
		try
		{
		
		 	$strQuery1 = "UPDATE tbl_add_cart SET quantity=quantity$quantity 1 WHERE  product_id='".$product_id."' AND user_id ='".$user_id."' AND order_id ='".$order_id."'";
			$strQuery2 = "UPDATE tbl_order SET order_total_amt=order_total_amt$quantity$product_amt WHERE  `order_id` ='".$order_id."' AND user_id ='".$user_id."'";
			 
			$this->dbConn = parent::BeginTransaction();
			if($quantity!=""){
			//echo "<pre>";print_r($strQuery1);die;
			$result = parent::insert($strQuery1, false, $this->dbConn);
			$result = parent::insert($strQuery2, false, $this->dbConn);
			}
			//else
			//$result = parent::execute($strQuery, false, $this->dbConn);
			
			parent::EndTransaction($this->dbConn);
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
	public function UpdateProductStatus($user_id,$status,$current_address,$billing_address)
	{
		try
		{
			$orderNumber = '';
			$this->dbConn = parent::BeginTransaction();
			
		    if($status=='Invoice Payment Pending' || $status=='Bank Transfer Payment Pending')
			{
				$strGetMaxOrderNoQuery = "SELECT MAX(order_number) AS MAX_ORDER_NUM FROM tbl_order WHERE YEAR(order_date)='".date('Y')."'";
				$arrGetMaxOrderNoData = parent::executeQuery($strGetMaxOrderNoQuery, false, $this->dbConn);
				
				if(count($arrGetMaxOrderNoData)>0)
				{
					if($arrGetMaxOrderNoData[0]->MAX_ORDER_NUM==NULL || $arrGetMaxOrderNoData[0]->MAX_ORDER_NUM==0 || $arrGetMaxOrderNoData[0]->MAX_ORDER_NUM=='')
					{
						$orderNumber = 1;
					}
					else
					{
						$orderNumber = $arrGetMaxOrderNoData[0]->MAX_ORDER_NUM+1;
					}
				
				}
			}
		
			$strQuery1 = "UPDATE tbl_order SET 
			order_current_status='".$status."', 
			user_address_id='".$current_address."',
			billing_user_address_id='".$billing_address."',
			order_number='".$orderNumber."', order_year='".date('Y')."'
			WHERE  user_id ='".$user_id."' AND  order_current_status='Cart'";
			
			$orderId = parent::execute($strQuery1, false, $this->dbConn);
						
			$strUpdateQuery = "SELECT * FROM tbl_order WHERE user_id ='".$user_id."' AND order_current_status='".$status."'";
			$arraData = parent::executeQuery($strUpdateQuery, false, $this->dbConn);
			
			//echo $orderId.'--------';	
			$strQuery2 = "INSERT INTO `tbl_order_history` (`order_id` ,`order_status`) VALUES('".$arraData[0]->ORDER_ID."','".$arraData[0]->ORDER_CURRENT_STATUS."')";
			$result = parent::insert($strQuery2, false, $this->dbConn);
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
	public function UpdateProductPaymentStatus($orderId,$item_transaction,$status)
	{
		try
		{ 
			$orderNumber='';
			$this->dbConn = parent::BeginTransaction();
		    
			$strOrderQuery="SELECT * FROM tbl_order WHERE order_id = '".$orderId."' ";  
			$arrOrderResult = parent::executeQuery($strOrderQuery, false, $this->dbConn);
			//echo "<pre>"; print_r($Orderresult); 

			if(count($arrOrderResult)>0)
			{
				if($arrOrderResult[0]->ORDER_CURRENT_STATUS=="Cart" || $arrOrderResult[0]->ORDER_CURRENT_STATUS=="Checkout")
				{
					if(($arrOrderResult[0]->ORDER_NUMBER=='' || $arrOrderResult[0]->ORDER_NUMBER==NULL || $arrOrderResult[0]->ORDER_NUMBER==0) && $status=='Payment Successful')
					{
						$strGetMaxOrderNoQuery = "SELECT MAX(order_number) AS MAX_ORDER_NUM FROM tbl_order WHERE YEAR(order_date)='".date('Y')."'";
						$arrGetMaxOrderNoData = parent::executeQuery($strGetMaxOrderNoQuery, false, $this->dbConn);
						
						if(count($arrGetMaxOrderNoData)>0)
						{
							if($arrGetMaxOrderNoData[0]->MAX_ORDER_NUM==NULL || $arrGetMaxOrderNoData[0]->MAX_ORDER_NUM==0 || $arrGetMaxOrderNoData[0]->MAX_ORDER_NUM=='')
							{
								$orderNumber = 1;
							}
							else
							{
								$orderNumber = $arrGetMaxOrderNoData[0]->MAX_ORDER_NUM+1;
							}
						
						}
					}
					
					$strQuery1 = "UPDATE tbl_order SET order_current_status='".$status."', pay_pal_tx_id='".$item_transaction."', 
					order_number='".$orderNumber."', order_year='".date('Y')."' WHERE  order_id ='".$orderId."'";
					
					$strQuery2 = "INSERT INTO `tbl_order_history` (`order_id` ,`order_status`) VALUES('".$orderId."','".$status."')";
					$result = parent::insert($strQuery2, false, $this->dbConn);
					$result = parent::insert($strQuery1, false, $this->dbConn);
				} 
			}
			
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
	public function DeleteProductFromCart($quantity,$product_id,$user_id,$product_amt,$order_id)
	
	{
		try
		{
			$this->dbConn = parent::BeginTransaction();
			$strQuery = "SELECT SUM(quantity) as val FROM tbl_add_cart WHERE  `product_id` ='".$product_id."' AND `order_id` ='".$order_id."' AND user_id ='".$user_id."'"; 
			$arrData = parent::executeQuery($strQuery,false, $this->dbConn);
			$strQuery1 = "DELETE FROM `tbl_add_cart`  WHERE  `product_id` ='".$product_id."' AND `order_id` ='".$order_id."' AND user_id ='".$user_id."'"; 
			$result = parent::execute($strQuery1, false, $this->dbConn);

			$strQuery2 = "UPDATE tbl_order SET order_total_amt=order_total_amt-".$arrData[0]->VAL.'*'.$product_amt." WHERE  `order_id` ='".$order_id."' AND user_id ='".$user_id."'";
			$result = parent::insert($strQuery2, false, $this->dbConn);
			$strOrderDetailQuery = "SELECT order_total_amt FROM tbl_order WHERE `order_id` ='".$order_id."' AND user_id ='".$user_id."'"; 
			$resultOrderDetail = parent::executeQuery($strOrderDetailQuery,false, $this->dbConn);
			if($resultOrderDetail[0]->ORDER_TOTAL_AMT=='0.00')
			{
				$strOrderDeleteQuery1 = "DELETE FROM `tbl_order`  WHERE `order_id` ='".$order_id."' AND user_id ='".$user_id."'"; 
				$result = parent::execute($strOrderDeleteQuery1, false, $this->dbConn);
			}
			 
			parent::EndTransaction($this->dbConn);
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
	public function GetOrderByUserId($user_id)
	 {
	   try{
	        $this->dbConn = parent::BeginTransaction();
			   $strQuery = "SELECT o.*,ua.*,oh.*,u.name,u.* FROM tbl_order o 
			  LEFT JOIN tbl_user_address ua ON  ua.user_address_id=o.user_address_id
			  LEFT JOIN tbl_order_history oh ON  oh.order_id=o.order_id
			  LEFT JOIN tbl_user u ON u.user_id=o.user_id
			  WHERE  o.user_id ='".$user_id."' ORDER BY o.order_id DESC "; 
			$arrData = parent::executeQuery($strQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arrData;
			
		}catch(ADODB_Exception $ae){
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae );
		}catch(Exception $e){
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	public function GetOrderDetailsByOrderId($OrderId)
	 {
	   try{
	        $this->dbConn = parent::BeginTransaction();
			 
			 $strQuery = "SELECT AC.*,P.* ,pi.image_id,pi.image_ext,pi.image_for,o.*,u.communication_email_id,u.name,address.*,history.*,country.country as country_name 
			  FROM tbl_add_cart AC
				LEFT JOIN tbl_product P ON AC.product_id = P.product_id 
				LEFT JOIN tbl_order o ON o.order_id = AC.order_id
				LEFT JOIN tbl_order_history history ON history.order_id = o.order_id
				LEFT JOIN tbl_user u ON u.user_id = o.user_id 
			  	LEFT JOIN tbl_user_address address on address.user_address_id=o.user_address_id
				LEFT JOIN tbl_country country ON country.country_id = address.country_id 
				LEFT JOIN tbl_product_img pi ON pi.product_id=P.product_id WHERE  AC.order_id ='".$OrderId."' Order BY  history.order_history_id DESC";
				
				//echo '<pre>'; print_r($strQuery);
			$arrData = parent::executeQuery($strQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arrData;
			
		}catch(ADODB_Exception $ae){
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae );
		}catch(Exception $e){
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	
	public function GetOrderDetails($orderId)
	 {
	   try{
	        $this->dbConn = parent::BeginTransaction();
			$strQuery = "SELECT o.*,u.*,ac.*,p.*,ua.* ,uab.user_address_id AS billing_user_address_id,uab.address AS billing_address,
			uab.city AS billing_city,uab.state AS billing_state,uab.zip AS billing_zip,uab.country AS billing_country
			,uab.landmark AS billing_landmark,uab.delivery_phone_no AS billing_delivery_phone_no,
			uab.mobile_country_code AS billing_mobile_country_code ,uab.user_name AS billing_user_name,uab.company_name AS billing_company_name,
			uab.eu_vat AS billing_eu_vat
			FROM tbl_order o 
			LEFT JOIN tbl_user u ON u.user_id=o.user_id 
			LEFT JOIN tbl_add_cart ac ON ac.order_id=o.order_id 
			LEFT JOIN tbl_product p ON p.product_id=ac.product_id 
			LEFT JOIN tbl_user_address ua ON o.user_address_id=ua.user_address_id
			LEFT JOIN tbl_user_address uab ON o.billing_user_address_id=uab.user_address_id
			WHERE o.order_id ='".$orderId."'"; 
			$arrData = parent::executeQuery($strQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arrData;
			
		}catch(ADODB_Exception $ae){
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae );
		}catch(Exception $e){
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	
	public function GetTotalOrderByUserId($user_id)
	 {
	   try{
	        $this->dbConn = parent::BeginTransaction();
			   $strQuery = "SELECT o.*,ua.*,u.name,u.* FROM tbl_order o 
			  LEFT JOIN tbl_user_address ua ON  ua.user_address_id=o.user_address_id
			  LEFT JOIN tbl_user u ON u.user_id=o.user_id
			  WHERE  o.user_id ='".$user_id."' and o.order_current_status IN ('Payment Successful','Payment Failed','Dispatched','Delivered') ORDER BY o.order_id DESC "; 
			$arrData = parent::executeQuery($strQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arrData;
			
		}catch(ADODB_Exception $ae){
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae );
		}catch(Exception $e){
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	
	public function GetProductHistoryByHistoryId($ORDER_ID)
	 {
	   try{
	        $this->dbConn = parent::BeginTransaction();
			  $strQuery = "SELECT * FROM tbl_order_history   WHERE order_id ='".$ORDER_ID."' ORDER BY order_history_id DESC"; 
			$arrData = parent::executeQuery($strQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arrData;
			
		}catch(ADODB_Exception $ae){
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae );
		}catch(Exception $e){
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	
	public function GetTotalOrderByUserIdPaging($user_id,$start_from,$limit)
	 {
	   try{
	        $this->dbConn = parent::BeginTransaction();
			$strQuery = "SELECT o.*,ua.*,u.name,u.*,country.country as country_name FROM tbl_order o 
			LEFT JOIN tbl_user_address ua ON  ua.user_address_id=o.user_address_id
			LEFT JOIN tbl_country country ON country.country_id = ua.country_id 
			LEFT JOIN tbl_user u ON u.user_id=o.user_id
			WHERE  o.user_id ='".$user_id."' and o.order_current_status IN ('Payment Successful','Payment Failed','Dispatched','Delivered') 
			ORDER BY o.order_id DESC LIMIT $start_from,$limit"; 
			$arrData = parent::executeQuery($strQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arrData;
			
		}catch(ADODB_Exception $ae){
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae );
		}catch(Exception $e){
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	
	public function getShipingAmt()
	 {
	   try{
	        $this->dbConn = parent::BeginTransaction();
			  $strQuery = "SELECT * FROM tbl_shiping_amt"; 
			$arrData = parent::executeQuery($strQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arrData;
			
		}catch(ADODB_Exception $ae){
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae );
		}catch(Exception $e){
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
	
		public function UpdateProductShipingAmt($user_id,$shiping_amt,$vat_amt)
	{
		try
		{
			$strQuery1 = "UPDATE tbl_order SET shiping_amt='".$shiping_amt."',tax_total_amount='".$vat_amt."' WHERE  user_id ='".$user_id."' AND  order_current_status='Cart'";
						$this->dbConn = parent::BeginTransaction();
						$result = parent::insert($strQuery1, false, $this->dbConn);
						parent::EndTransaction($this->dbConn);
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

	public function GetProductsNameIdByProductCategoryID($productCategoryId)
	{
		try
		{	
			$strSubCatByCat = "select product_category_id from tbl_product_category where parent_category_id = $productCategoryId";
		
			$this->dbConn = parent::BeginTransaction();
			$arraCatData = parent::executeQuery($strSubCatByCat, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			
			$subCategoryIds = '';
			if(count($arraCatData)>0)
			{
				foreach($arraCatData as $data)
				{
					$subCategoryIds = $subCategoryIds.','.$data->PRODUCT_CATEGORY_ID;
				}
				$subCategoryIds = ltrim($subCategoryIds,',');
			}

			$strSubSubCatByCat = "select product_category_id from tbl_product_category where parent_category_id IN (".$subCategoryIds.")";

			$this->dbConn = parent::BeginTransaction();
			$arraCatSubData = parent::executeQuery($strSubSubCatByCat, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);

			if(count($arraCatSubData)>0)
			{
				foreach($arraCatSubData as $data)
				{
					$subCategoryIds = $subCategoryIds.','.$data->PRODUCT_CATEGORY_ID;
				}
				$subCategoryIds = ltrim($subCategoryIds,',');
			}
			
			
			if($productCategoryId != "")
			$strQuery = "SELECT product_id, product_category_id, product_name, product_amt FROM tbl_product WHERE  product_category_id IN (".$subCategoryIds.") ORDER BY 
			product_category_id, product_name";

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

	public function getProductByDesId($productDesId)
	{
	  	try
	 	{	
			$whereClause="";
			if($productDesId !="")
			$whereClause="WHERE  p.product_id IN ($productDesId)";	
			$strQuery = "SELECT p.* FROM tbl_product p
		   ".$whereClause;
	
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
    
	//This is getting the enquiry list only         
	public function GetProductsEnquiryList($flag,$OrderStatus, $limit, $maxRecord)
	{
		try
	   	{
			$limitClause='';
			if($limit!='')
				$limitClause = " LIMIT $limit,$maxRecord ";
	   
			if($flag=="count")
			{
				$selectCondition=" COUNT(*) AS TOTAL ";
			}	
			else
			{
				$selectCondition=" * ";
			}
	   
			$strStatusWhereClause="";
			if($OrderStatus!='')
				$strStatusWhereClause=" AND enquiry_status = '".$OrderStatus."' ";
				
			$strQuery = "SELECT ".$selectCondition."
			FROM tbl_enquiry_quote 
			WHERE enquiry_quote_id>0 ".$strStatusWhereClause."
			ORDER BY enquiry_quote_id DESC ".$limitClause;
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

	public function GetEnquiryProductList($intEnquiryId)
	{
		try
	   	{			
			$strQuery = "SELECT eqp.*, p.product_name, pc.product_category_name
			FROM tbl_enquiry_quote_product eqp
			LEFT JOIN tbl_product p ON p.product_id=eqp.product_id
			LEFT JOIN tbl_product_category pc ON pc.product_category_id=eqp.product_category_id
			WHERE eqp.enquiry_quote_id=".$intEnquiryId." ";
			
			$this->dbConn = parent::BeginTransaction();
			$arrData = parent::executeQuery($strQuery, false, $this->dbConn);
			//echo $intNewsEventId;die;
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

	public function GetAndDisplayAllList($ProductId)
	{
		try
		{
			$whereClause="";
			if($ProductId !="")
			$whereClause=" WHERE enquiry_quote_id ='".$ProductId."' order by enquiry_quote_id";	
			 $strQuery = "SELECT * FROM tbl_enquiry_quote ".$whereClause;

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

   public function DeleteEnquiryQuoteById($enquiryId)
   {
	  	try
		{
			$strQuery = "DELETE FROM tbl_enquiry_quote WHERE enquiry_quote_id='".$enquiryId."'";
			$this->dbConn = parent::BeginTransaction();
			$enquiryReId = parent::execute($strQuery, false, $this->dbConn);
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

   public function GetEnquiryDetailsById($enquiryId,$flag)
   {
	  	try
		{
			$arrData=array();
			$strEnquiryQuery = "SELECT eq.*, u.name AS u_user_name, u.erp_password, ua.address AS ua_address, ua.city AS ua_city, ua.state AS ua_state, ua.zip AS ua_zip, 
			ua.landmark AS ua_landmark, cou.country AS ua_country, ua.delivery_phone_no AS ua_delivery_phone_no, ua.mobile_country_code AS ua_mobile_country_code, 
			ua.user_name AS ua_user_name, ua.company_name AS ua_company_name, ua.country_id AS ua_country_id, ua.eu_vat AS ua_eu_vat, o.order_id AS o_order_id, 
			o.user_id AS o_user_id, o.transaction_id AS o_transaction_id, o.order_date AS o_order_date, 
			o.order_current_status AS o_order_current_status, o.order_total_amt AS o_order_total_amt, o.shiping_amt AS o_shiping_amt, 
			o.tax_total_amount AS o_tax_total_amount, o.user_address_id AS o_user_address_id, o.pay_pal_tx_id AS o_pay_pal_tx_id
			FROM tbl_enquiry_quote eq
			LEFT JOIN tbl_user u ON eq.user_id = u.user_id 
			LEFT JOIN tbl_user_address ua ON eq.user_address_id = ua.user_address_id
			LEFT JOIN tbl_country cou ON ua.country_id = cou.country_id
			LEFT JOIN tbl_order o ON o.order_id = eq.order_id
			WHERE eq.enquiry_quote_id = '".$enquiryId."' ";

			$this->dbConn = parent::BeginTransaction();
			$arrEnquiryData = parent::executeQuery($strEnquiryQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);

			$strEnquiryProductQuery = "SELECT eqp.*, p.product_name, p.product_code, pc.product_category_name
			FROM tbl_enquiry_quote_product eqp
			LEFT JOIN tbl_product p ON p.product_id=eqp.product_id
			LEFT JOIN tbl_product_category pc ON pc.product_category_id=eqp.product_category_id
			WHERE eqp.enquiry_quote_id=".$enquiryId." ";

			$this->dbConn = parent::BeginTransaction();
			$arrEnquiryProductData = parent::executeQuery($strEnquiryProductQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			
			//echo '<pre>'; print_r($arrEnquiryData);
			//echo '<pre>'; print_r($arrEnquiryProductData); die;
			if(count($arrEnquiryData)>0 && count($arrEnquiryProductData)>0)
			{
				$password = $arrEnquiryData[0]->ERP_PASSWORD;
				$decriptPassword = $this->Decrypt($password);
				
				if($flag=='GenerateOrder' && $arrEnquiryData[0]->ORDER_ID==0 && $arrEnquiryData[0]->O_ORDER_ID==NULL && $arrEnquiryData[0]->USER_ID!=0)
				{
				
					$strGetMaxOrderNoQuery = "SELECT MAX(order_number) AS MAX_ORDER_NUM FROM tbl_order WHERE YEAR(order_date)='".date('Y')."'";
					$this->dbConn = parent::BeginTransaction();
					$arrGetMaxOrderNoData = parent::executeQuery($strGetMaxOrderNoQuery, false, $this->dbConn);
					parent::EndTransaction($this->dbConn);
					
					if(count($arrGetMaxOrderNoData)>0)
					{
						if($arrGetMaxOrderNoData[0]->MAX_ORDER_NUM==NULL || $arrGetMaxOrderNoData[0]->MAX_ORDER_NUM==0 || $arrGetMaxOrderNoData[0]->MAX_ORDER_NUM=='')
						{
							$orderNumber = 1;
						}
						else
						{
							$orderNumber = $arrGetMaxOrderNoData[0]->MAX_ORDER_NUM+1;
						}
					
					}
					
					$strMaxTransIdQuery = "SELECT MAX(transaction_id) AS max_transaction_id FROM tbl_order";
		
					$this->dbConn = parent::BeginTransaction();
					$arrMaxTransIdData = parent::executeQuery($strMaxTransIdQuery, false, $this->dbConn);
					parent::EndTransaction($this->dbConn);
					
					$transaction_id = $arrMaxTransIdData[0]->MAX_TRANSACTION_ID+1;
					
					$strOrderQuery = "INSERT INTO tbl_order (user_id, transaction_id, order_current_status, order_total_amt, shiping_amt, tax_total_amount, user_address_id, 
					customer_order_no, customer_supplier_no, order_number, order_year) 
					VALUES ('".$arrEnquiryData[0]->USER_ID."','".$transaction_id."','Invoice Payment Pending','".$arrEnquiryData[0]->ENQUIRY_TOTAL_AMT."',
					'".$arrEnquiryData[0]->ENQUIRY_SHIPPING_AMT."','".$arrEnquiryData[0]->ENQUIRY_VAT_AMT."','".$arrEnquiryData[0]->USER_ADDRESS_ID."',
					'".$arrEnquiryData[0]->CUSTOMER_ORDER_NO."','".$arrEnquiryData[0]->CUSTOMER_SUPPLIER_NO."','".$orderNumber."','".date('Y')."')";
					
					$this->dbConn = parent::BeginTransaction();
					$orderId = parent::insert($strOrderQuery,false, $this->dbConn);
					parent::EndTransaction($this->dbConn);
					
					$strOrderHistoryQuery = "INSERT INTO tbl_order_history (order_id, order_status) VALUES('".$orderId."','Invoice Payment Pending')";
					
					$this->dbConn = parent::BeginTransaction();
					$orderHistoryId = parent::insert($strOrderHistoryQuery,false, $this->dbConn);
					parent::EndTransaction($this->dbConn);
					
					foreach($arrEnquiryProductData as $arrEnquiryProductVal)
					{
						$strQuery3 = "INSERT INTO tbl_add_cart (quantity, product_id, user_id, product_code, product_amt, order_id) 
						VALUES('".$arrEnquiryProductVal->PRODUCT_QUANTITY."','".$arrEnquiryProductVal->PRODUCT_ID."','".$arrEnquiryData[0]->USER_ID."',
						'".$arrEnquiryProductVal->PRODUCT_CODE."','".$arrEnquiryProductVal->PRODUCT_AMT."','".$orderId."')"; 
						
						$this->dbConn = parent::BeginTransaction();
						$addCartId = parent::insert($strQuery3,false, $this->dbConn);
						parent::EndTransaction($this->dbConn);
					}

					$strUpEnqStatusOrderQuery = "UPDATE tbl_enquiry_quote SET enquiry_status='Order Generated', order_id='".$orderId."' 
					WHERE enquiry_quote_id='".$enquiryId."' AND enquiry_status='Quotation Sent'";
		
					$this->dbConn = parent::BeginTransaction();
					$enquiryStaOrdId = parent::insert($strUpEnqStatusOrderQuery,false, $this->dbConn);
					parent::EndTransaction($this->dbConn);
					
					$strEnquiryQuery = "SELECT eq.*, u.name AS u_user_name, ua.address AS ua_address, ua.city AS ua_city, ua.state AS ua_state, ua.zip AS ua_zip, 
					ua.landmark AS ua_landmark, cou.country AS ua_country, ua.delivery_phone_no AS ua_delivery_phone_no, ua.mobile_country_code AS ua_mobile_country_code, 
					ua.user_name AS ua_user_name, ua.company_name AS ua_company_name, ua.country_id AS ua_country_id, ua.eu_vat AS ua_eu_vat, o.order_id AS o_order_id, 
					o.user_id AS o_user_id, o.transaction_id AS o_transaction_id, o.order_date AS o_order_date, 
					o.order_current_status AS o_order_current_status, o.order_total_amt AS o_order_total_amt, o.shiping_amt AS o_shiping_amt, 
					o.tax_total_amount AS o_tax_total_amount, o.user_address_id AS o_user_address_id, o.pay_pal_tx_id AS o_pay_pal_tx_id, o.order_number, o.order_year
					FROM tbl_enquiry_quote eq
					LEFT JOIN tbl_user u ON eq.user_id = u.user_id 
					LEFT JOIN tbl_user_address ua ON eq.user_address_id = ua.user_address_id
					LEFT JOIN tbl_country cou ON ua.country_id = cou.country_id
					LEFT JOIN tbl_order o ON o.order_id = eq.order_id
					WHERE eq.enquiry_quote_id = '".$enquiryId."' ";
		
					$this->dbConn = parent::BeginTransaction();
					$arrEnquiryData = parent::executeQuery($strEnquiryQuery, false, $this->dbConn);
					parent::EndTransaction($this->dbConn);
				}
				else
				{
					if($arrEnquiryData[0]->O_ORDER_ID!='' && $flag=='GenerateOrder')
					{
						$strUpEnqStatusOrderQuery = "UPDATE tbl_enquiry_quote SET enquiry_status='Order Generated', order_id='".$arrEnquiryData[0]->O_ORDER_ID."' 
						WHERE enquiry_quote_id='".$enquiryId."' AND enquiry_status='Quotation Sent'";
			
						$this->dbConn = parent::BeginTransaction();
						$enquiryStaOrdId = parent::insert($strUpEnqStatusOrderQuery,false, $this->dbConn);
						parent::EndTransaction($this->dbConn);				
					}
					
					$strEnquiryQuery = "SELECT eq.*, u.name AS u_user_name, ua.address AS ua_address, ua.city AS ua_city, ua.state AS ua_state, ua.zip AS ua_zip, 
					ua.landmark AS ua_landmark, cou.country AS ua_country, ua.delivery_phone_no AS ua_delivery_phone_no, ua.mobile_country_code AS ua_mobile_country_code, 
					ua.user_name AS ua_user_name, ua.company_name AS ua_company_name, ua.country_id AS ua_country_id, ua.eu_vat AS ua_eu_vat, o.order_id AS o_order_id, 
					o.user_id AS o_user_id, o.transaction_id AS o_transaction_id, o.order_date AS o_order_date, 
					o.order_current_status AS o_order_current_status, o.order_total_amt AS o_order_total_amt, o.shiping_amt AS o_shiping_amt, 
					o.tax_total_amount AS o_tax_total_amount, o.user_address_id AS o_user_address_id, o.pay_pal_tx_id AS o_pay_pal_tx_id, o.order_number, o.order_year
					FROM tbl_enquiry_quote eq
					LEFT JOIN tbl_user u ON eq.user_id = u.user_id 
					LEFT JOIN tbl_user_address ua ON eq.user_address_id = ua.user_address_id
					LEFT JOIN tbl_country cou ON ua.country_id = cou.country_id
					LEFT JOIN tbl_order o ON o.order_id = eq.order_id
					WHERE eq.enquiry_quote_id = '".$enquiryId."' ";
		
					$this->dbConn = parent::BeginTransaction();
					$arrEnquiryData = parent::executeQuery($strEnquiryQuery, false, $this->dbConn);
					parent::EndTransaction($this->dbConn);
				}
			}
			
			$arrData=array($arrEnquiryData, $arrEnquiryProductData, $decriptPassword);			
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

   public function UpdateEnquiryStatusById($enquiryId, $enquiryChangedStatus, $enquiryCurrentStatus)
   {
	  	try
		{
			$strQuery = "UPDATE tbl_enquiry_quote SET enquiry_status='".$enquiryChangedStatus."' WHERE enquiry_quote_id='".$enquiryId."' 
			AND enquiry_status='".$enquiryCurrentStatus."'";

			$this->dbConn = parent::BeginTransaction();
			$enquiryReId = parent::insert($strQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			
			//echo $enquiryReId; die;

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

	public function GetOrderAmountByUserIdStatusOrderId($user_id,$status,$OrderId)
	 {
	   try{
			$whereClause = "";
			if($OrderId!='')
			$whereClause = "  AND o.order_id = '".$OrderId."'";
			$this->dbConn = parent::BeginTransaction();
			$strQuery = "SELECT o.*  FROM  tbl_add_cart ac
			  LEFT JOIN tbl_order o on ac.order_id=o.order_id
			WHERE o.order_total_amt>0 AND  o.user_id  ='".$user_id."' AND o.order_current_status = '".$status."'".$whereClause;
			$arrData = parent::executeQuery($strQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			
			return $arrData;
			
		}catch(ADODB_Exception $ae){
			parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae );
		}catch(Exception $e){
			parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}

	public function GetUserAddressVatInfo($userAddressId)
	 {
	   try{
			
			$this->dbConn = parent::BeginTransaction();
			$strQuery = "SELECT eu_vat FROM tbl_user_address WHERE user_address_id= '".$userAddressId."'";
			$arrData = parent::executeQuery($strQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			
			return $arrData;
			
		}catch(ADODB_Exception $ae){
			parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae );
		}catch(Exception $e){
			parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
    
	/*
	public function InsertProductOrderDeta($arrUserDetails,$arrProductOrderDetails,$arrProductOrderCartDetails)
	{
		try
		{
		
			$strUserQuery = "SELECT user_id, erp_password FROM tbl_user WHERE communication_email_id LIKE '%".$arrUserDetails['user_email']."%'";

			$this->dbConn = parent::BeginTransaction();
			$arrUserData = parent::executeQuery($strUserQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);

			if(count($arrUserData)>0)
			{
				$userId=$arrUserData[0]->USER_ID;
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
				}
			}
			else
			{
				$randomKeyward=rand(100,10000);
				$decriptPassword=rand(100,1000000000);
				$encrypePassword = $this->Encrypt($decriptPassword);

				$strInsertQuery="INSERT INTO tbl_user (user_type_id, name, communication_phone_num_isd, communication_phone_num, communication_mobile_num_isd,
 				communication_mobile_num, communication_email_id, erp_password, company_name, designation, account_activation_flag, random_activation_key) 
				VALUES('2','".$arrUserDetails['user_name']."','".$arrUserDetails['phone_country_code']."','".$arrUserDetails['user_phone']."'
				,'".$arrUserDetails['phone_country_code']."','".$arrUserDetails['user_phone']."','".$arrUserDetails['user_email']."','".$encrypePassword."'
				,'".$arrUserDetails['company_name']."','','1','".$randomKeyward."')";
			
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

					$this->dbConn = parent::BeginTransaction();
					$userAddressId = parent::insert($strUserAddQuery,false,$this->dbConn);
					parent::EndTransaction($this->dbConn);
				}
			}
	   			
			if(count($arrProductOrderDetails)>0)
			{
				$strQueryOrder="INSERT INTO tbl_order (
				user_id,
				order_date,
				order_current_status,
				order_total_amt,
				tax_total_amount,
				user_address_id,
				dispatch_courier_company,
				dispatch_courier_tracking_id,
				dispatch_courier_tracking_url,
				customer_order_no,
				customer_supplier_no,
				order_number,
				order_year)
				VALUES (
				'".$userId."',
				'".$arrProductOrderDetails['order_date']."',
				'".$arrProductOrderDetails['order_current_status']."',
				'".$arrProductOrderDetails['order_total_amt']."',
				'".$arrProductOrderDetails['tax_total_amount']."',
				'".$userAddressId."',
				'".$arrProductOrderDetails['dispatch_courier_company']."',
				'".$arrProductOrderDetails['dispatch_courier_tracking_id']."',
				'".$arrProductOrderDetails['dispatch_courier_tracking_url']."',
				'".$arrProductOrderDetails['customer_order_no']."',
				'".$arrProductOrderDetails['customer_supplier_no']."',
				'".$arrProductOrderDetails['order_number']."',
				'".$arrProductOrderDetails['order_year']."')";
				$this->dbConn = parent::BeginTransaction();
				$orderId=parent::insert($strQueryOrder,false,$this->dbConn);
				parent::EndTransaction($this->dbConn);
				
				if($orderId!='')
				{
					$strQueryOrderHistory="INSERT INTO tbl_order_history (order_id,
					order_status,
					order_status_date) 
					VALUES (
					'".$orderId."',
					'".$arrProductOrderDetails['order_current_status']."',
					'".$arrProductOrderDetails['order_date']."'
					)";
					
					$this->dbConn = parent::BeginTransaction();
					$orderHistoryId=parent::insert($strQueryOrderHistory,false,$this->dbConn);
					parent::EndTransaction($this->dbConn);	
					
					
					if(count($arrProductOrderCartDetails)>0)
					{
						foreach($arrProductOrderCartDetails as $val)
						{
							$strQueryOrderAddCart="INSERT INTO tbl_add_cart (
							quantity,
							product_id,
							user_id,
							date,
							product_code,
							product_amt,
							product_tax,
							product_discount,
							order_id) VALUES
							('".$val['productQuantity']."',
							'".$val['proudctId']."',
							'".$userId."',
							'".$val['order_date']."',
							'".$val['product_code']."',
							'".$val['product_amt']."',
							'".$val['product_tax']."',
							'".$val['product_discount']."',
							'".$orderId."')";
							
							$this->dbConn = parent::BeginTransaction();
							$addKartId=parent::insert($strQueryOrderAddCart,false,$this->dbConn);
							parent::EndTransaction($this->dbConn);
							
							if($arrProductOrderDetails['order_current_status']=='Other Channel Sell Successful')
							{
								$strProductQtyQuery = "SELECT product_id, total_product, total_sold, total_remaining, product_threshold, product_name FROM tbl_product 
								WHERE product_id = '".$val['proudctId']."' ";
								$this->dbConn = parent::BeginTransaction();
								$arrProductQtyData = parent::executeQuery($strProductQtyQuery, false, $this->dbConn);
								parent::EndTransaction($this->dbConn);
								
								$totProduct = $arrProductQtyData[0]->TOTAL_PRODUCT;
								$totProductSold = $arrProductQtyData[0]->TOTAL_SOLD + $val['productQuantity'];
								$totRemProduct = $totProduct - $totProductSold;
								
								//echo $arrProductQtyData[0]->TOTAL_PRODUCT.' -- '.$intQuantityPurchased.' -- '.$totProduct.' -- '.$arrProductQtyData[0]->TOTAL_SOLD.' -- '.$totRemProduct;
		
								$strUpdateProductQtyQuery = "UPDATE tbl_product SET total_product='".$totProduct."', total_remaining='".$totRemProduct."', 
								total_sold='".$totProductSold."' WHERE product_id='".$val['proudctId']."'";
		
								$this->dbConn = parent::BeginTransaction();
								$intUpProductId = parent::insert($strUpdateProductQtyQuery,false, $this->dbConn);
								parent::EndTransaction($this->dbConn);
								
								if($arrProductQtyData[0]->PRODUCT_THRESHOLD >= $totRemProduct)
								{
									include "../smtpmail/classes/class.phpmailer.php"; 
									
									$host = "box5213.bluehost.com";
									$userName = "web@sinelec-tech.com";
									$password = "{Ge-[]sE(wq,";
									$fromname = "alert@sinelec-tech.com";
									$from = 'alert@sinelec-tech.com';
									$email = 'sales@sinelec-tech.com';
									$mail = new PHPMailer(); // create a new object
									$mail->IsSMTP(); // enable SMTP
									$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
									$mail->SMTPAuth = true; // authentication enabled
									$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
									$mail->Host = $host;
									$mail->Port = 465; // 465 or 587
									$mail->IsHTML(true);
									$mail->Username = $userName;
									$mail->Password = $password;
									$mail->FromName = $fromname;
									$mail->From = $from;         
									$subject = 'Alert: Threshold of product '.$arrProductQtyData[0]->PRODUCT_NAME.' has fallen';    
									$mail->Subject = $subject;
									
									$messageBody = 'Product: '.$arrProductQtyData[0]->PRODUCT_NAME.'<br>'.
									'Threshold Value: '.$arrProductQtyData[0]->PRODUCT_THRESHOLD.'<br>'.
									'Remaining Product: '.$totRemProduct;
									
									$mail->Body = $messageBody;
				
									$mail->AddAddress($email); //send to mail id
									//echo "<pre>";print_r($mail);
									if (!$mail->Send()) 
									{
										//header("Location:ViewOrderDetailsNew.php?urlstring=".EncryptURL("action=&msg=update&OrderNo=".$order_id));
									} 

								}
							}

						}
					}
								
				}
			}
			return $orderId;
			
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
	*/
	
	public function GetDirectSellProductDetails()
	 {
	   try{
			
			$this->dbConn = parent::BeginTransaction();
			$strQuery = "SELECT o.*,ac.*,p.product_name,p.product_code
			FROM tbl_order o
			LEFT JOIN tbl_add_cart ac ON ac.order_id=o.order_id
			LEFT JOIN tbl_product p ON p.product_id=ac.product_id
			WHERE order_current_status='Other Channel Sell Successful'";
			$arrData = parent::executeQuery($strQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			
			$arrReturnData=array();
			
			
			if(count($arrData)>0)
			{
				foreach($arrData as $val)
				{
				
					$orderDetails=$val->ORDER_DATE.'@@'.$val->ORDER_ID.'@@'.$val->ORDER_TOTAL_AMT.'@@'.$val->ORDER_NUMBER.'@@'.$val->ORDER_YEAR;
					$arrReturnData[$orderDetails][]=$val;
				
				}
			
			}			
			//echo "<pre>"; print_r($arrReturnData); die;
			return $arrReturnData;
			
		}catch(ADODB_Exception $ae){
			parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae );
		}catch(Exception $e){
			parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
    public function DeleteDirectSellProductByOrderId($intOrderId)
	 {
	   try
	   {
			
			$strOrderProductFromCartQuery = "SELECT quantity, product_id, order_id FROM tbl_add_cart WHERE order_id='".$intOrderId."' ";
			$this->dbConn = parent::BeginTransaction();
			$arrOrderProductFromCartData = parent::executeQuery($strOrderProductFromCartQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			
			if(count($arrOrderProductFromCartData)>0)
			{
				foreach($arrOrderProductFromCartData as $arrOrderProductFromCartVal)
				{
					$strProductQtyQuery = "SELECT product_id, total_product, total_sold, total_remaining FROM tbl_product 
					WHERE product_id = '".$arrOrderProductFromCartVal->PRODUCT_ID."' ";
					$this->dbConn = parent::BeginTransaction();
					$arrProductQtyData = parent::executeQuery($strProductQtyQuery, false, $this->dbConn);
					parent::EndTransaction($this->dbConn);
					
					$totProduct = $arrProductQtyData[0]->TOTAL_PRODUCT;
					$totProductSold = $arrProductQtyData[0]->TOTAL_SOLD - $arrOrderProductFromCartVal->QUANTITY;
					$totRemProduct = $totProduct - $totProductSold;
					
					//echo $arrProductQtyData[0]->TOTAL_PRODUCT.' -- '.$intQuantityPurchased.' -- '.$totProduct.' -- '.$arrProductQtyData[0]->TOTAL_SOLD.' -- '.$totRemProduct;
		
					$strUpdateProductQtyQuery = "UPDATE tbl_product SET total_product='".$totProduct."', total_remaining='".$totRemProduct."', 
					total_sold='".$totProductSold."' WHERE product_id='".$arrOrderProductFromCartVal->PRODUCT_ID."'";
		
					$this->dbConn = parent::BeginTransaction();
					$intUpProductId = parent::insert($strUpdateProductQtyQuery,false, $this->dbConn);
					parent::EndTransaction($this->dbConn);
				}	

			}
			
			$strQuery = "DELETE FROM tbl_order WHERE order_id='".$intOrderId."'";
			$this->dbConn = parent::BeginTransaction();
			$intId = parent::execute($strQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $intId;
			
		}catch(ADODB_Exception $ae){
			parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae );
		}catch(Exception $e){
			parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
    public function UpdateEnquiryOrderDetails($enquiryId, $intSupplierNo, $intOrderNo)
	 {
	   try
	   {
			
			$strQuery = "UPDATE tbl_enquiry_quote SET customer_order_no='".$intOrderNo."',customer_supplier_no='".$intSupplierNo."'
			WHERE enquiry_quote_id='".$enquiryId."'";
			$this->dbConn = parent::BeginTransaction();
			$arrData = parent::insert($strQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			
			return $arrData;
			
		}catch(ADODB_Exception $ae){
			parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back".$ae );
		}catch(Exception $e){
			parent::RollBackTransaction($this->dbConn);
			throw $e;
		}
	}
}
?>