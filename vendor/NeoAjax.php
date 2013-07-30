<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eyapici
 * Date: 09.07.2013
 * Time: 10:04
 * To change this template use File | Settings | File Templates.
 */

class NeoAjax {
    public $js = "";
    public function strip($str){
        return addcslashes(preg_replace('!\s+!u', ' ',$str),"'");
    }
    public function reload($forceGet = true){
        $this->js.="location.reload($forceGet);";
    }
    /**
     * @param $js
     */
    public function script($js){
        $js = str_replace("\r\n" , "" , $js);
        $js = str_replace("\n" , "" , $js);
        $js = trim($js);

        //$js = addcslashes($js,'"');
        $this->js.= $js;
    }
    public function alert($str){
        $this->js.="alert('$str');";
    }
    public function html($selector, $html){
        $this->js.="$('$selector').html('$html');";
    }
    public function showModal($selector){
        $this->js.="$('$selector').modal('show');";
    }
    /**
     *
     */
    public function run(){
        exit(json_encode(array('script'=>$this->js)));
    }

    public function getResult(){
        return array('script'=>$this->js);
    }
}