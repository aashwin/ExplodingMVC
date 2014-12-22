<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: login.view.php
 * Date: 21/12/14
 * Time: 21:18
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
        <h1>Existing User</h1>
       <form name="Login" action="<?php echo Functions::pageLink($this->getController(), $this->getAction());?>" method="post">
           <div class="field">
               <label for="username">Username/Email:</label>
               <input type="text" id="username" name="username" value="" />
           </div>
           <div class="field">
               <label for="password">Password:</label>
               <input type="password" id="password"name="password" value="" />
           </div>
           <label for="rememberMe" class="rememberMe"><input type="checkbox" value="1" name="rememberMe" id="rememberMe" /> Remember?</label><input class="button" type="submit" id="LogMeIn" value="Login now" />
       </form>
    </div>
<script type="text/javascript" src="<?php echo WWW_PUBLIC;?>/js/all.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
       $("#LogMeIn").click(function(e){
           var username=$("#username");
           if(username.val()==''){
               username.addClass('error');
           }else{
               $("form").slideUp(600);
               $(".errors").remove();
               var $loginH1=$("h1");
               var h1Text=$loginH1.html();
               $loginH1.html("Logging you in...");
               $.post(
                   $(this).closest('form').attr("action")+'/ajax/',
                   {'username' : username.val(), 'password':$("#password").val(), 'rememberMe':$("#rememberMe").val()},
                   function(data){
                       if(data.return=='success') {
                           $loginH1.html("Success :)");
                           $("form").html("Redirecting you...").stop().slideDown(300);
                           location.href=data.url;
                       }else{
                           for(var i=0;i<data.errors.length;i++){
                               $("form").append('<div class="errors">'+data.errors[i]+'</div>');
                           }
                           username.addClass("error");
                           $("#password").addClass("error");
                           $loginH1.html(h1Text);
                           $("form").slideDown(600);
                       }
                   },
                   "JSON"
               );
           }
           e.preventDefault();
           return false;
       });
    });
</script>
</body>
</html>
