<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: default.template.php
 * Date: 29/10/14
 * Time: 22:54
 */

$templateModel=$this->loadModel('template');
$tournamentsModel=$this->loadModel('tournaments');

?>
<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title><?php echo $this->title();?></title>
    <meta name="description" content="<?php echo $this->description();?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes"/>

    <link href="<?php echo WWW_PUBLIC;?>/css/main.css" rel="stylesheet" type="text/css">
    <link href="<?php echo WWW_PUBLIC;?>/css/grid.css" rel="stylesheet" type="text/css">
    <script type="text/javascript">
        var WWW_PUBLIC='<?php echo WWW_PUBLIC;?>';
        var WWW_ROOT='<?php echo WWW_ROOT;?>';
    </script>

    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
    <![endif]-->
    <script type="text/javascript" src="<?php echo WWW_PUBLIC;?>/js/jquery-1.11.1.min.js"></script>

</head>

<body>
    <div id="overlay"></div>
   <header>
       <a id="showMenu" href="#"></a>
       <div id="logo">
           <a href="<?php echo Functions::pageLink('Index');?>">CricketEvents</a>
       </div>
       <nav id="navigation">
           <ul>
               <li id="closeMenu"><a href="#">Close</a></li>
               <li><a href="<?php echo Functions::pageLink('Index');?>">Home</a></li>
               <li><a href="<?php echo Functions::pageLink('Index');?>">What's on?</a></li>
               <li><a href="<?php echo Functions::pageLink('Index', 'All');?>">All Fixtures</a></li>
               <li><a href="<?php echo Functions::pageLink('Index');?>">Teams</a></li>
               <li><a href="<?php echo Functions::pageLink('Index');?>">Contact Us</a></li>
           </ul>
       </nav>
       <div class="clear"></div>
   </header>
    <section id="container">

        <div class="grid_mob_12 grid_med_8 grid_lrg_9">
            <div id="content">
                <?php $this->getView($view, $page); ?>
            </div>
        </div>
        <div class="grid_mob_12 grid_med_4 grid_lrg_3">
            <div id="sidebar">
                <h3>Find an Event</h3>
                <form name="Search" action="<?php echo Functions::pageLink();?>" method="POST">
                    <div class="field">
                        <label for="tournamentField">Which Tournament?</label>
                        <select name="tournamentField" id="tournamentField">
                            <option value="0">Which Tournament?</option>
                            <?php
                            $q=$tournamentsModel->getSearchList();
                            if($q!==false)
                                while($r=$q->fetch())
                                    echo '<option value="'.$r['tournamentId'].'">'.$r['tournamentName'].'</option>';
                            ?>
                        </select>
                    </div>
                </form>
            </div>
        </div>
        <div class="clear"></div>
    </section>
   <footer>

   </footer>
    <script type="text/javascript" src="<?php echo WWW_PUBLIC;?>/js/main.js"></script>
    <script type="text/javascript" src="<?php echo WWW_PUBLIC;?>/js/all.js"></script>

</body>
</html>
