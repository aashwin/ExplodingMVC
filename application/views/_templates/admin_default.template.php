<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: admin_default.template.php
 * Date: 29/10/14
 * Time: 22:54
 */
$viewArray=$this->getViewArray();


?>
<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title><?php echo $this->title();?></title>
    <meta name="description" content="<?php echo $this->description();?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo WWW_PUBLIC;?>/css/adminmain.css" rel="stylesheet" type="text/css">

    <link  href="<?php echo WWW_PUBLIC;?>/css/admin_animations.css"  rel="stylesheet" type="text/css">
    <link href="<?php echo WWW_PUBLIC;?>/css/admin_mobile.css" media="only screen and (max-width: 599px)" rel="stylesheet" type="text/css">
    <link href="<?php echo WWW_PUBLIC;?>/css/admin_medium.css" media="only screen and (min-width: 600px)" rel="stylesheet" type="text/css">
    <link href="<?php echo WWW_PUBLIC;?>/css/admin_desktop.css" media="only screen and (min-width: 1000px)" rel="stylesheet" type="text/css">
    <link href="<?php echo WWW_PUBLIC;?>/css/jquery-ui.css" rel="stylesheet" type="text/css">
    <script>
        var WWW_ROOT='<?php echo WWW_ROOT;?>';
        var WWW_PUBLIC='<?php echo WWW_PUBLIC;?>';
    </script>
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
    <![endif]-->
    <script type="text/javascript" src="<?php echo WWW_PUBLIC;?>/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="<?php echo WWW_PUBLIC;?>/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?php echo WWW_PUBLIC;?>/js/admin/adminmain.js"></script>
</head>

<body>
    <header>
        <a href="#" class="showMenu"></a>
        <nav id="navigation">
            <ul class="left">
                <li><a href="<?php echo Functions::pageLink($this->getController());?>" <?php echo ($viewArray['page']=='index')?'class="active"':''; ?>>Dashboard</a></li>
                <li><a href="<?php echo Functions::pageLink($this->getController(),'tournaments');?>" <?php echo ($viewArray['page']=='tournaments')?'class="active"':''; ?>>Tournaments</a></li>
                <li><a href="<?php echo Functions::pageLink($this->getController(),'events');?>" <?php echo ($viewArray['page']=='events')?'class="active"':''; ?>>Events</a></li>
                <li><a href="<?php echo Functions::pageLink($this->getController(),'teams');?>" <?php echo ($viewArray['page']=='teams')?'class="active"':''; ?>>Teams</a></li>
                <li><a href="<?php echo Functions::pageLink($this->getController(),'users');?>" <?php echo ($viewArray['page']=='users')?'class="active"':''; ?>>User</a></li>
            </ul>
            <ul class="right">
                <li><span>Hello <?php echo $this->userModel->loggedInUserData('username');?></span></li>
                <li><a href="<?php echo Functions::pageLink('user', 'logout');?>">Logout</a></li>
            </ul>
        </nav>
    </header>
    <section id="container">
        <?php
        if(count($_SESSION['SuccessMessages'])){
            for($i=0;$i<count($_SESSION['SuccessMessages']);$i++){
                echo '<div class="success">'.$_SESSION['SuccessMessages'][$i].'</div>';
            }
        }
        if(count($_SESSION['ErrorMessages'])){
            for($i=0;$i<count($_SESSION['ErrorMessages']);$i++){
                echo '<div class="error">'.$_SESSION['ErrorMessages'][$i].'</div>';
            }
        }
        unset($_SESSION['ErrorMessages'],$_SESSION['SuccessMessages']);

        $this->getView($view, $page); ?>
    </section>
    <footer>Copyright <?php echo date('Y');?> &copy; Aashwin</footer>
<script type="text/javascript" src="<?php echo WWW_PUBLIC;?>/js/all.js"></script>
</body>
</html>
