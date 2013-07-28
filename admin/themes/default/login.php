<?php theme_header(); ?>


<div class="block-table block-table-full">
    
    <div class="block-table-cell-center">
        <div class="form-login-container">
            <?php if ( is_login() ): ?>
            <div class="alert alert-error">
                <strong><?php print_text('INCORRECT DATA'); ?></strong>
            </div>
            <?php endif; ?>
            <?php /*
            <div class="alert alert-success">
                <strong><?php print_text('RESTORED PASSWORD'); ?></strong>
            </div>
            */ ?>
            <form action="<?php print_html_attr(get_base_url_admin()); ?>" method="post" class="form-login" validate>
                <h1 class="form-login-heading">
                    <img src="<?php print_html_attr( DEFAULT_ADMIN_THEME_URL ); ?>img/iceberg_logo.png" alt="Iceberg  v<?php print_html_attr( ICEBERG_VERSION ); ?>" />
                    <img src="<?php print_html_attr( DEFAULT_ADMIN_THEME_URL ); ?>img/iceberg_header.jpg" alt="Iceberg  v<?php print_html_attr( ICEBERG_VERSION ); ?>" />
                </h1>
                <label for="username" class="hidden"><?php print_text('User'); ?></label>
                <input name="username" id="username" type="text" class="input-block-level" placeholder="<?php print_text('User'); ?>" required>
                <label for="password" class="hidden"><?php print_text('Password'); ?></label>
                <input name="password" id="password" type="password" class="input-block-level" placeholder="<?php print_html_attr( _T('Password') ); ?>" required>
                <label for="language" class="hidden"><?php print_text('Language'); ?>:</label>
                <select id="language" name="lang" class="input-block-level">
                    <?php foreach(get_active_langs() AS $locale=>$language): ?>
                    <option value="<?php print $locale; ?>" <?php print get_lang()==$locale?'selected':''; ?>><?php print $language['name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <p class="text-center">
                    <button class="btn btn-large btn-inverse" type="subdmit"><?php print_html_attr( _T('ENTER') ); ?></button>
                </p>
                <div id="footer">
                    Iceberg v<?php print ICEBERG_VERSION; ?>  &copy; <a href="http://www.articstudio.com" title="Developed by Artic Studio" target="_blank">Artic Studio</a>
                </div>
            </form>
        </div>
    </div>

    
</div>

<?php theme_footer(); ?>