<?php
include 'pathDao.php';
require_once ("BaseDao.php");
require_once ($path."BL/CryptedPassword.inc.php");

class UserDao extends BaseDao
{

	private $dbConn;
	private $_PASS_LENGTH = 8;
	
	private function Encrypt($strData)
	{
		$objEncryptManager = new CryptedPassword();
		return $objEncryptManager->LinEncrypt($strData);
	}
	
	public function InsertUser($objUser,$userType,$DecyptPass)
	{
		try
		{
			$this->dbConn = parent::BeginTransaction();
			$strQuery = "INSERT INTO `tbl_user` (user_type_id, name , communication_phone_num,communication_mobile_num,
			communication_email_id,erp_password,company_name,designation,communication_phone_num_isd,communication_mobile_num_isd,random_activation_key,account_activation_flag) 
			VALUES('".$userType."','".$objUser->getName()."','".$objUser->getPhoneNumber()."','".$objUser->getMobileNumber()."','".$objUser->getEmail()."',
			'".$DecyptPass."','".$objUser->getCompanyname()."','".$objUser->getDesignation()."','".$objUser->getPhoneISD()."','".$objUser->getMobileISD()."',
			'".$objUser->getUserRandomKey()."','1')";
					
			$productId = parent::insert($strQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $productId;
			
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
	
	public function InsertUserFromWebsite($objUser,$userType,$DecyptPass,$arrAddress)
	{
		try
		{
			$strQuery = "INSERT INTO `tbl_user` (user_type_id, name , communication_phone_num,communication_mobile_num,
			communication_email_id,erp_password,company_name,designation,communication_phone_num_isd,
			communication_mobile_num_isd,random_activation_key,account_activation_flag) 
			VALUES('".$userType."','".$objUser->getName()."','".$objUser->getPhoneNumber()."','".$objUser->getMobileNumber()."','".$objUser->getEmail()."',
			'".$DecyptPass."','".$objUser->getCompanyname()."','".$objUser->getDesignation()."','".$objUser->getPhoneISD()."','".$objUser->getMobileISD()."',
			'".$objUser->getUserRandomKey()."','1')";
			$this->dbConn = parent::BeginTransaction();
			$intUserId = parent::insert($strQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			
			if(isset($arrAddress) && count($arrAddress)>0)
			{
				$strUserAddQuery = "INSERT INTO tbl_user_address (user_id, address, city, state, zip, country, landmark, delivery_phone_no, user_name, 
				eu_vat, company_name, mobile_country_code, country_id) 
				VALUES('".$intUserId."','".$arrAddress['delivery_address']."','".$arrAddress['delivery_city']."',
				'".$arrAddress['delivery_state']."','".$arrAddress['delivery_zip']."',
				'".$arrAddress['delivery_country']."','','".$arrAddress['user_phone']."',
				'".$objUser->getName()."','".$arrAddress['vat_number']."','".$objUser->getCompanyname()."',
				'".$objUser->getMobileISD()."','".$arrAddress['delivery_country_id']."')"; 
				$this->dbConn = parent::BeginTransaction();
				$userAddressId = parent::insert($strUserAddQuery,false,$this->dbConn);
				parent::EndTransaction($this->dbConn);
			}
			
			return $intUserId;
			
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
		
	public function InsertUserAddress($adressArray)
	{
		try
		{
			
			$this->dbConn = parent::BeginTransaction();
			list($mobileCode,$mobileNo)=explode("_",$adressArray['Phone']);
			list($countryId,$countryName)=explode("@_@",$adressArray['Country']);
			if($adressArray['USER_ADDRESS_ID']=="")
			{
				$strQuery = "INSERT INTO tbl_user_address (user_id, address, city, state, zip, country_id,  landmark, delivery_phone_no, user_name,  
				eu_vat, company_name, mobile_country_code, country) VALUES('".$adressArray['user_id']."','".$adressArray['Address']."','".$adressArray['City']."',
				'".$adressArray['State']."','".$adressArray['ZIP']."','".$countryId."','".$adressArray['landmark']."','".$mobileNo."',
				'".$adressArray['Name']."','".$adressArray['eu_vat']."','".$adressArray['company_name']."','".$mobileCode."','".$countryName."')"; 
			}
			else 
			{
				$strQuery= "UPDATE tbl_user_address SET user_id='".$adressArray['user_id']."', address ='".$adressArray['Address']."',
				city='".$adressArray['City']."', state='".$adressArray['State']."' ,zip='".$adressArray['ZIP']."', country_id='".$adressArray['Country']."', 
				landmark='".$adressArray['landmark']."', delivery_phone_no='".$mobileNo."' , user_name='".$adressArray['Name']."',
				eu_vat='".$adressArray['eu_vat']."' , company_name='".$adressArray['company_name']."', mobile_country_code='".$mobileCode."',
				country='".$countryName."'  WHERE user_address_id='".$adressArray['USER_ADDRESS_ID']."'"; 
			}
			$productId = parent::insert($strQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $productId;
			
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
	
	public function Getuser($user_id,$password,$user_type)
	{
	   	try{
	        $this->dbConn = parent::BeginTransaction();
			$strQuery = "SELECT * FROM `tbl_user` WHERE communication_email_id='".$user_id."' AND erp_password = '".$password."' AND user_type_id='".$user_type."' ";
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
		
	public function GetuserAddress($userId)
	{
	   	try
		{
		
			$this->dbConn = parent::BeginTransaction();
			$strQuery = "SELECT address.*,country.* FROM tbl_user_address address
			LEFT JOIN tbl_country country on country.country_id = address.country_id
			WHERE address.user_id ='".$userId."'"; 
			$arrData = parent::executeQuery($strQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arrData;
			
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
	
	public function UpdateuserAddress($userAddressId)
	{
	   	try
		{
			$this->dbConn = parent::BeginTransaction();
			$strQuery = "SELECT * FROM tbl_user_address WHERE `user_address_id` ='".$userAddressId."'"; 
			$arrData = parent::executeQuery($strQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arrData;
			
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
	
	public function Updateuser($userId)
		{
	   	try
		{
		
			$this->dbConn = parent::BeginTransaction();
			$strQuery = "SELECT * FROM tbl_user WHERE `user_id` ='".$userId."'"; 
			$arrData = parent::executeQuery($strQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arrData;
			
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

	public function GetuserInfo($Email,$user_type)
	{
	   	try{
		
	        $this->dbConn = parent::BeginTransaction();
			$strQuery = "SELECT * FROM tbl_user WHERE `communication_email_id` ='".$Email."' AND user_type_id='".$user_type."'";
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
		
	public function UpdateCustomerPassword($objUser,$userType)
	{
	   	try
		{
			$this->dbConn = parent::BeginTransaction();
			$strQuery = "UPDATE `tbl_user` SET erp_password='".$objUser->getPassword()."' WHERE user_type_id='".$userType."' AND communication_email_id='".$objUser->getEmail().
			"' AND  user_id='".$objUser->getuser_id()."'";
			$result = parent::insert($strQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $result;
			
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
	
	public function UpdateUserPassword($arrUserDetails,$encryptNewPass,$encryptOldPass)
	{
	   	try
		{
			$returnVal=0;
			$matchOldPasswordQuery="SELECT * FROM  tbl_user WHERE user_id = '".$arrUserDetails[0]['userId']."' AND user_type_id = '".$arrUserDetails[0]['userType']."' 
			AND erp_password = '".$encryptOldPass."' " ;
			$this->dbConn = parent::BeginTransaction();
			$arrMatchOldPasswordData = parent::executeQuery($matchOldPasswordQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			if(count($arrMatchOldPasswordData)>0)
			{
		  		$strPassUpdateQuery = "UPDATE tbl_user SET erp_password='".$encryptNewPass."' WHERE user_type_id='".$arrUserDetails[0]['userType']."' 
				AND user_id='".$arrUserDetails[0]['userId']."'";
				
				$this->dbConn = parent::BeginTransaction();
				$updateUserId = parent::insert($strPassUpdateQuery,false, $this->dbConn);
				parent::EndTransaction($this->dbConn);
				$returnVal=1;
			}
			return $returnVal;
			
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

	public function UpdateUserPassByOTP($intUserId,$encryptPass,$strUserEmailId,$otp)
	{
	   	try
		{
			$returnVal=0;
			$matchOldPasswordQuery="SELECT * FROM  tbl_user WHERE user_id = '".$intUserId."' AND communication_email_id = '".$strUserEmailId."' 
			AND random_activation_key = '".$otp."' " ;
			$this->dbConn = parent::BeginTransaction();
			$arrMatchOldPasswordData = parent::executeQuery($matchOldPasswordQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			if(count($arrMatchOldPasswordData)>0)
			{
		  		$strPassUpdateQuery = "UPDATE tbl_user SET erp_password='".$encryptPass."' WHERE communication_email_id='".$strUserEmailId."' 
				AND user_id='".$intUserId."' AND random_activation_key='".$otp."'";
				
				$this->dbConn = parent::BeginTransaction();
				$updateUserId = parent::insert($strPassUpdateQuery,false, $this->dbConn);
				parent::EndTransaction($this->dbConn);
				$returnVal=1;
			}
			return $returnVal;
			
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

	
	public function ResetUserPassword($DecyptPass,$custId)
	{
	   	try
		{
			$this->dbConn = parent::BeginTransaction();
			$strQuery = "UPDATE `tbl_user` SET erp_password='".$DecyptPass."' WHERE  user_id='".$custId."'";
			$result = parent::insert($strQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return true;
			
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
	
	public function VerifyUserAccount($Email)
	{
	   	try
		{
			$this->dbConn = parent::BeginTransaction();
			$strQuery = "UPDATE `tbl_user` SET account_activation_flag='1' WHERE  communication_email_id='".$Email."'";
			$result = parent::insert($strQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return true;
			
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
	
	public function GetAllCountryList()
	{
	   	try
		{
			$this->dbConn = parent::BeginTransaction();
			$strQuery = "SELECT * FROM `tbl_country` ";
			$arrData = parent::executeQuery($strQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arrData;
			
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
	
	public function UpdateAllCountryAmountById(array $InsertArray,$updateArray,$CountryId)
	{
		try
		{
			if(count($InsertArray)>0)
			{
				$strQuery="INSERT INTO tbl_country(country,shipping_amt) VALUES ('$InsertArray[0]','$InsertArray[1]')";
				$this->dbConn = parent::BeginTransaction();
				$arrData = parent::insert($strQuery, false, $this->dbConn);
				parent::EndTransaction($this->dbConn);		     
			}
		   if(count($updateArray)>0)
      	   {
				$strQuery="UPDATE tbl_country SET country='$updateArray[0]',shipping_amt='$updateArray[1]' WHERE country_id='$CountryId'";
				$this->dbConn = parent::BeginTransaction();
				$arrData = parent::insert($strQuery, false, $this->dbConn);
				parent::EndTransaction($this->dbConn); 
			}
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
	
	public function GetAddDeleteCountryAmount()
	{
	   try
	   {
	        $strQuery="SELECT * FROM tbl_country ";
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
	
	public function DeleteCountryAmountById($CountryId)
	{
	   try
	   {
			$strQuery="DELETE FROM tbl_country WHERE country_id='".$CountryId."'";
			$this->dbConn = parent::BeginTransaction();
			$arrData = parent::execute($strQuery, false, $this->dbConn);
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
	
	public function GetAllCountryAmountById($CountryId)
	{
	   try
	   {
	        $strQuery="SELECT * FROM tbl_country  WHERE country_id='".$CountryId."'";
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
	
	public function GetAllFqadata()
	{
		try
		{
    	    $strQuery="SELECT * FROM tbl_faq ORDER BY faq_order ASC";
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
	
	public function InsertFaq(array $InsertArray,$UpdateArray,$IntfaqId)
	{
	  try
	  {
	       if(count($InsertArray)>0)
			{     
				$this->dbConn = parent::BeginTransaction();
				$strQuery = "INSERT INTO tbl_faq (faq_question,faq_answer,faq_order) VALUES ('$InsertArray[0]','$InsertArray[1]','$InsertArray[2]')";
				$faqId = parent::insert($strQuery,false, $this->dbConn);
				parent::EndTransaction($this->dbConn);
			}
			else
			{
			  if(count($UpdateArray)>0)
			  {
					$this->dbConn = parent::BeginTransaction();
					$strQuery = "UPDATE tbl_faq SET faq_question='$UpdateArray[0]',faq_answer='$UpdateArray[1]',faq_order='$UpdateArray[2]' WHERE faq_id='".$IntfaqId."'";
					$faqId = parent::insert($strQuery,false, $this->dbConn);
					parent::EndTransaction($this->dbConn); 
			  }
			}
			return true;
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
	
	public function DeleteFAQById($faqId)
	{
	   try
	   {
			$strQuery="DELETE FROM tbl_faq WHERE faq_id='".$faqId."'";
			$this->dbConn = parent::BeginTransaction();
			$arrData = parent::execute($strQuery, false, $this->dbConn);
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
	
	public function GetFaqById($faqId)
	{
	try
	{
    	    $strQuery="SELECT * FROM tbl_faq WHERE faq_id='".$faqId."'";
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
	public function UpdateUserOTP($userEmailId, $otp)
	{
		try
		{	
			$arrUserData = array();
			if($userEmailId!='')
			{
				$strUserQuery="SELECT user_id, communication_email_id, user_type_id, account_activation_flag FROM tbl_user WHERE communication_email_id='".$userEmailId."' ";
				//echo "<pre>"; print_r($strQuery); die;
				$this->dbConn = parent::BeginTransaction();
				$arrUserData = parent::executeQuery($strUserQuery, false,$this->dbConn);
				parent::EndTransaction($this->dbConn);
				
				if(count($arrUserData)>0)
				{
					$strUpOTPQuery = "UPDATE tbl_user SET random_activation_key='".$otp."' WHERE user_id='".$arrUserData[0]->USER_ID."'"; 
					$this->dbConn = parent::BeginTransaction();
					$intUserId = parent::insert($strUpOTPQuery,false,$this->dbConn);
					parent::EndTransaction($this->dbConn);
				}
			}

			return $arrUserData;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back");
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}		
	}
	
	public function GetUserOTPDetailsForVerification($userEmailId,$strOTP)
	{
		try
		{	
			$strQuery="SELECT user_id, communication_email_id, user_type_id, account_activation_flag, random_activation_key FROM tbl_user
			WHERE communication_email_id='".$userEmailId."' ";

			$this->dbConn = parent::BeginTransaction();
			$arrData = parent::executeQuery($strQuery,false,$this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arrData;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back");
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}		
	}
	public function InsertUpdateUserAddress($arrInsertUpdate)
	{
		try
		{	
			$this->dbConn = parent::BeginTransaction();
			if($arrInsertUpdate['intUserAddId']=="")
			{
				$strInsertQuery="INSERT INTO tbl_user_address (user_id,address,city,state,zip,country,user_name,company_name,
				mobile_country_code,delivery_phone_no,landmark,country_id,eu_vat) VALUES 
				('".$arrInsertUpdate['intUserId']."','".$arrInsertUpdate['strAddress']."','".$arrInsertUpdate['strCity']."',
				'".$arrInsertUpdate['strState']."','".$arrInsertUpdate['strZipCode']."','".$arrInsertUpdate['intCountryName']."',
				'".$arrInsertUpdate['strName']."','".$arrInsertUpdate['strCompName']."','".$arrInsertUpdate['strMobCode']."',
				'".$arrInsertUpdate['strMobNum']."','".$arrInsertUpdate['strLandmark']."','".$arrInsertUpdate['intCountryId']."',
				'".$arrInsertUpdate['strVatNo']."')";	
				$intRetId = parent::insert($strInsertQuery,false, $this->dbConn);
			}
			else
			{
				$strInsertQuery="UPDATE tbl_user_address SET address='".$arrInsertUpdate['strAddress']."',city='".$arrInsertUpdate['strCity']."',
				state='".$arrInsertUpdate['strState']."',zip='".$arrInsertUpdate['strZipCode']."',country='".$arrInsertUpdate['intCountryName']."',
				user_name='".$arrInsertUpdate['strName']."',company_name='".$arrInsertUpdate['strCompName']."',
				mobile_country_code='".$arrInsertUpdate['strMobCode']."',delivery_phone_no='".$arrInsertUpdate['strMobNum']."',
				landmark='".$arrInsertUpdate['strLandmark']."',country_id='".$arrInsertUpdate['intCountryId']."',
				eu_vat='".$arrInsertUpdate['strVatNo']."' WHERE user_address_id='".$arrInsertUpdate['intUserAddId']."' 
				AND user_id='".$arrInsertUpdate['intUserId']."'";	
				$intUpdateId = parent::insert($strInsertQuery,false, $this->dbConn);
				$intRetId=$arrInsertUpdate['intUserAddId'];
			}
			parent::EndTransaction($this->dbConn);
			return $intRetId;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back");
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}		
	}
	public function DeleteUserAddressesById($intUserAddId,$intUserId)
	{
	   try
	   {
			$strQuery="DELETE FROM tbl_user_address WHERE user_address_id='".$intUserAddId."' 
			AND user_id='".$intUserId."'";
			$this->dbConn = parent::BeginTransaction();
			$arrData = parent::execute($strQuery, false, $this->dbConn);
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
	public function UpdateUserDetails($arrUpdate)
	{
		try
		{	
			$this->dbConn = parent::BeginTransaction();
			
			$strISDUpdate="";
			$strEmailUpdate="";
			if($arrUpdate['strUserMobISD']!='')
			{
				$strISDUpdate=", communication_phone_num_isd='".$arrUpdate['strUserMobISD']."'";
			}
			if($arrUpdate['strEmailId']!='')
			{
				$strEmailUpdate=", communication_email_id='".$arrUpdate['strEmailId']."'";
			}
			
			$strInsertQuery="UPDATE tbl_user SET name='".$arrUpdate['strUserName']."',communication_mobile_num='".$arrUpdate['strUserMobNo']."',
			company_name='".$arrUpdate['strCompName']."',designation='".$arrUpdate['strDesgName']."'".$strISDUpdate.$strEmailUpdate." 
			WHERE user_id='".$arrUpdate['intUserId']."'";
			$intUpdateId = parent::insert($strInsertQuery,false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arrUpdate['intUserId'];
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back");
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}		
	}
	public function GetUserCompleteDataByIds($arrSearch)
	{
		try
		{	
			$strUserQuery="SELECT * FROM tbl_user a
			LEFT JOIN tbl_user_address b ON a.user_id=b.user_id
			WHERE a.user_id='".$arrSearch['intUserId']."'";
			$this->dbConn = parent::BeginTransaction();
			$arrUserData = parent::executeQuery($strUserQuery, false,$this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arrUserData;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back");
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}		
	}
	public function MarkUserVerifiedByIds($intUserId)
	{
		try
		{	
			$strUserQuery="UPDATE tbl_user SET verified_flag='Yes' WHERE user_id='".$intUserId."'";
			$this->dbConn = parent::BeginTransaction();
			$arrUserData = parent::insert($strUserQuery, false,$this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $arrUserData;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back");
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}		
	}
	
	public function DelUserAddress($adressDelArray)
	{
		try
		{	
			$addressId=$adressDelArray['addressId'];
			
			$strUserQuery="DELETE 
			FROM `tbl_user_address`
			WHERE `user_address_id`='".$addressId."'";
			$this->dbConn = parent::BeginTransaction();
			$intId = parent::execute($strUserQuery, false,$this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $intId;
		}
		catch(ADODB_Exception $ae)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw new Exception("Error in DB- Transaction Rolled Back");
		}
		catch(Exception $e)
		{
		 	parent::RollBackTransaction($this->dbConn);
			throw $e;
		}		
	}
	
	
	
}
?>