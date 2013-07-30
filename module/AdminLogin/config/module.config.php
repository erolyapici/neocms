<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eyapici
 * Date: 26.06.2013
 * Time: 14:22
 * To change this template use File | Settings | File Templates.
 */

return array(
    'controllers'   => array(
        'invokables'    => array(
            'AdminLogin\Controller\AdminLogin'    => 'AdminLogin\Controller\AdminLoginController',
        ),
    ),
    'router'    => array(
        'routes'   => array(
           /* 'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults'  => array(
                        'controller'    =>'AdminLogin\Controller\AdminLogin',
                        'action'        =>'index',
                    )
                ),
            ),*/
            'admin' => array(//router's name
                'type'  => 'segment',
                'options'=>array(
                    'route' => '/admin[/][:action]',
                    'constraints'=> array(
                        'action'=>'[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults'  => array(
                        'controller'    =>'AdminLogin\Controller\AdminLogin',
                        'action'        =>'index',
                    )
                ),

            )
        )
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
    ),

    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/adminlogin'           => __DIR__ . '/../view/layout/layout.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            'adminlogin'           => __DIR__ . '/../view',
        ),
    ),
);