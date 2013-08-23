<?php
$links = get_menubar_links();
$pages = get_pages(array('order'=>'name'));
?>
<form action="<?php print get_admin_action_link(array('action'=>'save')); ?>" method="post" id="content-menubar" validate>
    <div class="well">
        <header><?php print_text('Settings'); ?></header>
        
        <div class="row-fluid">
            
            <div class="span6">
                <div class="well" data-push="links" data-push-template="tpl-menubar-link">
                    <header><?php print_text('Page'); ?></header>
                    <input type="hidden" id="typepage" value="page" data-push-value="type" />
                    <input type="hidden" id="urlpage" value="" data-push-value="url" />
                    <p>
                        <label for="pages"><?php print_text('Page'); ?>:</label>
                        <select id="pages" class="input-block-level" data-push-value="page">
                            <option value="-1"><?php print_text('Home'); ?></option>
                            <?php foreach($pages AS $id => $page): ?>
                            <option value="<?php printf($id); ?>"><?php printf($page->GetTitle()); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </p>
                    <p>
                        <label class="mini" for="titlepage"><?php print_text('Title'); ?></label>
                        <input type="text" class="input-block-level" id="titlepage" value="" data-push-value="title" />
                    </p>
                    <div class="row-fluid">
                        <div class="span6">
                            <p>
                                <label class="checkbox" for="externalpage">
                                    <input type="checkbox" id="externalpage" value="1" data-push-value="external" />
                                    <?php print_text('External'); ?>
                                </label>
                            </p>
                        </div>
                        <div class="span6">
                            <p>
                                <label class="checkbox" for="submenupage">
                                    <input type="checkbox" id="submenupage" value="1" data-push-value="submenu" />
                                    <?php print_text('Submenu'); ?>
                                </label>
                            </p>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span6">
                            <p>
                                <label class="mini" for="cssclassespage"><?php print_text('CSS classes'); ?></label>
                                <input type="text" class="input-block-level" id="cssclassespage" value="" data-push-value="cssclasses" />
                            </p>
                        </div>
                    </div>
                    <div class="form-actions form-actions-mini text-right">
                        <button class="btn btn-large btn-success btn-mini"><i class="icon-plus-sign icon-white"></i> <?php print_text('Add'); ?></button>
                    </div>
                </div>
                
                <div class="well" data-push="links" data-push-template="tpl-menubar-link">
                    <header><?php print_text('URL'); ?></header>
                    <input type="hidden" id="typelink" value="link" data-push-value="type" />
                    <p>
                        <label class="mini" for="urllink"><?php print_text('URL'); ?></label>
                        <input type="text" class="input-block-level" id="urllink" value="" data-push-value="url" />
                    </p>
                    <p>
                        <label class="mini" for="titlelink"><?php print_text('Title'); ?></label>
                        <input type="text" class="input-block-level" id="titlelink" value="" data-push-value="title" />
                    </p>
                    <div class="row-fluid">
                        <div class="span6">
                            <p>
                                <label class="checkbox" for="externallink">
                                    <input type="checkbox" id="externallink" value="1" data-push-value="external" />
                                    <?php print_text('External'); ?>
                                </label>
                            </p>
                        </div>
                        <div class="span6">
                            <p>
                                <label class="checkbox" for="submenulink">
                                    <input type="checkbox" id="submenulink" value="1" data-push-value="submenu" />
                                    <?php print_text('Submenu'); ?>
                                </label>
                            </p>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span6">
                            <p>
                                <label class="mini" for="cssclasseslink"><?php print_text('CSS classes'); ?></label>
                                <input type="text" class="input-block-level" id="cssclasseslink" value="" data-push-value="cssclasses" />
                            </p>
                        </div>
                    </div>
                    <div class="form-actions form-actions-mini text-right">
                        <button class="btn btn-large btn-success btn-mini"><i class="icon-plus-sign icon-white"></i> <?php print_text('Add'); ?></button>
                    </div>
                </div>
            </div>
            
            <div class="span6">
                <div class="well">
                    <header><?php print_text('Links'); ?></header>
                    <ul class="unstyled" id="links" data-sortable="revert,droppable">
                        <?php foreach ($links AS $key => $link): ?>
                        <li>
                            <div class="well widget collapsed">
                                <header><?php print $link['name']; ?></header>
                                <div class="btn-toolbar header">
                                    <a href="#" class="btn btn-inverse btn-mini" btn-action="collapse"><i class="icon-chevron-up icon-white"></i></a>
                                    <a href="#" class="btn btn-inverse btn-mini" btn-action="expand"><i class="icon-chevron-down icon-white"></i></a>
                                    <a href="#" class="btn btn-danger btn-mini" btn-action="remove"><i class="icon-trash"></i></a>
                                </div>
                                <input type="hidden" name="menubar-type[]" value="<?php print_html_attr($link['type']); ?>">
                                <p>
                                    <input type="text" class="input-block-level" name="menubar-name[]" value="<?php print_html_attr($link['name']); ?>" widget-action="title" placeholder="<?php print_text('Name'); ?>" required>
                                </p>
                                <p>
                                    <input type="text" class="input-block-level" name="menubar-url[]" value="<?php print_html_attr($link['url']); ?>" placeholder="<?php print_text('URL'); ?>">
                                </p>
                                <p>
                                    <input type="text" class="input-block-level" name="menubar-page[]" value="<?php print_html_attr($link['page']); ?>" placeholder="<?php print_text('Page'); ?>">
                                </p>
                                <div class="row-fluid">
                                    <div class="span6">
                                        <p>
                                            <label class="checkbox" for="external">
                                                <input type="checkbox" name="menubar-external[]" value="1" %data-external%  />
                                                <?php print_text('External'); ?>
                                            </label>
                                        </p>
                                    </div>
                                    <div class="span6">
                                        <p>
                                            <label class="checkbox" for="submenu">
                                                <input type="checkbox" name="menubar-submenu[]" value="1" %data-submenu% />
                                                <?php print_text('Submenu'); ?>
                                            </label>
                                        </p>
                                    </div>
                                </div>
                                <p>
                                    <input type="text" class="input-block-level" name="menubar-cssclassess[]" value="<?php print_html_attr($link['cssclassess']); ?>" placeholder="<?php print_text('CSS classes'); ?>">
                                </p>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            
        </div>
        
        <div class="form-actions text-right">
            <button type="submit" class="btn btn-large btn-success"><?php print_text('Save'); ?> <i class="icon-ok-circle icon-white"></i></button>
        </div>
    </div>
</form>

<script type="text/template" id="tpl-menubar-link">
    <li>
        <div class="well widget">
            <header>%data-title%</header>
            <div class="btn-toolbar header">
                <a href="#" class="btn btn-inverse btn-mini" btn-action="collapse"><i class="icon-chevron-up icon-white"></i></a>
                <a href="#" class="btn btn-inverse btn-mini" btn-action="expand"><i class="icon-chevron-down icon-white"></i></a>
                <a href="#" class="btn btn-danger btn-mini" btn-action="remove"><i class="icon-trash"></i></a>
            </div>
            <input type="hidden" name="menubar-type[]" value="%data-type%">
            <p>
                <input type="text" class="input-block-level" name="menubar-name[]" value="%data-title%" widget-action="title" placeholder="<?php print_text('Name'); ?>" required>
            </p>
            <p>
                <input type="text" class="input-block-level" name="menubar-url[]" value="%data-url%" placeholder="<?php print_text('URL'); ?>">
            </p>
            <p>
                <input type="text" class="input-block-level" name="menubar-page[]" value="%data-page%" placeholder="<?php print_text('Page'); ?>">
            </p>
            <div class="row-fluid">
                <div class="span6">
                    <p>
                        <label class="checkbox" for="external">
                            <input type="checkbox" name="menubar-external[]" value="1" %data-external%  />
                            <?php print_text('External'); ?>
                        </label>
                    </p>
                </div>
                <div class="span6">
                    <p>
                        <label class="checkbox" for="submenu">
                            <input type="checkbox" name="menubar-submenu[]" value="1" %data-submenu% />
                            <?php print_text('Submenu'); ?>
                        </label>
                    </p>
                </div>
            </div>
            <p>
                <input type="text" class="input-block-level" name="menubar-cssclassess[]" value="%data-url%" placeholder="<?php print_text('CSS classes'); ?>">
            </p>
        </div>
    </li>
</script