<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eyapici
 * Date: 11.07.2013
 * Time: 10:07
 * To change this template use File | Settings | File Templates.
 */

namespace Admin\Model\Entity;


class User {
    protected $_id;
    protected $_username;
    protected $_password;
    protected $_name;
    protected $_surname;
    protected $_email;
    protected $_grup_id;
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

    public function getUsername(){
        return $this->_username;
    }

    public function setUsername($username){
        $this->_username = (string)$username;
    }

    public function getPassword(){
        return $this->_password;
    }

    public function setPassword($password){
        $this->_password = (string)$password;
    }

    public function getName(){
        return $this->_name;
    }

    public function setName($name){
        $this->_name = (string)$name;
    }

    public function getSurname(){
        return $this->_surname;
    }

    public function setSurname($surname){
        $this->_surname = (string)$surname;
    }

    public function getEmail(){
        return $this->_email;
    }

    public function setEmail($email){
        $this->_email = (string)$email;
    }

    public function getGrup_id(){
        return $this->_grup_id;
    }

    public function setGrup_id($grup_id){
        $this->_grup_id = (int)$grup_id;
    }

    public function getState(){
        return $this->_state;
    }

    public function setState($satate){
        $this->_state = (int)$satate;
    }
}