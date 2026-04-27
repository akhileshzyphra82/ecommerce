<?php
include 'pathManager.php';
require_once ($path."Dao/HomeDao.php");
require_once ("CryptedPassword.inc.php");
require_once ("BaseManager.php");

class HomeManager extends BaseManager
{
	public function InsertBannerData($title,$imagExt,$priority,$discription,$hyperlink)
	{
		try
		{
			$objBannerHomeDao = new HomeDao();
 			return $objBannerHomeDao->InsertBannerData($title,$imagExt,$priority,$discription,$hyperlink);
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	
	public function GetAllJobData()
	{
		try
		{
			$objAddProductsDetailsHomeDao = new HomeDao();
			$arrData=$objAddProductsDetailsHomeDao->GetAllJobData();
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	
	public function InsertJobPost($JobPosition,$JobPriority,$Location,$Description,$Status)
	{
		try
		{
			$objBannerHomeDao = new HomeDao();
			return $objBannerHomeDao->InsertJobPost($JobPosition,$JobPriority,$Location,$Description,$Status);
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}	
	
	public function InsertProduct($InsertProductDetailArray,$imageArray,$manualArray,$SampleCodeArray)
	{
		try
		{
			$objProductHomeDao = new HomeDao();
 			return $objProductHomeDao->InsertProduct($InsertProductDetailArray,$imageArray,$manualArray,$SampleCodeArray);
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	
	public function UpdateProduct($InsertProductDetailArray,$imageArray,$manualArray,$AddProductId,$SampleCodeArray)
	{
		try
		{
			$objHomeDao=new HomeDao();
			$arrData=$objHomeDao->UpdateProduct($InsertProductDetailArray,$imageArray,$manualArray,$AddProductId,$SampleCodeArray);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	public function GetProductsDetailsByProductCategoryID($productCategoryId)
	{
		try
		{
			$objNewsAndEventHomeDao=new HomeDao();
			$arrData=$objNewsAndEventHomeDao->GetProductsDetailsByProductCategoryID($productCategoryId);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}	

	public function DeleteAddedProductData($AddProductId)
	{
		try
		{
			$objHomeDao=new HomeDao();
			$arrData=$objHomeDao->DeleteAddedProductData($AddProductId);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	public function DeleteProductImage($ImageId,$ext,$imageFor)
	{
		try
		{
			$objNewsAndEventHomeDao=new HomeDao();
			$arrData=$objNewsAndEventHomeDao->DeleteProductImage($ImageId,$ext,$imageFor);
			
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}		

	public function DeleteSampleCode($SampleCodeID,$ext)
	{
		try
		{
			$objNewsAndEventHomeDao=new HomeDao();
			$arrData=$objNewsAndEventHomeDao->DeleteSampleCode($SampleCodeID,$ext);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}					

	public function DeleteImageById($ProductId,$ext)
	{
		try
		{
			$objHomeDao=new HomeDao();
			$arrData=$objHomeDao->DeleteImageById($ProductId,$ext);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
		
	public function GetProductData($AddProductId)
	{
		try
		{
			$objNewsAndEventHomeDao = new HomeDao();
			$arrData=$objNewsAndEventHomeDao->GetProductData($AddProductId);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	public function GetAndDisplayAllAddProductsDetails($arrSrchData)
	{
		try
		{
			$objAddProductsDetailsHomeDao = new HomeDao();
			$arrData=$objAddProductsDetailsHomeDao->GetAndDisplayAllAddProductsDetails($arrSrchData);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	public function GetAllSubProductDetailByProductCatId($id)
	{
		try
		{
			$objProductHomeDao = new HomeDao();
			$arrData=$objProductHomeDao->GetAllSubProductDetailByProductCatId($id);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
		
	public function GetParentNameByCatId($CatId)
	{
		try
		{
			$objNewsAndEventHomeDao=new HomeDao();
			$arrData=$objNewsAndEventHomeDao->GetParentNameByCatId($CatId);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}	
	
		
	public function GetAllJobById($JobId)
	{
		try
		{
			$objBannerHomeDao = new HomeDao();
			$arrData=$objBannerHomeDao->GetAllJobById($JobId);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	public function InsertProductData($ProductCategoryName,$ParentCategoryId,$priority,$Description,$imagExt)
	{
		try
		{
			$objBannerHomeDao = new HomeDao();
 			return $objBannerHomeDao->InsertProductData($ProductCategoryName,$ParentCategoryId,$priority,$Description,$imagExt);
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	
	public function GetAndDisplayAllListProduct()
	{
		try
		{
			$objBannerHomeDao = new HomeDao();
			$arrData=$objBannerHomeDao->GetAndDisplayAllListProduct();
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	public function GetAndDisplayAllListProduct1()
	{
		try
		{
			$objAdminDetailsHomeDao = new HomeDao();
			$arrData=$objAdminDetailsHomeDao->GetAndDisplayAllListProduct1();
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	
	public function GetAndDisplayAllListProductById1($ProductId)
	{
		try
		{
			$objAdminDetailsHomeDao = new HomeDao();
			$arrData=$objAdminDetailsHomeDao->GetAndDisplayAllListProductById1($ProductId);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	public function GetAndDisplayAllListProductById($ProductId)
	{
		try
		{
			$objBannerHomeDao = new HomeDao();
			$arrData=$objBannerHomeDao->GetAndDisplayAllListProductById($ProductId);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	public function DeleteBannerData($bannerId)
	{
		try
		{
			$objBannerHomeDao = new HomeDao();
			$arrData=$objBannerHomeDao->DeleteBannerData($bannerId);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	public function DeleteProductCategory($ProductId)
	{
		try
		{
			$objBannerHomeDao = new HomeDao();
			$arrData=$objBannerHomeDao->DeleteProductCategory($ProductId);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
		
	}
	
	public function InsertNewsAndEventData($flag,$title,$date,$discription,$empId,$imgExt,$docExt)
	{
		try
		{
			$objNewsAndEventHomeDao = new HomeDao();
			return $objNewsAndEventHomeDao->InsertNewsAndEventData($flag,$title,$date,$discription,$empId,$imgExt,$docExt);
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	public function DeleteNewsAndEventData($intNewsAndEventId)
	{
		try
		{
			$objNewsAndEventHomeDao = new HomeDao();
 			return $objNewsAndEventHomeDao->DeleteNewsAndEventData($intNewsAndEventId);
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	
	public function UpdateNewsAndEventData($flag,$title,$date,$discription,$empId,$intNewsEventId,$imgExt,$docExt)
	{
		try
		{
			$objNewsAndEventHomeDao = new HomeDao();
 			return $objNewsAndEventHomeDao->UpdateNewsAndEventData($flag,$title,$date,$discription,$empId,$intNewsEventId,$imgExt,$docExt);
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	
	public function GetAndDisplayAllNewsAndEventDetails()
	{
		try
		{
			$objNewsAndEventHomeDao = new HomeDao();
			$arrData=$objNewsAndEventHomeDao->GetAndDisplayAllNewsAndEventDetails();
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	public function GetNewsAndEventById($NewsEventId)
	{
		try
		{
			$objNewsAndEventHomeDao = new HomeDao();
			$arrData=$objNewsAndEventHomeDao->GetNewsAndEventById($NewsEventId);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	
	public function GetAndDisplayAllInsertedImages($NewsEventId)
	{
		try
		{
			$objNewsAndEventHomeDao = new HomeDao();
			$arrData=$objNewsAndEventHomeDao-> GetAndDisplayAllInsertedImages($NewsEventId);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	public function DeleteJobPost($JobId)
	{
		try
		{
			$objHomeDao=new HomeDao();
			$arrData=$objHomeDao->DeleteJobPost($JobId);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	public function UpdateProductData($ProductId,$ProductCategoryName,$ParentCategoryId,$priority,$Description,$imagExt)
	{
		try
		{
			$objBannerHomeDao = new HomeDao();
			$arrData=$objBannerHomeDao->UpdateProductData($ProductId,$ProductCategoryName,$ParentCategoryId,$priority,$Description,$imagExt);
			return  $arrData;
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
			$objBannerHomeDao = new HomeDao();
			$arrData=$objBannerHomeDao->UpdateJobPost($JobId,$JobPosition,$JobPriority,$Location,$Description,$Status);
			return  $arrData;
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
			$objNewsAndEventHomeDao=new HomeDao();
			$arrData=$objNewsAndEventHomeDao->DeleteNewsImageData($ImageId);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}	
	
	public function GetAllProductOrderedDetails($OrderStatus,$limit,$maxRecord)
	{
		try
		{
			$objAdminDetailsHomeDao = new HomeDao();
			$arrData=$objAdminDetailsHomeDao->GetAllProductOrderedDetails($OrderStatus,$limit,$maxRecord);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	public function UpdateOrderStatusByOrderId($orderStatus,$orderId,$dispatch_courier_company, $dispatch_courier_tracking_id, $dispatch_courier_tracking_url)
	{
		try
		{
			$objAdminDetailsHomeDao = new HomeDao();
			$arrData=$objAdminDetailsHomeDao->UpdateOrderStatusByOrderId($orderStatus,$orderId,$dispatch_courier_company, $dispatch_courier_tracking_id, $dispatch_courier_tracking_url);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	public function DeleteOrderByOrderId($orderId)
	{
		try
		{
			$objAdminDetailsHomeDao = new HomeDao();
			$arrData=$objAdminDetailsHomeDao->DeleteOrderByOrderId($orderId);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	
	public function GetAllUserDetails($limit,$maxRecord)
	{
		try
		{
			$objUserDetailsHomeDao = new HomeDao();
			$arrData=$objUserDetailsHomeDao->GetAllUserDetails($limit,$maxRecord);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	public function GetExcelUserDetails($name,$emil)
	{
		try
		{
			$objUserDetailsHomeDao = new HomeDao();
			$arrData=$objUserDetailsHomeDao->GetExcelUserDetails($name,$emil);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	public function DeleteCustomerData($CustomerId)
	{
		try
		{
			$objUserDetailsHomeDao  = new HomeDao();
			$arrData=$objUserDetailsHomeDao->DeleteCustomerData($CustomerId);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	
	public function getUserAddressByUserId($userId)
	{
		try
		{
			$objUserDetailsHomeDao  = new HomeDao();
			$arrData=$objUserDetailsHomeDao->getUserAddressByUserId($userId);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	
	
	public function GetAllUserDetailsForPaging($startFrom,$limit,$customerName)
	{
		try
		{
			$objCustomerDetailsHomeDao = new HomeDao();
			$arrData=$objCustomerDetailsHomeDao->GetAllUserDetailsForPaging($startFrom,$limit,$customerName);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
		
	public function InsertCandidateDetails($name,$positionId,$email,$phone,$experience,$FileExt,$FileTempName)
	{
		try
		{
			$objCustomerDetailsHomeDao = new HomeDao();
			$arrData=$objCustomerDetailsHomeDao->InsertCandidateDetails($name,$positionId,$email,$phone,$experience,$FileExt,$FileTempName);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	public function GetAllAppliedJobCandidate($arrSrchData)
	{
		try
		{
			$objCustomerDetailsHomeDao = new HomeDao();
			$arrData=$objCustomerDetailsHomeDao->GetAllAppliedJobCandidate($arrSrchData);
			return  $arrData;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	public function InsertProductsEnquiry($arrUserDetails,$arrProductOrderDetails)
	{
		try 
		{
		 	$objInsertInquiry= new HomeDao();
		 	$arrData=$objInsertInquiry->InsertProductsEnquiry($arrUserDetails,$arrProductOrderDetails);
		 	return $arrData;
        }

        catch (Exception $e)

		{
			throw $e;
		}
	}

	public function UpdateEnquiryStatus($enquiryId,$enquiryStatus,$vatAmount,$deliveryCountryShipping,$totalAmount)
	{
		try 
		{
		 	$objInsertInquiry= new HomeDao();
		 	$arrData=$objInsertInquiry->UpdateEnquiryStatus($enquiryId,$enquiryStatus,$vatAmount,$deliveryCountryShipping,$totalAmount);
		 	return $arrData;
        }

        catch (Exception $e)

		{
			throw $e;
		}
	}
	
	
	public function GetAndDisplayAllBannerDetails()
	{
		 try
		 {
			 $objHomeDao= new HomeDao();
			 return $objHomeDao->GetAndDisplayAllBannerDetails();
		 }
		 catch(Exception $e)
		 {
			 throw $e;
		 }

	}
	
	
	
	public function SelectUser($flag)
		{
		 try
		 {
			 $objHomeDao= new HomeDao();
			 return $objHomeDao->SelectUser($flag);
		 }
		 catch(Exception $e)
		 {
			 throw $e;
		 }

	}
	
		
	public function GetAllProductOrderedCount($flag,$OrderStatus)
	{
		try
		{
			 $objHomeDao= new HomeDao();
			 return $objHomeDao->GetAllProductOrderedCount($flag,$OrderStatus);
		}
		catch(Exception $e)
		{
		 throw $e;
		}
	}	 

	public function InsertProductPurchaseData($arrProductPurchase)
	{
		try
		{
			 $objHomeDao= new HomeDao();
			 return $objHomeDao->InsertProductPurchaseData($arrProductPurchase);
		}
		catch(Exception $e)
		{
		 throw $e;
		}
	}	 

	public function GetProPurchaseDetailsByProId($intProductId)
	{
		try
		{
			 $objHomeDao= new HomeDao();
			 return $objHomeDao->GetProPurchaseDetailsByProId($intProductId);
		}
		catch(Exception $e)
		{
		 throw $e;
		}
	}	 

	public function DelProductPurchaseData($intProductPurchaseId, $intProductId, $intProductQuantity)
	{
		try
		{
			 $objHomeDao= new HomeDao();
			 return $objHomeDao->DelProductPurchaseData($intProductPurchaseId, $intProductId, $intProductQuantity);
		}
		catch(Exception $e)
		{
		 throw $e;
		}
	}	 
	public function GetPurchaseFromDistictDataByProductId($intProductId)
	{
		try
		{
			 $objHomeDao= new HomeDao();
			 return $objHomeDao->GetPurchaseFromDistictDataByProductId($intProductId);
		}
		catch(Exception $e)
		{
		 throw $e;
		}
	}	 

	public function GetAlreadyApplied($arrSrch)
	{
		try
		{
			 $objHomeDao= new HomeDao();
			 return $objHomeDao->GetAlreadyApply($arrSrch);
		}
		catch(Exception $e)
		{
		 throw $e;
		}
	}	 
	public function DeleteMultiCandidate($arrDelData)
	{
		try
		{
			 $objHomeDao= new HomeDao();
			 return $objHomeDao->DeleteMultiCandidate($arrDelData);
		}
		catch(Exception $e)
		{
		 throw $e;
		}
	}	 

}
?>
