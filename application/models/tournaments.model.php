<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: tournaments.model.php
 * Date: 19/11/14
 * Time: 03:28
 */

class tournamentsModel extends Model {
    public function getTournaments($start=NULL, $limitby=NULL,$order='tournamentName', $by='ASC', $filterField='', $filterVal=''){
        $limit='';
        if($start!==NULL && $limitby!==NULL){
            $start=intval($start);
            $limitby=intval($limitby);
            $limit="LIMIT $start, $limitby";
        }
        $by=strtoupper($by);
        if($by!='ASC' && $by!='DESC') return false;
        $orderAllowed=array("tournamentId","tournamentName",'tournamentStart','tournamentEnd','homepagePriority');
        if(!in_array($order, $orderAllowed)) return false;
        if(!in_array($filterField, $orderAllowed) && $filterField!='') return false;
        $where='';
        if($filterField!='' && $filterVal!=''){
            $where="WHERE $filterField LIKE :value";
        }
        $query=$this->getDB()->prepare("SELECT * FROM tournaments $where ORDER BY $order $by $limit");
        if($filterField!='' && $filterVal!=''){
            $query->bindValue(':value', '%'.$filterVal.'%');
        }
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
    public function dataValidation($tournamentName, $start, $end, $img='',  $homepagePriority=0){

        if(empty($tournamentName))
            $this->addErrors('Tournament Name cannot be empty');
        if(strlen($tournamentName)>50)
            $this->addErrors('Tournament Name cannot be more than 50 characters');
        if($homepagePriority>10 || $homepagePriority<0)
            $this->addErrors('Homepage priority has to be between 0 and 10');

        if(strtotime($start)>strtotime($end))
            $this->addErrors('Tournament cannot end before it starts');
        if(!file_exists(TOURNAMENT_IMG_DIR.$img) && $img!='')
            $this->addErrors('Tournament Image File Not FOUND!');
        if($this->numErrors()>0)
            return false;
        return true;
    }
    public function add($tournamentName , $start, $end, $img){
        $tournamentName=trim($tournamentName);
        $start=date('Y-m-d' , strtotime($start));
        $end=date('Y-m-d' , strtotime($end));
        if(!$this->dataValidation($tournamentName, $start, $end, $img))
            return false;
        $queryInsert=$this->getDB()->prepare('INSERT INTO tournaments (tournamentName, tournamentStart, tournamentEnd, image) VALUES (:name, :start, :endTime, :img)');
        $queryInsert->bindValue(':name',$tournamentName);
        $queryInsert->bindValue(':start',$start);
        $queryInsert->bindValue(':endTime',$end);
        $queryInsert->bindValue(':img',$img);
        $queryInsert->execute();
        if($queryInsert->rowCount()>0){
            return true;
        }
        $this->addErrors('Could not add tournament to database');
        return false;
    }
    public function update($id, $tournamentName, $start, $end,$img){
        $tournamentName=trim($tournamentName);
        $start=date('Y-m-d' , strtotime($start));
        $end=date('Y-m-d' , strtotime($end));
        if($this->getTournament($id)===false)
        {
            $this->addErrors('Invalid Tournament ID');
            return false;
        }
        if(!$this->dataValidation($tournamentName, $start, $end, $img))
            return false;
        $queryInsert=$this->getDB()->prepare('UPDATE tournaments SET tournamentName=:name,image=:img, tournamentStart=:start, tournamentEnd=:endTime WHERE tournamentId=:id LIMIT 1');
        $queryInsert->bindValue(':name',$tournamentName);
        $queryInsert->bindValue(':start',$start);
        $queryInsert->bindValue(':endTime',$end);
        $queryInsert->bindValue(':img',$img);
        $queryInsert->bindValue(':id',$id, PDO::PARAM_INT);
        $queryInsert->execute();
        if($queryInsert->rowCount()>0){
            return true;
        }
        $this->addErrors('Could not update tournament in database');
        return false;
    }
    public function delete($id){
        $img=$this->getTournament($id, 'image');
        if($img!=''){
            if(file_exists(TOURNAMENT_IMG_DIR.$img)){
                unlink(TOURNAMENT_IMG_DIR.$img);
                unlink(TOURNAMENT_IMG_DIR.'originals/'.$img);
            }
        }
        $query=$this->getDB()->prepare('DELETE FROM tournaments WHERE tournamentId=:id LIMIT 1');
        if($query->execute(array(':id'=>$id))){
            return true;
        }
        return false;

    }
    public function updateField($id, $field,$val){
        if($this->getTournament($id)===false)
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
        }else if($field=='homepagePriority') {
            if (empty($val))
                return false;
            $val=intval($val);
            if ($val>10 || $val<0)
                return false;
        }else{
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
    }
    public function getSearchList(){
        $today=date(DB_DATE_FORMAT, time());
        $query=$this->getDB()->prepare("SELECT tournamentName, tournamentId,image,tournamentStart, tournamentEnd FROM tournaments WHERE tournamentEnd>=$today ORDER BY homepagePriority DESC, tournamentStart ASC");

        if($query->execute()){
            return $query;
        }
        return false;
    }
    public function search($q, $from, $to){

        if($q=='')
            return false;
        $ConditionArray = array();
        $ConditionArray[]='tournamentName LIKE :q';

        if($from!='') {
            $from=date(DB_DATE_FORMAT, strtotime($from));
            $ConditionArray[] = 'tournamentStart>=:from';
        }
        if($to!='') {
            $to=date(DB_DATE_FORMAT, strtotime($to));
            $ConditionArray[] = 'tournamentEnd<=:to';
        }
        $query=$this->getDB()->prepare("SELECT tournamentName, tournamentId,image,tournamentStart, tournamentEnd FROM tournaments WHERE (".implode(' AND ', $ConditionArray).") ORDER BY tournamentStart ASC LIMIT 10");
        $query->bindValue(':q', '%'.$q.'%');
        if($from!=''){
            $query->bindValue(':from', $from);
        }
        if($to!=''){
            $query->bindValue(':to', $to);
        }
        if($query->execute()){
            return $query;
        }
        return false;
    }
} 