<?php
$lang_iso = get_lang_iso();
?><!DOCTYPE html>
<!--[if lt IE 8]><html class="no-js ie7 oldie"  lang="<?php echo $lang_iso; ?>"><![endif]-->
<!--[if IE 8]><html class="no-js ie8 oldie"  lang="<?php echo $lang_iso; ?>"><![endif]-->
<!--[if IE 9]><html class="no-js ie9"  lang="<?php echo $lang_iso; ?>"><![endif]-->
<!--[if gt IE 9]><!--><html class="no-js"  lang="<?php echo $lang_iso; ?>"><!--<![endif]-->
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Iceberg v<?php print_text( ICEBERG_VERSION ); ?></title>
        <link rel="shortcut icon" href="<?php print get_theme_url(); ?>img/iceberg.ico">
        
        <script type="text/javascript">
            var icebergAPI = "<?php echo get_iceberg_api_link(); ?>";
        </script>
        
        <?php theme_head(); ?>
    </head>
    <body class="<?php echo get_environment_controller(); ?>">