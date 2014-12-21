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
    public function index()
    {
        $this->title("Events Homepage");
        $this->setTemplateLayout('default');
        $this->loadView('Index', 'Index');
    }

}