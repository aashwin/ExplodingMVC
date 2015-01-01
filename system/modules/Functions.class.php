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
        global $_ROUTERS;
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
        foreach($_ROUTERS as $v){
            if(strtolower($v[1])==strtolower($controller).'/'.strtolower($action)){

                unset($getArgs[0], $getArgs[1]);
                if($numArgs-2!=count($v[2])){
                    $getArgs[$numArgs-1]=0;
                }
                $getArgs=array_map('self::makeSlug', $getArgs);

                $link=stripslashes(str_replace($v[2], $getArgs ,WWW_ROOT.'/'.$v[0] ));
                return $link;
            }
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
    public static function timeTill($start, $format){
        $seconds = $start - time();

        $days = floor($seconds / 86400);
        $seconds %= 86400;

        $hours = floor($seconds / 3600);
        $seconds %= 3600;

        $minutes = floor($seconds / 60);
        $seconds %= 60;


        return str_replace(array('%d', '%h', '%m', '%s'), array($days, $hours, $minutes, $seconds), $format);
    }
    public static function timeAgo($time){

        $periods = array("second", "minute", "hour", "day", "week", "month", "year");
        $lengths = array("60", "60", "24", "7", "4.35", "12");
        $difference = time() - $time;

        for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
            $difference /= $lengths[$j];
        }

        $difference = round($difference);
        if ($difference != 1) {
            $periods[$j] .= "s";
        }

        return "$difference $periods[$j] ago";


    }
    public static function HTMLmail($from, $to, $subject, $message, $replyTo=''){
        $headers = "From: ".$from."\r\n";
        $headers .= "Reply-To: ".($replyTo==''?$from:$replyTo)."\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $message='<html><body>'.$message;
        $message .='</body></html>';
       return mail($to, $subject, $message, $headers);
    }
}