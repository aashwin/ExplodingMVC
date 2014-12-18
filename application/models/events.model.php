<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: tournaments.model.php
 * Date: 19/11/14
 * Time: 03:28
 */

class events extends Model {
    public function getEvents($start=NULL, $limitby=NULL,$order='eventName', $by='ASC'){
        $limit='';
        if($start!==NULL && $limitby!==NULL){
            $start=intval($start);
            $limitby=intval($limitby);
            $limit="LIMIT $start, $limitby";
        }
        $by=strtoupper($by);
        if($by!='ASC' && $by!='DESC') return false;
        $orderAllowed=array("eventId","eventName","tournamentId","startTime","addressId");
        if(!in_array($order, $orderAllowed)) return false;
        $query=$this->getDB()->prepare("SELECT * FROM events ORDER BY $order $by $limit");
        if($query->execute()){
            return $query;
        }
        return false;
    }
    public function getEvent($id,$field='*'){
        $query=$this->getDB()->prepare("SELECT $field FROM events WHERE eventId=:id LIMIT 1");

        if($query->execute(array(':id'=>$id))){

            if($field=='*'){
                return $query->fetch();
            }
            $fetch=$query->fetch();
            return $fetch[$field];
        }
        return false;
    }
    public function dataValidation($eventName, $tournamentId, $addressId, $startTime){
        $addressModel=$this->loadModel('address');
        $tournamentModel=$this->loadModel('tournaments');

        if(empty($eventName))
            $this->addErrors('Event Name cannot be empty');
        if(strlen($eventName)>60)
            $this->addErrors('Event Name cannot be more than 60 characters');
        if($addressId==0)
            $this->addErrors('Please Select a tournament');
        else if($addressModel->getAddress($addressId)===false)
            $this->addErrors('Address Invalid');
        if($addressId==0)
            $this->addErrors('Please select a tournament');
        else if($tournamentModel->getTournament($tournamentId)===false)
            $this->addErrors('Tournament Invalid');

        if($this->numErrors()>0)
            return false;
        return true;
    }
    public function add($eventName, $tournamentId, $addressId, $startTime){
        $eventName=trim($eventName);
        $tournamentId=intval($tournamentId);
        $addressId=intval($addressId);
        $startTime=date(DB_DATETIME_FORMAT , strtotime($startTime));
        if(!$this->dataValidation($eventName, $tournamentId, $addressId, $startTime))
            return false;
        $queryInsert=$this->getDB()->prepare('INSERT INTO events (eventName, tournamentId, addressId, startTime) VALUES (:name, :tournament, :address, :startTime)');
        $queryInsert->bindValue(':name',$eventName);
        $queryInsert->bindValue(':tournament',$tournamentId, PDO::PARAM_INT);
        $queryInsert->bindValue(':address',$addressId, PDO::PARAM_INT);
        $queryInsert->bindValue(':startTime',$startTime);
        $queryInsert->execute();
        if($queryInsert->rowCount()>0){
            return true;
        }
        $this->addErrors('Could not add events to database');
        return false;
    }
    public function update($id,$eventName, $tournamentId, $addressId, $startTime){
        $eventName=trim($eventName);
        $tournamentId=intval($tournamentId);
        $addressId=intval($addressId);
        $startTime=date(DB_DATETIME_FORMAT , strtotime($startTime));
        if(!$this->dataValidation($eventName, $tournamentId, $addressId, $startTime))
            return false;
        $queryInsert=$this->getDB()->prepare('UPDATE events SET eventName=:name, tournamentId=:tournament, addressId=:address, startTime=:startTime WHERE tournamentId=:id LIMIT 1');
		$queryInsert->bindValue(':name',$eventName);
        $queryInsert->bindValue(':tournament',$tournamentId, PDO::PARAM_INT);
        $queryInsert->bindValue(':address',$addressId, PDO::PARAM_INT);
	 	$queryInsert->bindValue(':id',$id, PDO::PARAM_INT);
        $queryInsert->bindValue(':startTime',$startTime);
        $queryInsert->execute();
        if($queryInsert->rowCount()>0){
            return true;
        }
        $this->addErrors('Could not update events in database');
        return false;
    }
    public function delete($id){
        $query=$this->getDB()->prepare('DELETE FROM events WHERE eventId=:id LIMIT 1');
        if($query->execute(array(':id'=>$id))){
            return true;
        }
        return false;

    }
} 