<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eyapici
 * Date: 25.06.2013
 * Time: 11:38
 * To change this template use File | Settings | File Templates.
 */
namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\Model\Album;
use Zend\Form\Form;
class AlbumController extends AbstractActionController{
    protected $albumTable;

    /**
     * @return array|ViewModel
     */
    public function indexAction(){
        return new ViewModel(array(
            'albums'    => $this->getAlbumTable()->fetchAll(),
        ));
    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function addAction(){
        $request = $this->getRequest();
        $form = new \NeoForm('album');

        if($request->isPost()){
            $album = new Album();

            $form->setInputFilter($album->getInputFilter());
            $this->setForm($form);
            $form->setAttribute('method','post');

            $form->setData($request->getPost());

            if($form->isValid()){
                $album->exchangeArray($form->getData());
                $this->getAlbumTable()->saveAlbum($album);

                return $this->redirect()->toRoute('album');
            }
        }
        return array('form' => $form);
    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function editAction(){
        $id = (int) $this->params()->fromRoute('id',0);
        if(!$id){
            return $this->redirect()->toRoute('album',array('action'=>'add'));
        }

        try{
            $album = $this->getAlbumTable()->getAlbum($id);
        }catch (\Exception $ex){
            return $this->redirect()->toRoute('album',array('action'=>'index'));
        }
        $form = new \NeoForm('album');
        $request = $this->getRequest();

        if($request->isPost()){

            $form->setInputFilter($album->getInputFilter());
            $this->setForm($form);
            $form->setAttribute('method','post');

            $form->setData($request->getPost());
             if($form->isValid()){
                 $album->exchangeArray($form->getData());
                 $this->getAlbumTable()->saveAlbum($album);

                 return $this->redirect()->toRoute('album');
             }
        }
        return array('form' => $form,'id'=>$id,'data'=>$album);

    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function deleteAction(){
        $id = (int) $this->params()->fromRoute('id',0);
        if(!$id){
            return $this->redirect()->toRoute('album');
        }
        $request = $this->getRequest();

        if($request->isPost()){
            $del = $request->getPost('del','No');
            if($del == 'Yes'){
                $id = (int)$request->getPost('id');
                $this->getAlbumTable()->deleteAlbum($id);
            }

            return $this->redirect()->toRoute('album');
        }
        return array('id'=>$id,'album'=>$this->getAlbumTable()->getAlbum($id));
    }

    /**
     * @return array|object
     */
    public function getAlbumTable(){
        if(!$this->albumTable){
            $sm = $this->getServiceLocator();
            $this->albumTable = $sm->get('Album\Model\AlbumTable');
        }
        return $this->albumTable;
    }

    public function setForm(Form $form){
        $form->add(array(
                'name' => 'id',
                'type' => 'hidden',
                'filters'  => array(
                    array('name'=>'Int'),
                )
            )
        );
        $form->add(array(
                'name' => 'title',
                'type' => 'Text',
                'options' => array(
                    'label' => 'Title',
                ),
            )
        );
        $form->add(array(
                'name' => 'artist',
                'type' => 'Text',
                'options' => array(
                    'label' => 'Artist',
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
}