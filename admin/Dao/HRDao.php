<?php
include 'pathDao.php';
require_once ("BaseDao.php");

class HRDao extends BaseDao
{

	private $dbConn;
	
	public function GetById($userId)
	{
		try
		{
			$strQuery="SELECT * FROM tbl_user WHERE communication_email_id='".$userId."' AND user_type_id IN(1,3)"; 
			$this->dbConn = parent::BeginTransaction();
			$arrData = parent::executeQuery($strQuery, false,$this->dbConn);
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

	public function InsertUserType($InsertUserTypeArray,$UpdatetUserTypeArray)
	{
		try
		{
			if(count($InsertUserTypeArray)>0)
			{
				$this->dbConn = parent::BeginTransaction();
				$strQuery = "INSERT INTO tbl_user_type(user_type_name,is_mobile_app, is_employee,ranking) VALUES 
				('".$InsertUserTypeArray['textUserTypeName']."','".$InsertUserTypeArray['isMobileApp']."','".$InsertUserTypeArray['isEmployee']."',
				'".$InsertUserTypeArray['texttRanking']."')"; 
				$intInsertUserTypeId = parent::insert($strQuery,false,$this->dbConn); 
			}
			if(count($UpdatetUserTypeArray)>0)
			{
				$this->dbConn = parent::BeginTransaction();
				$strQuery1 = "UPDATE tbl_user_type SET user_type_name='".$UpdatetUserTypeArray['textUserTypeName']."',is_mobile_app='".$UpdatetUserTypeArray['isMobileApp']."',
				is_employee='".$UpdatetUserTypeArray['isEmployee']."',ranking='".$UpdatetUserTypeArray['texttRanking']."'
				WHERE user_type_id='".$UpdatetUserTypeArray['UserTypeId']."'";
				$intUpdateUserTypeId = parent::insert($strQuery1,false,$this->dbConn); 
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
	
	public function GetAllUserTypes()
	{
		try
		{
			$strQuery = "SELECT * FROM tbl_user_type  WHERE 1 ORDER BY ranking";
			$this->dbConn = parent::BeginTransaction();
			$intModuleId = parent::executeQuery($strQuery,false,$this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $intModuleId;
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

	public function GetUserTypeDetailBySearch($UserTypeId)
	{
		try
		{
			$strQuery = "SELECT * FROM tbl_user_type  WHERE user_type_id='".$UserTypeId."'";
			$this->dbConn = parent::BeginTransaction();
			$intModuleId = parent::executeQuery($strQuery,false,$this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $intModuleId;
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
	
	public function DeleteUserType($UserTypeId)
	{
		try
		{
			$strQuery = "DELETE FROM tbl_user_type  WHERE user_type_id='".$UserTypeId."'";
			
			$this->dbConn = parent::BeginTransaction();
			$intUeserTypeId = parent::execute($strQuery,false,$this->dbConn);
			parent::EndTransaction($this->dbConn);
			return $intUeserTypeId;
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