<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: tournaments.model.php
 * Date: 19/11/14
 * Time: 03:28
 */

class eventsModel extends Model {

    public function getEvents($start=NULL, $limitby=NULL,$order='eventName', $by='ASC', $filterField='', $filterVal='', $explictFilter=false, $count=false, $additionalWhere=''){
        $limit='';
        if($start!==NULL && $limitby!==NULL){
            $start=intval($start);
            $limitby=intval($limitby);
            $limit="LIMIT $start, $limitby";
        }
        $by=strtoupper($by);
        if($by!='ASC' && $by!='DESC') return false;
        $orderAllowed=array("eventId","eventName","tournamentId","startTime","addressId", 'rand()');
        if(!in_array($order, $orderAllowed)) return false;
        if(!in_array($filterField, $orderAllowed) && $filterField!='') return false;
        $where='';
        if($filterField!='' && $filterVal!='' && $explictFilter===false){
            $where="WHERE $filterField LIKE :value";
        }else if($filterField!='' && $filterVal!='' && $explictFilter===true){
            $where="WHERE $filterField = :value";
        }
        if($additionalWhere!=''){
            if($where==''){
                $where='WHERE ';
            }else{
                $where.=' AND ';
            }
            $where.=$additionalWhere;
        }
        $orderby='';
        if($order!=NULL && $by!=NULL){
            $orderby="ORDER BY $order $by";
        }
        $query=$this->getDB()->prepare("SELECT ".($count?'count(eventId)':'*')." FROM events ".$where." $orderby $limit");
        if($filterField!='' && $filterVal!='' && $explictFilter===false){
            $filterVal='%'.$filterVal.'%';
        }
        if($filterField!='' && $filterVal!=''){
            $query->bindValue(':value', $filterVal);
        }
        if($query->execute()){
            return $query;
        }
        return false;
    }
    public function buildName($name, $teamOne, $teamTwo){
        $teamModel=$this->loadModel('teams');
        if(is_numeric($teamOne))
            $teamOne=$teamModel->getTeam($teamOne,'teamName');
        if(is_numeric($teamTwo))
            $teamTwo=$teamModel->getTeam($teamTwo,'teamName');
        return str_replace(array('[%team1%]','[%team2%]'), array($teamOne,$teamTwo), $name);
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
    public function dataValidation($eventName, $tournamentId,$teamOne, $teamTwo, $addressId, $startTime){
        $addressModel=$this->loadModel('address');
        $tournamentModel=$this->loadModel('tournaments');
        $teamModel=$this->loadModel('teams');

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
        if($teamOne==0)
            $this->addErrors('Please select a team #1');
        else if($teamModel->getTeam($teamOne)===false)
            $this->addErrors('Team #1 Invalid');
        if($teamTwo==0)
            $this->addErrors('Please select a team #2');
        else if($teamModel->getTeam($teamTwo)===false)
            $this->addErrors('Team #2 Invalid');
        if($teamOne==$teamTwo)
            $this->addErrors("Both Teams cannot be the same!");

        if($this->numErrors()>0)
            return false;
        return true;
    }
    public function add($eventName, $tournamentId,$teamOne, $teamTwo, $addressId, $startTime){
        $eventName=trim($eventName);
        $tournamentId=intval($tournamentId);
        $teamOne=intval($teamOne);
        $teamTwo=intval($teamTwo);
        $tournamentId=intval($tournamentId);
        $addressId=intval($addressId);
        $startTime=date(DB_DATETIME_FORMAT , strtotime($startTime));
        if(!$this->dataValidation($eventName, $tournamentId,$teamOne, $teamTwo, $addressId, $startTime))
            return false;
        $queryInsert=$this->getDB()->prepare('INSERT INTO events (eventName, tournamentId, addressId, startTime, teamOne, teamTwo) VALUES (:name, :tournament, :address, :startTime,:teamOne, :teamTwo)');
        $queryInsert->bindValue(':name',$eventName);
        $queryInsert->bindValue(':tournament',$tournamentId, PDO::PARAM_INT);
        $queryInsert->bindValue(':address',$addressId, PDO::PARAM_INT);
        $queryInsert->bindValue(':teamOne',$teamOne, PDO::PARAM_INT);
        $queryInsert->bindValue(':teamTwo',$teamTwo, PDO::PARAM_INT);
        $queryInsert->bindValue(':startTime',$startTime);
        $queryInsert->execute();
        if($queryInsert->rowCount()>0){
            return true;
        }
        $this->addErrors('Could not add events to database');
        return false;
    }
    public function update($id,$eventName, $tournamentId,$teamOne, $teamTwo, $addressId, $startTime){
        $eventName=trim($eventName);
        $tournamentId=intval($tournamentId);
        $addressId=intval($addressId);
        $teamOne=intval($teamOne);
        $teamTwo=intval($teamTwo);
        $startTime=date(DB_DATETIME_FORMAT , strtotime($startTime));
        if(!$this->dataValidation($eventName, $tournamentId,$teamOne, $teamTwo, $addressId, $startTime))
            return false;
        $queryInsert=$this->getDB()->prepare('UPDATE events SET eventName=:name, tournamentId=:tournament, addressId=:address, startTime=:startTime, teamOne=:teamOne, teamTwo=:teamTwo WHERE eventId=:id LIMIT 1');
		$queryInsert->bindValue(':name',$eventName);
        $queryInsert->bindValue(':tournament',$tournamentId, PDO::PARAM_INT);
        $queryInsert->bindValue(':address',$addressId, PDO::PARAM_INT);
	 	$queryInsert->bindValue(':id',$id, PDO::PARAM_INT);
        $queryInsert->bindValue(':startTime',$startTime);
        $queryInsert->bindValue(':teamOne',$teamOne, PDO::PARAM_INT);
        $queryInsert->bindValue(':teamTwo',$teamTwo, PDO::PARAM_INT);
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
    public function similarEvents($thisId=0, $tournamentId=0, $limit=4){
        $notThis='';
        if($thisId!=0){
            $notThis='eventId!='.$thisId.' AND ';
        }
        if($tournamentId==0)
            return $this->getEvents(0,$limit, 'rand()', 'asc', '','',false, false, $notThis."startTime>'".date(DB_DATETIME_FORMAT, time())."'");

        return $this->getEvents(0,$limit, 'rand()', 'asc', 'tournamentId',$tournamentId, true, false, $notThis."startTime>'".date(DB_DATETIME_FORMAT, time())."'");
        }
} 