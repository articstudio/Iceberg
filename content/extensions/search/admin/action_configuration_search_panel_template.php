<div class="well">
    
    <form action="<?php print get_admin_action_link(array('action'=>'generate')); ?>" method="post" id="search-generate-form" role="form" validate>
        <p class="form-group">
            <button type="submit" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-wrench"></span> <?php print_text('Generate search metas'); ?></button>
            <span class="checkbox-inline">
                <label class="checkbox" for="generate-confirm">
                    <input type="checkbox" name="generate-confirm" id="generate-confirm" value="1" required>
                    <?php print_text('Confirm generate search metas'); ?>
                </label>
            </span>
        </p>
    </form>
    
    <?php
    $taxonomies = get_pagetaxonomies();
    $config = get_search_config();
    $search_taxonomies = isset($config['taxonomies']) ? $config['taxonomies'] : array();
    $search_taxonomies_fields = isset($config['fields']) ? $config['fields'] : array();
    ?>
    <h4><?php print_text('Taxonomies and fields'); ?></h4>
    <form action="<?php print get_admin_action_link(array('action'=>'save')); ?>" method="post">
        <div class="row">
            <?php $i=1; foreach ($taxonomies AS $id => $taxonomy): $taxonomy_elements = $taxonomy->GetElements(); $search_taxonomy_fields = isset($search_taxonomies_fields[$id]) ? $search_taxonomies_fields[$id] : array(); ?>
            <div class="col-md-3">
                <p class="radio">
                    <label class="checkbox" for="ctax-<?php print $id; ?>">
                        <input type="checkbox" name="taxonomy[]" value="<?php print $id; ?>" id="ctax-<?php print $id; ?>" <?php print in_array($id, $search_taxonomies) ? 'checked' : ''; ?>>
                        <strong><?php print $taxonomy->GetName(); ?></strong>
                    </label>
                </p>
                <ul>
                    <li class="radio">
                        <label class="checkbox" for="ctax-<?php print $id; ?>-title">
                            <input type="checkbox" name="taxonomy<?php print $id; ?>-fields[]" value="title" id="ctax-<?php print $id; ?>-title" <?php print in_array('title', $search_taxonomy_fields) ? 'checked' : ''; ?>>
                            Title
                        </label>
                    </li>
                    <li class="radio">
                        <label class="checkbox" for="ctax-<?php print $id; ?>-text">
                            <input type="checkbox" name="taxonomy<?php print $id; ?>-fields[]" value="text" id="ctax-<?php print $id; ?>-text" <?php print in_array('text', $search_taxonomy_fields) ? 'checked' : ''; ?>>
                            Text
                        </label>
                    </li>
                    <?php foreach($taxonomy_elements AS $e_name => $element): ?>
                    <li class="radio">
                        <label class="checkbox" for="ctax-<?php print $id; ?>-<?php print $e_name; ?>">
                            <input type="checkbox" name="taxonomy<?php print $id; ?>-fields[]" value="<?php print $e_name; ?>" id="ctax-<?php print $id; ?>-<?php print $e_name; ?>" <?php print in_array($e_name, $search_taxonomy_fields) ? 'checked' : ''; ?>>
                            <?php print $e_name . ' (' . $element->GetName() . ')'; ?>
                        </label>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php if ($i%4==0): ?>
        </div>
        <div class="row">
            <?php endif; ?>
            <?php $i++; endforeach; ?>
        </div>
        
        <div class="form-actions text-right">
            <button type="submit" class="btn btn-large btn-success"><span class="glyphicon glyphicon-ok"></span> <?php print_text('Save'); ?></button>
        </div>
    </form>
    
    
    
    
</div>