<?php
theme_header();

$language = get_language_info();
$languages = get_active_langs();
$back = get_admin_reverse();
$breadcrumb = get_admin_breadcrumb();
$modules = get_modules();
$modes = get_modes();
$moduleObject = get_module();
$modeObject = get_mode();
$alerts = get_alerts();
?>
        
        <header id="header">
            <div class="pull-right btn-toolbar">
                <?php if (count($languages) > 1): ?>
                <div class="btn-group">
                    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                        <img src="<?php print get_base_url() . $language['flag']; ?>" alt="<?php print_html_attr($language['name']); ?>" /> <?php print $language['name']; ?>
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <?php foreach ($languages AS $lang): ?>
                        <li>
                            <a href="<?php print get_admin_action_link(array('lang'=>$lang['locale'])); ?>">
                                <img src="<?php print get_base_url() . $lang['flag']; ?>" alt="<?php print_html_attr($lang['name']); ?>" />
                                <?php print $lang['name']; ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php else: ?>
                <a href="<?php print_link(array('module'=>'configuration','mode'=>'languages'));?>" class="btn"><img src="<?php print get_base_url() . $language['flag']; ?>" alt="<?php print_html_attr($language['name']); ?>" /> <?php print $language['name']; ?></a>
                <?php endif; ?>
                
                <div class="btn-group">
                    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="icon-list-alt"></i> <?php print get_domain_name(); ?>
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu" role="menu"></ul>
                </div>
                
                <a href="#" class="btn"><i class="icon-user"></i> <?php print get_user_name(); ?></a>
                
                <a href="<?php print get_logout(); ?>" class=" btn"><i class="icon-remove-sign"></i> <?php print_text('Logout'); ?></a>
            </div>
            <h1>
                <img src="<?php print_html_attr( DEFAULT_ADMIN_THEME_URL ); ?>/img/iceberg_header.jpg" class="brand" alt="Iceberg  v<?php print_html_attr( ICEBERG_VERSION ); ?>" />
            </h1>
        </header>

        <div id="container">
            <div class="block-table block-table-full">
            
                <div class="block-table-row">
                    <sidebar id="sidebar" class="block-table-cell">
                        <section id="sidebar-reverse">
                            <?php if ($back): ?>
                            <a href="<?php print_html_attr($back); ?>" title="<?php print_html_attr(_T('Go back')); ?>" class="go-back"><?php print_text('Go back'); ?></a>
                            <?php endif; ?>
                        </section>
                        <nav>
                            <?php foreach($modules AS $key => $module): ?>
                            <a href="<?php print_link(array('module'=>$key)); ?>" title="<?php print_text($module['name']); ?>" class="option <?php print_html_attr($moduleObject['module']===$key ? 'active' : '' ); ?>">
                                <img src="<?php print_html_attr( DEFAULT_ADMIN_THEME_URL . 'img/icon_' . strtolower($module['name']) . '.png' ); ?>" alt="<?php print_html_attr($module['name']); ?>" />
                                <span><?php print_text($module['name']); ?></span>
                            </a>
                            <?php endforeach; ?>
                        </nav>
                        
                        <footer id="footer">
                            Iceberg v<?php print ICEBERG_VERSION; ?>  &copy; <a href="http://www.articstudio.com" title="Developed by Artic Studio" target="_blank">Artic Studio</a>
                        </footer>
                    </sidebar>

                    <article id="article" class="block-table-cell">
                        <header id="article-header">
                            <h1><?php print_text($moduleObject['name']); ?> / <?php print_text($modeObject['name']); ?></h1>
                            <span><?php print_text('Path'); ?>:</span>&nbsp;
                            
                            <?php $n_breadcrumb=count($breadcrumb); $i=1; foreach ($breadcrumb AS $key => $value): ?>
                            <a href="<?php print_html_attr($value); ?>" title="<?php print_html_attr(_T($key)); ?>"><?php print_text($key); ?></a>
                            <?php print ( $i<$n_breadcrumb ? '&nbsp&gt;&nbsp;' : '' ); $i++; ?>
                            <?php endforeach; ?>
                            
                            <?php if (!in_admin_dashboard()): ?>
                            <a href="<?php print get_admin_dashboard(); ?>" class="close" title="<?php print_text('Close'); ?>"><?php print_text('Close'); ?></a>
                            <?php endif; ?>
                        </header>
                        <div id="article-content">
                            <?php if (count($modes)>1): ?>
                            <div class="text-center">
                                <p class="btn-toolbar">
                                    <?php foreach ($modes AS $key => $mode): ?>
                                    <a href="<?php print_link(array('module'=>$moduleObject['module'], 'mode'=>$mode['mode'])); ?>" class="btn btn-large <?php print_html_attr( $key==$modeObject['mode'] ? 'active' : '' ); ?>" title="<?php print_text($mode['name']); ?>"><?php print_text($mode['name']); ?></a>
                                    <?php endforeach; ?>
                                </p>
                            </div>
                            <? endif; ?>

                            <div id="alerts">
                                <?php foreach ($alerts AS $sms): ?>
                                <?php if ($sms['type']==='success'): ?>
                                <p class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="icon-ok icon-white"></i> <strong><?php print_text( $sms['text'] ); ?></strong></p>
                                <?php elseif ($sms['type']==='info'): ?>
                                <p class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="icon-hand-right icon-white"></i> <strong><?php print_text( $sms['text'] ); ?></strong></p>
                                <?php else: ?>
                                <p class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="icon-warning-sign icon-white"></i> <strong><?php print_text( $sms['text'] ); ?></strong></p>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </div>

                            <?php theme_page(); ?>
                        </div>
                    </article>
                </div>

            </div>
        </div>

<?php theme_footer(); ?>