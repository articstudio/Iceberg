<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>File Manager</title>

        <link rel="stylesheet" type="text/css" media="screen" href="<?php print DEFAULT_ADMIN_THEME_URL; ?>css/jquery-ui-1.8.18/jquery-ui.css">
        <script type="text/javascript" src="<?php print DEFAULT_ADMIN_THEME_URL; ?>js/jquery-1.7.2.js"></script>

        <link rel="stylesheet" type="text/css" media="screen" href="<?php print DEFAULT_ADMIN_THEME_URL; ?>elfinder/jquery-elfinder.css">

        <script type="text/javascript" src="<?php print DEFAULT_ADMIN_THEME_URL; ?>elfinder/jquery-elfinder.js"></script>

        <script type="text/javascript">
            var callbackFunc = "<?php print_html_attr(get_request_action()); ?>";
            $(document).ready(function() {
                var elf = $('#elfinder').elfinder({
                    url : '<?php print get_elfinder_api_link(); ?>',
                    getFileCallback : function(file) {
                            if (typeof window.opener[callbackFunc] === 'function')
                            {
                                window.opener[callbackFunc](file);
                            }
                            window.close();
                    },
                    resizable: false
                }).elfinder('instance');
            });
        </script>
    </head>
    <body>
        <div id="elfinder"></div>
    </body>
</html>