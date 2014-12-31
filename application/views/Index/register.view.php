<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: register.view.php
 * Date: 30/12/14
 * Time: 19:18
 */
?>

<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title><?php echo $this->title();?></title>
    <meta name="description" content="<?php echo $this->description();?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo WWW_PUBLIC;?>/css/login.css" rel="stylesheet">
   <script>
        var WWW_ROOT='<?php echo WWW_ROOT;?>';
        var WWW_PUBLIC='<?php echo WWW_PUBLIC;?>';
    </script>
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
    <![endif]-->
    <script type="text/javascript" src="<?php echo WWW_PUBLIC;?>/js/jquery-1.11.1.min.js"></script>
</head>

<body>

    <div id="LoginContainer">
        <h1>Create an Account</h1>
       <form name="Register" action="<?php echo Functions::pageLink($this->getController(), $this->getAction());?>" method="post">
           <div class="field">
               <label for="username">Username:</label>
               <input type="text" id="username" name="username" value="" />
           </div>
           <div class="field">
               <label for="email">Email:</label>
               <input type="email" id="email" name="email" value="" />
           </div>
           <div class="field">
               <label for="passwordagain">Password:</label>
               <input type="password" id="passwordagain"name="passwordagain" value="" />
           </div>
           <div class="field">
               <label for="password">Enter Again:</label>
               <input type="password" id="password"name="password" value="" />
           </div>

           <input class="button" type="submit" value="Sign up now!" />
           <?php
           if($this->userModel->numErrors()>0){
               for($i=0;$i<$this->userModel->numErrors();$i++){
                       echo '<div class="errors">'.$this->userModel->getErrors($i).' </div>';
               }
           }
           ?>
       </form>
    </div>
<script type="text/javascript" src="<?php echo WWW_PUBLIC;?>/js/all.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        console.log($("#LoginContainer").outerHeight(true)+$("#LoginContainer").offset().top>$(document).height());
        if($("#LoginContainer").outerHeight(true)+$("#LoginContainer").offset().top>$(document).height()){
            $("body,html").height($("#LoginContainer").outerHeight(true)+$("#LoginContainer").offset().top);
        }
    });
</script>
</body>
</html>
