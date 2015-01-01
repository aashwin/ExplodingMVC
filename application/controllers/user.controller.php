<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: user.class.php
 * Date: 21/12/14
 * Time: 21:19
 */

class userController extends BaseController {
    public $userModel=null;
    public function __construct(){
        parent::__construct();
        $this->userModel=$this->loadModel("user");
    }
    public function login($ajax=false,$permission=false)
    {
        if(!isset($_SESSION['lastURL']))
             $_SESSION['lastURL']=isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

        $this->addViewArray('permission', $permission);
        if($permission=='nopermission'){
            $this->userModel->logout();
        }else if($this->userModel->isLoggedIn()){
            header("Location: ".Functions::pageLink());
            exit;
        }
        if(isset($_POST['username']) && isset($_POST['password'])){
            if($this->userModel->login($_POST['username'], $_POST['password'], $_POST['rememberMe'])){

                if(isset($_SESSION['lastURL']) && strpos($_SESSION['lastURL'], WWW_ROOT)===0){
                    $url=$_SESSION['lastURL'];
                    unset($_SESSION['lastURL']);
                }else{
                    $url=Functions::pageLink();
                }

                if($ajax=='ajax'){
                    echo json_encode(array('return'=>'success','url'=>$url));
                }else{
                    header("Location: ".$url);
                }
            }else{
                if($ajax=='ajax'){
                    echo json_encode(array('return'=>'error','errors'=> $this->userModel->getErrors()));
                }else{
                    header("Location: ".Functions::pageLink($this->getController(), $this->getAction()));

                }
            }
            exit;
        }
        $this->title("Login");
        $this->loadView('Index', 'login');
    }
    public function logout(){
        $this->userModel->logout();
        header("Location: ".Functions::pageLink());
        exit;
    }
    public function activate($id, $key){
        if($this->userModel->isLoggedIn()){
            header("Location: ".Functions::pageLink());
            exit;
        }
        $userInfo=$this->userModel->getUserDataFromID($id);
        if($userInfo['activation_key']!=NULL){
            $this->userModel->activateAccount($id);
            $this->userModel->loginAfterSignup($userInfo);

        }
        header("Location: ".Functions::pageLink());
        exit;


    }
    public function register()
    {
        if($this->userModel->isLoggedIn()){
            header("Location: ".Functions::pageLink());
            exit;
        }
        if(isset($_POST['username'])){
            $create=$this->userModel->create($_POST['username'], $_POST['password'], $_POST['passwordagain'], $_POST['email']);
            if($create!==false){
                $userInfo=$this->userModel->getUserDataFromID($create);

                $message='<h1>Thank you for signing up</h1>';
                $message.='To complete your registration follow this link: <a href="'.Functions::pageLink('user','activate', $create, $userInfo['activation_key']).'">'.Functions::pageLink('user','activate',$create, $userInfo['activation_key']).'</a>';
                $message.="<br />Thank you once again :)";
                if(!Functions::HTMLmail('aashwin@hotmail.co.uk', $_POST['email'], 'Your registration at Cricket Events', $message)){
                    $this->addViewArray('mailSent', "There was a issue sending your email, please contact an administration.");
                }
                $this->title("Thank you for signing up");
                $this->setTemplateLayout('default');
                $this->loadView('Index', 'Register_Success');
                exit;
            }
        }
        $this->title("Register");
        $this->setTemplateLayout('');
        $this->loadView('Index', 'Register');
    }
    public function noPermission()
    {
        $this->userModel->logout();
        header("Location: ".Functions::pageLink($this->getController(), 'login'));
        exit;
    }
} 