<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: Model.class.php
 * Date: 11/11/14
 * Time: 20:44
 */

class Model {
    private $db=null;
    private $errors=array();
    public function __construct($db=null){

        if($db!=null)
            $this->db=$db;
    }


    public function getDB()
    {
        return $this->db;
    }

    /**
     * Gets the errors for this model
     * @param int $index (optional) Get a particular error message. Leave null to get all errors
     * @return mixed|string Returns all errors in Model as an Array, if $index is specified then returns String.
     */
    public function getErrors($index=NULL){
        if($index==NULL) return $this->errors;
        if($index>=0 && $index<count($this->errors));
        return $this->errors[$index];
    }

    /**
     * Gets the number of errors in this model
     * @return int Number of errors in this model
     */
    public function numErrors(){
        return count($this->errors);
    }

    /**
     * Adds $msg to errors array
     * @param string $msg Message to add to errors array
     */
    public function addErrors($msg){
        $this->errors[]=$msg;
    }

    /**
     * @param string $model Which model do you want to load
     * @return Object Loaded Object
     */

    public function loadModel($model){
        require_once(APP_DIR.'/models/'.strtolower($model).'.model.php');
        $model=$model.'Model';
        return new $model($this->db);
    }

} 