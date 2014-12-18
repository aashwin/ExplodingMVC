<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: Database.class.php
 * Extending MySQLi class
 * Date: 26/10/14
 * Time: 14:32
 */

class Database{
    private $link;
    private $debug=false;
    public function __construct($dbHost, $dbName, $dbUser, $dbPass, $debug=false){
       $this->link=new mysqli($dbHost, $dbUser, $dbPass, $dbName);
       $this->debug=$debug;
        if ($this->link->connect_errno) {
            if ($debug) {
                echo $this->link->connect_error;

            }
        }

    }
    public function __destruct(){
        if( $this->link)
        {
            $this->closeLink();
        }
    }
    public function closeLink()
    {
        $this->link->close();
    }
    public function query($query){
        return $this->link->query($query);
    }

} 