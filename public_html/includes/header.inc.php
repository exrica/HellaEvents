<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="styles/common.css">
        <link rel="stylesheet" type="text/css" media="screen and (min-width: 1024px)" href="styles/full.css">
        <link rel="stylesheet" type="text/css" media="screen and (min-width: 769px) and (max-width: 1024px)" href="styles/tab1024.css" />
        <link rel="stylesheet" type="text/css" media="screen and (min-width: 361px) and (max-width: 768px)" href="styles/tab768.css" />
        <link rel="stylesheet" type="text/css" media="screen and (max-width: 360px)" href="styles/small320.css" />
        <link rel="stylesheet" type="text/css" href="styles/datePicker.css">
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script type="text/javascript" src="js/date.js"></script>
        <script type="text/javascript" src="js/jquery.datePicker.js"></script>
        <script type="text/javascript" src="js/main.js"></script>
        <title><?php echo constant('SITENAME') ?></title>
    </head>
    <body>

        <div id="pagewrapper">
            
            <div id ="topbar">
                
                
                <div id="loginbar">
                    <div id="lbleft">
                        <a href="index.php"><?php echo constant('SITENAME') ?></a>
                    </div>
                    
                    <div id="lbright">
                    <p>
                        
                        <?php if ($loggedin) {
                            echo '<a href="?page=profile&amp;mid='
                                . $currentuser 
                                .'">'
                                . constant("PROFILE")
                                .'</a>';
                            ?>
                             | <a href="?page=logout"><?php echo constant('LOGOUT') ?></a>
                        <?php } else { ?>
                        <a href="?page=register"><?php echo constant('REGISTER') ?></a>
                        | 
                        <a href="?page=login"><?php echo constant('LOGIN') ?></a>
                        <?php } ?>
                    </p>
                    </div>
                </div>
            </div>
            
        <?php 
            if ($page == "index") { ?>
            <div id="bannerbox">
                <h1><?php echo constant('SITENAME') ?></h1>
            </div>
            
        <?php } ?>
            
            <?php require_once ("$WEB_ROOT/$APP_PATH/includes/menu.inc.php"); ?>
            
            <div id="contentwrapper">
                
            <div id="maincontent">
                    
