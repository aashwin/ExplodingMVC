<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: user.class.php
 * Date: 21/12/14
 * Time: 21:19
 */

class userController extends BaseController {

    public function login($backUrl='')
    {
        $this->title("Login");
        $this->loadView('Index', 'login');
    }
    public function register()
    {
        $this->title("Register");
        $this->setTemplateLayout('default');
        $this->loadView('Index', 'Register');
    }
    public function noPermission()
    {
        $this->title("No Permission to access this page!");
        $this->setTemplateLayout('default');
        $this->loadView('Index', 'nopermission');
    }
} 