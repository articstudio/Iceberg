<?php
$obj_id = get_request_id();
$obj = get_pagetaxonomy($obj_id);
$templates = get_templates_content();
$accepted_templates = $obj->GetTemplates();
$taxonomy_elements = $obj->GetElements();
$elements = get_texonomy_elements();
?>

<hr />

<h5><?php print_text('Uses'); ?></h5>
<div class="row-fluid">
    <div class="span4">
        <label class="checkbox" for="permalink">
            <input type="checkbox" value="1" name="permalink" id="permalink" <?php print $obj->UsePermalink() ? 'checked' : ''; ?>>
            <?php print_text('Permalink'); ?>
        </label>
    </div>
    <div class="span4">
        <label class="checkbox" for="text">
            <input type="checkbox" value="1" name="text" id="text" <?php print $obj->UseText() ? 'checked' : ''; ?>>
            <?php print_text('Text'); ?>
        </label>
    </div>
    <div class="span4">
        <label class="checkbox" for="image">
            <input type="checkbox" value="1" name="image" id="image" <?php print $obj->UseImage() ? 'checked' : ''; ?>>
            <?php print_text('Image'); ?>
        </label>
    </div>
</div>

<hr />

<div class="row-fluid" data-select="templates">
    <div class="span6">
        <h5><?php print_text('Templates'); ?></h5>
        <select id="theme-templates" class="input-block-level" multiple="multiple" data-list="<?php print implode(',', $templates); ?>">
            <?php foreach ($templates AS $template): ?>
            <?php if (!$obj->AcceptedTemplate($template)): ?>
            <option value="<?php print $template; ?>"><?php print $template; ?></option>
            <?php endif; ?>
            <?php endforeach; ?>
        </select>
        <a href="#" data-add="theme-templates" title="<?php print_html_attr( _T('ADD') ); ?>" class="btn btn-inverse"><i class="icon-plus-sign icon-white"></i> <?php print_text( 'ADD' ); ?></a>
        <h5><?php print_text('Private templates'); ?></h5>
        <input type="text" id="private-templates" value="" class="input-block-level" />
        <a href="#" data-add="private-templates" title="<?php print_html_attr( _T('ADD') ); ?>" class="btn btn-inverse"><i class="icon-plus-sign icon-white"></i> <?php print_text( 'ADD' ); ?></a>
    </div>
    <div class="span6">
        <h5><?php print_text('Accepted templates'); ?></h5>
        <select id="templates-list" class="input-block-level" multiple="multiple" data-destionation="templates">
            <?php foreach ($accepted_templates AS $template): ?>
            <option value="<?php print $template; ?>"><?php print $template; ?></option>
            <?php endforeach; ?>
        </select>
        <input type="hidden" name="templates" id="templates" value="<?php print implode(',', $accepted_templates); ?>" />
        <a href="#" data-remove="templates-list" title="<?php print_html_attr( _T('REMOVE') ); ?>" class="btn btn-inverse"><i class="icon-minus-sign icon-white"></i> <?php print_text( 'REMOVE' ); ?></a>
    </div>
</div>

<hr />

<div class="row-fluid" data-select="templates">
    <div class="span6">
        <h5><?php print_text('Elements'); ?></h5>
        <ul class="unstyled" id="newelements" data-draggable="elements">
            <?php foreach($elements AS $class): ?>
            <li>
                <div class="well widget closed">
                    <header><?php print $class::Name(); ?></header>
                    <div class="btn-toolbar header">
                        <a href="#" class="btn btn-danger btn-mini" btn-action="remove"><i class="icon-trash"></i></a>
                    </div>
                    <input type="text" class="input-block-level" name="element_name[]" value="" placeholder="<?php print_text('Name'); ?>" drop-attr="required">
                    <input type="hidden" class="input-block-level" name="element_type[]" value="<?php print $class; ?>">
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="span6">
        <h5><?php print_text('Taxonomy elements'); ?></h5>
        <ul class="unstyled" id="elements" data-sortable="revert,droppable">
            <?php foreach($taxonomy_elements AS $e_name => $element): ?>
            <li>
                <div class="well widget">
                    <header><?php print $element->GetName(); ?></header>
                    <div class="btn-toolbar header">
                        <a href="#" class="btn btn-danger btn-mini" btn-action="remove"><i class="icon-trash"></i></a>
                    </div>
                    <input type="text" class="input-block-level" name="element_name[]" value="<?php print $e_name; ?>" placeholder="<?php print_text('Name'); ?>" required>
                    <input type="hidden" class="input-block-level" name="element_type[]" value="<?php print $element->GetType(); ?>">
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>