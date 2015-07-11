<?php
theme_header();

$language = get_language_info();
$languages = get_active_langs();

//$back = get_admin_reverse();

$breadcrumb = get_admin_breadcrumb();
$n_breadcrumb=count($breadcrumb);

$modules = get_modules();
$modes = get_modes();
$moduleObject = get_module();
$modeObject = get_mode();
$actionObject = get_action();

$actionObject = apply_filters('application_page_header_title_object', $actionObject);
$actionObject = apply_filters('application_page_header_title_object_' . $moduleObject['module'] . '_' . $modeObject['mode'] . '_' . $actionObject['action'], $actionObject);


$alerts = get_env_alerts();

$sidebar_toggle = Request::GetCookie('ICEBERG_SIDEBAR_TOGGLE') === 'true';
?>
        
<header id="header" class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="pull-left">
        <img src="<?php echo get_theme_url(); ?>/img/iceberg_logo.png" class="iceberg-logo" alt="Iceberg  v<?php print_html_attr( ICEBERG_VERSION ); ?>">
        <img src="<?php echo get_theme_url(); ?>/img/iceberg_header.jpg" class="iceberg-name" alt="Iceberg  v<?php print_html_attr( ICEBERG_VERSION ); ?>">
    </div>
    <div class="pull-right btn-toolbar">
        <?php if (!in_admin_dashboard() && user_has_capability('module_dashboard')): ?>
        <div id="btn-group-dashboard" class="btn-group">
            <a href="<?php echo get_admin_dashboard(); ?>" class="btn btn-default"><span class="glyphicon glyphicon-dashboard"></span> <span class="hidden-xs hidden-sm visible-md-inline visible-lg-inline"><?php print_text('Dashboard'); ?></span></a>
        </div>
        <?php endif; ?>

        <div id="btn-group-languages" class="btn-group">
            <?php if (count($languages) > 1): ?>
            <button class="btn btn-default dropdown-toggle" type="button" id="dropdown-languages" data-toggle="dropdown">
                <img src="<?php echo get_base_url() . $language['flag']; ?>" alt="<?php print_html_attr($language['name']); ?>" class="flag">
                <span class="hidden-xs hidden-sm visible-md-inline visible-lg-inline"><?php echo $language['name']; ?></span>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdown-languages">
                <?php foreach ($languages AS $lang): ?>
                <li role="presentation">
                    <a role="menuitem" tabindex="-1" href="<?php echo get_admin_action_link(array('lang'=>$lang['locale'])); ?>">
                        <img src="<?php echo get_base_url() . $lang['flag']; ?>" alt="<?php print_html_attr($lang['name']); ?>" class="flag">
                        <?php echo $lang['name']; ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>

        <div id="btn-group-domains" class="btn-group">
            <?php do_action('application_header_domains_group'); ?>
            <?php /* @todo Domain dropdown */ ?>
        </div>

        <div id="btn-group-profile" class="btn-group">
            <?php if (user_has_capability('module_profile')): ?>
            <a href="<?php print_link(array('module'=>'profile'));?>" class="btn btn-default"><span class="glyphicon glyphicon-user"></span> <span class="hidden-xs hidden-sm visible-md-inline visible-lg-inline"><?php print get_user_name(); ?></span></a>
            <?php else: ?>
            <button class="btn btn-default" disabled="disabled"><span class="glyphicon glyphicon-user"></span> <span class="hidden-xs hidden-sm visible-md-inline visible-lg-inline"><?php print get_user_name(); ?></span></button>
            <?php endif; ?>
            <a href="<?php print get_logout(); ?>" class="btn btn-default"><span class="glyphicon glyphicon-log-out"></span> <span class="hidden-xs hidden-sm visible-md-inline visible-lg-inline"><?php print_text('Logout'); ?></span></a>
        </div>
    </div>
</header>

<div id="wrapper" class="<?php echo $sidebar_toggle ? 'toggled' : ''; ?>">
            
    <sidebar id="sidebar-wrapper" role="navigation">
        <nav class="sidebar-nav">
            <a href="#sidebar-toggle" class="option sidebar-toggle" title="<?php print_html_attr(_T('Toggle sidebar')); ?>">
                <span class="glyphicon glyphicon-chevron-left toggle-left"></span>
                <span class="glyphicon glyphicon-chevron-right toggle-right"></span>
            </a>
            <?php foreach($modules AS $key => $module): ?>
            <a href="<?php print_link(array('module'=>$key)); ?>" title="<?php print_html_attr($module['name']); ?>" class="option <?php print_html_attr($moduleObject['module']===$key ? 'active' : '' ); ?>">
                <?php if ($module['badge'] !== false): ?>
                <span class="badge pull-right"><?php echo $module['badge']; ?></span>
                <?php endif; ?>
                <img src="<?php print_html_attr($module['icon']); ?>" alt="<?php print_html_attr($module['name']); ?>" title="<?php print_html_attr($module['name']); ?>">
                <span class="option-title"><?php echo $module['name']; ?></span>
            </a>
            <?php endforeach; ?>
            <footer id="footer">
                Iceberg v<?php print ICEBERG_VERSION; ?>  &copy; <a href="http://www.articstudio.com" title="Developed by Artic Studio" target="_blank">Artic Studio</a>
            </footer>
        </nav>
    </sidebar>

    <article id="page-wrapper">
        <header id="page-header">
            <?php do_action('application_page_header_title_pre'); ?>
            <?php do_action('application_page_header_title_pre_' . $moduleObject['module'] . '_' . $modeObject['mode'] . '_' . $actionObject['action']); ?>
            <h2><?php echo $actionObject['name']; ?></h2>
            <?php do_action('application_page_header_title_post'); ?>
            <?php do_action('application_page_header_title_post_' . $moduleObject['module'] . '_' . $modeObject['mode'] . '_' . $actionObject['action']); ?>
            <ol class="breadcrumb">
                <?php $n=1; foreach ($breadcrumb AS $key => $value): ?>
                <?php if ($n>=$n_breadcrumb): ?>
                <li class="active"><?php echo $key; ?></li>
                <?php else: ?>
                <li><a href="<?php print_html_attr($value); ?>" title="<?php print_html_attr($key); ?>"><?php echo $key; ?></a></li>
                <?php endif; ?>
                <?php ++$n; endforeach; ?>
            </ol>
            <?php do_action('application_page_header_breadcrumb_post'); ?>
        </header>
        <?php if (count($modes)>1): ?>
        <div class="btn-toolbar">
            <?php foreach ($modes AS $key => $mode): ?>
            <a href="<?php print_link(array('module'=>$moduleObject['module'], 'mode'=>$mode['mode'])); ?>" class="btn btn-default btn-large <?php print_html_attr( $key==$modeObject['mode'] ? 'active' : '' ); ?>" title="<?php print_html_attr($mode['name']); ?>"><?php echo $mode['name']; ?></a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <div id="alerts">
            <?php foreach ($alerts AS $sms): ?>
            <?php if ($sms['type']==='success'): ?>
            <p class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only"><?php print_text('Close'); ?></span></button><span class="glyphicon glyphicon-ok"></span> <strong><?php echo $sms['text']; ?></strong></p>
            <?php elseif ($sms['type']==='info'): ?>
            <p class="alert alert-info"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only"><?php print_text('Close'); ?></span></button><span class="glyphicon glyphicon-info-sign"></span> <strong><?php echo $sms['text']; ?></strong></p>
            <?php else: ?>
            <p class="alert alert-danger"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only"><?php print_text('Close'); ?></span></button><span class="glyphicon glyphicon-warning-sign"></span> <strong><?php echo $sms['text']; ?></strong></p>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
        
        <?php theme_page(); ?>
    </article>

</div>

<?php theme_footer(); ?>