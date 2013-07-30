<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eyapici
 * Date: 08.07.2013
 * Time: 14:35
 * To change this template use File | Settings | File Templates.
 */

namespace Admin\Controller;

use Zend\Db\Sql\Predicate\In;
use Zend\Db\Sql\Select;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Adapter\Iterator;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use NeoAjax;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver;
use Zend\Json\Json;
use Admin\Model\Entity\User;
use Zend\I18n\Validator\Alpha;
use Zend\InputFilter\Factory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator;

class UsersController extends AbstractActionController{
    protected $table;
    protected $inputFilter;

    public function indexAction(){
        $select     = new Select();
        $request    = $this->getRequest();
        $post       = $request->getPost();
        $username   = $post->get('username');
        $name       = $post->get('name');
        $surname    = $post->get('surname');
        $email      = $post->get('email');
        $state      = (int)$post->get('state',-1);

        $order_by   = $this->params()->fromRoute('order_by')? $this->params()->fromRoute('order_by')    : 'id';
        $order      = $this->params()->fromRoute('order')   ? $this->params()->fromRoute('order')       : Select::ORDER_DESCENDING;
        $page       = $this->params()->fromRoute('page')    ? $this->params()->fromRoute('page')        : 1;

        if(!empty($username)){
            $select->where->like("username","%$username%");
        }
        if(!empty($name)){
            $select->where->like("name","%$name%");
        }
        if(!empty($surname)){
            $select->where->like("surname","%$surname%");
        }
        if(!empty($email)){
            $select->where->like("email","%$email%");
        }
        if(is_int($state) && $state > -1){
            $select->where(array("state"=>$state));
        }

        $data = $this->getTable()->fetchList($select->order($order_by. ' '.$order));

        $itemsPerPage = 10;
      // $data->current();

        $paginator = new Paginator(new Iterator($data));
        $paginator
                ->setCurrentPageNumber($page)
                ->setItemCountPerPage($itemsPerPage)
                ->setPageRange(7);

        return new ViewModel(array(
            'order_by'=>$order_by,
            'order'=>$order,
            'page'=>$page,
            'paginator' =>$paginator
        ));
    }
    public function editAction(){
        $neoAjax = new neoAjax();
        $id = (int)$this->params()->fromRoute('id');


        if(!empty($id)){
            $request    = $this->getRequest();
            $post       = $request->getPost();
            $post_id    = $post->get('id');

            if(empty($post_id)){

                $data = $this->getTable()->get($id);
                $renderer = new PhpRenderer();
                $resolver = new Resolver\TemplateMapResolver();
                $resolver->setMap(array(
                    'edit'=>__DIR__ . '../../../../view/admin/users/edit.phtml'
                ));
                $renderer->setResolver($resolver);

                $viewModel = new ViewModel();
                $viewModel->setTemplate('edit')
                    ->setVariables(array(
                        'data'=>$data,
                        'save_url' => $this->url()->fromRoute('users', array('action'=>'edit','id'=>$id)),
                    ));
                $html = $neoAjax->strip($renderer->render($viewModel));
                $neoAjax->html('#myModal',$html);
                $neoAjax->showModal('#myModal');
            }else{

                if($request->isPost()){
                    $inputFilter = $this->setInputFilter();
                    $inputFilter->setData($post);

                    if($inputFilter->isValid()){

                        $entity = new User($inputFilter->getRawValues());
                        $this->getTable()->save($entity);
                        $neoAjax->alert('İşlem başarılı şekilde gerçekleşti!');
                        $neoAjax->reload();
                    }else{
                        $messages = $inputFilter->getMessages();
                        if(!empty($messages)){
                            foreach($messages AS $key=>$message){
                                $neoAjax->html('#'.$key.'_error',$neoAjax->strip(implode(" ",$message)));
                            }
                        }
                    }
                }
            }


        }else{
            $neoAjax->alert('Kayıt bulunamadı!');
        }
        $response = $this->getResponse();
        $response->setContent(\Zend\Json\Json::encode($neoAjax->getResult()));
        return $response;
    }
    public function addAction(){
        $neoAjax = new neoAjax();
        $request    = $this->getRequest();
        $post       = $request->getPost();
        $username   = $post->get('username',false);

        if($username === FALSE){
            $renderer = new PhpRenderer();
            $resolver = new Resolver\TemplateMapResolver();
            $resolver->setMap(array(
                'add' => __DIR__ . '../../../../view/admin/users/add.phtml'
            ));
            $renderer->setResolver($resolver);


            $viewModel = new ViewModel();
            $viewModel->setTemplate('add')
                ->setVariables(array(
                    'save_url'  => $this->url()->fromRoute('users', array('action'=>'add')),
                ));

            $html = $neoAjax->strip($renderer->render($viewModel));
            $neoAjax->html('#myModal',$html);
            $neoAjax->showModal('#myModal');

        }else{
            $form = new \NeoForm('user');

            if($request->isPost()){

                $form->setInputFilter($this->getInputFilter());
                $this->setForm($form);
                $form->setData($request->getPost());

                if($form->isValid()){
                    $entity = new User($form->getData());
                    $entity->setState(1);
                    $entity->setGrup_id(1);
                    $entity->setPassword(md5("123456"));
                    $this->getTable()->save($entity);
                    $neoAjax->alert('İşlem başarılı şekilde gerçekleşti!');
                    $neoAjax->reload();
                }else{
                    $messages = $form->getMessages();
                    if(!empty($messages)){
                        foreach($messages AS $key=>$message){
                            $neoAjax->html('#'.$key.'_error',$neoAjax->strip(implode(" ",$message)));
                        }
                    }

                }
            }
        }
        $response = $this->getResponse();
        $response->setContent(\Zend\Json\Json::encode($neoAjax->getResult()));
        return $response;
    }

    public function setInputFilter(){
        if(!$this->inputFilter){
            $factory = new Factory();
            $inputFilter = $factory->createInputFilter(
                array(
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
                    )
                )
            );

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
    public function setForm( $form){
        $form->add(array(
                'name' => 'id',
                'type' => 'hidden',
                'filters'  => array(
                    array('name'=>'Int'),
                )
            )
        );
        $form->add(array(
                'name' => 'username',
                'type' => 'text',
                'options' => array(
                    'label' => 'Kullanıcı Adı',
                ),
            )
        );
        $form->add(array(
                'name' => 'name',
                'type' => 'Text',
                'options' => array(
                    'label' => 'Ad',
                ),
            )
        );
        $form->add(array(
                'name' => 'surname',
                'type' => 'Text',
                'options' => array(
                    'label' => 'SoyAd',
                ),
            )
        );
        $form->add(array(
                'name' => 'email',
                'type' => 'Text',
                'options' => array(
                    'label' => 'Email',
                ),
            )
        );
        $form->add(array(
                'name' => 'submit',
                'type' => 'Submit',
                'attributes' => array(
                    'value' => 'Go',
                    'id' => 'submitbutton',
                ),
            )
        );
    }
    /**
     * @return array|object
     */
    public function getTable(){
        if(!$this->table){
            $sm = $this->getServiceLocator();

            $this->table = $sm->get('User\Model\UserTable');

        }
        return $this->table;
    }

}