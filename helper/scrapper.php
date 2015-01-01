<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: scrapper.php
 * Date: 31/12/14
 * Time: 20:57
 */
$start=microtime(true);
include('../application/config/database.config.php');
date_default_timezone_set('Europe/London');
$db=null;
error_reporting(0);
try{
    if(DB_TYPE=='mysql')
        $db=new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
        $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);


}catch(PDOException $e){
   echo ($e);
}
function curl_download($url){

    if (!function_exists('curl_init')){
        die('cURL is not installed. Install and try again.');
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    $start = strpos($output, '<span  class="large-4 medium-4 columns">WEATHER</span>');
    $end = strpos($output, '<div class="lhs_container_holder">', $start);
    $length = $end-$start;
    $output = str_replace(array("\t", "\n"), '', strip_tags(substr($output, $start, $length), "<span>"));
    return $output;
}
function makeSlug($str){
    return (trim(preg_replace("/\W+/",'-', $str)));
}


if(isset($_POST['eventName'])){
    var_dump($_POST);
    $query=$db->prepare("INSERT INTO events (eventName, tournamentId, startTime, teamOne, teamTwo, addressId) VALUES (:name, :tournament, :start, :one, :two, :address)");
    $query->bindValue(':name', $_POST['eventName']);
    $query->bindValue(':start', $_POST['startTime']);
    $query->bindValue(':tournament', $_POST['tournament']);
    $query->bindValue(':one', $_POST['teamOne']);
    $query->bindValue(':two', $_POST['teamTwo']);
    $query->bindValue(':address', $_POST['addressId']);
    $query->execute();
    echo 'Added';
    exit;
}elseif(!isset($_POST['url'])){
//
    ?>
    <form action="" method="POST" ">
        <label> URL: </label><input type="text" name="url" value="http://www.espncricinfo.com/icc-cricket-world-cup-2015/content/series/509587.html?template=fixtures">
        <select name="tournament">
            <?php
    $query=$db->query("SELECT tournamentName, tournamentId FROM tournaments");
    while($r=$query->fetch()){
        echo '<option value="'.$r['tournamentId'].'">'.$r['tournamentName'].'</option>';
    }

    ?>
</select>
        <input type="submit" value="Parse URL">
    </form>
    <?php
    exit;
}
$url=$_POST['url'];
$teamArray=array();
$query=$db->query("SELECT teamId, teamName FROM teams");
while($r=$query->fetch()){
    $teamArray[strtolower($r['teamName'])]=$r['teamId'];
}
$query=$db->query("SELECT groundName,addressId FROM address");
$addresses='';
while($r=$query->fetch()){
    $addresses.='<option value="'.$r['addressId'].'">'.$r['groundName'].'</option>';
}
if(file_exists('cache/'.makeSlug($url))){
    $html=file_get_contents('cache/'.makeSlug($url));
    echo 'From Cache';
}else {
    $f = fopen('cache/' . makeSlug($url), "w");
    $html = (curl_download($url));
    fwrite($f, $html);
    fclose($f);
}

$match = preg_match_all('#<span class="fixture_date">(.+?)</span>#ims', $html, $dates);
$match = preg_match_all('#<span class="play_team">(.+?)</span>#ims', $html, $titles);
$match = preg_match_all('#ovs\) (.+?) GMT (.+?) \|#ims', $html, $times);
if($_POST['tournament']==3){
    $match = preg_match_all('#Match, Pool [A|B] - (.+?) v (.+?)</span>#ims', $html, $teams);
} else if($_POST['tournament']==16 || $_POST['tournament']==6){
    $match = preg_match_all('#<span class="play_team">(.+?) v (.+?)</span>#ims', $html, $teams); //Big Bash League
}else if($_POST['tournament']==9){
    $match = preg_match_all('#<span class="play_team">[1-9][st|nd|rd|th] 1st Test - (.+?) v (.+?)</span>#ims', $html, $teams); //Big Bash League
}
$match = preg_match_all('#<span class="play_stadium">(.+?)</span>#ims', $html, $ground);

?>
<table border="1" cellpadding="5" cellspacing="5">
    <thead>
        <tr>
            <th>Event Date</th>
            <th>Event Name</th>
            <th>Team 1</th>
            <th>Team 2</th>
            <th>Ground</th>
        </tr>
    </thead>
    <tbody>
    <?php
        $count=count($dates[1]);
    $monthArray=array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul','Aug', 'Sept','Oct','Nov', 'Dec');
        for($i=0;$i<$count;$i++){
            $explodeDate=explode(" ", $dates[1][$i]);
            $time=explode(":", $times[1][$i]);
            $titles[1][$i]=str_replace(array($teams[1][$i],$teams[2][$i]), array('[%team1%]','[%team2%]'), $titles[1][$i]);
            $timeUnix=mktime($time[0],$time[1],0, (array_search($explodeDate[1], $monthArray)+1), intval($explodeDate[2]), ($explodeDate[1]=='Dec'?2014:2015));
            if($times[2][$i]=='(prev day)'){
                $timeUnix-=(24*3600);
            }
            echo '
            <tr>
            <form action="scrapper.php?add=1" method="POST"target="_blank">
                <td>'.date('Y-m-d H:i:s', $timeUnix).'<br />
                    <input type="text" style="width:100%" name="startTime" value="'.date('Y-m-d H:i:s', $timeUnix).'" />
                </td>
                <td>'.$titles[1][$i].'<br />
                    <input type="text" style="width:100%" name="eventName" value="'.trim($titles[1][$i]).'" /></td>
                <td>'.$teams[1][$i].'<br />
                    <input type="text" style="width:100%" name="teamOne" value="'.$teamArray[trim(strtolower($teams[1][$i]))].'" /></td>
                <td>'.$teams[2][$i].'<br />
                    <input type="text" style="width:100%" name="teamTwo" value="'.$teamArray[trim(strtolower($teams[2][$i]))].'" /></td>
                <td>'.$ground[1][$i].'<br />
                    <select name="addressId">
                    '.$addresses.'
                    </select></td>
                    <td>
                    <input type="hidden"  name="tournament" value="'.$_POST['tournament'].'" />
                    <input type="submit" value="add this event"></td>
            </form>
            </tr>';
        }

    ?>
    </tbody>
</table>
<?php
print_r($times[1]);
echo '<br />Took '.round(microtime(true)-$start,3).' seconds';