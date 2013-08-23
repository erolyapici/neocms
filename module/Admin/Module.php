<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eyapici
 * Date: 25.06.2013
 * Time: 11:17
 * To change this template use File | Settings | File Templates.
 */
namespace Admin;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Admin\Model\UserTable;
use Admin\Model\UserGroupTable;
use Admin\Model\BlogCategoriesTable;
use Admin\Model\TagTable;
use Admin\Model\BlogTable;

class Module{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig(){
        return array(
            'factories' => array(
                'Admin/Model/UserTable'    => function($sm){
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new UserTable($dbAdapter);
                    return $table;
                },
                'Admin/Model/UserGroupTable'    => function($sm){
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new UserGroupTable($dbAdapter);
                    return $table;
                },
                'Admin/Model/BlogCategoriesTable'    => function($sm){
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new BlogCategoriesTable($dbAdapter);
                    return $table;
                },
                'User/Model/TagTable'    => function($sm){
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new TagTable($dbAdapter);
                    return $table;
                },
                'Admin/Model/BlogTable' => function($sm){
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new BlogTable($dbAdapter);
                    return $table;
                }
            )
        );
    }
}