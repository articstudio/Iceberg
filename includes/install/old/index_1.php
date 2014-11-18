<!DOCTYPE html>
<!--[if lt IE 8]><html class="no-js ie7 oldie"  lang="<?php echo get_lang_iso(); ?>"><![endif]-->
<!--[if IE 8]><html class="no-js ie8 oldie"  lang="<?php echo get_lang_iso(); ?>"><![endif]-->
<!--[if IE 9]><html class="no-js ie9"  lang="<?php echo get_lang_iso(); ?>"><![endif]-->
<!--[if gt IE 9]><!--><html class="no-js"  lang="<?php echo get_lang_iso(); ?>"><!--<![endif]-->
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
        <title>Iceberg v<?php print_text( ICEBERG_VERSION ); ?> - <?php print_text('INSTALLATION'); ?> - <?php print_text(get_install_step()); ?></title>
        <link rel="shortcut icon" href="<?php print get_install_url(); ?>img/iceberg.ico">
        <link rel="stylesheet" type="text/css" href="<?php print get_dependences_url(); ?>bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="<?php print get_dependences_url(); ?>bootstrap/css/bootstrap-theme.min.css">
        <link rel="stylesheet" type="text/css" href="<?php print get_install_url(); ?>install.css">
        <script src="<?php print get_dependences_url(); ?>js/modernizr.js"></script>
    </head>
    <body>
        
        <header id="header">
            <h1>
                <img src="<?php print get_install_url(); ?>/img/iceberg_header.jpg" class="brand" alt="Iceberg  v<?php print_html_attr( ICEBERG_VERSION ); ?>" />
            </h1>
        </header>
        
        <div id="container">
            <div class="block-table block-table-full">
            
                <div class="block-table-row">
                    <sidebar id="sidebar" class="block-table-cell">
                        <section id="sidebar-reverse"></section>
                        <nav>
                            <?php foreach (get_install_steps() AS $key => $value): ?>
                            <p class="option <?php print_html_attr(get_install_request_step()==$key ? 'active' : ''); ?>">
                                <img src="<?php print get_install_url() . 'img/icon_' . strtolower($value) . '.png'; ?>" alt="<?php print_html_attr(get_install_step()); ?>" />
                                <span><?php print_text(get_install_step()); ?></span>
                            </p>
                            <?php endforeach; ?>
                        </nav>
                        
                        <footer id="footer">
                            Iceberg v<?php print ICEBERG_VERSION; ?>  &copy; <a href="http://www.articstudio.com" title="Developed by Artic Studio" target="_blank">Artic Studio</a>
                        </footer>
                    </sidebar>

                    <article id="article" class="block-table-cell">
                        %CONTENT%
                    </article>
                </div>

            </div>
        </div>
        
        <div id="iceberg-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="iceberg-modal-label" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="iceberg-modal-label"></h3>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <a href="#" class="btn button-close" data-dismiss="modal" aria-hidden="true"><?php print_text('Close'); ?></a>
                <a href="#" class="btn button-save btn-primary"><?php print_text('Save'); ?></a>
            </div>
        </div>
        
        <div id="loading">
            <img src="<?php print get_install_url(); ?>/img/loading.gif" class="img" alt="<?php print_text('Loading...'); ?>" />
            <span class="title"></span>
        </div>
        
        <script src="<?php print get_dependences_url(); ?>jquery/jquery.min.js"></script>
        <script src="<?php print get_dependences_url(); ?>bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php print get_install_url(); ?>install.js"></script>

    </body>
</html>