<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        
        <title>Iceberg v<?php print_text( ICEBERG_VERSION ); ?></title>
        
        <link rel="shortcut icon" href="<?php print_html_attr( DEFAULT_ADMIN_THEME_URL ); ?>img/iceberg.ico" />
        
        <!--[if lt IE 9]>
        <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        
        <?php theme_head(); ?>
        <script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
    </head>
    <body class="<?php print in_admin_login() ? 'overflow' : ''; ?>">
