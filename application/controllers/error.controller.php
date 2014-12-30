<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: Eror404.controller.php
 * Date: 29/10/14
 * Time: 22:27
 */
class ErrorController extends BaseController {
    public $userModel=null;
    public function __construct()
    {
        parent::__construct();
        $this->userModel = $this->loadModel('user');
    }
    public function Error404(){
        header('HTTP/1.0 404 Not Found',true, 404);
        $this->title('404 Error: Page Not Found');
        $this->setTemplateLayout('default');
        $this->loadView('ErrorPages', '404');

    }
} 