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

// Local class part
use Application\Model\UserManagement;
use Application\Model\UserLoginManagement;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
    	// Check session
    	$session = new Container('base');
    	if($session->offsetExists('username') == TRUE)
    	{
    		// Not yet logged in, redirect to user login page
    		$response = $this->getResponse();
    		$response->getHeaders()->addHeaderLine('Location', '/uplink/public/application/list');
    		$response->setStatusCode(302);
    		$response->sendHeaders();
	        exit;
    	}
    	// Perform main action
    	// Initiate return array
    	$ret = array();	
    	$ret["message"] = '';
    	
    	//Check post request
    	if($this->getRequest()->getMethod() == "POST")
    	{
    		//----------------------------------------------//
	    	// Get form data
	    	//----------------------------------------------//
	    	
	    	$arrInput = $_POST;
	    	if(empty($arrInput) == FALSE)
	    	{
	    		// Initial user data input
	    		$arrData = array( 'username' => $arrInput['usn'],
	    						  'password' => $arrInput['pwd']
	    					);
	    		$objUserLogin = new UserLoginManagement();
	    		$arrCheck = $objUserLogin->checkLogin($arrData);
	    		if(empty($arrCheck) == TRUE)
	    		{
	    			// Login successfully, set session
	    			$session = new Container('base');
	    			$session->offsetSet('username', $arrData['username']);
	    			
	    			// Go to list page
	    			$response = $this->getResponse();
	    			$response->getHeaders()->addHeaderLine('Location', '/uplink/public/application/list');
    				$response->setStatusCode(302);
    				$response->sendHeaders();
			        exit;
	    		}
	    		else
	    		{
	    			$ret["message"] = $arrCheck['error_validation'];
	    		}
	    	}
	    	/*
	    	$arrUserData = array( 'username' => 'mynnt',
	    						  'password' => 'thymynguyen',
	    						  'first_name' => 'My',
	    	                      'last_name' => 'Nguyen');
	    	$objUserMgt = new UserManagement();
	    	$arrRet = $objUserMgt->addUser($arrUserData);
	    	
	    	if(empty($arrRet) == FALSE)
	    	{
	    		//return error array
	    		$ret["message01"] = $arrRet;
	    	}
	    	*/
    	}
    	return $ret;
    }
    
}