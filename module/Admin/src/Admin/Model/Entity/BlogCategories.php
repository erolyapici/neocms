<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eyapici
 * Date: 15/08/13
 * Time: 21:17
 * To change this template use File | Settings | File Templates.
 */

namespace Admin\Model\Entity;


class BlogCategories {
    protected $_id;
    protected $_name;
    protected $_description;
    protected $_state;

    public function __construct($options = null){
        if(is_array($options) || is_object($options)){
            $this->setOptions($options);
        }
    }

    /**
     * Let's Options set
     * @param array $options
     */
    public function setOptions($options){
        $methods = get_class_methods($this);
        foreach($options AS $key=>$value){
            $method = 'set'.ucfirst($key);
            if(in_array($method,$methods)){
                $this->$method($value);
            }
        }
    }

    public function __set($name,$value){
        $method = 'set'.$name;
        if(!method_exists($this,$method)){
            throw new \Exception('Invalid Method');
        }
        $this->$method($value);
    }

    public function __get($name){
        $method = 'get'.$name;
        if(!method_exists($this,$method)){
            throw new \Exception('Invalid Method');
        }
        return $this->$method();
    }

    public function getId(){
        return $this->_id;
    }

    public function setId($id){
        $this->_id = (int)$id;
    }

    public function getName(){
    return $this->_name;
}

    public function setName($name){
        $this->_name = (string)$name;
    }

    public function getDescription(){
        return $this->_description;
    }

    public function setDescription($description){
        $this->_description = (string)$description;
    }
    public function getState(){
        return $this->_state;
    }

    public function setState($satate){
        $this->_state = (int)$satate;
    }
}