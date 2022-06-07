<?php 
namespace Propel; 

    use Zend\ModuleManager\ModuleManager; 

    class Module 
    {     
        public function init(ModuleManager $moduleManager) {     
            \Propel::init(__DIR__.'/conf/uplink-conf.php');         
            set_include_path(__DIR__.'/classes'.PATH_SEPARATOR.get_include_path());     
        }
        
        public function getAutoloaderConfig() {         
            return array(             
                'Zend\Loader\ClassMapAutoloader' => array(
                    __DIR__.'/autoload_classmap.php',
                ),
				/*
                'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__.'/classes/uplink/'.__NAMESPACE__,
                	),
                ),*/
             );
        }
		/*
        public function getConfig()
        {
        	return include _DIR_.'/config/module.config.php';
        }
		*/
    }