<?php
namespace Application\Model;

use Propel\User;
use Propel\UserQuery;
use Propel\Rights;
use Propel\RightsQuery;
use Application\Model\Common;

class UserListManagement
{
	/*
	* Get all users including their rights
	*/
	
	public function getListUser()
	{
		$ret = array();
		$arrUser = UserQuery::create()->orderById('desc')->find();
		foreach($arrUser as $objUser)
		{
			// User information
			$user['id']         = $objUser->getId();
			$user['username']   = $objUser->getUsername();
			$user['first_name'] = $objUser->getFirstName();
			$user['last_name']  = $objUser->getLastName();	
			// Get rights
			$objRights = $objUser->getRights();
			$user['unlocked'] = $objRights->getUnlocked();
			$user['right1'] = $objRights->getRight1();
			$user['right2'] = $objRights->getRight2();
			
			$ret[] = $user;
		}
		return $ret;
	}
	
	public function getListUserJson()
	{
		$objCommon = new Common();
		return $objCommon->arrayToMessage2($this->getListUser());
	}
}