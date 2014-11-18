<?php theme_header(); ?>

<?php
$active_langs = get_active_langs();
$lang = get_lang();
?>

<article id="form-login-wrapper">
    
    <form action="<?php print_html_attr(get_base_url_admin()); ?>" method="post" id="form-login" role="form" validate>
        <header>
            <img src="<?php print_html_attr( DEFAULT_ADMIN_THEME_URL ); ?>img/iceberg_logo.png" alt="Iceberg  v<?php print_html_attr( ICEBERG_VERSION ); ?>" />
            <img src="<?php print_html_attr( DEFAULT_ADMIN_THEME_URL ); ?>img/iceberg_header.jpg" alt="Iceberg  v<?php print_html_attr( ICEBERG_VERSION ); ?>" />
        </header>
        <?php if (!is_null(Domain::GetID())): ?>
        <?php if (is_login()): ?>
        <div class="alert alert-danger">
            <strong><?php print_text('INCORRECT DATA'); ?></strong>
        </div>
        <?php endif; ?>
        <input type="hidden" name="login" value="<?php echo nonce_make('login'); ?>">
        <p class="form-group">
            <label for="username" class="control-label sr-only"><?php print_text('User'); ?></label>
            <input type="text" name="username" id="username" class="form-control" placeholder="<?php print_html_attr( _T('User') ); ?>" required>
        </p>
        <p class="form-group">
            <label for="password" class="control-label sr-only"><?php print_text('Password'); ?></label>
            <input type="password" name="password" id="password" class="form-control" placeholder="<?php print_text('Password'); ?>" required>
        </p>
        <?php if (!empty($active_langs) && count($active_langs)>1): ?>
        <p class="form-group">
            <label for="lang" class="control-label sr-only"><?php print_text('Language'); ?></label>
            <select id="lang" name="lang" class="form-control">
                <?php foreach($active_langs AS $locale=>$language): ?>
                <option value="<?php print $locale; ?>" <?php print $lang===$locale?'selected':''; ?>><?php print $language['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php endif; ?>
        <p class="form-group">
            <label class="checkbox" for="rememberme">
                <input type="checkbox" name="rememberme" id="rememberme" value="1" />
                <?php print_text('Remember me'); ?>
            </label>
        </p>
        <p class="form-group">
            <button class="btn btn-default btn-lg" type="subdmit"><?php print_text('ENTER'); ?></button>
        </p>
        <?php else: ?>
        <div class="alert alert-danger">
            <strong><?php print_text('DOMAIN ERROR'); ?></strong>
        </div>
        <?php endif; ?>
        <footer>
            Iceberg v<?php print ICEBERG_VERSION; ?>  &copy; <a href="http://www.articstudio.com" title="Developed by Artic Studio" target="_blank">Artic Studio</a>
        </footer>
    </form>
</article>

<?php theme_footer(); ?>