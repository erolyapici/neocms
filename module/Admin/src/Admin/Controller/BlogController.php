<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eyapici
 * Date: 22.08.2013
 * Time: 14:24
 * To change this template use File | Settings | File Templates.
 */

namespace Admin\Controller;

use NeoAjax;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Db\Sql\Predicate\In;
use Zend\Db\Sql\Select;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver;
use Zend\Json\Json;

class BlogController extends  AbstractActionController{
    protected $table;

    /**
     * @return ViewModel
     */
    public function indexAction(){
        $select = new Select();
        $request = $this->getRequest();
        $post = $request->getPost();
        $name       = $post->get('name');
        $seo        = $post->get('seo');
        $state      = (int)$post->get('state',-1);


        $order_by   = $this->params()->fromRoute('order_by')? $this->params()->fromRoute('order_by')    : 'id';
        $order      = $this->params()->fromRoute('order')   ? $this->params()->fromRoute('order')       : Select::ORDER_DESCENDING;
        $page       = $this->params()->fromRoute('page')    ? $this->params()->fromRoute('page')        : 1;

        if(!empty($seo)){
            $select->where->like("seo","%$seo%");
        }
        if(!empty($name)){
            $select->where->like("name","%$seo%");
        }
        $select->columns(array('seo','name','state'));
        $data = $this->getTable()->fetchList($select->order($order_by.' '.$order));

        $itemsPerPage = 20;

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

    /**
     * @return mixed
     */
    public function addAction(){
        $neoAjax = new neoAjax();
        $request    = $this->getRequest();
        $post       = $request->getPost();

        $name   = $post->get('name',false);
        if($name === FALSE){
            $renderer = new PhpRenderer();
            $resolver = new Resolver\TemplateMapResolver();
            $resolver->setMap(array(
                'add' => __DIR__ . '../../../../view/admin/blog/add.phtml'
            ));

            $renderer->setResolver($resolver);


            $viewModel = new ViewModel();
            $viewModel->setTemplate('add')
                ->setVariables(array(
                    'save_url'  => $this->url()->fromRoute('blog', array('action'=>'add')),
                ));

            $html = $neoAjax->strip($renderer->render($viewModel));
            $neoAjax->html('#myModal',$html);
            $neoAjax->showModal('#myModal');
            $neoAjax->script("$('.modal').css('width','100% !important');");
            $neoAjax->script("var editor = CKEDITOR.replace( $('.ckeditor').attr('id') );editor.setData( '' );CKFinder.setupCKEditor( editor, BASE_PATH+'/js/ckfinder' ) ;");
        }else{
            if($request->isPost()){

                $inputFilter = $this->getTable()->setInputFilter();
                $inputFilter->setData($post);

                if($inputFilter->isValid()){

                    $array = $inputFilter->getRawValues();
                    $this->getTable()->saveArray($array);
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

        $response = $this->getResponse();
        $response->setContent(\Zend\Json\Json::encode($neoAjax->getResult()));
        return $response;
    }

    /**
     * @return mixed
     */
    public function getTable(){
        if(!$this->table){
            $sm = $this->getServiceLocator();
            $this->table = $sm->get('Admin\Model\BlogTable');
        }
        return $this->table;
    }
}