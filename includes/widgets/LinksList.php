<?php

class LinksListWidget extends CMSWidgetExtension
{
    public $_name = 'List of links';
    public $_description = 'To show a list of a links';
    public $_orientation = 'vlist';
    public $_cssclasses = '';
    public $_title = array();
    public $_links = array();
    
    
    public function API(){}
    public function Form(){
        $locale = get_lang();
        ?>
        <div class="bgcolor">
            <header><?php print_text('Settings'); ?></header>
            <div class="content">
                <p>
                    <input type="radio" name="orientation" value="vlist" id="vlist" <?php printf( $this->_orientation=='vlist'?'checked':'' ); ?> />
                    <label for="vlist" class="mini"><?php print_text('Vertical'); ?></label>
                </p>
                <p>
                    <input type="radio" name="orientation" value="hlist" id="hlist" <?php printf( $this->_orientation=='hlist'?'checked':'' ); ?> />
                    <label for="hlist" class="mini"><?php print_text('Horizontal'); ?></label>
                </p>
                <p>
                    <label class="mini" for="cssclasses"><?php print_text('CSS classes'); ?></label>
                    <input type="text" class="text-input medium-input" name="cssclasses" id="cssclasses" value="<?php printf($this->_cssclasses); ?>" />
                </p>
                <p>
                    <label class=""><?php print_text('Title'); ?>:</label>
                    <?php foreach(get_active_langs() AS $locale => $language): ?>
                    <span class="lang-input">
                        <span class="flag">
                            <img src="<?php print_html_attr(get_base_url() . $language['flag'] ); ?>" alt="<?php print_html_attr( $language['name'] ); ?>" />
                        </span>
                        <input type="text" class="input-text medium-input" id="listtitle_<?php printf($locale); ?>" name="listtitle[<?php printf($locale); ?>]" value="<?php print_html_attr(isset($this->_title[$locale]) ? $this->_title[$locale] : '', false); ?>" />
                    </span>
                    <?php endforeach; ?>
                </p>
            </div>
        </div>
        
        <div class="column-2 left">
            <div class="bgcolor">
                <header><?php print_text('Page'); ?></header>
                <div class="content">
                    <p>
                        <label class="mini"><?php print_text('Page'); ?>:</label>
                        <select id="pages" class="input-text medium-input">
                            <?php $pages=get_pages(); foreach($pages AS $id => $page): ?>
                            <option value="<?php printf($id); ?>"><?php printf($page->GetName()); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </p>
                    <p>
                        <input type="checkbox" id="externalpage" value="1" />
                        <label class="mini"><?php print_text('External'); ?></label>
                    </p>
                </div>
                <footer>
                    <input type="button" class="button right" value="<?php print_html_attr( print_text('ADD') ); ?>" onclick="addLink('page')" />
                </footer>
            </div>
            
            <div class="bgcolor">
                <header><?php print_text('URL'); ?></header>
                <div class="content">
                    <p>
                        <label class=""><?php print_text('Title'); ?>:</label>
                        <?php foreach(get_active_langs() AS $locale => $language): ?>
                        <span class="lang-input">
                            <span class="flag">
                                <img src="<?php print_html_attr(get_base_url() . $language['flag'] ); ?>" alt="<?php print_html_attr( $language['name'] ); ?>" />
                            </span>
                            <input type="text" class="input-text medium-input" id="title_<?php printf($locale); ?>" value="" />
                        </span>
                        <?php endforeach; ?>
                    </p>
                    <p>
                        <label class=""><?php print_text('URL'); ?>:</label>
                        <?php foreach(get_active_langs() AS $locale => $language): ?>
                        <span class="lang-input">
                            <span class="flag">
                                <img src="<?php print_html_attr(get_base_url() . $language['flag'] ); ?>" alt="<?php print_html_attr( $language['name'] ); ?>" />
                            </span>
                            <input type="text" class="input-text medium-input" id="url_<?php printf($locale); ?>" value="" />
                        </span>
                        <?php endforeach; ?>
                    </p>
                    <p>
                        <input type="checkbox" id="externallink" value="1" />
                        <label class="mini"><?php print_text('External'); ?></label>
                    </p>
                </div>
                <footer>
                    <input type="button" class="button right" value="<?php print_html_attr( print_text('ADD') ); ?>" onclick="addLink('url')" />
                </footer>
            </div>
            
        </div>
        <div class="right column-2">
            <div class="bgcolor">
                <header><?php print_text('Links'); ?></header>
                <div class="content">
                    <input type="hidden" name="count" id="count" value="<?php print count($this->_links); ?>" />
                    <ul class="vlist items wrapper" id="links">
                        <?php foreach($this->_links AS $n => $link): ?>
                        <?php
                        $title = '';
                        if ($link['type']=='page') {
                            $page=get_page($link['page']);
                            $title=$page->GetName();
                        }
                        else {
                            $title = isset($link['title'][$locale]) ? $link['title'][$locale] : '';
                            $title = empty($title) && !empty($link['title']) ? current($link['title']) : $title;
                        }
                        ?>
                        <li class="link">
                            <div class="widget">
                                <div class="widget-title">
                                    <?php printf($title); ?>
                                    <span class="icon-20 delete icon-button" onclick="deleteLink(event)"></span>
                                </div>
                                <?php if (is_array($link['title']) && !empty($link['title'])): ?>
                                <?php foreach ($link['title'] AS $blocale=>$btitle): ?>
                                <input type="hidden" name="title[<?php printf($n); ?>][<?php printf($blocale); ?>]" value="<?php print_html_attr($btitle); ?>" />
                                <?php endforeach; ?>
                                <?php else: ?>
                                <input type="hidden" name="title[<?php printf($n); ?>][<?php printf($locale); ?>]" value="<?php print_html_attr($title); ?>" />
                                <?php endif; ?>
                                <input type="hidden" name="type[<?php printf($n); ?>]" value="<?php print_html_attr($link['type']); ?>" />
                                <input type="hidden" name="page[<?php printf($n); ?>]" value="<?php print_html_attr($link['page']); ?>" />
                                <?php if (is_array($link['url']) && !empty($link['url'])): ?>
                                <?php foreach ($link['url'] AS $blocale=>$burl): ?>
                                <input type="hidden" name="url[<?php printf($n); ?>][<?php printf($blocale); ?>]" value="<?php print_html_attr($burl); ?>" />
                                <?php endforeach; ?>
                                <?php else: ?>
                                <input type="hidden" name="url[<?php printf($n); ?>][<?php printf($locale); ?>]" value="" />
                                <?php endif; ?>
                                <input type="hidden" name="external[<?php printf($n); ?>]" value="<?php print_html_attr($link['external']); ?>" />
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <script type="text/javascript">
            var langs = ['<?php printf( implode("','", get_active_locales()) ); ?>'];
            var lang ='<?php printf(get_lang()); ?>';
            function addLink(type) {
                var title = [];
                var page = '';
                var url = [];
                var external = 0;
                var count = $('#count').val();
                if (type=='page') {
                    var $option = $('#pages option:selected');
                    title[lang] = $option.text();
                    page = $option.val();
                    external = $('#externalpage').is(':checked') ? 1 : 0;
                }
                else {
                    for (n in langs) {
                        title[langs[n]] = $('#title_'+langs[n]).val();
                        url[langs[n]] = $('#url_'+langs[n]).val();
                    }
                    external = $('#externallink').is(':checked') ? 1 : 0;
                }
                var titles = '';
                for (n in title) {
                    titles += '<input type="hidden" name="title['+count+']['+n+']" value="'+title[n]+'" />';
                }
                var urls = '';
                var nurls = 0;
                for (n in url) {
                    nurls++;
                    urls += '<input type="hidden" name="url['+count+']['+n+']" value="'+url[n]+'" />';
                }
                if (nurls==0) {
                    urls += '<input type="hidden" name="url['+count+']['+lang+']" value="" />';
                }
                var widget = '<li class="link">'
                                +'<div class="widget">'
                                    +'<div class="widget-title">'
                                        +title[lang]
                                        +'<span class="icon-20 delete icon-button" onclick="deleteLink(event)"></span>'
                                    +'</div>'
                                    +titles
                                    +'<input type="hidden" name="type['+count+']" value="'+type+'" />'
                                    +'<input type="hidden" name="page['+count+']" value="'+page+'" />'
                                    +urls
                                    +'<input type="hidden" name="external['+count+']" value="'+external+'" />'
                                +'</div>'
                            +'</li>';
                $('#links').append(widget);
                count++;
                $('#count').val(count);
            }
            function deleteLink(event) {
                $(event.target).parents('.link').slideUp(500, function(){
                    $(this).remove();
                });
            }
            $(function() {
                $( '.items' ).sortable({revert:true});
            });
        </script>
        <?php
    }
    public function Save()
    {
        //$this->_text = get_request_p('randomtext', array(array()), true);
        $this->_orientation = get_request_p('orientation', 'vlist');
        $this->_cssclasses = get_request_p('cssclasses', '');
        $this->_title = get_request_p('listtitle', array(), true);
        $titles = get_request_p('title', array(), true);
        $types = get_request_p('type', array());
        $pages = get_request_p('page', array());
        $urls = get_request_p('url', array());
        $externals = get_request_p('external', array());
        $this->_links = array();
        $i = 0;
        foreach ($types AS $n => $type) {
            $this->_links[$i] = array(
                'title' => $titles[$n],
                'type' => $type,
                'page' => $pages[$n],
                'url' => $urls[$n],
                'external' => $externals[$n]
            );
            $i++;
        }// var_dump($this->_links);
        return true;
    }
    public function Show(){
        $locale = get_lang();
        $listtitle = isset($this->_title[$locale]) ? $this->_title[$locale] : '';
        ?>
        <?php if (!empty($listtitle)): ?>
        <h3><?php print $listtitle; ?></h3>
        <?php endif; ?>
        <ul class="<?php printf($this->_orientation); ?> <?php printf($this->_cssclasses); ?>">
            <?php foreach($this->_links AS $link): ?>
            <?php
            $title = '';
            $url = '#';
            if ($link['type']=='page') {
                $page=get_page($link['page']);
                $title=$page->GetName();
                $url = page_link(array(REQUEST_VAR_PAGE=>$page->_id), false);
            }
            else {
                $title = isset($link['title'][$locale]) ? $link['title'][$locale] : '';
                $title = empty($title) && !empty($link['title']) ? current($link['title']) : $title;
                $url = isset($link['url'][$locale]) ? $link['url'][$locale] : '';
                $url = empty($url) && !empty($link['url']) ? current($link['url']) : $url;
            }
            ?>
            <li>
                <a href="<?php print_html_attr($url); ?>" title="<?php print_text($title); ?>" <?php printf( $link['external']==1?'target="_blank"':'' ); ?>>&middot; <?php print_text($title); ?></a>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php
    }
}
