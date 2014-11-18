<?php
$lang_iso = get_lang_iso();
$steps = get_install_steps();
$step = (int)get_install_request_step();
?><!DOCTYPE html>
<!--[if lt IE 8]><html class="no-js ie7 oldie"  lang="<?php echo $lang_iso; ?>"><![endif]-->
<!--[if IE 8]><html class="no-js ie8 oldie"  lang="<?php echo $lang_iso; ?>"><![endif]-->
<!--[if IE 9]><html class="no-js ie9"  lang="<?php echo $lang_iso; ?>"><![endif]-->
<!--[if gt IE 9]><!--><html class="no-js"  lang="<?php echo $lang_iso; ?>"><!--<![endif]-->
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Iceberg v<?php print_text( ICEBERG_VERSION ); ?> - <?php print_text('INSTALLATION'); ?> - <?php print_text(get_install_step()); ?></title>
        <link rel="shortcut icon" href="<?php print get_install_url(); ?>img/iceberg.ico">
        <link rel="stylesheet" type="text/css" href="<?php print get_dependences_url(); ?>bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="<?php print get_install_url(); ?>css/install.css">
        <script src="<?php print get_dependences_url(); ?>modernizr.js"></script>
    </head>
    <body>
        
        <header id="header" class="navbar navbar-fixed-top" role="navigation">
            <img src="<?php print get_install_url(); ?>/img/iceberg_logo.png" class="brand" alt="Iceberg  v<?php print_html_attr( ICEBERG_VERSION ); ?>">
            <img src="<?php print get_install_url(); ?>/img/iceberg_header.jpg" class="brand" alt="Iceberg  v<?php print_html_attr( ICEBERG_VERSION ); ?>">
        </header>
        
        <div id="wrapper">
            
            <sidebar id="sidebar-wrapper" role="navigation">
                
                <nav class="sidebar-nav">
                    <a href="#sidebar-toggle" class="option sidebar-toggle" title="<?php print_html_attr('Toggle sidebar'); ?>">
                        <span class="glyphicon glyphicon-chevron-left toggle-left"></span>
                        <span class="glyphicon glyphicon-chevron-right toggle-right"></span>
                    </a>
                    <?php foreach ($steps AS $key => $value): ?>
                    <p class="option <?php echo $step===$key ? 'active' : ''; ?>">
                        <img src="<?php print get_install_url() . 'img/icon_' . strtolower($value) . '.png'; ?>" alt="<?php print_html_attr(get_install_step($key)); ?>" title="<?php print_html_attr(get_install_step($key)); ?>">
                        <span class="option-title"><?php print_text(get_install_step($key)); ?></span>
                    </p>
                    <?php endforeach; ?>
                </nav>
            </sidebar>
            
            <article id="page-wrapper">
                <?php print_template(ICEBERG_DIR_INSTALL . strtolower(get_install_step()) . '.php', get_install_url()); ?>
            </article>
            
        </div>
        
        <footer id="footer">
            Iceberg v<?php print ICEBERG_VERSION; ?>  &copy; <a href="http://www.articstudio.com" title="Developed by Artic Studio" target="_blank">Artic Studio</a>
        </footer>
        
        <script src="<?php print get_dependences_url(); ?>jquery/jquery.min.js"></script>
        <script src="<?php print get_dependences_url(); ?>bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php print get_dependences_url(); ?>jquery-validate/jquery.validate.min.js"></script>
        <script src="<?php print get_dependences_url(); ?>jquery-validate/iceberg-methods.js"></script>
        <script src="<?php print get_install_url(); ?>js/install.js"></script>

    </body>
</html>