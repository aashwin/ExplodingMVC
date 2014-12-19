<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: tournaments.model.php
 * Date: 19/11/14
 * Time: 03:28
 */

class tournaments extends Model {
    public function getTournaments($start=NULL, $limitby=NULL,$order='tournamentName', $by='ASC'){
        $limit='';
        if($start!==NULL && $limitby!==NULL){
            $start=intval($start);
            $limitby=intval($limitby);
            $limit="LIMIT $start, $limitby";
        }
        $by=strtoupper($by);
        if($by!='ASC' && $by!='DESC') return false;
        $orderAllowed=array("tournamentId","tournamentName","tournamentSport",'tournamentStart','tournamentEnd');
        if(!in_array($order, $orderAllowed)) return false;
        $query=$this->getDB()->prepare("SELECT * FROM tournaments ORDER BY $order $by $limit");
        if($query->execute()){
            return $query;
        }
        return false;
    }
    public function getTournament($id,$field='*'){
        $query=$this->getDB()->prepare("SELECT $field FROM tournaments WHERE tournamentId=:id LIMIT 1");

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

        if(empty($tournamentName))
            $this->addErrors('Tournament Name cannot be empty');
        if(strlen($tournamentName)>50)
            $this->addErrors('Tournament Name cannot be more than 50 characters');

        if(strtotime($start)>strtotime($end))
            $this->addErrors('Tournament cannot end before it starts');

        if($this->numErrors()>0)
            return false;
        return true;
    }
    public function add($tournamentName , $start, $end){
        $tournamentName=trim($tournamentName);
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
    }
    public function update($id, $tournamentName, $start, $end){
        $tournamentName=trim($tournamentName);
        $start=date('Y-m-d' , strtotime($start));
        $end=date('Y-m-d' , strtotime($end));
        if($this->getTournament($id)===false)
        {
            $this->addErrors('Invalid Tournament ID');
            return false;
        }
        if(!$this->dataValidation($tournamentName, $start, $end))
            return false;
        $queryInsert=$this->getDB()->prepare('UPDATE tournaments SET tournamentName=:name, tournamentStart=:start, tournamentEnd=:endTime WHERE tournamentId=:id LIMIT 1');
        $queryInsert->bindValue(':name',$tournamentName);
        $queryInsert->bindValue(':start',$start);
        $queryInsert->bindValue(':endTime',$end);
        $queryInsert->bindValue(':id',$id, PDO::PARAM_INT);
        $queryInsert->execute();
        if($queryInsert->rowCount()>0){
            return true;
        }
        $this->addErrors('Could not update tournament in database');
        return false;
    }
    public function delete($id){
        $query=$this->getDB()->prepare('DELETE FROM tournaments WHERE tournamentId=:id LIMIT 1');
        if($query->execute(array(':id'=>$id))){
            return true;
        }
        return false;

    }
} 