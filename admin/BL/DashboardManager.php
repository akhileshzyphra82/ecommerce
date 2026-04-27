<?php
require_once ("../../Dao/DashboardDao.php");

require_once ("BaseManager.php");

class DashboardManager extends BaseManager
{
	public function GetUserDetailByUserId($intEmployeeId)
	{
		try
		{
			$objDashboardDao = new DashboardDao();
 			return $objDashboardDao->GetUserDetailByUserId($intEmployeeId);
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	
	public function GetDashBoardGraphData()
	{
		try
		{
			$objDashboardDao = new DashboardDao();
 			return $objDashboardDao->GetDashBoardGraphDetails();
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	
	
}
?>