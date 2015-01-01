<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: index.controller.php
 * Date: 29/10/14
 * Time: 20:40
 */

class indexController extends BaseController
{
    public $userModel=null;
    public function __construct(){
        parent::__construct();
        $this->userModel=$this->loadModel("user");
    }
    public function index()
    {
        $this->title("Cricket Events");
        $this->setTemplateLayout('default');
        $this->loadView('Index', 'Index');
    }
}