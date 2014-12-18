<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: default.template.php
 * Date: 29/10/14
 * Time: 22:54
 */

$templateModel=$this->loadModel('template');
?>
<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title><?php echo $this->title();?></title>
    <meta name="description" content="<?php echo $this->description();?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo WWW_PUBLIC;?>/css/main.css" rel="stylesheet" type="text/css">
    <link href="<?php echo WWW_PUBLIC;?>/css/bigscreen.css" media="only screen and (min-width: 950px)" rel="stylesheet" type="text/css">
    <link href="<?php echo WWW_PUBLIC;?>/css/mediumscreen.css" media="only screen and (max-width: 950px)" rel="stylesheet" type="text/css">
    <link href="<?php echo WWW_PUBLIC;?>/css/mobile.css" media="only screen and (max-width: 360px)" rel="stylesheet" type="text/css">

    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
    <![endif]-->
</head>

<body>
    <header>
        <a href="#" class="showMenu"></a>
        <a href="<?php echo Functions::pageLink('Index');?>" id="logo">BookMyTickets</a>
    </header>
    <nav id="navigation">
        <ul>
            <?php
            $query=$templateModel->menuModel();
            if($query){
                while($row=$query->fetch(PDO::FETCH_ASSOC)){
                    echo '<li><a href="'.Functions::pageLink("sports", "view", $row['sportId'], $row['sportName']).'">'.$row['sportName'].'</a></li>';
                }
            }
            ?>

        </ul>
    </nav>
    <section id="searchEvent">
        <div class="section">
            <h3>What<span class="notMobile"> are you looking for</span>?</h3>
            <input type="text" placeholder="Enter a event name..." />
        </div>
        <div class="section">
            <h3><span class="notMobile">What</span> Date<span class="small">(s)</span>?</h3>
            <input type="text" placeholder="Enter a event date..." />
        </div>
        <div class="section">
            <h3>Where? <span class="notMobile highlight small">(optional)</span></h3>
            <input type="text" placeholder="Enter a event place..." />
        </div>
        <div class="section">
            <a href="" class="advancedOption">Advanced Search >></a>
            <input type="submit" class="searchButton" value="Search for event..." />
        </div>
    </section>
    <section id="container">
        <?php $this->getView($view, $page); ?>
    </section>
    <footer></footer>
    <script type="text/javascript" src="<?php echo WWW_PUBLIC;?>/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="<?php echo WWW_PUBLIC;?>/js/main.js"></script>
</body>
</html>
