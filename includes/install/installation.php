<header id="article-header">
    <h1><?php print_text('INSTALLATION'); ?></h1>
    <span><?php print_text('Path'); ?>:</span>&nbsp;<?php print_text('Install'); ?> &gt; <?php print_text('Installation'); ?>
</header>

<div id="article-content">
    
    <div class="alert alert-info">
        <h4><?php print_text('Progress'); ?></h4>
        <div class="progress progress-striped active">
            <?php if (count_install_errors() == 0): ?>
            <div class="bar bar-success" style="width: 100%;"></div>
            <?php else: ?>
            <div class="bar bar-success" style="width: 85%;"></div>
            <div class="bar bar-danger" style="width: 15%;"></div>
            <?php endif; ?>
        </div>
    </div>
    
    
    <?php foreach (get_install_alerts() AS $sms): ?>
    <?php if ($sms['type']==='title'): ?>
    <h3><?php print_text( $sms['text'] ); ?></h3>
    <?php elseif ($sms['type']==='success'): ?>
    <p class="alert alert-success"><i class="icon-ok icon-white"></i> <?php print_text( $sms['text'] ); ?></p>
    <?php else: ?>
    <p class="alert alert-error"><i class="icon-warning-sign icon-white"></i> <?php print_text( $sms['text'] ); ?></p>
    <?php endif; ?>
    <? endforeach; ?>

    
    <div class="form-actions text-right">
        <a href="<?php print get_install_reinstall_link(); ?>" title="<?php print_html_attr( _T('Reinstall') ); ?>" class="btn btn-inverse"><?php print_text('Reinstall'); ?> <i class="icon-refresh icon-white"></i></a>
        <?php if (count_install_errors() == 0): ?>
        <a href="<?php print get_base_url_admin(); ?>" title="<?php print_html_attr( _T('Go to Administrator Panel') ); ?>" class="btn btn-inverse"><?php print_text('Finish'); ?> <i class="icon-chevron-right icon-white"></i></a>
        <?php endif; ?>
    </div>
</div>

