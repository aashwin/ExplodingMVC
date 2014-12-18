<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: teams.model.php
 * Date: 17/11/14
 * Time: 22:05
 */

class teams extends Model {

    public function getTeam($id,$field='*'){
        $query=$this->getDB()->prepare("SELECT $field FROM teams WHERE teamId=:id LIMIT 1");

        if($query->execute(array(':id'=>$id))){

            if($field=='*'){
                 return $query->fetch();
            }
            $fetch=$query->fetch();
            return $fetch[$field];
        }
        return false;
    }
    public function getTeams($start=NULL, $limitby=NULL,$order='teamId', $by='ASC'){
        $limit='';
        if($start!==NULL && $limitby!==NULL){
            $start=intval($start);
            $limitby=intval($limitby);
            $limit="LIMIT $start, $limitby";
        }
        $by=strtoupper($by);
        if($by!='ASC' && $by!='DESC') return false;
        $orderAllowed=array("teamId","teamName","teamSport");
        if(!in_array($order, $orderAllowed)) return false;
        $query=$this->getDB()->prepare("SELECT * FROM teams ORDER BY $order $by $limit");
        if($query->execute()){
            return $query;
        }
        return false;
    }
    public function delete($id){
        $query=$this->getDB()->prepare('DELETE FROM teams WHERE teamId=:id LIMIT 1');
        if($query->execute(array(':id'=>$id))){
            return true;
        }
        return false;

    }
    public function dataValidation($name){
        if(empty($name))
            $this->addErrors('Team Name cannot be empty');
        if(strlen($name)>40)
            $this->addErrors('Team Name cannot be more than 40 characters');
        if($this->numErrors()>0)
            return false;
        return true;
    }
    public function add($name){

        $name=trim($name);
        if($this->dataValidation($name)===false)
            return false;
        $queryInsert=$this->getDB()->prepare('INSERT INTO teams (teamName) VALUES (:name)');
        $queryInsert->bindValue(':name',$name);
        $queryInsert->execute();
        if($queryInsert->rowCount()>0){
            return true;
        }
        $this->addErrors('Could not add team to database');
        return false;
    }
    public function update($id, $name){
        if($this->getTeam($id)===false)
        {
            $this->addErrors('Invalid Team ID');
            return false;
        }
        $name=trim($name);
        if($this->dataValidation($name)===false)
            return false;
        $queryInsert=$this->getDB()->prepare('UPDATE teams SET teamName=:name WHERE teamId=:id LIMIT 1');
        $queryInsert->bindValue(':name',$name);
        $queryInsert->bindValue(':id',$id, PDO::PARAM_INT);
        $queryInsert->execute();
        if($queryInsert->rowCount()>0){
            return true;
        }
        $this->addErrors('Could not update team to database');
        return false;
    }
} 