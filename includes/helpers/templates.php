<?php

/**
 * Print content of template
 * @param string $file
 * @param string $url
 * @param string $callback 
 */
function print_template($file, $url=null, $callback=null)
{
    $template = new Template();
    $template->SetTemplateFile($file);
    $template->SetTemplateUrl($url);
    $template->SetTemplateCallback($callback);
    $template->GenerateTemplateContent();
    $template->PrintTemplateContent();
}

/**
 * Returns content of template
 * @param string $file
 * @param string $url
 * @param string $callback
 * @return string 
 */
function get_template($file, $url=null, $callback=null)
{
    $template = new Template();
    $template->SetTemplateFile($file);
    $template->SetTemplateUrl($url);
    $template->SetTemplateCallback($callback);
    $template->GenerateTemplateContent();
    return $template->GetTemplateContent();
}



/**
 * Get string for html attribute
 * 
 * @param string $str
 * @return string 
 */
function get_html_attr($str, $strip_tags=true)
{
    $str = sprintf( '%s', $str);
    $str = $strip_tags ? strip_tags( $str ) : $str;
    $str = addcslashes($str, '"');
    $str = str_replace('<br />', '', nl2br($str));
    $str = str_replace(array("\n","\r"), '', $str);
    return $str;
}

/**
 * Print string for html attribute
 * @param string $str 
 */
function print_html_attr($str, $strip_tags=true)
{
    printf('%s', get_html_attr($str, $strip_tags) );
}

function cut_text($text, $len=180, $strip_tags=true, $end='...', $force=false)
{
    if ($strip_tags) { $text = nl2br($text); $text = strip_tags($text); }
    //$text = html_entity_decode(utf8_encode($text), ENT_QUOTES, 'UTF-8');
    if ( strlen($text) > $len ) {
        if ($force)
        {
            $text = substr($text, 0, $len);
            $text .= $end;
        }
        else
        {
        $whitespaceposition = strpos($text," ",$len)-1;
        if( $whitespaceposition > 0 ) {
            $text = substr($text, 0, ($whitespaceposition+1));
            $text .= $end;
        }
        if (!$strip_tags && preg_match_all("|<([a-zA-Z]+)>|",$text,$aBuffer) ) {
            if( !empty($aBuffer[1]) ) {
                preg_match_all("|</([a-zA-Z]+)>|",$text,$aBuffer2);
                if( count($aBuffer[1]) != count($aBuffer2[1]) ) {
                    foreach( $aBuffer[1] as $index => $tag ) {
                        if( empty($aBuffer2[1][$index]) || $aBuffer2[1][$index] != $tag) {
                            $text .= '</'.$tag.'>';
                        }
                    }
                }
            }
        }
    }
    }
    return $text; 
}

function add_template($environment, $template)
{
    return Template::AddTemplate($environment, $template);
}

function remove_template($environment, $template)
{
    return Template::RemoveTemplate($environment, $template);
}

function get_templates($environment)
{
    return Template::GetTemplates($environment);
}

function get_templates_content()
{
    return Template::GetTemplatesContent();
}


