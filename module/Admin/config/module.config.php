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
            'Admin\Controller\Login'        => 'Admin\Controller\LoginController',
            'Admin\Controller\Dashboard'    => 'Admin\Controller\DashboardController',
            'Admin\Controller\Users'        => 'Admin\Controller\UsersController',
            'Admin\Controller\UserGroup'    => 'Admin\Controller\UserGroupController',
            'Admin\Controller\BlogCategories'    => 'Admin\Controller\BlogCategoriesController',
            'Admin\Model\UserTable'         => 'Admin\Model\UserTable',
            'Admin\Model\UserGroupTable'    => 'Admin\Model\UserGroupTable',
            'Admin\Model\BlogCategoriesTable'    => 'Admin\Model\BlogCategoriesTable'
        ),
    ),
    'router'    => array(
        'routes'   => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/admin',
                    'defaults'  => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'Login',
                        'action'        => 'index',
                    )
                ),
            ),
            'admin'=>array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/admin[/:action]',
                    'constraints'=> array(
                        'action'=>'[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults'  => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'Login',
                        'action'        => 'index',
                    )
                ),
            ),
            'dashboard' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/admin/dashboard',
                    'defaults'  => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'Dashboard',
                        'action'        => 'index',
                    )
                ),
            ),
            'users' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/admin/users[/:action][/:id][/page/:page][/order_by/:order_by][/:order]',
                    'constraints' => array(
                        'action' => '(?!\bpage\b)(?!\border_by\b)[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                        'page' => '[0-9]+',
                        'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'order' => 'ASC|DESC',
                    ),
                    'defaults'  => array(
                        'page'          => 1,
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'Users',
                        'action'        => 'index',
                    )
                ),
            ),
            'usergroup' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/admin/usergroup[/:action][/:id][/page/:page][/order_by/:order_by][/:order]',
                    'constraints' => array(
                        'action' => '(?!\bpage\b)(?!\border_by\b)[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                        'page' => '[0-9]+',
                        'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'order' => 'ASC|DESC',
                    ),
                    'defaults'  => array(
                        'page'          => 1,
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'UserGroup',
                        'action'        => 'index',
                    )
                ),
            ),
            'blogcategories' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/admin/blogcategories[/:action][/:id][/page/:page][/order_by/:order_by][/:order]',
                    'constraints' => array(
                        'action' => '(?!\bpage\b)(?!\border_by\b)[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                        'page' => '[0-9]+',
                        'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'order' => 'ASC|DESC',
                    ),
                    'defaults'  => array(
                        'page'          => 1,
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'BlogCategories',
                        'action'        => 'index',
                    )
                ),
            ),

        )
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/admin'           => __DIR__ . '/../view/layout/layout.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
            'paginator-slide' => __DIR__ . '/../view/layout/slidePaginator.phtml',
        ),
        'template_path_stack' => array(
            'admin'           => __DIR__ . '/../view',
        ),
    ),
);