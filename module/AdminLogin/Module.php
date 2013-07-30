<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eyapici
 * Date: 25.06.2013
 * Time: 11:17
 * To change this template use File | Settings | File Templates.
 */
namespace AdminLogin;

use Zend\Db\ResultSet\ResultSet;
use Zend\Mvc\ModuleRouteListener;
class Module{
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
}