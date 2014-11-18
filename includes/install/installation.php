<header id="page-header">
    <h2><?php print_text('INSTALLATION'); ?></h2>
    <ol class="breadcrumb">
        <li><?php print_text('Install'); ?></li>
        <li class="active"><?php print_text('Installation'); ?></li>
    </ol>
</header>

<div class="progress">
    <?php if (count_install_errors() == 0): ?>
    <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%;">
        <span class="sr-only">100%</span>
    </div>
    <?php else: ?>
    <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width:85%;">
        <span class="sr-only">85%</span>
    </div>
    <div class="progress-bar progress-bar-danger progress-bar-striped active" style="width: 15%">
        <span class="sr-only">15%</span>
    </div>
    <?php endif; ?>
</div>

<?php $alerts = get_install_alerts(); foreach ($alerts AS $sms): ?>
<?php if ($sms['type']==='title'): ?>
<h3><?php print_text( $sms['text'] ); ?></h3>
<?php elseif ($sms['type']==='success'): ?>
<p class="alert alert-success"><i class="glyphicon glyphicon-ok"></i> <?php print_text( $sms['text'] ); ?></p>
<?php else: ?>
<p class="alert alert-danger"><i class="glyphicon glyphicon-warning-sign"></i> <?php print_text( $sms['text'] ); ?></p>
<?php endif; ?>
<? endforeach; ?>

<div class="form-actions text-right">
    <a href="<?php print get_install_reinstall_link(); ?>" title="<?php print_html_attr( _T('Reinstall') ); ?>" class="btn btn-default"><?php print_text('Reinstall'); ?> <span class="glyphicon glyphicon-refresh"></span></a>
    <?php if (count_install_errors() == 0): ?>
    <a href="<?php print get_base_url_admin(); ?>" title="<?php print_html_attr( _T('Go to Administrator Panel') ); ?>" class="btn btn-default"><?php print_text('Finish'); ?> <span class="glyphicon glyphicon-chevron-right"></span></a>
    <?php endif; ?>
</div>

