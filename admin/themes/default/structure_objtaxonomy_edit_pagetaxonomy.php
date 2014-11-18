<?php
$obj_id = get_request_id();
$obj = get_pagetaxonomy($obj_id);
$templates = get_templates_content();
$accepted_templates = $obj->GetTemplates();
$taxonomy_elements = $obj->GetElements();
$elements = get_texonomy_elements();

$role_default_id = get_default_user_role();
$role_default = get_user_role($role_default_id);
$role_root_id = get_root_user_role();
$roles = get_user_roles();
?>

<hr />

<h4><?php print_text('Settings'); ?></h4>
<div class="row">
    <div class="col-md-6">
        <p class="radio">
            <label class="checkbox" for="permalink">
                <input type="checkbox" value="1" name="permalink" id="permalink" <?php print $obj->UsePermalink() ? 'checked' : ''; ?>>
                <?php print_text('Permalink'); ?>
            </label>
        </p>
        <p class="control-group">
            <textarea class="form-control" name="comments-permalink" id="comments-permalink"><?php print $obj->PermalinkComments(); ?></textarea>
        </p>
    </div>
    <div class="col-md-6">
        <p class="radio">
            <label class="checkbox" for="text">
                <input type="checkbox" value="1" name="text" id="text" <?php print $obj->UseText() ? 'checked' : ''; ?>>
                <?php print_text('Text'); ?>
            </label>
        </p>
        <p class="control-group">
            <textarea class="form-control" name="comments-text" id="comments-text"><?php print $obj->TextComments(); ?></textarea>
        </p>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <p class="radio">
            <label class="checkbox" for="image">
                <input type="checkbox" value="1" name="image" id="image" <?php print $obj->UseImage() ? 'checked' : ''; ?>>
                <?php print_text('Image'); ?>
            </label>
        </p>
        <p class="control-group">
            <textarea class="form-control" name="comments-image" id="comments-image"><?php print $obj->ImageComments(); ?></textarea>
        </p>
    </div>
    <div class="col-md-6">
        <p class="radio">
            <label class="checkbox" for="childs">
                <input type="checkbox" value="1" name="childs" id="childs" <?php print $obj->ChildsAllowed() ? 'checked' : ''; ?>>
                <?php print_text('Childs allowed'); ?>
            </label>
        </p>
        <p class="radio">
            <label class="checkbox" for="user_relation">
                <input type="checkbox" value="1" name="user_relation" id="user_relation" <?php print $obj->UserRelation() ? 'checked' : ''; ?>>
                <?php print_text('User relation'); ?>
            </label>
        </p>
        <p class="form-group" data-filter="user_relation">
            <label for="user_role"><?php print_text('User Role'); ?></label>
            <select name="user_role" id="user_role" class="form-control">
                <option value="<?php echo $role_default->GetID(); ?>" <?php echo $role_default->GetID()===$obj->UserRole() ? 'selected' : ''; ?>><?php echo $role_default->GetName(); ?></option>
                <?php foreach ($roles AS $role): ?>
                <?php if ($role->GetID()!==$role_default_id && $role->GetID()!==$role_root_id): ?>
                <option value="<?php echo $role->GetID(); ?>" <?php echo $role->GetID()===$obj->UserRole() ? 'selected' : ''; ?>><?php echo $role->GetName(); ?></option>
                <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </p>
    </div>
</div>

<hr />

<div class="row" data-select="templates-list">
    <div class="col-md-6">
        <p class="control-group">
            <label for="theme-templates" class="control-label"><?php print_text('Templates'); ?></label>
            <select id="theme-templates" class="form-control" multiple="multiple" data-list="<?php print implode(',', $templates); ?>">
                <?php foreach ($templates AS $template): ?>
                <?php if (!$obj->AcceptedTemplate($template)): ?>
                <option value="<?php print $template; ?>"><?php print $template; ?></option>
                <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </p>
        <p class="control-group">
            <a href="#" data-add="theme-templates" title="<?php print_html_attr( _T('ADD') ); ?>" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span> <?php print_text( 'ADD' ); ?></a>
        </p>
        
        <p class="control-group">
            <label for="private-templates" class="control-label"><?php print_text('Private templates'); ?></label>
            <input type="text" id="private-templates" value="" class="form-control" />
        </p>
        <p class="control-group">
            <a href="#" data-add="private-templates" title="<?php print_html_attr( _T('ADD') ); ?>" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span> <?php print_text( 'ADD' ); ?></a>
        </p>
    </div>
    <div class="col-md-6">
        <p class="control-group">
            <label for="templates-list" class="control-label"><?php print_text('Accepted templates'); ?></label>
            <select id="templates-list" class="form-control" multiple="multiple" data-destionation="templates">
                <?php foreach ($accepted_templates AS $template): ?>
                <option value="<?php print $template; ?>"><?php print $template; ?></option>
                <?php endforeach; ?>
            </select>
            <input type="hidden" name="templates" id="templates" value="<?php print implode(',', $accepted_templates); ?>" />
        </p>
        <p class="control-group">
            <a href="#" data-remove="templates-list" title="<?php print_html_attr( _T('REMOVE') ); ?>" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-minus"></span> <?php print_text( 'REMOVE' ); ?></a>
        </p>
        
    </div>
</div>

<hr />

<div class="row">
    <div class="col-md-6">
        <label class="control-label"><?php print_text('Elements'); ?></label>
        <ul class="list-unstyled" id="newelements" data-draggable="#elements">
            <?php foreach($elements AS $class): ?>
            <li>
                <div class="well widget closed">
                    <header><?php print $class::Name(); ?></header>
                    <div class="btn-toolbar header">
                        <a href="#" class="btn btn-danger btn-xs" btn-action="remove"><span class="glyphicon glyphicon-trash"></span></a>
                    </div>
                    <p class="form-group">
                        <input type="text" class="form-control" name="element_name[]" value="" placeholder="<?php print_text('Name'); ?>" data-drop-attr="required">
                    </p>
                    <input type="hidden" name="element_type[]" value="<?php print $class; ?>">
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="col-md-6">
        <label class="control-label"><?php print_text('Taxonomy elements'); ?></label>
        <ul class="list-unstyled" id="elements" data-sortable="droppable">
            <?php foreach($taxonomy_elements AS $e_name => $element): ?>
            <li>
                <div class="well widget">
                    <header><?php print $element->GetName(); ?></header>
                    <div class="btn-toolbar header">
                        <a href="#" class="btn btn-danger btn-xs" btn-action="remove"><span class="glyphicon glyphicon-trash"></span></a>
                    </div>
                    <p class="form-group">
                        <input type="text" class="form-control" name="element_name[]" value="<?php print $e_name; ?>" placeholder="<?php print_text('Name'); ?>" required>
                    </p>
                    <input type="hidden" name="element_type[]" value="<?php print $element->GetType(); ?>">
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>