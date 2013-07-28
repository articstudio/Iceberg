<header id="article-header">
    <h1><?php print_text('INSTALLATION INSTRUCCIONS'); ?></h1>
    <h2 class="breadcrumb">
        <span><?php print_text('Path'); ?>:</span>&nbsp;<?php print_text('Install'); ?> &gt; <?php print_text('Instruccions'); ?>
    </h2>
</header>

<div id="article-content">
    
    <div class="alert alert-info">
        <h4><?php print_text('Progress'); ?></h4>
        <div class="progress progress-striped active">
            <div class="bar bar-success" style="width: 20%;"></div>
        </div>
    </div>
    
    <p><?php print_text('The ICEBERG installation is very simple. You just need to follow the steps.'); ?></p>
    <p><?php print_text('First you have to choose the language of the installer.'); ?></p>

    <form action="<?php print get_install_step_link(); ?>" method="post" id="form_language">
        <fieldset>
            <label for="language_selection" class="mini"><?php print_text('Choose your language:'); ?></label>
            <select name="<?php print Install::REQUEST_KEY_LANGUAGE; ?>" id="language_selection" class="input-text small-input">
                <? foreach (get_langs() AS $key => $value ): ?>
                <option value="<?php print_html_attr( $key ); ?>" <?php printf( get_lang()===$key ? 'selected' : '' ); ?>><?php printf( $value['name'] ); ?></option>
                <? endforeach; ?>
            </select>
        </fieldset>
    </form>

    <div class="form-actions text-right">
        <a href="<?php print get_install_next_step_link(); ?>" title="<?php print_html_attr( _T('Next step') ); ?>" class="btn btn-inverse"><?php print_text('Next step'); ?> <i class="icon-chevron-right icon-white"></i></a>
    </div>

</div>
