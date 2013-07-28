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
            function getUrlParam(paramName) {
                var reParam = new RegExp('(?:[\?&]|&)' + paramName + '=([^&]+)', 'i');
                var match = window.location.search.match(reParam);
                return (match && match.length > 1) ? match[1] : '' ;
            }
            
            $(document).ready(function() {
                var funcNum = getUrlParam('CKEditorFuncNum');
                var elf = $('#elfinder').elfinder({
                    url : '<?php print get_elfinder_api_link(array('mode'=>get_request_mode())); ?>',
                    getFileCallback : function(file) {
                            window.opener.CKEDITOR.tools.callFunction(funcNum, file);
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