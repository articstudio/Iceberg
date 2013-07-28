<?php

class RandomTextWidget extends CMSWidgetExtension
{
    public $_name = 'Random text';
    public $_description = 'To show a random text of list';
    public $_text = array(array());
    
    
    public function API(){}
    public function Form()
    {
        ?>
        <div id="randomtext">
            <?php $n =0; foreach ($this->_text AS $key => $value): ?>
            <p rel="randomtext">
                <?php foreach(get_active_langs() AS $locale => $language): ?>
                <span class="lang-input">
                    <span class="flag">
                        <img src="<?php print_html_attr(get_base_url() . $language['flag'] ); ?>" alt="<?php print_html_attr( $language['name'] ); ?>" />
                    </span>
                    <input type="text" class="input-text medium-input" name="randomtext[][<?php printf($locale); ?>]" value="<?php print_html_attr( isset($value[$locale]) ? $value[$locale] : '' ); ?>" />
                </span>
                <?php $n++; endforeach; ?>
                <small><a href="#" onclick="removeRandomText(event);return false;">- <?php print_text('Remove text'); ?></a></small>
            </p>
            <?php endforeach; ?>
        </div>
        <p>
            <a href="javascript:addRandomText();">+ <?php print_text('Add text'); ?></a>
        </p>
        <script type="text/javascript">
            function addRandomText() {
                var content = '<p rel="randomtext">'
                                <?php foreach(get_active_langs() AS $locale => $language): ?>
                                +'<span class="lang-input">'
                                    +'<span class="flag">'
                                        +'<img src="<?php print_html_attr(get_base_url() . $language['flag'] ); ?>" alt="<?php print_html_attr( $language['name'] ); ?>" />'
                                    +'</span>'
                                    +'<input type="text" class="input-text medium-input" name="randomtext[][<?php printf($locale); ?>]" value="" />'
                                +'</span>'
                                <?php $n++; endforeach; ?>
                                +'<small><a href="#" onclick="removeRandomText(event);return false;">- <?php print_text('Remove text'); ?></a></small>'
                            +'</p>';
                $('#randomtext').append(content);
            }
            function removeRandomText(event) {
                $(event.target).parents('p[rel=randomtext]').slideUp(500, function(){
                    $(this).remove();
                });
            }
        </script>
        <?php
    }
    public function Save()
    {
        $this->_text = get_request_p('randomtext', array(array()), true);
        return true;
    }
    public function Show(){
        $lang = get_lang();
        $n = 0;
        $text = $this->getText($lang);
        while (empty($text) && $n<5) {
            $text = $this->getText($lang);
        }
        printf($text);
    }
    
    private function getText($lang)
    {
        $n = count($this->_text);
        $n = rand(0, $n-1);
        $text = isset($this->_text[$n][$lang]) ? $this->_text[$n][$lang] : '';
        return $text;
    }
}
