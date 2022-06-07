<?php
namespace Application\Model;

use Propel\User;
use Propel\UserQuery;
use Propel\Rights;
use Propel\RightsQuery;
use Application\Model\Common;

class UserManagement
{
	/*
	 * Function addUser
	 * $userData = array('username', 'password', 'first_name', 'last_name');
	 */
	public function addUser($userData)
	{
		// Trim data, except password
		$objCommon = new Common();
		$tmp_pass = $userData['password'];
		
		$userData = $objCommon->myTrim($userData);
		$userData['password'] = $tmp_pass;
		
		//---------------------------//
		// Error array return
		//---------------------------//
		$arrError = $this->checkValidateUserData($userData);
		return $arrError;
	}
	/*
	 * Function checkValidate data
	 * Check the input date:
	 * 			If the input is valid, perform INSERT operation, return the empty array
	 *          else return the array containing errors,
	 * $userData = array('username', 'password', 'first_name', 'last_name');
	 */
	public function checkValidateUserData($userData)
	{
		//---------------------------//
		// Error array return
		//---------------------------//
		$arrError = array();
		//---------------------------//
		// Check error
		//---------------------------//
		if(is_array($userData) == TRUE && empty($userData) == FALSE)
		{
			$userObj = new User();
			
			//Initiate object data
			$userObj->setUsername(strtolower($userData["username"]));
			$userObj->setPassword($userData["password"]);
			$userObj->setFirstName($userData["first_name"]);
			$userObj->setLastName($userData["last_name"]);
			// Validate data
			if($userObj->validate() == TRUE)
			{
				// No error, encrypt password before saving
				$userObj->setPassword(md5($userData["password"]));
				// No error, add row
				$userObj->save();
				
				// Get right
				$objCommon = new Common();
				$userData['unlocked']   = $objCommon->iniRight($userData['unlocked']);
				$userData['right1']     = $objCommon->iniRight($userData['right1']);
				$userData['right2']     = $objCommon->iniRight($userData['right2']);
				
				// Add right
				$objRight = new Rights();
				$objRight->setUnlocked($userData['unlocked']);
				$objRight->setRight1($userData['right1']);
				$objRight->setRight2($userData['right2']);
				$objRight->setUser($userObj);
				$objRight->save();
			}
			else
			{
				// Error occurs
				$arrOutput = $userObj->getValidationFailures();
				// Build return error array
				$arrTemp = array();
				foreach ($arrOutput as $key=>$val)
				{
					$arrTemp[$key] = $val->getMessage();
				}
				$arrError["error"] = $arrTemp;
			}
		}
		else
		{
			//---------------------------//
			// Case: null input array
			//---------------------------//
			$arrError["error_input"] = "Invalid input data";
		}
		return $arrError;
	}
	/*
	 * Function getUserFullData
	 * Return user data of the given username, including
	 * user table data
	 * rights data
	 */
	public function getUserFullData($username)
	{
		// Initiate return array
		$arrRet = array();
		if(empty($username) == FALSE)
		{
			// Check if user exists
			$user = UserQuery::create()->findOneByUsername($username);
			if(empty($user) == FALSE)
			{
				// User exists, get data
				$arrRet['user']['first_name'] = $user->getFirstName();
				$arrRet['user']['last_name']  = $user->getLastName();
				$arrRet['user']['id']         = $user->getId();
				$arrRet['user']['username']   = $user->getUsername();
				
				$right = $user->getRights();
				$arrRet['right']['unlocked'] = $right->getUnlocked();
				$arrRet['right']['right1']   = $right->getRight1();
				$arrRet['right']['right2']   = $right->getRight2();
			}
		}
		return $arrRet;
	}
	
	public function getUserFullDataById($userId)
	{
		$arrayRet = array();
		if(is_numeric($userId) && $userId > 0)
		{
			$user = UserQuery::create()->findById($userId);
			if(empty($user) == false)
			{
				// User exists, get data of the first (and only) user
				$arrayRet['first_name'] = $user[0]->getFirstName();
				$arrayRet['last_name']  = $user[0]->getLastName();
				$arrayRet['id']         = $user[0]->getId();
				$arrayRet['username']   = $user[0]->getUsername();
				
				$rights = $user[0]->getRights();
				if(empty($rights) == FALSE)
				{
					$arrayRet['unlocked'] = $rights->getUnlocked();
					$arrayRet['right1']   = $rights->getRight1();
					$arrayRet['right2']   = $rights->getRight2();
				}
				else
				{
					// Rights not set, default FALSE
					$arrayRet['unlocked'] = 0;
					$arrayRet['right1']   = 0;
					$arrayRet['right2']   = 0;
				}
			}
		}
		return $arrayRet;
	}
	
	public function addNewUser($userData)
	{
		$checkValidate = $this->checkValidateUserData($userData);
		if(empty($checkValidate) == FALSE)
		{
			$objCommon = new Common();
			$strError  = $objCommon->arrayToMessage2($checkValidate);
			return array('error' => $strError);
		}
		else
		{
			return array('success' => TRUE);
		}
	}
	
	public function editUserData($userData)
	{
		// Trim data
		$objCommon = new Common();
		$userData = $objCommon->myTrim($userData);
		
		$check = $this->editUserDataJR($userData);
		if(empty($check) == FALSE)
		{
			$strError  = $objCommon->arrayToMessage2($check);
			return array('error' => $strError);
		}
		else
		{
			return array('success' => TRUE);
		}
	}
	public function editUserDataJR($userData)
	{
		// Error array return
		$arrError = array();
		// Check valid userId
		if(is_numeric($userData['id']) == TRUE && ($userData > 0))
		{
			$userObj = UserQuery::create()->findPk($userData['id']);
			
			//Initiate object data
			$userObj->setUsername(strtolower($userData["username"]));
			$userObj->setFirstName($userData["first_name"]);
			$userObj->setLastName($userData["last_name"]);
			// Validate data
			if($userObj->validate() == TRUE)
			{
				// No error, add row
				$userObj->save();
				
				// Get right
				$objCommon = new Common();
				$userData['unlocked']   = $objCommon->iniRight($userData['unlocked']);
				$userData['right1']     = $objCommon->iniRight($userData['right1']);
				$userData['right2']     = $objCommon->iniRight($userData['right2']);
				
				// Add right
				$objRight = $userObj->getRights();
				if(empty($objRight) == FALSE)
				{
					$objRight->setUnlocked($userData['unlocked']);
					$objRight->setRight1($userData['right1']);
					$objRight->setRight2($userData['right2']);
					$objRight->save();
				}
				else
				{
					// Add new Rights
					$objRights = new Rights();
					$objRights->setUnlocked($userData['unlocked']);
					$objRights->setRight1($userData['right1']);
					$objRights->setRight2($userData['right2']);
					$objRights->setUser($userObj);
					$objRights->save();
				}
			}
			else
			{
				// Error occurs
				$arrOutput = $userObj->getValidationFailures();
				// Build return error array
				$arrTemp = array();
				foreach ($arrOutput as $key=>$val)
				{
					$arrTemp[$key] = $val->getMessage();
				}
				$arrError["error"] = $arrTemp;
			}
		}
		else
		{
			//---------------------------//
			// Case: null input array
			//---------------------------//
			$arrError["error"] = array('other_error' => 'Invalid input data');
		}
		return $arrError;
	}
	
	public function deleteUser($userId)
	{
		// Return string
		$strError = "";
		$ret = $this->deleteUserJR($userId);
		$objCommon = new Common();
		$strError  = $objCommon->arrayToMessage($ret);
		return $strError;
	}
	public function deleteUserJR($userId)
	{
		if(is_numeric($userId) && $userId > 0)
		{
			$userObj = UserQuery::create()->findPk($userId);
			if(empty($userObj) == FALSE)
			{
				// Object user found, go deleting
				$userObj->delete();
				return array('success_delete' => 'User deleted successfully',
							  'error_delete' => '');
			}
		}
		return array('error_delete' => 'User not found',
					  'success_delete' => '');
	}
}