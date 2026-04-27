<?php
require_once ("BaseDao.php");
//require_once ("EmployeeTypeDao.php");

class DashboardDao extends BaseDao
{
	private $dbConn;
	
	public function GetUserDetailByUserId($intEmployeeId)
	{
	   try
	   {
			$strQuery = "";
			$strQuery="SELECT * FROM `tbl_user` WHERE user_id='".$intEmployeeId."' ";
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
	
	
	public function GetDashBoardGraphDetails()
	{
	   try
	   {
	   		$arrReturnData=array();	
			$this->dbConn = parent::BeginTransaction();
			$strEnquryQuery="SELECT enquiry_status ,COUNT(*) AS TOTAL
			FROM tbl_enquiry_quote GROUP BY enquiry_status";
			$arrEnqueyCountData = parent::executeQuery($strEnquryQuery, false, $this->dbConn);
			
			$strOrderQuery="SELECT order_current_status ,COUNT(*) AS TOTAL
			FROM tbl_order WHERE order_current_status!='' GROUP BY order_current_status";
			$arrOrderCountData = parent::executeQuery($strOrderQuery, false, $this->dbConn);
			parent::EndTransaction($this->dbConn);
			
			$arrReturnData=array($arrEnqueyCountData,$arrOrderCountData);
			return $arrReturnData;
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