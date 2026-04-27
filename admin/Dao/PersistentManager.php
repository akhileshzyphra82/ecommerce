<?php
include('adodb/adodb.inc.php');
include('adodb/adodb-exceptions.inc.php');
session_start();
date_default_timezone_set("Asia/Shanghai");
abstract class PersistentManager {

	private function connectDB() {
			$DB = NewADOConnection('mysqli');
			$DB->Connect('162.241.224.212', 'sinelect_db', 'password@12345', 'sinelect_panel_productdb');
			// $DB->Connect('localhost', 'sinelect_db', '0W@IdwHzWxE&', 'sinelect_panel_productdb');
			
			return $DB;
	}

	private function closeConnection($DB) {
			$DB->Close();
	}

	public function insert($strQuery, $param, $DB )// for insert
	{
			$prepQuery = $DB->Prepare($strQuery);
			$result = $DB->Execute($prepQuery,$param);
			if (!$result) {
				$this->getErrorMsg($strQuery);
				return 0;
			}
			$intTSID = $DB->Insert_ID();
			if($result)
			{
				$this->activityLog($strQuery,$param,$DB,$intTSID);
				return $intTSID;
			}
	  }

	  public function executeQuery($strQuery, $param, $DB)// for search
	  {
			$prepQuery = $DB->Prepare($strQuery);
			$result = $DB->Execute($prepQuery,$param);
			if (!$result) {
				$this->getErrorMsg($strQuery);
				return NULL;
			}
			else {
				$arrData = array();
				while ($object1 = $result->FetchNextObject()) {
					array_push($arrData, $object1);
				}
				//$this->activityLog($strQuery,$param);
				return $arrData;
			}
	  }

	  public function execute($strQuery, $param, $DB)// for delete/update
	  {
			$prepQuery = $DB->Prepare($strQuery);
			$result = $DB->Execute($prepQuery,$param);

			if (!$result) {
				$this->getErrorMsg($strQuery);
				return NULL;
			}
			else {
				if($DB->Affected_Rows()<=0) {
					return 0;
				}
				else {
					$affectedRows=$DB->Affected_Rows();
					$this->activityLog($strQuery,$param,$DB,'');
					return $affectedRows;
				}
			}
	  }

	  public function checkDelete($strQuery, $param, $DB)
	  {
			$prepQuery = $DB->Prepare($strQuery);
			$result = $DB->Execute($strQuery,$param);

			if (!$result) {
				if($this->getErrorCode()==1451) { // referencial integrity error code
					return NULL;
				}
				else {
					$this->getErrorMsg($strQuery);
					return 0;
				}
			}
			else {
				if($DB->Affected_Rows()<=0) {
					return 0;
				}
				else {
					$affectedRows=$DB->Affected_Rows();
					$this->activityLog($strQuery,$param,$DB,'');
					return $affectedRows;
				}
			}
	  }

	  private function getErrorMsg($strQuery) {
			 $message  = 'Query error: ' . mysql_error() . "\n";
			 $message .= 'Whole Query: ' . $strQuery;
			 echo $message;
	  }

	  private function getErrorCode() {
			 return mysql_errno();
	  }

	  public function BeginTransaction()
	  {
			$DB=$this->connectDB();
			$DB->BeginTrans();
			$DB->SetTransactionMode('SERIALIZABLE');

			return $DB;
	  }

	  public function EndTransaction($DBConn)
	  {
			$DBConn->CommitTrans();
			$this->closeConnection($DBConn);
	  }

	  public function RollBackTransaction($DBConn)
	  {
			$DBConn->RollbackTrans();
			$DBConn->SetTransactionMode('');
			$this->closeConnection($DBConn);
	  }
	  
	  
	  ////////////////////////////////////insert  log//////////////////////////
	  
	  public function activityLog($strQuery, $param, $DB, $insertedId)
	  {
			if(isset($_SESSION) && !empty($_SESSION))
			{
				//echo '<pre>';print_r($_SESSION); die;
				
				$linkArray = explode("/",$_SERVER['PHP_SELF']);
				
				$userTypeId=$_SESSION['EMPLOYEETYPEID'];
				$userId=$_SESSION['EMPLOYEEID'];	
				$activityTime = date('Y-m-d : h:i:s');
				$activity = substr(trim($strQuery),0,6);
				$menuLink = $linkArray[count($linkArray)-1];
				$activityQuery = addslashes($strQuery);
				//$activityParamArray = $paramStr;
	
				preg_match('/(tbl_[a-z0-9_]+)/', $activityQuery, $matches);
				$tableName = $matches[0];
				if($userId!='')
				{
					$strLogQuery = "INSERT INTO tbl_activity_log(
					user_id,
					user_type_id,
					activity_type,
					file_name,
					activity_query,
					table_name)
					VALUES(
					".$userId.",
					'".$userTypeId."',
					'".$activity."',
					'".$menuLink."',
					'".$activityQuery."',
					'".$tableName."'
					)";
					//echo '<pre>';print_r($strLogQuery); die;
					$this->insertLog($strLogQuery,false,$DB);
				}
			}
			return true;  
	}

	  
	  ///////////////////////////////////////////////////////////////////////
	  
	public function insertLog($strQuery, $param, $DB )// for insert
	{
		$prepQuery = $DB->Prepare($strQuery);
		$result = $DB->Execute($prepQuery,$param);
		if (!$result) {
			$this->getErrorMsg($strQuery);
			return 0;
		}
		$intTSID = $DB->Insert_ID();
		if($result)
		{
			return $intTSID;
		}
	}
}
?>