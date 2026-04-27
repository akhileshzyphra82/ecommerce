<?php
date_default_timezone_set('Asia/Kolkata');

/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */
class BaseObject 
{
	public function getAdditionalInfo()
	{
		return $this->_hashtable;
	}

	public function setAdditionalInfo($_hashtable)
	{
		$this->_hashtable = $_hashtable;
	}
}
?>