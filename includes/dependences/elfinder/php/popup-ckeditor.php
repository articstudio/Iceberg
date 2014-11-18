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
            function getUrlParam(paramName) {
                var reParam = new RegExp('(?:[\?&]|&)' + paramName + '=([^&]+)', 'i');
                var match = window.location.search.match(reParam);
                return (match && match.length > 1) ? match[1] : '' ;
            }
            $(document).ready(function() {
                var funcNum = getUrlParam('CKEditorFuncNum');
                $('#elfinder').elfinder({
                    url : '<?php print get_elfinder_api_link(array('mode'=>get_request_mode())); ?>',
                    lang : '<?php echo get_lang_iso(); ?>',
                    getFileCallback : function(file) {
                        window.opener.CKEDITOR.tools.callFunction(funcNum, file);
                        window.close();
                    },
                    resizable: false
                }).elfinder('instance');
            });
        </script>
    </body>
</html>