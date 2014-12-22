<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: user.class.php
 * Date: 21/12/14
 * Time: 21:19
 */

class userController extends BaseController {

    public function login($ajax=false)
    {
        $user=$this->loadModel('user');
        if($user->isLoggedIn()){
            header("Location: ".Functions::pageLink());
            exit;
        }
        if(isset($_POST['username']) && isset($_POST['password'])){
            if($user->login($_POST['username'], $_POST['password'], $_POST['rememberMe'])){
                if($ajax!==false){
                    echo json_encode(array('return'=>'success','url'=>Functions::pageLink()));
                }else{
                    header("Location: ".Functions::pageLink());
                }
            }else{
                if($ajax!==false){
                    echo json_encode(array('return'=>'error','errors'=> $user->getErrors()));
                }else{
                    header("Location: ".Functions::pageLink($this->getController(), $this->getAction()));

                }
            }
            exit;
        }
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