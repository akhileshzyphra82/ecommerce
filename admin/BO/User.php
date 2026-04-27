<?php
date_default_timezone_set('Asia/Kolkata');

/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */
 


 
class User 
{
 private $name="";
 private $Email="";
 private $PhoneISD="";
 private $PhoneNumber="";
 private $MobileISD="";
 private $MobileNumber="";
 private $Password="";
 private $user_id="";
 private $company_name="";
 private $designation="";
 private $UserRandomKey=0;
 
	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;
	}
	public function getEmail()
	{
		return $this->Email;
	}

	public function setEmail($Email)
	{
		$this->Email = $Email;
	}
	public function getPhoneNumber()
	{
		return $this->PhoneNumber;
	}

	public function setPhoneNumber($PhoneNumber)
	{
		$this->PhoneNumber = $PhoneNumber;
	}
	public function getMobileNumber()
	{
		return $this->MobileNumber;
	}

	public function setMobileNumber($MobileNumber)
	{
		$this->MobileNumber = $MobileNumber;
	}
	public function getPassword()
	{
		return $this->Password;
	}

	public function setPassword($Password)
	{
		$this->Password = $Password;
	}
	public function getuser_id()
	{
		return $this->user_id;
	}

	public function setuser_id($user_id)
	{
		$this->user_id = $user_id;
	}
	
	public function getCompanyname()
	{
		return $this->company_name;
	}

	public function setCompanyname($company_name)
	{
		$this->company_name = $company_name;
	}
	
	public function getDesignation()
	{
		return $this->designation;
	}

	public function setDesignation($designation)
	{
		$this->designation = $designation;
	}
	
	public function getPhoneISD()
	{
		return $this->PhoneISD;
	}

	public function setPhoneISD($PhoneISD)
	{
		$this->PhoneISD = $PhoneISD;
	}
	
	public function getMobileISD()
	{
		return $this->MobileISD;
	}

	public function setMobileISD($MobileISD)
	{
		$this->MobileISD = $MobileISD;
	}
	
	public function getUserRandomKey()
	{
		return $this->UserRandomKey;
	}

	public function setUserRandomKey($UserRandomKey)
	{
		$this->UserRandomKey = $UserRandomKey;
	}
}
?>