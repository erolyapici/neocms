<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eyapici
 * Date: 26.06.2013
 * Time: 11:12
 * To change this template use File | Settings | File Templates.
 */

use Zend\Form;
class NeoForm extends Form\Form{

    public function __construct(){
        parent::__construct();
    }

    public function  getMessageText($alias,$implode = "\n"){
        $text = "";
        $messages = $this->getMessages();
        if(!empty($messages[$alias])){
            $text = implode($implode,$messages[$alias]);
        }
        return $text;
    }
}