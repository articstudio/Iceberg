<header id="page-header">
    <h2><?php print_text('REQUERIMENTS'); ?></h2>
    <ol class="breadcrumb">
        <li><?php print_text('Install'); ?></li>
        <li class="active"><?php print_text('Requeriments'); ?></li>
    </ol>
</header>

<div class="progress">
    <?php if (install_check_requeriments()): ?>
    <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:50%;">
        <span class="sr-only">50%</span>
    </div>
    <?php else: ?>
    <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width:20%;">
        <span class="sr-only">20%</span>
    </div>
    <div class="progress-bar progress-bar-danger progress-bar-striped active" style="width: 30%">
        <span class="sr-only">30%</span>
    </div>
    <?php endif; ?>
</div>

<p><?php print_text('To install ICEBERG is necessary that the system meets a set of requirements. Until we meet can not be installed. If you have any questions please contact your administrator.'); ?></p>

<br />
<h4><?php printf( _T('PHP version required is %s and you are using the %s'), ICEBERG_PHP_VERSION_REQUIRED, phpversion() ); ?></h4>
<?php if (install_compatible_version()): ?>
<p class="alert alert-success"><i class="glyphicon glyphicon-ok"></i> <?php print_text('PHP Version is compatible'); ?></p>
<?php else: ?>
<p class="alert alert-danger"><i class="glyphicon glyphicon-warning-sign"></i> <?php print_text('PHP Version is incompatible'); ?></p>
<?php endif; ?>

<br />
<h4><?php printf( _T('The DB File path is: %s'), ICEBERG_DB_FILE ); ?></h4>
<?php if (install_db_file_writable()): ?>
<p class="alert alert-success"><i class="glyphicon glyphicon-ok"></i> <?php print_text('The DB File is writable'); ?></p>
<?php else: ?>
<p class="alert alert-danger"><i class="glyphicon glyphicon-warning-sign"></i> <?php print_text('The DB File is unwritable'); ?></p>
<?php endif; ?>

<br />
<h4><?php printf( _T('The Uploads Directory path is: %s'), ICEBERG_DIR_UPLOADS ); ?></h4>
<?php if (install_uploads_dir_writable()): ?>
<p class="alert alert-success"><i class="glyphicon glyphicon-ok"></i> <?php print_text('The Uploads Directory is writable'); ?></p>
<?php else: ?>
<p class="alert alert-danger"><i class="glyphicon glyphicon-warning-sign"></i> <?php print_text('The Uploads Directory is unwritable'); ?></p>
<?php endif; ?>

<br />
<h4><?php printf( _T('The Temporary Directory path is: %s'), ICEBERG_DIR_TEMP ); ?></h4>
<?php if (install_temp_dir_writable()): ?>
<p class="alert alert-success"><i class="glyphicon glyphicon-ok"></i> <?php print_text('The Temporary Directory is writable'); ?></p>
<?php else: ?>
<p class="alert alert-danger"><i class="glyphicon glyphicon-warning-sign"></i> <?php print_text('The Temporary Directory is unwritable'); ?></p>
<?php endif; ?>


<div class="form-actions text-right">
    <?php if (install_check_requeriments()): ?>
    <a href="<?php print get_install_next_step_link(); ?>" title="<?php print_html_attr( _T('Next step') ); ?>" class="btn btn-default"><?php print_text('Next step'); ?> <span class="glyphicon glyphicon-chevron-right"></span></a>
    <?php else: ?>
    <a href="<?php print get_install_step_link(); ?>" title="<?php print_html_attr( _T('Refresh') ); ?>" class="btn btn-default"><span class="glyphicon glyphicon-refresh"></span> <?php print_text('Refresh'); ?></a>
    <?php endif; ?>
</div>