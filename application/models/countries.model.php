<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: address.model.php
 * Date: 19/11/14
 * Time: 03:28
 */

class countriesModel extends Model {
    public function getCountries($start=NULL, $limitby=NULL,$order='countryName', $by='ASC'){
        $limit='';
        if($start!==NULL && $limitby!==NULL){
            $start=intval($start);
            $limitby=intval($limitby);
            $limit="LIMIT $start, $limitby";
        }
        $by=strtoupper($by);
        if($by!='ASC' && $by!='DESC') return false;
        $orderAllowed=array("countryId","countryName","countryCode");
        if(!in_array($order, $orderAllowed)) return false;
        $query=$this->getDB()->prepare("SELECT * FROM countries ORDER BY $order $by $limit");
        if($query->execute()){
            return $query;
        }
        return false;
    }
    public function getCountry($id,$field='*'){
        $query=$this->getDB()->prepare("SELECT $field FROM countries WHERE countryId=:id LIMIT 1");

        if($query->execute(array(':id'=>$id))){

            if($field=='*'){
                return $query->fetch();
            }
            $fetch=$query->fetch();
            return $fetch[$field];
        }
        return false;
    }
} 