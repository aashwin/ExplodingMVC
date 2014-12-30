<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: Core.class.php
 * Date: 29/10/14
 * Time: 19:46
 */

class Core {
    private $controller,$action, $data;
    private $options;
    public function __construct(){
        if(get_magic_quotes_gpc())
        {
            $_GET=array_map('stripslashes', $_GET);
            $_POST=array_map('stripslashes', $_POST);
            $_COOKIE=array_map('stripslashes', $_COOKIE);
        }
       $this->_handleURL();
        $this->init();
    }
    public function init(){
        $controllerClass=$this->controller;
        $actionMethod=$this->action;

        $controllerClass=$this->getControllerClass();
        $controller=new $controllerClass();
        if(is_callable(array($controller, $actionMethod), false)) {

            $controller->setRequest($this->options);
            $controller->setAction($this->action);

           if(count($this->options)>0){
               call_user_func_array(array($controller, $actionMethod), $this->options);
           }else
               $controller->$actionMethod();
        }else{

            $this->controller='Error';
            $this->action='Error404';
            $controller->setRequest($this->options);
            $controller->setAction($this->action);

            $controllerClass=$this->getControllerClass();
            $controller=new $controllerClass();
            $actionMethod=$this->action;
            $controller->$actionMethod();

        }




    }
    private function getControllerClass(){
        return $this->controller.'Controller';
    }
    private function _handleURL(){
        global $_ROUTERS;
        $URIParam=(isset($_GET['action']) ? ($_GET['action']) : '');
        $foundRoute=false;
        foreach($_ROUTERS as $v){
            if(preg_match('@'.$v[0].'@', $URIParam, $matches)){
                $exploded= explode('/', $v[1]);
                $this->controller=$exploded[0];
                $this->action=$exploded[1];
                if (!file_exists(APP_DIR . '/controllers/' . strtolower($this->controller) . '.controller.php')) {
                    $this->controller = 'Error';
                    $this->action = 'Error404';
                }
                unset($matches[0]);
                if(count($matches)>0){
                    $this->options=$matches;
                }

                $foundRoute=true;
                unset($exploded);
                break;
            }
        }

        if($foundRoute===false) {
            $URIExploded = explode('/', $URIParam);
            $countURIParts = count($URIExploded);
            for ($i = 0; $i < $countURIParts; ++$i) {
                $URIExploded[$i] = trim($URIExploded[$i]);
                if ($URIExploded[$i] == '') {
                    unset($URIExploded[$i]);
                    --$countURIParts;
                }
            }

            if (isset($URIExploded[0])) $this->controller = $URIExploded[0];
            else $this->controller = 'index';
            if (isset($URIExploded[1])) $this->action = $URIExploded[1];
            else $this->action = 'index';
            $this->data = $URIExploded;
            if (!file_exists(APP_DIR . '/controllers/' . strtolower($this->controller) . '.controller.php')) {
                $this->controller = 'Error';
                $this->action = 'Error404';
            }
            unset($URIExploded[0], $URIExploded[1]);
            if ($countURIParts > 2) {
                $this->options = $URIExploded;
            }
            unset($URIExploded);
        }
    }

    public function getController(){
        return $this->controller;
    }

    public function getAction(){
        return $this->action;
    }
    public function getRequest(){
        return $this->data;
    }
} 