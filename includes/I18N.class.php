<?php

/** Include helpers request file */
require_once ICEBERG_DIR_HELPERS . 'i18n.php';

/**
 * I18N
 * 
 * Translation management
 *  
 * @package Iceberg
 * @subpackage I18N
 * @author Marc Mascort Bou
 * @version 1.0
 * @todo js plugin translations
 * @todo .po .mo
 * @todo all translations
 */
class I18N extends ObjectConfig
{
    
    /**
     * Configuration use language
     * @var boolean
     */
    public static $CONFIG_USE_LANGUAGE = false;
    
    /**
     * Configuration key
     * @var string
     */
    public static $CONFIG_KEY = 'i18n_config';
    
    /**
     * Configuration defaults
     * @var array
     */
    public static $CONFIG_DEFAULTS = array(
        'default' => 'en_US',
        'languages' => array()
    );
    
    /**
     * Language defult data
     * @var array 
     */
    public static $LANGUAGE_DEFAULTS = array(
        'name' => '',
        'locale' => '',
        'iso' => '',
        'flag' => '',
        'active' => false,
        'visible' => false
    );
    
    public static function NormalizeLanguage($lang)
    {
        if (is_array($lang))
        {
            return array_merge(static::$LANGUAGE_DEFAULTS, $lang);
        }
        return static::$LANGUAGE_DEFAULTS;
    }
    
    public static function NormalizeLanguages($langs=array())
    {
        $langs = empty($langs) ? static::GetLanguages() : $langs;
        foreach ($langs AS $k => $v)
        {
            $langs[$k] = static::NormalizeLanguage($v);
        }
        return $langs;
    }

    /**
     * Initialize languages
     * 
     * @global array $__ICEBERG_LANGUAGES
     * @global array $__LANGUAGES 
     */
    public static function Initialize()
    {
        global $__ICEBERG_LANGUAGES, $__LANGUAGES;
        $_TEXT = $__LANGUAGES = array();
        foreach( $__ICEBERG_LANGUAGES AS $value ) {
            $file_lang =  ICEBERG_DIR_LANGUAGES . $value . '.php';
            if (is_file($file_lang) && is_readable($file_lang)) {
                include( $file_lang );
            }
        }
        $__LANGUAGES = static::NormalizeLanguages($__LANGUAGES);
    }

    /**
     * Load default language
     * 
     * @global string $__LANGUAGE 
     */
    public static function LoadDefaultLanguage()
    {
        global $__LANGUAGE;
        $__LANGUAGE = ICEBERG_DEFAULT_LANGUAGE;
        $__LANGUAGE = apply_filters('i18n_load_default', $__LANGUAGE);
        self::LoadLanguage( $__LANGUAGE );
    }
    
    /**
     * Load dinamic languages
     * 
     * @uses action_event() for 'i18n_load_dinamics'
     * @global array $__LANGUAGES 
     */
    public static function LoadDinamicLanguages()
    {
        global $__LANGUAGES;
        $__LANGUAGES = array();
        $languages = static::GetConfigValue('languages', array());
        $__LANGUAGES = apply_filters('i18n_load_dinamics', $languages);
        $__LANGUAGES = static::NormalizeLanguages($__LANGUAGES);
    }

    /**
     * Load a language
     * 
     * @uses action_event() for 'i18n_load_language'
     * @global type $__I18N_TEXT
     * @global string $__LANGUAGE
     * @param string $lang
     * @return boolean 
     */
    public static function LoadLanguage($lang, $forceConfig=false)
    {
        global $__I18N_TEXT, $__LANGUAGE, $__LANGUAGES;
        $__LANGUAGE = $lang;
        if ( !isset($__I18N_TEXT[$__LANGUAGE]) || !is_array($__I18N_TEXT[$__LANGUAGE]) ) {
            $__I18N_TEXT[$__LANGUAGE] = array();
        }
        $file_lang =  ICEBERG_DIR_LANGUAGES . $__LANGUAGE . '.php';
        $_TEXT = array();
        $_LANGUAGE = array();
        if ( is_file($file_lang) && is_readable($file_lang) )
        {
            include( $file_lang );
            if (!isset($__LANGUAGES[$__LANGUAGE]))
            {
                $__LANGUAGES[$__LANGUAGE] = static::NormalizeLanguage($_LANGUAGE);
            }
        }
        $_TEXT = apply_filters('i18n_load_language', $_TEXT, $lang);
        $__I18N_TEXT[$__LANGUAGE] = array_merge($__I18N_TEXT[$__LANGUAGE], $_TEXT);
        setlocale(LC_ALL, $__LANGUAGE);
        $done = true;
        if ($forceConfig)
        {
            $config_classes = getSubclassesOf('ObjectConfig');
            $config = array();
            $configAll = array();
            foreach ($config_classes AS $class)
            {
                ($class::$CONFIG_USE_LANGUAGE) ? ($config[] = $class::$CONFIG_KEY) : ($configAll[] = $class::$CONFIG_KEY);
            }
            $done = Config::LoadConfig($config);
            if ($done)
            {
                static::LoadDinamicLanguages();
            }
        }
        return $done;
    }
    
    public static function LoadLanguageInstall($lang)
    {
        global $__LANGUAGES;
        static::LoadLanguage($lang);
        foreach ($__LANGUAGES AS $k => $v)
        {
            $__LANGUAGES[$k]['active'] = ($lang == $k);
            $__LANGUAGES[$k]['visible'] = ($lang == $k);
        }
        return true;
    }
    
    /**
     * Load language extension
     * 
     * @uses action_event() for 'i18n_load_language_extension'
     * @global array $__I18N_TEXT
     * @param string $lang
     * @param string $file_lang
     * @return boolean 
     */
    public static function LoadLanguageExtension($lang, $file_lang)
    {
        global $__I18N_TEXT;
        if ( !isset($__I18N_TEXT[$lang]) || !is_array($__I18N_TEXT[$lang]) ) {
            $__I18N_TEXT[$lang]=array();
        }
        $_TEXT = array();
        if ( is_file($file_lang) && is_readable($file_lang) )
        {
            include( $file_lang );
        }
        //$_TEXT = do_action('i18n_load_language_extension', $_TEXT, $lang);
        do_action('i18n_load_language_extension', $_TEXT, $lang);
        $__I18N_TEXT[$lang] = array_merge($__I18N_TEXT[$lang], $_TEXT);
        return true;
    }

    /**
     * Get language
     * 
     * @global string $__LANGUAGE
     * @return string 
     */
    public static function  GetLanguage()
    {
        global $__LANGUAGE;
        return $__LANGUAGE;
    }

    /**
     * Get all languages
     * 
     * @global array $__LANGUAGES
     * @return array 
     */
    public static function  GetLanguages()
    {
        global $__LANGUAGES;
        return $__LANGUAGES;
    }
    
    /**
     * Get all active languages
     * 
     * @global array $__LANGUAGES
     * @return array 
     */
    public static function GetActiveLanguages()
    {
        $languages = static::GetLanguages();
        $return = array();
        foreach ($languages AS $key => $value) {
            if (isset($value['active']) && $value['active']===true) {
                $return[$key]=$value;
            }
        }
        return $return;
    }
    
    /**
     * Get all active languages
     * 
     * @global array $__LANGUAGES
     * @return array 
     */
    public static function GetVisibleLanguages()
    {
        $languages = static::GetActiveLanguages();
        $return = array();
        foreach ($languages AS $key => $value) {
            if ($value['visible']===true) {
                $return[$key]=$value;
            }
        }
        return $return;
    }
    
    public static function IsActiveLanguage($lang)
    {
        $langs = static::GetActiveLanguages();
        return isset($langs[$lang]);
    }
    
    public static function IsVisibleLanguage($lang)
    {
        $langs = static::GetVisibleLanguages();
        return isset($langs[$lang]);
    }
    
    /**
     * Get all active locales
     * 
     * @return array 
     */
    static public function GetActiveLocales()
    {
        $languages = static::GetActiveLanguages();
        return array_keys($languages);
    }
    
    /**
     * Get all locales
     * 
     * @global array $__LANGUAGES
     * @return array 
     */
    static public function GetLocales()
    {
        $languages = static::GetLanguages();
        return array_keys($languages);
    }
    
    /**
     * Get info of a language
     * 
     * @param string $locale
     * @return array 
     */
    public static function GetLanguageInfo($locale=null)
    {
        $locale = is_null($locale) ? static::GetLanguage() : $locale;
        $languages = self::GetLanguages();
        return isset($languages[$locale]) ? $languages[$locale] : static::$LANGUAGE_DEFAULTS;
    }
    
    /**
     * Get default language
     * 
     * @return string 
     */
    public static function GetDefaultLanguage()
    {
        return static::GetConfigValue('default', ICEBERG_DEFAULT_LANGUAGE);
    }

    /**
     * Get ISO of language
     * 
     * @return string 
     */
    public static function GetLanguageISO($locale=null)
    {
        $locale = is_null($locale) ? static::GetLanguage() : $locale;
        $languages = self::GetLanguages();
        $iso = ( isset($languages[$locale]) && isset($languages[$locale]['iso']) ) ? $languages[$locale]['iso'] : '';
        return $iso;
    }
    
    /**
     * Get languages flags
     *  
     * @return array 
     */
    public static function GetFlags()
    {
        $array = array();
        $dir = ICEBERG_DIR_LANGUAGES_FLAGS;
        $dh = opendir($dir);
        if ($dh !== false) {
            while (($file = readdir($dh)) !== false) {
                $path_file = $dir . $file;
                if (is_file($path_file) && is_readable($path_file)) {
                    $info = pathinfo($path_file);
                    if ($info['extension'] == 'png') {
                        $array[get_file_url($path_file, ICEBERG_DIR, '')] = get_file_url($path_file, ICEBERG_DIR, get_base_url());
                    }
                }
            }
            closedir($dh);
        }
        ksort($array);
        return $array;
    }
    
    public static function SaveLanguagesConfig()
    {
        global $__LANGUAGES;
        $config = static::GetConfig();
        $config['languages'] = $__LANGUAGES;
        return static::SaveConfig($config, Config::REPLICATE_ALL_LANGUAGES);
    }
    
    public static function Active($id)
    {
        global $__LANGUAGES;
        if (isset($__LANGUAGES[$id]))
        {
            $__LANGUAGES[$id]['active'] = true;
            $__LANGUAGES[$id]['visible'] = false;
            return static::SaveLanguagesConfig();
        }
        return false;
    }
    
    public static function Unactive($id)
    {
        global $__LANGUAGES;
        if(static::GetDefaultLanguage() != $id)
        {
            if (isset($__LANGUAGES[$id]))
            {
                $__LANGUAGES[$id]['active'] = false;
                $__LANGUAGES[$id]['visible'] = false;
                return static::SaveLanguagesConfig();
            }
        }
        return false;
    }
    
    public static function Visible($id)
    {
        global $__LANGUAGES;
        if (static::IsActiveLanguage($id))
        {
            $__LANGUAGES[$id]['visible'] = true;
            return static::SaveLanguagesConfig();
        }
        return false;
    }
    
    public static function Invisible($id)
    {
        global $__LANGUAGES;
        if (static::IsActiveLanguage($id) && (static::GetDefaultLanguage()!=$id))
        {
            $__LANGUAGES[$id]['visible'] = false;
            return static::SaveLanguagesConfig();
        }
        return false;
    }
    
    public static function MakeDefault($id)
    {
        global $__LANGUAGES;
        if (static::IsActiveLanguage($id))
        {
            $__LANGUAGES[$id]['active'] = true;
            $__LANGUAGES[$id]['visible'] = true;
            if (static::SetConfigValue('default', $id))
            {
                return static::SaveLanguagesConfig();
            }
        }
        return false;
    }
    
    public static function Remove($id)
    {
        global $__LANGUAGES;
        if (isset($__LANGUAGES[$id]) && static::GetDefaultLanguage() != $id)
        {
            $__LANGUAGES[$id] = array();
            unset($__LANGUAGES[$id]);
            return static::SaveLanguagesConfig();
        }
        return false;
    }
     
    static public function Insert($lang)
    {
        global $__LANGUAGES;
        $lang = static::NormalizeLanguage($lang);
        if (!empty($lang['locale']) && !isset($__LANGUAGES[$lang['locale']])) {
            $__LANGUAGES[$lang['locale']] = $lang;
            return self::SaveLanguagesConfig();
        }
        return false;
    }
    
    static public function Update($id, $lang)
    {
        global $__LANGUAGES;
        $lang = static::NormalizeLanguage($lang);
        if (!empty($lang['locale']) && isset($__LANGUAGES[$id])) {
            $__LANGUAGES[$lang['locale']] = $lang;
            if ($id != $lang['locale'])
            {
                $__LANGUAGES[$id] = null;
                unset($__LANGUAGES[$id]);
            }
            return self::SaveLanguagesConfig();
        }
        return false;
    }
    
    
    static public function ReOrder($from, $to)
    {
        global $__LANGUAGES;
        //echo $from . "\n";
        //echo $to . "\n";
        //var_dump($__LANGUAGES);
        $__LANGUAGES = reOrderArray($__LANGUAGES, $from, $to);
        //var_dump($__LANGUAGES);
        return self::SaveLanguagesConfig();
    }
}

