<?php

/**
 * Load language extension
 * @param string $lang
 * @param string $file_lang 
 */
function load_language_extension($lang, $file_lang)
{
    I18N::LoadLanguageExtension($lang, $file_lang);
}

/**
 * Get all languages
 * 
 * @uses I18N::GetLanguages()
 * @return array 
 */
function get_langs()
{
    return I18N::GetLanguages();
}

/**
 * Get all active languages
 * 
 * @uses I18N::GetActiveLanguages()
 * @return array 
 */
function get_active_langs()
{
    return I18N::GetActiveLanguages();
}

/**
 * Get all locales
 * 
 * @uses I18N::GetLocales()
 * @return array 
 */
function get_locales()
{
    return I18N::GetLocales();
}

/**
 * Get active locales
 * 
 * @uses I18N::GetActiveLocales()
 * @return array 
 */
function get_active_locales()
{
    return I18N::GetActiveLocales();
}

function is_active_language($lang)
{
    return I18N::IsActiveLanguage($lang);
}

function is_visible_language($lang)
{
    return I18N::IsVisibleLanguage($lang);
}

/**
 * Get language
 * 
 * @uses I18N::GetLanguage()
 * @return string 
 */
function get_lang()
{
    return I18N::GetLanguage();
}

/**
 * Get language ISO
 * 
 * @uses I18N::GetLanguageISO()
 * @return type 
 */
function get_lang_iso()
{
    return I18N::GetLanguageISO();
}

/**
 * Get languages flags
 * 
 * @uses I18N::GetFlags()
 * @return array 
 */
function get_lang_flags()
{
    return I18N::GetFlags();
}

function get_flag_url($flag)
{
    return get_base_url() . $flag;
}

/**
 * Get info of a language
 * 
 * @uses I18N::GetLanguageInfo()
 * @param string $locale
 * @return array 
 */
function get_language_info($locale=null)
{
    return I18N::GetLanguageInfo($locale);
}

/**
 * Get default language
 * 
 * @uses I18N::GetDefaultLanguage()
 * @return string 
 */
function get_language_default()
{
    return I18N::GetDefaultLanguage();
}

/**
 * Print text
 * 
 * @global array $__I18N_TEXT
 * @global string $__LANGUAGE
 * @param string $str
 * @return string 
 */
function _T($str)
{
    global $__I18N_TEXT, $__LANGUAGE;
    $str = isset($__I18N_TEXT[$__LANGUAGE][$str]) ? $__I18N_TEXT[$__LANGUAGE][$str] : $str;
    return sprintf('%s', $str);
}

/**
 * Print text
 * 
 * @param string $str 
 */
function print_text($str)
{
    printf('%s', _T($str));
}










function language_navbar_defaults()
{
    $defaults = array(
        'separator' => '&middot;',
        'show_separator' => true,
        'id' => 'language-nav',
        'css_item_active' => 'active',
        'css_nav_class' => '',
        'css_item_class' => '',
        'css_last_item_class' => ''
    );
    return $defaults;
}
function language_navbar_show($config=array())
{
    $config = array_merge(language_navbar_defaults(), $config);
    $active_langs = I18N::GetActiveLanguages();
    $active_lang = I18N::GetLanguage();
    if (count($active_langs)>0)
    {
        ?>
        <nav id="<?php print_html_attr($config['id']); ?>" class="<?php print_html_attr($config['css_nav_class']); ?>">
            <?php $n=0; $k=count($active_langs); foreach($active_langs AS $locale => $lang): ?>
            <?php if ($n>0 && $config['show_separator']): ?><span><?php print($config['separator']); ?></span><?php endif; ?>
            <a href="<?php page_link(array('lang'=>$locale)); ?>" class="<?php print_html_attr($config['css_item_class']); ?> <?php print_html_attr($active_lang==$locale?$config['css_item_active']:''); ?> <?php print_html_attr($n==$k-1?$config['css_last_item_class']:''); ?>"><?php print($lang['name']); ?></a>
            <?php $n++; endforeach; ?>
        </nav>
        <?php
    }
}




