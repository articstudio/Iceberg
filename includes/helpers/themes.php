<?php

/**
 * Print header template
 * @return boolean 
 */
function theme_header()
{
    $theme = Theme::GetTheme();
    return !is_null($theme) ? $theme->Header() : false;
}

/**
 * Print footer template
 * @return boolean 
 */
function theme_footer()
{
    $theme = Theme::GetTheme();
    return !is_null($theme) ? $theme->Footer() : false;
}

/**
 * Returns URL of theme directory
 * @return string 
 */
function get_theme_url()
{
    $theme = Theme::GetTheme();
    return !is_null($theme) ? $theme->GetURL() : '';
}
/**
 * Prints URL of theme directory
 * @return boolean 
 */
function print_theme_url()
{
    printf('%s', get_theme_url());
}


/**
 * Returns realpath of theme directory
 * @return string 
 */
function get_theme_dir()
{
    $theme = Theme::GetTheme();
    return !is_null($theme) ? $theme->GetDirectory() : '';
}

function theme_register_script($name, $url, $version=null, $dependency=array(), $in_footer=false)
{
    $theme = Theme::GetTheme();
    return !is_null($theme) ? $theme->RegisterScript($name, $url, $version, $dependency, $in_footer) : false;
}

function theme_enqueue_script($name, $url='', $version=null, $dependency=array(), $in_footer=false)
{
    $theme = Theme::GetTheme();
    return !is_null($theme) ? $theme->EnqueueScript($name, $url, $version, $dependency, $in_footer) : false;
}

function theme_register_style($name, $url, $version=null, $dependency=array(), $media='all')
{
    $theme = Theme::GetTheme();
    return !is_null($theme) ? $theme->RegisterStyle($name, $url, $version, $dependency, $media) : false;
}

function theme_enqueue_style($name, $url='', $version=null, $dependency=array(), $media='all')
{
    $theme = Theme::GetTheme();
    return !is_null($theme) ? $theme->EnqueueStyle($name, $url, $version, $dependency, $media) : false;
}

/**
 * Print CMS head
 * @global object $__APP
 * @return boolean 
 */
function theme_head()
{
    $theme = Theme::GetTheme();
    return !is_null($theme) ? $theme->Head() : false;
}

/**
 * Print CMS head
 * @global object $__APP
 * @return boolean 
 */
function theme_foot()
{
    $theme = Theme::GetTheme();
    return !is_null($theme) ? $theme->Foot() : false;
}


function theme_page()
{
    $theme = Theme::GetTheme();
    return !is_null($theme) ? $theme->Page() : false;
}

function get_themes($dir)
{
    return Theme::GetThemes($dir);
}

function get_frontend_themes()
{
    return Theme::GetFrontendThemes();
}

function get_backend_themes()
{
    return Theme::GetBackendhemes();
}
















function get_backend_theme_dir()
{
    $themes_config = Config::GetConfig(Config::$KEY_THEME);
    $theme_config = $themes_config['backend'];
    $theme_dir = CMS_DIR_ADMIN_THEMES . $theme_config['dirname'] . '/';
    return $theme_dir;
}

function get_backend_theme_url()
{
    $themes_config = Config::GetConfig(Config::$KEY_THEME);
    $theme_config = $themes_config['backend'];
    $theme_dir = CMS_DIR_ADMIN_THEMES . $theme_config['dirname'] . '/';
    $theme_url = get_file_url($theme_dir, CMS_DIR, get_base_url()) . '/';
    return $theme_url;
}

function get_frontend_theme_dir()
{
    $themes_config = Config::GetConfig(Config::$KEY_THEME);
    $theme_config = $themes_config['frontend'];
    $theme_dir = CMS_DIR_THEMES . $theme_config['dirname'] . '/';
    return $theme_dir;
}

/**
 * Add helper to theme
 * @global object $__APP
 * @param string $helper
 * @param string $type
 * @return boolean 
 */
function add_theme_helper($helper, $type='js')
{
    global $__APP;
    return !is_null($__APP) ? $__APP->AddHelper($helper, $type) : false;
}

/**
 * Print sidebar template
 * @global object $__APP
 * @return boolean 
 */
function cms_sidebar()
{
    global $__APP;
    return !is_null($__APP) ? $__APP->CMSSidebar() : false;
}

/**
 * Print page template
 * @global object $__APP
 * @return boolean 
 */
function cms_page()
{
    global $__APP;
    return !is_null($__APP) ? $__APP->CMSPage() : false;
}

function get_theme_info($key=null)
{
    global $__APP;
    return !is_null($__APP) ? $__APP->GetThemeInfo($key) : false;
}

function get_theme_list($dir)
{
    global $__APP;
    return !is_null($__APP) ? $__APP->GetThemes($dir) : false;
}





function cms_menubar_defaults()
{
    $defaults = array(
        'separator' => '&middot;',
        'show_separator' => false,
        'id' => 'menubar-nav',
        'css_item_active' => 'active',
        'css_nav_class' => ''
    );
    return $defaults;
}

function cms_menubar($config=array())
{
    $config = array_merge(cms_menubar_defaults(), $config);
    $items = cms_menubar_items();
    $page_id = get_page_id();
    $locale = get_lang();
    if (count($items)>0)
    {
        ?>
        <ul id="<?php print_html_attr($config['id']); ?>" class="<?php print_html_attr($config['css_nav_class']); ?>">
            <?php $n=0; foreach($items AS $link): ?>
            <?php if ($n>0 && $config['show_separator']): ?><li><?php print($config['separator']); ?></li><?php endif; ?>
            <?php
            $title = '';
            $url = '';
            $page = null;
            if ($link['type']=='page') {
                if ($link['page']==-1) {
                    $title=_T('Inicio');
                    $url = page_link(array(), false);
                }
                else {
                    $page=get_page($link['page']);
                    $title=$page->GetName();
                    $url = page_link(array(REQUEST_VAR_PAGE=>$link['page']), false);
                    if ($link['submenu']==1) {
                        $url = '#';
                    }
                }
            }
            else {
                $title = isset($link['title'][$locale]) ? $link['title'][$locale] : '';
                $title = empty($title) && !empty($link['title']) ? current($link['title']) : $title;
                $url = isset($link['url'][$locale]) ? $link['url'][$locale] : '';
                $url = empty($url) && !empty($link['url']) ? current($link['url']) : $url;
            }
            ?>
            <li>
                <a href="<?php print_html_attr($url); ?>" class="<?php print_html_attr($page_id==$link['page']?$config['css_item_active']:''); ?> <?php print_html_attr($link['cssclasses']); ?>" <?php print($link['external']==1?'target="_blank"':'');?>><? print($title); ?></a>
                <?php if ($link['submenu']==1 && $page!=null): ?>
                <ul>
                    <?php $childs = $page->GetChilds(); ?>
                    <?php foreach ($childs AS $kc=>$child): ?>
                    <li><a href="<?php page_link(array(REQUEST_VAR_PAGE=>$kc)); ?>"><?php print $child->GetName(); ?></a></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </li>
            <?php $n++; endforeach; ?>
        </ul>
        <?php
    }
}
function cms_menubar_save()
{
    $titles = get_request_p('title', array(), true);
    $types = get_request_p('type', array());
    $pages = get_request_p('page', array());
    $urls = get_request_p('url', array());
    $externals = get_request_p('external', array());
    $submenu = get_request_p('submenu', array());
    $cssclasses = get_request_p('cssclasses', array());
    $_links = array();
    foreach ($types AS $n => $type) {
        $_links[$n] = array(
            'title' => $titles[$n],
            'type' => $type,
            'page' => $pages[$n],
            'url' => $urls[$n],
            'external' => $externals[$n],
            'submenu' => $submenu[$n],
            'cssclasses' => $cssclasses[$n]
        );
    }
    return Config::SaveConfig(Config::$KEY_MENUBAR, $_links, true);
}

function cms_menubar_items()
{
    return Config::GetConfig(Config::$KEY_MENUBAR, array());
}


/*
function theme_config() {
    global $__THEME_URL, $__THEME_PATH;
    action_event('theme_preconfig');
    $dir=theme_get_config('dir');
    Config::setConfig('theme_url', Request::getUrl() . CMS_DIR_THEMES . $dir . '/');
    Config::setConfig('theme_path', CMS_DIR . CMS_DIR_THEMES . $dir . '/');
    action_event('theme_postconfig');
}

function theme_get_config($keyname) {
    global $__CONFIG;
    $config=Config::getConfig(Config::$KEY_THEME);
    if (array_key_exists($keyname, $config)) {  return $config[$keyname]; }
    else {return false; }
}

function theme_load($file) {
    include($file);
}
*/
