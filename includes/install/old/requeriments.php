<header id="article-header">
    <h1><?php print_text('REQUERIMENTS'); ?></h1>
    <span><?php print_text('Path'); ?>:</span>&nbsp;<?php print_text('Install'); ?> &gt; <?php print_text('Requeriments'); ?>
</header>

<div id="article-content">
    
    <div class="alert alert-info">
        <h4><?php print_text('Progress'); ?></h4>
        <div class="progress progress-striped active">
            <?php if (install_check_requeriments()): ?>
            <div class="bar bar-success" style="width: 50%;"></div>
            <div class="bar bar-danger" style="width: 0%;"></div>
            <?php else: ?>
            <div class="bar bar-success" style="width: 20%;"></div>
            <div class="bar bar-danger" style="width: 30%;"></div>
            <?php endif; ?>
        </div>
    </div>
    
    <p><?php print_text('To install ICEBERG is necessary that the system meets a set of requirements. Until we meet can not be installed. If you have any questions please contact your administrator.'); ?></p>

    <br />
    <h5><?php printf( _T('PHP version required is %s and you are using the %s'), ICEBERG_PHP_VERSION_REQUIRED, phpversion() ); ?></h5>
    <?php if (install_compatible_version()): ?>
    <p class="alert alert-success"><i class="icon-ok icon-white"></i> <?php print_text('PHP Version is compatible'); ?></p>
    <?php else: ?>
    <p class="alert alert-error"><i class="icon-warning-sign icon-white"></i> <?php print_text('PHP Version is incompatible'); ?></p>
    <?php endif; ?>

    <br />
    <h5><?php printf( _T('The DB File path is: %s'), ICEBERG_DB_FILE ); ?></h5>
    <?php if (install_db_file_writable()): ?>
    <p class="alert alert-success"><i class="icon-ok icon-white"></i> <?php print_text('The DB File is writable'); ?></p>
    <?php else: ?>
    <p class="alert alert-error"><i class="icon-warning-sign icon-white"></i> <?php print_text('The DB File is unwritable'); ?></p>
    <?php endif; ?>

    <br />
    <h5><?php printf( _T('The Uploads Directory path is: %s'), ICEBERG_DIR_UPLOADS ); ?></h5>
    <?php if (install_uploads_dir_writable()): ?>
    <p class="alert alert-success"><i class="icon-ok icon-white"></i> <?php print_text('The Uploads Directory is writable'); ?></p>
    <?php else: ?>
    <p class="alert alert-error"><i class="icon-warning-sign icon-white"></i> <?php print_text('The Uploads Directory is unwritable'); ?></p>
    <?php endif; ?>

    <br />
    <h5><?php printf( _T('The Temporary Directory path is: %s'), ICEBERG_DIR_TEMP ); ?></h5>
    <?php if (install_temp_dir_writable()): ?>
    <p class="alert alert-success"><i class="icon-ok icon-white"></i> <?php print_text('The Temporary Directory is writable'); ?></p>
    <?php else: ?>
    <p class="alert alert-error"><i class="icon-warning-sign icon-white"></i> <?php print_text('The Temporary Directory is unwritable'); ?></p>
    <?php endif; ?>

    
    <div class="form-actions text-right">
        <?php if (install_check_requeriments()): ?>
        <a href="<?php print get_install_next_step_link(); ?>" title="<?php print_html_attr( _T('Next step') ); ?>" class="btn btn-inverse"><?php print_text('Next step'); ?> <i class="icon-chevron-right icon-white"></i></a>
        <?php else: ?>
        <a href="<?php print get_install_step_link(); ?>" title="<?php print_html_attr( _T('Refresh') ); ?>" class="btn btn-inverse"><i class="icon-refresh icon-white"></i> <?php print_text('Refresh'); ?></a>
        <?php endif; ?>
    </div>
</div>