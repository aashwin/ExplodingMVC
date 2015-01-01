<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: BaseController.class.php
 * Date: 29/10/14
 * Time: 19:41
 */

class BaseController {
    private $db=null;
    private $template='';
    private $title='',$description='';
    private $viewArray=array();
    private $action='', $request=array();
    private $breadcrumbs=array();
    public function __construct(){
        try{
             if(DB_TYPE=='mysql')
                $this->db=new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
             if(DEBUG_MODE)
                 $this->db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
             else
                 $this->db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);

        }catch(PDOException $e){
            $this->loadException($e);
        }

    }
    public function getDB(){
        return $this->db;
    }
    public function __destruct(){
    }
    public function addViewArray($k, $v){
        $this->viewArray[$k]=$v;
    }
    private function loadException($e){
        $this->viewArray['Exception']=$e;
        $this->loadView('ErrorPages', 'Exception');
    }
    public function loadModel($model){
        require_once(APP_DIR.'/models/'.strtolower($model).'.model.php');
        $model=$model.'Model';
        return new $model($this->db);
    }
    public function getView($view, $page){
        include(APP_DIR.'/views/'.$view.'/'.strtolower($page).'.view.php');
    }
    public function getViewArray($k=null){
        if($k===null)
            return $this->viewArray;
        return $this->viewArray[$k];
    }
    public function loadView($view, $page, $loadTemplate=true){
        if($this->template=='' || !$loadTemplate){
            $this->getView($view, $page);
        }else{

            include(APP_DIR.'/views/_templates/'.strtolower($this->template).'.template.php');

        }
    }
    public function setTemplateLayout($template){
        $this->template=$template;
    }
    public function title($title=NULL){
        if($title==NULL) return $this->title;
        $this->title=$title;
    }
    public function description($description=NULL){
        if($description==NULL) return $this->description;
        $this->c=$description;
    }
    public function getController(){
        return str_replace("Controller", '', get_class($this));
    }
    public function getAction(){
        return $this->action;
    }
    public function getRequest($index=NULL){
        if($index===NULL || ($index>=0 && $index<count($this->request)))
            return $this->request;
        return $this->request[$index];
    }
    public function setAction($s){
        $this->action=$s;
    }
    public function setRequest($s){
        $this->request=$s;
    }
    public function addCrumbs($k, $v){
        $this->breadcrumbs[$k]=$v;
        return $this;
    }
    public function getCrumbs(){
        return $this->breadcrumbs;
    }
} 