<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: Functions.class.php
 * Date: 11/11/14
 * Time: 15:54
 */

class Functions {
    /**
     *
     * @param string $controller
     * @param string $action (optional)
     * @param mixed[] $options  (optional)
     * @return string A Valid URL for a particular page
     */
    public static function pageLink(){
        $getArgs=func_get_args();
        $numArgs=func_num_args();
        if ($numArgs>0 ){
            $controller=($getArgs[0]);
            if($numArgs>1)
                $action=($getArgs[1]);
            else
                $action='index';
        }else{
            $controller='index';
            $action='index';
        }


        if($controller=='index' && $action=='index'){
            return WWW_ROOT;
        }
        if($action=='index'){
           return WWW_ROOT.'/'.$controller.'/';
        }
        if($numArgs==2){
            return WWW_ROOT.'/'.$controller.'/'.$action.'/';
        }
        $link= WWW_ROOT.'/'.$controller.'/'.$action.'/';
        for($i=2;$i<$numArgs;++$i)
            $link.=self::makeSlug($getArgs[$i]).'/';

        return $link;

    }

    /**
     * Converts string to a slug (removes bad characters, converts to lowercase, and adds -)
     * @param string $str String to be converted to slug
     * @return string The Slug
     */
    public static function makeSlug($str){
        return (trim(preg_replace("/\W+/",'-', $str)));
    }

    /**
     * Gets the IP of the user
     * @param bool $asLong (optional) Convert IP to long
     * @return int|string IP returned as int or string
     */
    public static function getIP($asLong=false){
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
            $ip= $_SERVER['HTTP_CLIENT_IP'];
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ip= $_SERVER['HTTP_X_FORWARDED_FOR'];
        else
        $ip=$_SERVER['REMOTE_ADDR'];

        if($asLong) return ip2long($ip);
        return $ip;

    }
    public static function getCurrentURL(){
        return ( $_SERVER["HTTPS"] != 'on' ) ? 'http://'.$_SERVER["SERVER_NAME"] :  'https://'.$_SERVER["SERVER_NAME"]
                 .( $_SERVER["SERVER_PORT"] !== 80 ) ? ":".$_SERVER["SERVER_PORT"] : ""
                    .$_SERVER["REQUEST_URI"];
    }
    public static function getTimeAsInt($months=0, $days=0, $hours=0, $minutes=0, $seconds=0){
        return ($seconds+($minutes*60)+($hours*3600)+($days*3600*24)+($months*3600*24*31));
    }
} 