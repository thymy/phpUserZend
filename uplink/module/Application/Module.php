<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager        = $e->getApplication()->getEventManager();
        
        // Module route listener
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        //-----------------------------------------------------------//
        // My part
        //-----------------------------------------------------------//
        //$eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'authPreDispatch'),1);
    }

    /*
     * Authenticating user to redirect to appropriate page 
     */
    /*
    public function authPreDispatch($event)
    {
    	// Check session
    	$session = new Container('base');
    	// Initial url
    	$url = '/uplink/public/application';
    	
    	if($session->offsetExists('username') == TRUE)
    	{
    		// Already logged in, redirect to list page
    		$url .= '/list';
    	}
    	// Redirect to url
    	$response = $event->getResponse();
    	$response->getHeaders()->addHeaderLine('Location', $url);
    	$response->setStatusCode(302);
    	$response->sendHeaders();
    	//exit;
    }
    */
    public function getConfig()
    {
        return include __DIR__.'/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__.'/src/'.__NAMESPACE__,
                ),
            ),
        );
    }
}
