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
        $this->addViewArray('permission', $permission);
        if($permission=='nopermission'){
            $this->userModel->logout();
        }else if($this->userModel->isLoggedIn()){
            header("Location: ".Functions::pageLink());
            exit;
        }
        if(isset($_POST['username']) && isset($_POST['password'])){
            if($this->userModel->login($_POST['username'], $_POST['password'], $_POST['rememberMe'])){
                if($ajax=='ajax'){
                    echo json_encode(array('return'=>'success','url'=>Functions::pageLink()));
                }else{
                    header("Location: ".Functions::pageLink());
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

                $headers = "From: aashwin@hotmail.co.uk\r\n";
                $headers .= "Reply-To: aashwin@hotmail.co.uk\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                $message = '<html><body>';
                $message.='<h1>Thank you for signing up</h1>';
                $message.='To complete your registration follow this link: <a href="'.Functions::pageLink('user','activate', $create, $userInfo['activation_key']).'">'.Functions::pageLink('user','activate',$create, $userInfo['activation_key']).'</a>';
                $message.="<br />Thank you once again :)";
                $message .='</body></html>';
                mail($_POST['email'], 'Your registration at Cricket Events', $message, $headers);
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