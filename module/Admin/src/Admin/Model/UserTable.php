<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eyapici
 * Date: 08.07.2013
 * Time: 14:38
 * To change this template use File | Settings | File Templates.
 */
namespace Admin\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\InputFilter\Factory;

class UserTable extends AbstractTableGateway{
    protected $table = 'users';
    protected $inputFilter;

    public function __construct(Adapter $adapter){
        $this->adapter = $adapter;
    }

    /**
     * @param Select $select
     * @return array
     */
    public function fetchAll(Select $select = null){
        if($select == null){
            $select = new Select();
        }
        $select->from($this->table);
        $resultSet = $this->selectWith($select);
        $entities = array();
        foreach($resultSet AS $row){
            $entity = new Entity\User();
            $entity->setId($row->id);
            $entity->setUsername($row->username);
            $entity->setPassword($row->password);
            $entity->setName($row->name);
            $entity->setSurname($row->surname);
            $entity->setEmail($row->email);
            $entity->setGrup_id($row->grup_id);
            $entity->setState($row->state);

            $entities[] = $entity;
        }
        return $entities;
    }

    /**
     * @param $id
     * @return Entity\User|bool
     */
    public function get($id){
        $row = $this->select(array('id'=>(int)$id))->current();
        if(!$row)
            return FALSE;
        $data = new Entity\User($row);
        return $data;
    }
    public function saveArray(array$array){
        $id = FALSE;
        if(isset($array['id'])){
            $id = (int)$array['id'];
            unset($array['id']);
        }
        if($id == 0){
            if(!$this->insert($array)){
                return FALSE;
            }else{
                return $this->getLastInsertValue();
            }
        }elseif($this->get($id)){
            if(!$this->update($array,array('id'=>$id))){
                return FALSE;
            }
            return $id;
        }
        return FALSE;
    }
    /**
     * @param Entity\User $object
     * @return bool|int
     */
    public function save(Entity\User $object){
        $data = array(
            'username'  =>$object->getUsername(),
            'name'      =>$object->getName(),
            'surname'   =>$object->getSurname(),
            'email'     =>$object->getEmail(),
            'grup_id'   =>$object->getGrup_id(),
            'state'     =>$object->getState(),
        );

        $id = (int)$object->getId();
        if($id == 0){
            if(!$this->insert($data)){
                return FALSE;
            }else{
                return $this->getLastInsertValue();
            }
        }elseif($this->get($id)){
            if(!$this->update($data,array('id'=>$id))){
                return FALSE;
            }
            return $id;
        }
        return FALSE;
    }
    public function fetchList(Select $select = null){
        if (null === $select)
            $select = new Select();
        $select->from($this->table);

        $resultSet = $this->selectWith($select);
        $resultSet->buffer();
        return $resultSet;
    }

    /**
     * @return \Zend\InputFilter\InputFilterInterface
     */
    public function setInputFilter(){
        if(!$this->inputFilter){
            $factory = new Factory();
            $inputFilter = $factory->createInputFilter(
                array(
                    'id' => array(
                        'name'      =>'id',
                        'required'  =>false,
                        'filters'  => array(
                            array('name' => 'Int'),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'Between',
                                'options' => array(
                                    'min' => 1,
                                    'max' => 1000,
                                ),
                            ),
                        ),
                    ),
                    'grup_id' => array(
                        'name'      =>'id',
                        'required'  =>true,
                        'filters'  => array(
                            array('name' => 'Int'),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'Between',
                                'options' => array(
                                    'min' => 1,
                                    'max' => 1000,
                                ),
                            ),
                        ),
                    ),
                    'state' => array(
                        'name'      =>'state',
                        'required'  =>true,
                        'filters'  => array(
                            array('name' => 'Int'),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'Between',
                                'options' => array(
                                    'min' => 1,
                                    'max' => 1000,
                                ),
                            ),
                        ),
                    ),
                    'name' => array(
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
                    ),
                    'surname' => array(
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
                    ),
                    'username' => array(
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
                    ),
                    'email' => array(
                        'name'      =>'email',
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
                    )
                )
            );

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}