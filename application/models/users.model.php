<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: users.model.php
 * Date: 19/11/14
 * Time: 03:28
 */

class users extends Model {
    public function getUsers($start=NULL, $limitby=NULL,$order='username', $by='ASC'){
        $limit='';
        if($start!==NULL && $limitby!==NULL){
            $start=intval($start);
            $limitby=intval($limitby);
            $limit="LIMIT $start, $limitby";
        }
        $by=strtoupper($by);
        if($by!='ASC' && $by!='DESC') return false;
        $orderAllowed=array("userId","username","userEmail",'userLevel');
        if(!in_array($order, $orderAllowed)) return false;
        $query=$this->getDB()->prepare("SELECT * FROM user_login ORDER BY $order $by $limit");
        if($query->execute()){
            return $query;
        }
        return false;
    }
    public function getUser($id,$field='*'){
        $query=$this->getDB()->prepare("SELECT $field FROM user_login WHERE userId=:id LIMIT 1");

        if($query->execute(array(':id'=>$id))){

            if($field=='*'){
                return $query->fetch();
            }
            $fetch=$query->fetch();
            return $fetch[$field];
        }
        return false;
    }
    public function dataValidation($tournamentName, $start, $end){

        /*if(empty($tournamentName))
            $this->addErrors('Tournament Name cannot be empty');
        if(strlen($tournamentName)>50)
            $this->addErrors('Tournament Name cannot be more than 50 characters');

        if(strtotime($start)>strtotime($end))
            $this->addErrors('Tournament cannot end before it starts');

        if($this->numErrors()>0)
            return false;
        return true;
        */
    }
    public function add($tournamentName , $start, $end){
      /*  $tournamentName=trim($tournamentName);
        $start=date('Y-m-d' , strtotime($start));
        $end=date('Y-m-d' , strtotime($end));
        if(!$this->dataValidation($tournamentName, $start, $end))
            return false;
        $queryInsert=$this->getDB()->prepare('INSERT INTO tournaments (tournamentName, tournamentStart, tournamentEnd) VALUES (:name, :start, :endTime)');
        $queryInsert->bindValue(':name',$tournamentName);
        $queryInsert->bindValue(':start',$start);
        $queryInsert->bindValue(':endTime',$end);
        $queryInsert->execute();
        if($queryInsert->rowCount()>0){
            return true;
        }
        $this->addErrors('Could not add tournament to database');
        return false;
      */
    }
    public function update($id, $username, $password, $userLevel, $userFailedLogins=0){
        $username=trim($username);
        $userLevel=intval($userLevel);
        if($this->getUser($id)===false)
        {
            $this->addErrors('Invalid User ID');
            return false;
        }

        $queryInsert=$this->getDB()->prepare('UPDATE user_login SET username=:name, userLevel=:level, userFailedLogins=:failedLogins WHERE userId=:id LIMIT 1');
        $queryInsert->bindValue(':name',$username);
        $queryInsert->bindValue(':level',$userLevel);
        $queryInsert->bindValue(':failedLogins',$userFailedLogins);
        $queryInsert->bindValue(':id',$id, PDO::PARAM_INT);
        $queryInsert->execute();
        if($queryInsert->rowCount()>0){
            return true;
        }
        $this->addErrors('Could not update user in database');
        return false;
    }
    public function delete($id){
        $query=$this->getDB()->prepare('DELETE FROM user_login WHERE userId=:id LIMIT 1');
        if($query->execute(array(':id'=>$id))){
            return true;
        }
        return false;

    }
    public function updateField($id, $field,$val){
        /*if($this->getTournament($id)===false)
        {
            $this->addErrors('Invalid Tournament ID');
            return false;
        }
        $val=trim($val);
        if($field=='tournamentName') {
            if (empty($val))
                return false;
            if (strlen($val) > 50)
                return false;
        }
        if($field!='tournamentName'){
            return false;
        }
        $queryInsert=$this->getDB()->prepare("UPDATE tournaments SET $field=:val WHERE tournamentId=:id LIMIT 1");
        $queryInsert->bindValue(':val',$val);
        $queryInsert->bindValue(':id',$id);
        $queryInsert->execute();
        if($queryInsert->rowCount()>0){
            return true;
        }
        $this->addErrors('Could not update tournaments to database');
        return false;
        */
    }
} 