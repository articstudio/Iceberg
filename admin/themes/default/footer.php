        
        <div id="iceberg-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="iceberg-modal-label" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 id="iceberg-modal-label"></h3>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <a href="#" class="btn button-close" data-dismiss="modal" aria-hidden="true"><?php print_text('Close'); ?></a>
                <a href="#" class="btn button-save btn-success"><?php print_text('Save'); ?></a>
            </div>
        </div>
        
        <div id="loading">
            <img src="<?php print_html_attr( get_theme_url() ); ?>img/loading.gif" class="img" alt="<?php print_text('Loading...'); ?>" />
            <span class="title"></span>
        </div>

        <script>
            var js_iceberg_i18n = {
                confirm_modal_title: "<?php print_html_attr(_T('Confirm')); ?>",
                button_new: "<?php print_html_attr(_T('New')); ?>",
                reorder_error: "<?php print_html_attr(_T('Failed to reorder items')); ?>"
            };
            var icebergAPI = "<?php echo get_iceberg_api_link(); ?>";
        </script>
        
        <?php theme_foot(); ?>

        <?php /*<script src="<?php print_html_attr( get_theme_url() ); ?>js/jquery.js"></script>
        <script src="<?php print_html_attr( get_theme_url() ); ?>js/bootstrap.min.js"></script>
        <script src="<?php print_html_attr( get_theme_url() ); ?>js/main.js"></script>
        <script src="<?php print_html_attr( get_theme_url() ); ?>js/login.js"></script>*/ ?>

    </body>
</html>