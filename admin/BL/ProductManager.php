<?php
include 'pathManager.php';
require_once ($path."Dao/ProductDao.php");
require_once ("CryptedPassword.inc.php");
require_once ("BaseManager.php");

class ProductManager extends BaseManager
{
	public function InsertProductToCart($productArray)
	{
		try{
			$objProductDao = new ProductDao();
 			return $objProductDao->InsertProductToCart($productArray);
		}catch (Exception $e){
			throw $e;
		}
	}
		public function GetProductToCart($user_id)
	{
		try{
			$objProductDao = new ProductDao();
 			return $objProductDao->GetProductToCart($user_id);
		}catch (Exception $e){
			throw $e;
		}
	}
	public function GetProductByUserId($user_id)
	{
		try{
			$objProductDao = new ProductDao();
 			return $objProductDao->GetProductByUserId($user_id);
		}catch (Exception $e){
			throw $e;
		}
	}
	public function UpdateProductFromCart($quantity,$product_id,$user_id,$product_amt,$order_id)
	{
		try{
			$objProductDao = new ProductDao();
 			return $objProductDao->UpdateProductFromCart($quantity,$product_id,$user_id,$product_amt,$order_id);
		}catch (Exception $e){
			throw $e;
		}
	}
	public function GetProductToCartById($product_id,$user_id)
	{
		try{
			$objProductDao = new ProductDao();
 			return $objProductDao->GetProductToCartById($product_id,$user_id);
		}catch (Exception $e){
			throw $e;
		}
	}
	public function countItemByUserId($user_id)
	{
		try{
			$objProductDao = new ProductDao();
 			return $objProductDao->countItemByUserId($user_id);
		}catch (Exception $e){
			throw $e;
		}
	}
	public function UpdateProductStatus($user_id,$status,$current_address,$billing_address)
	{
		try{
			$objProductDao = new ProductDao();
 			return $objProductDao->UpdateProductStatus($user_id,$status,$current_address,$billing_address);
		}catch (Exception $e){
			throw $e;
		}
	}
	public function InsertOtherProductToCart($productArray,$order_id)
	{
		try{
			$objProductDao = new ProductDao();
 			return $objProductDao->InsertOtherProductToCart($productArray,$order_id);
		}catch (Exception $e){
			throw $e;
		}
	}
	public function DeleteProductFromCart($quantity,$product_id,$user_id,$product_amt,$order_id)
	{
		try{
			$objProductDao = new ProductDao();
 			return $objProductDao->DeleteProductFromCart($quantity,$product_id,$user_id,$product_amt,$order_id);
		}catch (Exception $e){
			throw $e;
		}
	}
	public function GetOrderByUserId($user_id)
	{
		try{
			$objProductDao = new ProductDao();
 			return $objProductDao->GetOrderByUserId($user_id);
		}catch (Exception $e){
			throw $e;
		}
	}
	public function GetOrderDetailsByOrderId($OrderId)
	{
		try{
			$objProductDao = new ProductDao();
 			return $objProductDao->GetOrderDetailsByOrderId($OrderId);
		}catch (Exception $e){
			throw $e;
		}
	}
	public function UpdateProductPaymentStatus($orderId,$item_transaction,$status)
	{
		try{
			$objProductDao = new ProductDao();
 			return $objProductDao->UpdateProductPaymentStatus($orderId,$item_transaction,$status);
		}catch (Exception $e){
			throw $e;
		}
	}
	
	public function GetOrderDetails($orderId)
	{
		try{
			$objProductDao = new ProductDao();
 			return $objProductDao->GetOrderDetails($orderId);
		}catch (Exception $e){
			throw $e;
		}
	}
	
	public function GetTotalOrderByUserId($user_id)
	{
		try{
			$objProductDao = new ProductDao();
 			return $objProductDao->GetTotalOrderByUserId($user_id);
		}catch (Exception $e){
			throw $e;
		}
	}
	
	public function GetProductHistoryByHistoryId($ORDER_ID)
	{
		try{
			$objProductDao = new ProductDao();
 			return $objProductDao->GetProductHistoryByHistoryId($ORDER_ID);
		}catch (Exception $e){
			throw $e;
		}
	}
	
	public function GetTotalOrderByUserIdPaging($user_id,$start_from,$limit)
	{
		try{
			$objProductDao = new ProductDao();
 			return $objProductDao->GetTotalOrderByUserIdPaging($user_id,$start_from,$limit);
		}catch (Exception $e){
			throw $e;
		}
	}
	public function getShipingAmt()
	{
		try{
			$objProductDao = new ProductDao();
 			return $objProductDao->getShipingAmt();
		}catch (Exception $e){
			throw $e;
		}
	}
	public function UpdateProductShipingAmt($user_id,$shiping_amt,$vat_amt)
	{
		try{
			$objProductDao = new ProductDao();
 			return $objProductDao->UpdateProductShipingAmt($user_id,$shiping_amt,$vat_amt);
		}catch (Exception $e){
			throw $e;
		}
	}
	public function GetProductsNameIdByProductCategoryID($productCategoryId)
	{
		try{
			$objProductDao = new ProductDao();
 			return $objProductDao->GetProductsNameIdByProductCategoryID($productCategoryId);
		}catch (Exception $e){
			throw $e;
		}
	}

	public function getProductByDesId($productDesId)
	{
		try{
			$objProductDao = new ProductDao();
 			return $objProductDao->getProductByDesId($productDesId);
		}catch (Exception $e){
			throw $e;
		}
	}
	public function GetProductsEnquiryList($flag,$OrderStatus, $limit, $maxRecord)
	{
		try
		{
			$objProductDao = new ProductDao();
			$arrData=$objProductDao->GetProductsEnquiryList($flag,$OrderStatus, $limit, $maxRecord);
		    return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	public function GetEnquiryProductList($intEnquiryId)
	{
		try
		{
			$objProductDao = new ProductDao();
			$arrData=$objProductDao-> GetEnquiryProductList($intEnquiryId);
		    return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

   	public function GetAndDisplayAllList1($ProductId)
	{
		try
		{
			$objAdminDetailsHomeDao = new ProductDao();
			$arrData=$objAdminDetailsHomeDao->GetAndDisplayAllList($ProductId);
			
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	public function DeleteEnquiryQuoteById($enquiryId)
	{
	 try
		{
			$objProductDao = new ProductDao();
			$arrData=$objProductDao->DeleteEnquiryQuoteById($enquiryId);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}	

	public function UpdateEnquiryStatusById($enquiryId, $enquiryChangedStatus, $enquiryCurrentStatus)
	{
	 try
		{
			$objProductDao = new ProductDao();
			$arrData=$objProductDao->UpdateEnquiryStatusById($enquiryId, $enquiryChangedStatus, $enquiryCurrentStatus);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	public function GetEnquiryDetailsById($enquiryId,$flag)
	{
	 try
		{
			$objProductDao = new ProductDao();
			$arrData=$objProductDao->GetEnquiryDetailsById($enquiryId,$flag);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}	
		
	public function GetOrderAmountByUserIdStatusOrderId($user_id,$status,$OrderId)
	{
	 try
		{
			$objProductDao = new ProductDao();
			$arrData=$objProductDao->GetOrderAmountByUserIdStatusOrderId($user_id,$status,$OrderId);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}			

	public function GetUserAddressVatInfo($userAddressId)
	{
	 try
		{
			$objProductDao = new ProductDao();
			$arrData=$objProductDao->GetUserAddressVatInfo($userAddressId);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}			

	public function InsertProductOrderDetails($arrUserDetails,$arrProductOrderDetails,$arrProductOrderCartDetails)
	{
	 try
		{
			$objProductDao = new ProductDao();
			$arrData=$objProductDao->InsertProductOrderDeta($arrUserDetails,$arrProductOrderDetails,$arrProductOrderCartDetails);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}			
	public function GetDirectSellProductData()
	{
	 try
		{
			$objProductDao = new ProductDao();
			$arrData=$objProductDao->GetDirectSellProductDetails();
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}			
	public function DeleteDirectSellProductByOrderId($intOrderId)
	{
	 try
		{
			$objProductDao = new ProductDao();
			$arrData=$objProductDao->DeleteDirectSellProductByOrderId($intOrderId);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}			
	public function UpdateEnquiryOrderDetail($enquiryId, $intSupplierNo, $intOrderNo)
	{
	 try
		{
			$objProductDao = new ProductDao();
			$arrData=$objProductDao->UpdateEnquiryOrderDetails($enquiryId, $intSupplierNo, $intOrderNo);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}			

}
?>
