<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title><?php print_text('MEDIA'); ?></title>
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo get_dependences_url(); ?>jquery-ui/jquery-ui.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo get_dependences_url(); ?>elfinder/css/elfinder.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo get_dependences_url(); ?>elfinder/css/theme.css">
    </head>
    <body>
        <div id="elfinder"></div>
        <script type="text/javascript" src="<?php echo get_dependences_url(); ?>jquery/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo get_dependences_url(); ?>jquery/jquery-migrate.min.js"></script>
        <script type="text/javascript" src="<?php echo get_dependences_url(); ?>jquery-ui/jquery-ui.min.js"></script>
        <script type="text/javascript" src="<?php echo get_dependences_url(); ?>elfinder/js/elfinder.min.js"></script>
        <script type="text/javascript">
            var callbackFunc = "<?php print_html_attr(get_request_action()); ?>";
            var callbackAttr = "<?php print_html_attr(get_request_gp('callbackAttr', '')); ?>";
            $(document).ready(function() {
                $('#elfinder').elfinder({
                    url : '<?php echo get_elfinder_api_link(); ?>',
                    lang : '<?php echo get_lang_iso(); ?>',
                    getFileCallback : function(file) {
                            if (typeof window.opener[callbackFunc] === 'function')
                            {
                                window.opener[callbackFunc](file, callbackAttr);
                            }
                            window.close();
                    },
                    resizable: true
                }).elfinder('instance');
            });
        </script>
    </body>
</html>