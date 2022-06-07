<?php
namespace Application\Model;

use Propel\User;
use Propel\UserQuery;

class UserLoginManagement
{
	/*
	 * Function: checkLoginUser
	 * InputArr: username => $username, password => $pwd
	 * Return: $retArr
	 *               + success: $retArr['message'] = TRUE, $retArr['data'] = null array
	 *               + failure: $retArr['message'] = FALSE, $retArr['data'] = 'error message'
	 */
	public function checkLogin($arrInput)
	{
		$arrRet = array();
		// Check validation of login data
		$arrResult = $this->checkValidateLogin($arrInput);
		if($arrResult['result'] == FALSE)
		{
			// Login failed
			$arrRet['error_validation'] = $arrResult['message'];
		}
		return $arrRet;
	}
	
	/*
	 * Check validate Login
	 */
	public function checkValidateLogin($arrInput)
	{
		$arrRet = array();
		$arrRet['result'] = FALSE;
		// Check input data
		if(is_array($arrInput) == TRUE && empty($arrInput) == FALSE)
		{		
			$objUserQuery = new UserQuery();
			// Check if username exists
			$user = $objUserQuery->findOneByUsername(strtolower($arrInput['username']));
			if(empty($user) == FALSE)
			{
				// User exists, check password
				$pwd = $user->getPassword();
				if($pwd == md5($arrInput['password']))
				{
					// Valid user
					$arrRet['result']  = TRUE;
					$arrRet['message'] = '';
					return $arrRet;
				}
			}
		}
		$arrRet['message'] = 'Invalid username or password'; // MESSAGE_01
		return $arrRet;
	}
}