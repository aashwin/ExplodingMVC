<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: template_menu.model.php
 * Date: 11/11/14
 * Time: 20:24
 */

class templateModel extends Model{
    public function menuModel(){

        $Query=$this->getDB()->query("SELECT sportId, sportName FROM sports ORDER BY navOrder ASC");
       return $Query;
    }
} 