<?php
/*********************************************************************
 * [IndexController] 
 * @ProjectName UplinkIT Test
 * @Detail: 2013/03/21 New Create
 * @Programmer: My Nguyen
 *********************************************************************/

namespace Application\Controller;

// Zend class part
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;

// Local class part
use Application\Model\UserManagement;
use Application\Model\UserLoginManagement;
use Application\Model\UserListManagement;
use Application\Model\Common;

class ListController extends AbstractActionController
{
    public function indexAction()
    {
    	// Check session
    	if( $this->checkSession()  == TRUE)
		{
			// Perform main action
			// Initial value
			$arrRet = array();
			$arrRet['message'] = array();
			$arrRet['listUser'] = array();
			
			// Check session exists
			$session  = new Container('base');
			if($session->offsetExists('username') == TRUE)
			{
				$username = $session->offsetGet('username');
				$objUserManagement = new UserManagement();
				$arrData = $objUserManagement->getUserFullData($username);
				// Return array: current logged in user data
				$arrRet['message'] = $arrData;
			}
			return $arrRet;
		}
    }
    
    /*
     * Check if session is set, return TRUE if it is, redirect to login page if it is NOT
     */
    public function checkSession()
    {
    	// Check session
    	$session = new Container('base');
    	if($session->offsetExists('username') == FALSE)
    	{
    		// Not yet logged in, redirect to user login page
    		$response = $this->getResponse();
    		$response->getHeaders()->addHeaderLine('Location', '/uplink/public');
    		$response->setStatusCode(302);
    		$response->sendHeaders();
	        exit;
    	}
    	return TRUE;
    }
    
    public function getUserInfoAction()
	{
		// Check session
		if($this->checkSession() == TRUE)
		{
			// Check POST action
			if($this->getRequest()->getMethod() == "POST")
			{
				if(is_numeric($_POST['user_id']) && $_POST['user_id'] > 0)
				{
					$content = "";
					$userId  = $_POST['user_id'];
					// Get user information
					$objUserMngmt = new UserManagement();
					$userInfo = $objUserMngmt->getUserFullDataById($userId);
					if(empty($userInfo) == FALSE)
					{
						$objCommon = new Common();
						// Append converted string to content to return
						$content .= $objCommon->arrayToMessage($userInfo);
					}
					$response = $this->getResponse();
					$response->setStatusCode(200);
					$response->setContent($content);
					return $response;
				}
			}
		}
		else
		{
			// Login error
			$response = $this->getResponse();
			$response->setStatusCode(200);
			$response->setContent('login');
			return $response;
		}
	}
	
	public function addAction()
	{
		// Check session
		if($this->checkSession() == TRUE)
		{
			// Check post request
			if($this->getRequest()->getMethod() == "POST")
			{
				// Return string
				$content = "";
				// Get input array
				$userData['username']   = $_POST['new_username'];
				$userData['password']   = $_POST['new_password'];
				$userData['first_name'] = $_POST['new_firstname'];
				$userData['last_name']  = $_POST['new_lastname'];
				// Get right
				$userData['unlocked']   = $_POST['new_unlocked'];
				$userData['right1']     = $_POST['new_right1'];
				$userData['right2']     = $_POST['new_right2'];
				
				$objUserManagement = new UserManagement();
				$ret = $objUserManagement->addNewUser($userData);
				
				if(empty($ret['error']) == FALSE)
				{
					$content = $ret['error'];
				}
				else
					$content = 'success';
				
				// Return
				$response = $this->getResponse();
				$response->setStatusCode(200);
				$response->setContent($content);
				return $response;
			}
		}
		else
		{
			// Login error
			$response = $this->getResponse();
			$response->setStatusCode(200);
			$response->setContent('login');
			return $response;
		}
	}
	
	public function editAction()
	{
		// Check session
		if($this->checkSession() == TRUE)
		{
			// Check post request
			if($this->getRequest()->getMethod() == "POST")
			{
				// Return string
				$content = "";
				// Get input array
				$userData['id']         = $_POST['edit_userid'];
				$userData['username']   = $_POST['edit_username'];
				$userData['first_name'] = $_POST['edit_firstname'];
				$userData['last_name']  = $_POST['edit_lastname'];
				// Get right
				$userData['unlocked']   = $_POST['edit_unlocked'];
				$userData['right1']     = $_POST['edit_right1'];
				$userData['right2']     = $_POST['edit_right2'];
				
				$objUserManagement = new UserManagement();
				$ret = $objUserManagement->editUserData($userData);
				
				if(empty($ret['error']) == FALSE)
				{
					$content = $ret['error'];
				}
				else
					$content = 'success';
				
				// Return
				$response = $this->getResponse();
				$response->setStatusCode(200);
				$response->setContent($content);
				return $response;
			}
		}
		else
		{
			// Login error
			$response = $this->getResponse();
			$response->setStatusCode(200);
			$response->setContent('login');
			return $response;
		}
	}
	
	public function deleteAction()
	{
		if($this->checkSession() == TRUE)
		{
			// Check post request
			if($this->getRequest()->getMethod() == "POST")
			{
				// Return string
				$content = "";
				
				$userId = $_POST['user_id'];
				$objUserManagement = new UserManagement();
				$str = $objUserManagement->deleteUser($userId);
		
				$content  = $str;
				// Return
				$response = $this->getResponse();
				$response->setStatusCode(200);
				$response->setContent($content);
				return $response;
			}
		}
		else
		{
			// Login error
			$response = $this->getResponse();
			$response->setStatusCode(200);
			$response->setContent('login');
			return $response;
		}
	}
	
	public function getJsonListAction()
	{
		if($this->checkSession() == TRUE)
		{
			// Check post request
			if($this->getRequest()->getMethod() == "POST")
			{
				$json = '';
				$objUList = new UserListManagement();
				$json = $objUList->getListUserJson();
				// Return
				$response = $this->getResponse();
				$response->setStatusCode(200);
				$response->setContent($json);
				return $response;
			}
		}
		else
		{
			// Login error
			$response = $this->getResponse();
			$response->setStatusCode(200);
			$response->setContent('login');
			return $response;
		}
	}
	
	public function logoutAction()
	{
		// Check session
    	$session = new Container('base');
		$session->getManager()->destroy(); 
		$response = $this->getResponse();
		$response->getHeaders()->addHeaderLine('Location', '/uplink/public');
		$response->setStatusCode(302);
		$response->sendHeaders();
		exit;
	}
}
