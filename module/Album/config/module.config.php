<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eyapici
 * Date: 25.06.2013
 * Time: 11:22
 * To change this template use File | Settings | File Templates.
 */
return array(
    'controllers'   => array(
        'invokables'    => array(
            'Album\Controller\Album'    => 'Album\Controller\AlbumController',
        ),
    ),
    'router'    => array(
        'routes'   => array(
            'album' => array(//router's name
                'type'  => 'segment',
                'options'=>array(
                    'route' => '/album[/][:action][/:id]',
                    'constraints'=> array(
                        'action'=>'[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'    => '[0-9]+',
                    ),
                    'defaults'  => array(
                        'controller'    =>'Album\Controller\Album',
                        'action'        =>'index',
                    )
                ),

            )
        )
    ),
    'view_manager'  => array(
        'template_path_stack'   => array(
            'album'=> __DIR__ . '/../view',
        )
    )
);