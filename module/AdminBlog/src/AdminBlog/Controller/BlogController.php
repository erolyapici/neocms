<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eyapici
 * Date: 30.07.2013
 * Time: 11:10
 * To change this template use File | Settings | File Templates.
 */
namespace AdminBlog;

use Application\Controller\EntityUsingController;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use Zend\View\Model\ViewModel;

class BlogController extends EntityUsingController{

    public function indexAction(){
        $em = $this->getEntityManager();


    }
}