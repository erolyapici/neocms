<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eyapici
 * Date: 28.06.2013
 * Time: 17:03
 * To change this template use File | Settings | File Templates.
 */

namespace AdminLogin\Model;


use Zend\Authentication\Storage;

class MyAuthStorage extends Storage\Session
{
    public function setRememberMe($rememberMe = 0, $time = 1209600)
    {
        if ($rememberMe == 1) {
            $this->session->getManager()->rememberMe($time);
        }
    }

    public function forgetMe()
    {
        $this->session->getManager()->forgetMe();
    }
}