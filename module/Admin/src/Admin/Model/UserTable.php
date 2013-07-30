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

class UserTable extends AbstractTableGateway{
    protected $table = 'users';

    public function __construct(Adapter $adapter){
        $this->adapter = $adapter;
    }
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
   public function get($id){
        $row = $this->select(array('id'=>(int)$id))->current();
        if(!$row)
            return FALSE;
        $data = new Entity\User($row);
        return $data;
   }
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
    /**
     * @param \Zend\Db\Adapter\Adapter $adapter
     * @internal param \Zend\Db\TableGateway\TableGateway $tableGateway
     *
    public function __construct(Adapter $adapter){
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new User());
        $this->tableGateway=new TableGateway($this->table,$adapter,null,$this->resultSetPrototype);
        $this->initialize();
    }
*/
    public function fetchList(Select $select = null){
        if (null === $select)
            $select = new Select();
        $select->from($this->table);

        $resultSet = $this->selectWith($select);
        $resultSet->buffer();
        return $resultSet;
    }
    /**
     * @param $id
     * @return array|\ArrayObject|null
     * @throws \Exception
     *
    public function get($id){
        $id = (int)$id;
        $select = new Select();
        $rowset = $select->from($this->table)->where(array('id'=>$id));
        $row = $rowset->current();
        if(!$row){
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    /**
     * @param User $data
     * @throws \Exception
     *
    public function save(User $data){
        $insert_data = array(
            'username'    => $data->username,
            'name'     => $data->name,
            'surname'     => $data->surname,
            'email'     => $data->email,
        );
        $id = (int)$data->id;
        if($id == 0){
            $this->tableGateway->insert($insert_data);
        }else{
            if($this->get($id)){
                $this->tableGateway->update($insert_data,array('id'=>$id));
            }else{
                throw new \Exception("User id does not exist");
            }
        }
    }*/
}