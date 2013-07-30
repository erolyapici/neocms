<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eyapici
 * Date: 05.07.2013
 * Time: 17:08
 * To change this template use File | Settings | File Templates.
 */

namespace Admin\Controller;


use Zend\Mvc\Controller\AbstractActionController;

class DashboardController extends AbstractActionController{

    public function indexAction(){
        $this->layout('layout/layout');
        return array();
    }
}