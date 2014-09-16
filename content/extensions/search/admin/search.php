
<div class="well">
    <header><?php print_text('Search tools'); ?></header>
    
    
    <form action="<?php print get_admin_action_link(array('action'=>'generate-metas')); ?>" method="post">
        <p>
            <button type="submit" class="btn btn-inverse btn-mini"><i class="icon-wrench icon-white"></i></button> <?php print_text('Generate search metas'); ?>
        </p>
    </form>
    
    <?php
    $taxonomies = get_pagetaxonomies();
    $config = get_search_config();
    $search_taxonomies = isset($config['taxonomies']) ? $config['taxonomies'] : array();
    $search_taxonomies_fields = isset($config['fields']) ? $config['fields'] : array();
    ?>
    <div class="well">
        <header><?php print_text('Taxonomies and fields'); ?></header>
        <form action="<?php print get_admin_action_link(array('action'=>'taxonomies-fields')); ?>" method="post">
            <div class="row-fluid">
                <?php $i=1; foreach ($taxonomies AS $id => $taxonomy): $taxonomy_elements = $taxonomy->GetElements(); $search_taxonomy_fields = isset($search_taxonomies_fields[$id]) ? $search_taxonomies_fields[$id] : array(); ?>
                <div class="span3">
                    <label class="checkbox" for="ctax-<?php print $id; ?>">
                        <input type="checkbox" name="taxonomy[]" value="<?php print $id; ?>" id="ctax-<?php print $id; ?>" <?php print in_array($id, $search_taxonomies) ? 'checked' : ''; ?>>
                        <strong><?php print $taxonomy->GetName(); ?></strong>
                    </label>
                    <ul>
                        <li>
                            <label class="checkbox" for="ctax-<?php print $id; ?>-title">
                                <input type="checkbox" name="taxonomy<?php print $id; ?>-fields[]" value="title" id="ctax-<?php print $id; ?>-title" <?php print in_array('title', $search_taxonomy_fields) ? 'checked' : ''; ?>>
                                Title
                            </label>
                        </li>
                        <li>
                            <label class="checkbox" for="ctax-<?php print $id; ?>-text">
                                <input type="checkbox" name="taxonomy<?php print $id; ?>-fields[]" value="text" id="ctax-<?php print $id; ?>-text" <?php print in_array('text', $search_taxonomy_fields) ? 'checked' : ''; ?>>
                                Text
                            </label>
                        </li>
                        <?php foreach($taxonomy_elements AS $e_name => $element): ?>
                        <li>
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
            <div class="row-fluid">
                <?php endif; ?>
                <?php $i++; endforeach; ?>
            </div>
            <p class="text-right">
                <button type="submit" class="btn btn-inverse"><?php print_text('Save'); ?></button>
            </p>
        </form>
    </div>
    
    
    
    
</div>