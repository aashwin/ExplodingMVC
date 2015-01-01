<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: event_ratings.model.php
 * Date: 1/1/15
 * Time: 20:080
 */

class event_ratingsModel extends Model {
    public function get($id){
        $query=$this->getDB()->prepare("SELECT SUM(rating)/COUNT(rating) as endRating FROM event_ratings WHERE eventId=:id");

        if($query->execute(array(':id'=>$id))){


            $fetch=$query->fetch();
            return $fetch['endRating']*100;
        }
        return 0;
    }
    public function alreadyRated($user, $id){
        $user=intval($user);
        $id=intval($id);

        $query=$this->getDB()->prepare("SELECT count(rating) as rated FROM event_ratings WHERE eventId=:id AND ratingUser=:user LIMIT 1");

        if($query->execute(array(':id'=>$id, ':user'=>$user))!==false){

            $fetch=$query->fetch();

            return intval($fetch['rated']);
        }

        return false;
    }
    public function add($id, $rate){
        $id=intval($id);
        $rate=intval($rate);

        if($rate!=0 && $rate!=1)
            return false;

        $userModel=$this->loadModel("user");
        if($userModel->isLoggedIn()===false)
            return false;

        if($this->alreadyRated($userModel->loggedInUserData('userId'), $id)==1)
            return false;

        $queryInsert=$this->getDB()->prepare('INSERT INTO event_ratings (eventId,rating, ratingUser, ratedIp, ratedTime) VALUES (:id,:rating, :user, :ip, :time)');
        $queryInsert->bindValue(':id',$id, PDO::PARAM_INT);
        $queryInsert->bindValue(':rating',$rate, PDO::PARAM_INT);
        $queryInsert->bindValue(':user',$userModel->loggedInUserData('userId'), PDO::PARAM_INT);
        $queryInsert->bindValue(':ip',Functions::getIP(true), PDO::PARAM_INT);
        $queryInsert->bindValue(':time',time(), PDO::PARAM_INT);
        $queryInsert->execute();
        if($queryInsert->rowCount()>0)
            return $this->get($id);


        return false;
    }

}