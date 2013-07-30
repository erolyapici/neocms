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
            'AdminBlog\Controller\Blog'    => 'AdminBlog\Controller\BlogController',
        ),
    ),
    'router'    => array(
        'routes'   => array(
            'blog'=>array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/admin/blog[/:action]',
                    'constraints'=> array(
                        'action'=>'[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults'  => array(
                        '__NAMESPACE__' => 'AdminBlog\Controller',
                        'controller'    => 'Blog',
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
            'blog'           => __DIR__ . '/../view',
        ),
    ),
);