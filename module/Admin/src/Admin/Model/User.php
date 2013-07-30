<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eyapici
 * Date: 08.07.2013
 * Time: 14:38
 * To change this template use File | Settings | File Templates.
 */
namespace Admin\Model;
// Add these import statements
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator;
use Zend\InputFilter\Input;

class User implements InputFilterAwareInterface{
    public $id;
    public $username;
    public $password;
    public $name;
    public $surname;
    public $email;
    public $grup_id;
    public $state;
    protected $inputFilter;

    public function exchangeArray($data){
        $this->id       = (!empty($data['id']))         ? $data['id']       : null;
        $this->username = (!empty($data['username']))   ? $data['username'] : null;
        $this->password = (!empty($data['password']))   ? $data['password'] : null;
        $this->name     = (!empty($data['name']))       ? $data['name']     : null;
        $this->surname  = (!empty($data['surname']))    ? $data['surname']  : null;
        $this->email    = (!empty($data['email']))      ? $data['email']    : null;
        $this->grup_id  = (!empty($data['grup_id']))    ? $data['grup_id']  : null;
        $this->state    = (!empty($data['state']))      ? $data['state']    : null;
    }

    // Add content to these methods:
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getData(array$form = array()){

    }
    public function getInputFilter(){
        if(!$this->inputFilter){
            $inputFilter    = new InputFilter();
            $factory        = new InputFactory();

            /*  $inputFilter->add($factory->createInput(array(
                  'name'      =>'id',
                  'required'  => true,
                  'filters'   => array(
                      array('name'=>'int'),
                  )
              )));*/
            $inputFilter->add($factory->createInput(array(
                'name'      =>'name',
                'required'  =>true,
                'filters'   =>array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators'=>array(
                    array(
                        'name'  => 'StringLength',
                        'options'=>array(
                            'encoding'  => 'UTF-8',
                            'min'       => 3,
                            'max'       =>100,
                        )
                    )
                )
            )));
            $inputFilter->add($factory->createInput(array(
                'name'      =>'surname',
                'required'  =>true,
                'filters'   =>array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators'=>array(
                    array(
                        'name'  => 'StringLength',
                        'options'=>array(
                            'encoding'  => 'UTF-8',
                            'min'       => 3,
                            'max'       =>100,
                        )
                    )
                )
            )));
            $inputFilter->add($factory->createInput(array(
                'name'      =>'username',
                'required'  =>true,
                'filters'   =>array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators'=>array(
                    array(
                        'name'  => 'StringLength',
                        'options'=>array(
                            'encoding'  => 'UTF-8',
                            'min'       => 3,
                            'max'       =>100,
                        )
                    )
                )
            )));
           /* $inputFilter->add(array(
                'type' => 'Zend\Form\Element\Email',
                'name' => 'email',
                'options' => array(
                    'label' => 'Your email address',
                ),
            ));
*/
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}