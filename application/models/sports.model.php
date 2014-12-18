<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: sports.model.php
 * Date: 17/11/14
 * Time: 22:05
 */

class sports extends Model {
    public function nextNavOrder(){
        $query=$this->getDB()->query("SELECT navOrder FROM sports ORDER BY navOrder DESC LIMIT 1");
        $query->execute();
        return $query->fetchObject()->navOrder+1;
    }
    public function getSport($id,$field='*'){
        $query=$this->getDB()->prepare("SELECT $field FROM sports WHERE sportId=:id LIMIT 1");

        if($query->execute(array(':id'=>$id))){

            if($field=='*'){
                 return $query->fetch();
            }
            $fetch=$query->fetch();
            return $fetch[$field];
        }
        return false;
    }
    public function getSports($start=NULL, $limitby=NULL,$order='navOrder', $by='ASC'){
        $limit='';
        if($start!==NULL && $limitby!==NULL){
            $start=intval($start);
            $limitby=intval($limitby);
            $limit="LIMIT $start, $limitby";
        }
        $by=strtoupper($by);
        if($by!='ASC' && $by!='DESC') return false;
        $orderAllowed=array("sportId","navOrder","sportName");
        if(!in_array($order, $orderAllowed)) return false;
        $query=$this->getDB()->prepare("SELECT * FROM sports ORDER BY $order $by $limit");
        if($query->execute()){
            return $query;
        }
        return false;
    }
    public function delete($id){
        $query=$this->getDB()->prepare('DELETE FROM sports WHERE sportId=:id LIMIT 1');
        if($query->execute(array(':id'=>$id))){
            return true;
        }
        return false;

    }
    public function dataValidation($sportName, $navOrder){
        if(empty($sportName))
            $this->addErrors('Sport Name cannot be empty');
        if(strlen($sportName)>30)
            $this->addErrors('Sport Name cannot be more than 30 characters');
        if($navOrder==0)
            $this->addErrors('Navigation Order cannot be 0 or empty');
        if($this->numErrors()>0)
            return false;
        return true;
    }
    public function add($sportName, $navOrder){

        $sportName=trim($sportName);
        $navOrder=intval($navOrder);
        if($this->dataValidation($sportName, $navOrder)===false)
            return false;
        $queryInsert=$this->getDB()->prepare('INSERT INTO sports (sportName, navOrder) VALUES (:name, :order)');
        $queryInsert->bindValue(':name',$sportName);
        $queryInsert->bindValue(':order',$navOrder, PDO::PARAM_INT);
        $queryInsert->execute();
        if($queryInsert->rowCount()>0){
            return true;
        }
        $this->addErrors('Could not add sport to database');
        return false;
    }
    public function update($id, $sportName, $navOrder){
        if($this->getSport($id)===false)
        {
            $this->addErrors('Invalid Sport ID');
            return false;
        }
        $sportName=trim($sportName);
        $navOrder=intval($navOrder);
        if($this->dataValidation($sportName, $navOrder)===false)
            return false;
        $queryInsert=$this->getDB()->prepare('UPDATE sports SET sportName=:name, navOrder=:order WHERE sportId=:id LIMIT 1');
        $queryInsert->bindValue(':name',$sportName);
        $queryInsert->bindValue(':order',$navOrder, PDO::PARAM_INT);
        $queryInsert->bindValue(':id',$id, PDO::PARAM_INT);
        $queryInsert->execute();
        if($queryInsert->rowCount()>0){
            return true;
        }
        $this->addErrors('Could not update sport to database');
        return false;
    }
} 