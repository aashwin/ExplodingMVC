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
    <link href="<?php echo WWW_PUBLIC;?>/css/jquery-ui.css" rel="stylesheet" type="text/css">
    <script type="text/javascript">
        var WWW_PUBLIC='<?php echo WWW_PUBLIC;?>';
        var WWW_ROOT='<?php echo WWW_ROOT;?>';
    </script>

    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
    <![endif]-->
    <script type="text/javascript" src="<?php echo WWW_PUBLIC;?>/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="<?php echo WWW_PUBLIC;?>/js/jquery-ui.min.js"></script>


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
               <li><a href="<?php echo Functions::pageLink('Events', 'all');?>">What's On</a></li>
               <li><a href="<?php echo Functions::pageLink('Tournaments', 'all');?>">Series</a></li>
               <li><a href="<?php echo Functions::pageLink('Teams', 'all');?>">Teams</a></li>
           </ul>
       </nav>
       <div class="clear"></div>
   </header>
    <section id="container">

        <div class="grid_mob_12 grid_med_8 grid_lrg_9 mob_right med_left">
            <div id="content">
                <?php $this->getView($view, $page); ?>
            </div>
        </div>
        <div class="grid_mob_12 grid_med_4 grid_lrg_3 mob_left med_left">
            <div id="sidebar">
                <div class="sidebar_elem">
                    <?php
                    if(!$this->userModel->isLoggedIn()) {

                        ?>
                        <div class="grid_mob_6"><a href="<?php echo Functions::pageLink('User', 'Login'); ?>"
                                                   class="login_btn">Login</a></div>
                        <div class="grid_mob_6"><a href="<?php echo Functions::pageLink('User', 'register'); ?>"
                                                   class="signup_btn">Signup</a></div>
                        <div class="clear"></div>
                    <?php
                    }else {
                        ?>

                        <div class="grid_mob_4 grid_med_2 grid_lrg_4"><img class="profile_img" src="<?php echo $this->userModel->loggedInUserData('profileImageUrl');?>" ></div>
                        <div class="grid_mob_8 grid_med_10 grid_lrg_8">
                            <div class="profileinfo">
                                <h3>Hello <?php echo $this->userModel->loggedInUserData('username');?>!</h3>
                                Last Login: <?php echo Functions::TimeAgo($this->userModel->loggedInUserData('userLastLogin'));?> <br />
                                <a href="<?php echo Functions::pageLink('User', 'logout'); ?>"
                                   class="logout_btn" style="margin-top:5px;">Logout</a>
                            </div>
                        </div>
                        <div class="clear"></div>
                    <?php
                    }
                    ?>
                </div>

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
