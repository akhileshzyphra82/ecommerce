<?php
include 'pathManager.php';
require_once ($path."Dao/UserDao.php");
require_once ("CryptedPassword.inc.php");
require_once ("BaseManager.php");

class UserManager extends BaseManager
{

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
	
	public function InsertUser($objUser,$userType,$Password)
	{
		try
		{
			$DecyptPass=$this->Encrypt($Password);
			$objUserDao = new UserDao();
			return $objUserDao->Insertuser($objUser,$userType,$DecyptPass);
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	public function InsertUserFromWeb($objUser,$userType,$Password,$arrAddress)
	{
		try
		{
			$DecyptPass=$this->Encrypt($Password);
			$objUserDao = new UserDao();
			return $objUserDao->InsertUserFromWebsite($objUser,$userType,$DecyptPass,$arrAddress);
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	public function Getuser($user_id,$password,$user_type)
	{
		try{
		
		   $EncryptPass=$this->Encrypt($password);
			$objUserDao = new UserDao();
 			return $objUserDao->Getuser($user_id,$EncryptPass,$user_type);
		}catch (Exception $e){
			throw $e;
		}
	}
		
	public function InsertUserAddress($adressArray)
	{
		try
		{
			$objUserDao = new UserDao();
 			return $objUserDao->InsertuserAddress($adressArray);
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	
	public function GetuserAddress($userId)
	{
		try
		{
			$objUserDao = new UserDao();
 			return $objUserDao->GetuserAddress($userId);
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	
	public function UpdateuserAddress($userAddressId)
	{
		try
		{
			$objUserDao = new UserDao();
 			return $objUserDao->UpdateuserAddress($userAddressId);
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	
	public function Updateuser($userId)
	{
		try
		{
			$objUserDao = new UserDao();
 			return $objUserDao->Updateuser($userId);
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	public function CheckuserPassInfo($Email,$user_type)
	{
		try{
			$objUserDao = new UserDao();
 		    $ResultArray= $objUserDao->GetuserInfo($Email,$user_type);
			if(count($ResultArray)>0)
			{
			   $DecyptPass[]=$this->Decrypt($ResultArray[0]->ERP_PASSWORD);
			   $DecyptPass[]=$ResultArray[0]->COMMUNICATION_EMAIL_ID;
			   $DecyptPass[]=$ResultArray[0]->NAME;
		    }
			else
			{
			 $DecyptPass=array();
			}
			//echo "<pre>";print_r($DecyptPass);die;
			return $DecyptPass;
		}catch (Exception $e){
			throw $e;
		}
	}
		
	public function GetuserInfo($Email,$user_type)
	{
		try
		{
			$objUserDao = new UserDao();
 			return $objUserDao->GetuserInfo($Email,$user_type);
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	
	
	public function UpdateCustomerPassword($objUser,$userType)
	{
		try
		{
			$objUserDao = new UserDao();
 			return $objUserDao->UpdateCustomerPassword($objUser,$userType);
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	
	public function UpdateUserPassword($arrUserDetails)
	{
		try
		{
			$objUserDao = new UserDao();
 		   	$encryptNewPass=$this->Encrypt($arrUserDetails[0]['newPassword']);
 		   	$encryptOldPass=$this->Encrypt($arrUserDetails[0]['oldPassword']);
 			return $objUserDao->UpdateUserPassword($arrUserDetails,$encryptNewPass,$encryptOldPass);
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}	

	public function UpdateUserPasswordByOtp($intUserId,$password,$strUserEmailId,$otp)
	{
		try
		{
			$objUserDao = new UserDao();
 		   	$encryptPass=$this->Encrypt($password);
 			return $objUserDao->UpdateUserPassByOTP($intUserId,$encryptPass,$strUserEmailId,$otp);
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}	
	
	
	public function ResetUserPassword($changePass,$custId)
	{
		try
		{
		   $DecyptPass=$this->Encrypt($changePass);
			$objUserDao = new UserDao();
 			return $objUserDao->ResetUserPassword($DecyptPass,$custId);
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	
	public function VerifyUserAccount($Email)
	{
		try
		{
			$DecyptPass=$this->Encrypt($changePass);
			$objUserDao = new UserDao();
			return $objUserDao->VerifyUserAccount($Email);
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	
	public function GetAllCountryList()
	{
		try
		{
			$objUserDao = new UserDao();
 			return $objUserDao->GetAllCountryList();
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	public function UpdateAllCountryAmountById($InsertArray,$updateArray,$CountryId)
	{
		try
		{
			$objUserDao = new UserDao();
 			return $objUserDao->UpdateAllCountryAmountById($InsertArray,$updateArray,$CountryId);
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	public function GetAddDeleteCountryAmount()
	{
		try
		{
			$objUserDao = new UserDao();
 			return $objUserDao->GetAddDeleteCountryAmount();
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	public function DeleteCountryAmountById($CountryId)
	{
		try
		{
			$objUserDao = new UserDao();
 			return $objUserDao->DeleteCountryAmountById($CountryId);
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	public function GetAllCountryAmountById($CountryId)
	{
		try
		{
			$objUserDao = new UserDao();
 			return $objUserDao->GetAllCountryAmountById($CountryId);
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	
	public function GetAllFqadata()
	{
		try
		{
			$objUserDao = new UserDao();
 			return $objUserDao->GetAllFqadata();
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	
	public function InsertFaq($InsertArray,$UpdateArray,$IntfaqId)
	{
		try
		{
			$objUserDao = new UserDao();
 			return $objUserDao->InsertFaq($InsertArray,$UpdateArray,$IntfaqId);
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	
	public function DeleteFAQById($faqId)
	{
		try
		{
			$objUserDao = new UserDao();
 			return $objUserDao->DeleteFAQById($faqId);
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	
	public function GetFaqById($faqId)
	{
		try
		{
			$objUserDao = new UserDao();
 			return $objUserDao->GetFaqById($faqId);
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	public function UpdateInsUserOTP($userEmailId, $otp)
	{
		try
		{
			$objUserDao = new UserDao();
			return  $objUserDao->UpdateUserOTP($userEmailId, $otp);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	public function GetInsUserOTPDetailsForVerification($userEmailId,$strOTP)
	{
		try 
		{
			$objUserDao = new UserDao();
			return  $objUserDao->GetUserOTPDetailsForVerification($userEmailId,$strOTP);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	public function InsertUpdateUserAddresses($arrInsertUpdate)
	{
		try 
		{
			$objUserDao = new UserDao();
			return  $objUserDao->InsertUpdateUserAddress($arrInsertUpdate);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	public function DeleteUserAddressById($intUserAddId,$intUserId)
	{
		try 
		{
			$objUserDao = new UserDao();
			return  $objUserDao->DeleteUserAddressesById($intUserAddId,$intUserId);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	public function UpdateUserDetail($arrUpdate)
	{
		try 
		{
			$objUserDao = new UserDao();
			return  $objUserDao->UpdateUserDetails($arrUpdate);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	public function GetUserCompleteDataById($arrSearch)
	{
		try 
		{
			$objUserDao = new UserDao();
			return  $objUserDao->GetUserCompleteDataByIds($arrSearch);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	public function MarkUserVerifiedById($intUserId)
	{
		try 
		{
			$objUserDao = new UserDao();
			return  $objUserDao->MarkUserVerifiedByIds($intUserId);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	
	public function DeleteUserAddress($adressDelArray)
	{
		try 
		{
			$objUserDao = new UserDao();
			return  $objUserDao->DelUserAddress($adressDelArray);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	
}

?>
