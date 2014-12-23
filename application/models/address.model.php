<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: address.model.php
 * Date: 19/11/14
 * Time: 03:28
 */

class addressModel extends Model {
    public function getAddresses($start=NULL, $limitby=NULL,$order='addressId', $by='ASC'){
        $limit='';
        if($start!==NULL && $limitby!==NULL){
            $start=intval($start);
            $limitby=intval($limitby);
            $limit="LIMIT $start, $limitby";
        }
        $by=strtoupper($by);
        if($by!='ASC' && $by!='DESC') return false;
        $orderAllowed=array("addressId","addressLine1","addressLine2","postCode","countryId");
        if(!in_array($order, $orderAllowed)) return false;
        $query=$this->getDB()->prepare("SELECT * FROM address ORDER BY $order $by $limit");
        if($query->execute()){
            return $query;
        }
        return false;
    }
    public function getAddress($id,$field='*'){
        $query=$this->getDB()->prepare("SELECT $field FROM address WHERE addressId=:id LIMIT 1");

        if($query->execute(array(':id'=>$id))){

            if($field=='*'){
                return $query->fetch();
            }
            $fetch=$query->fetch();
            return $fetch[$field];
        }
        return false;
    }
    public function dataValidation($groundName, $addressLine1, $addressLine2, $postCode, $countryId){

        if(empty($addressLine1))
            $this->addErrors('Address Line 1 cannot be empty');
        if(empty($groundName))
            $this->addErrors('Address Line 1 cannot be empty');
        if(strlen($groundName)>35)
            $this->addErrors('Ground name cannot be more than 35 characters');
        if(strlen($addressLine1)>100)
            $this->addErrors('Address Line 1 cannot be more than 100 characters');
        if(strlen($addressLine2)>100)
            $this->addErrors('Address Line 1 cannot be more than 100 characters');
        $countriesModel=$this->loadModel('countries');
        if(!$countriesModel->getCountry($countryId))
            $this->addErrors('Country Invalid');

        if($this->numErrors()>0)
            return false;
        return true;
    }
    public function add($groundName, $addressLine1, $addressLine2, $postCode, $countryId){
        $addressLine1=trim($addressLine1);
        $addressLine2=trim($addressLine2);
        $groundName=trim($groundName);
        $postCode=trim($postCode);
        $countryId=intval($countryId);

        if(!$this->dataValidation($groundName, $addressLine1, $addressLine2, $postCode, $countryId))
            return false;
        $queryInsert=$this->getDB()->prepare('INSERT INTO address (groundName, addressLine1, addressLine2, postCode, countryId) VALUES (:groundName, :addressLine1, :addressLine2, :postCode, :country)');
        $queryInsert->bindValue(':groundName',$groundName);
        $queryInsert->bindValue(':addressLine1',$addressLine1);
        $queryInsert->bindValue(':addressLine2',$addressLine2);
        $queryInsert->bindValue(':postCode',$postCode);
        $queryInsert->bindValue(':country',$countryId, PDO::PARAM_INT);
        $queryInsert->execute();
        if($queryInsert->rowCount()>0){
            return true;
        }
        $this->addErrors('Could not add address to database');
        return false;
    }

}