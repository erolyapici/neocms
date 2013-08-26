<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eyapici
 * Date: 22.08.2013
 * Time: 11:18
 * To change this template use File | Settings | File Templates.
 */

namespace Admin\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\InputFilter\Factory;

class BlogTable extends AbstractTableGateway{
    protected $table = 'blogs';
    /**
     * @param Adapter $adapter
     */
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
            $entity = new Entity\Blog();
            $entity->setId($row->id);
            $entity->setName($row->name);
            $entity->setSeo($row->seo);
            $entities[] = $entity;
        }
        return $entities;
    }

    /**
     * @param Select $select
     * @return mixed
     */
    public function fetchList(Select $select = null){
        if($select == null){
            $select = new Select();
        }
        $select->from($this->table);

        $resultSet = $this->selectWith($select);
        $resultSet->buffer();
        return $resultSet;
    }
    /**
     * @param $id
     * @return Entity\BlogCategories|bool
     */
    public function get($id){
        $row = $this->select(array('id'=>(int)$id))->current();
        if(!$row){
            return FALSE;
        }
        return $row;
    }

    /**
     * @param array $array
     * @return bool|int
     */
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
     * @param Entity\Blog $object
     * @return bool|int
     */
    public function save(Entity\Blog $object){
        $data = array(
            'seo'  =>$object->getSeo(),
            'name'      =>$object->getName(),
            'description'   =>$object->getDescription(),
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

    /**
     * @return mixed
     */
    public function setInputFilter(){
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
                'seo' => array(
                    'name'      =>'seo',
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
                        )/*,
                        array(
                            'name'  => 'alnum',
                            'options'=>array(
                                'encoding'  => 'UTF-8',
                                'min'       => 3,
                                'max'       =>100,
                            )
                        )*/
                    )
                ),
                'description' => array(
                    'name'      =>'description',
                    'required'  =>false,
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
            )
        );
        return $inputFilter;
    }
}