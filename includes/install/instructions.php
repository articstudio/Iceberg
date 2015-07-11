<header id="page-header">
    <h2><?php print_text('INSTALLATION INSTRUCCIONS'); ?></h2>
    <ol class="breadcrumb">
        <li><?php print_text('Install'); ?></li>
        <li class="active"><?php print_text('Instruccions'); ?></li>
    </ol>
</header>

<div class="progress">
    <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width:20%;">
        <span class="sr-only">20%</span>
    </div>
</div>

<p><?php print_text('The ICEBERG installation is very simple. You just need to follow the steps.'); ?></p>
<p><?php print_text('First you have to choose the language of the installer.'); ?></p>

<?php
$langs = get_langs();
?>
<form action="<?php print get_install_step_link(); ?>" method="post" id="form-language" role="form">
    <div class="form-group">
        <label for="language-select" class="control-label"><?php print_text('Choose your language'); ?></label>
        <select name="<?php print Install::REQUEST_KEY_LANGUAGE; ?>" id="language-select" class="form-control">
            <?php foreach ($langs AS $key => $value ): ?>
            <option value="<?php print_html_attr( $key ); ?>" <?php echo get_lang()===$key ? 'selected' : ''; ?>><?php echo $value['name']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</form>

<div class="form-actions text-right">
    <a href="<?php print get_install_next_step_link(); ?>" title="<?php print_html_attr( _T('Next step') ); ?>" class="btn btn-default"><?php print_text('Next step'); ?> <span class="glyphicon glyphicon-chevron-right"></span></a>
</div>
