<?php
include 'pathManager.php';
require_once ($path."Dao/HRDao.php");
require_once ("CryptedPassword.inc.php");
require_once ("BaseManager.php");

class HRManager extends BaseManager
{
	public function ValidateLogin($userId, $password )
	{
		$objHRDao = new HRDao();
		$arrEmployee = $objHRDao->GetById($userId);
		
		$enPassword = $this->Encrypt($password);
		$DecryptPass = $this->Decrypt($arrEmployee[0]->ERP_PASSWORD);
		
		//echo '<pre>'; print_r($DecryptPass); die;
		//echo '<pre>'; print_r($arrEmployee); die;

		if (count($arrEmployee)>0 && $arrEmployee[0]->USER_STATUS == 'In-Active')
		{
			$loginStatus['LOGINMESSAGE'] = "INACTIVE";
		}
		elseif (count($arrEmployee)>0  && ($arrEmployee[0]->ERP_PASSWORD == $enPassword))
		{
 			$loginStatus['LOGINMESSAGE'] = "SUCCESS";
		}		
		elseif($arrEmployee == NULL)
		{
			$loginStatus['LOGINMESSAGE'] = "NOTFOUND";
		}
		else
		{
			$loginStatus['LOGINMESSAGE'] = "LOGINFAILURE";
		}
		
		return array($arrEmployee,$loginStatus['LOGINMESSAGE']);
	}
	
	public function InsertUserType($InsertUserTypeArray,$UpdatetUserTypeArray)
	{
		try
		{
			$objHRDao = new HRDao();
 			return $objHRDao->InsertUserType($InsertUserTypeArray,$UpdatetUserTypeArray);
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

 	public function GetAllUserTypes()
	{
		try
		{
			$objHRDao = new HRDao();
 			return $objHRDao->GetAllUserTypes();
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	
	public function GetUserTypeDetailBySearch($UserTypeId)
	{
		try
		{
			$objHRDao = new HRDao();
 			return $objHRDao->GetUserTypeDetailBySearch($UserTypeId);
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	public function DeleteUserType($UserTypeId)	
	{
		try
		{
			$objHRDao = new HRDao();
 			return $objHRDao->DeleteUserType($UserTypeId);
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

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
}
?>
