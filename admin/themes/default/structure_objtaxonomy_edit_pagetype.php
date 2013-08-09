<?php
$obj_id = get_request_id();
$obj = get_pagetype($obj_id);
$taxonomies = get_pagetaxonomies();
?>

<h5><?php print_text('Taxonomies'); ?></h5>
<div class="row-fluid">
    <?php $i=1; foreach ($taxonomies AS $id => $taxonomy): ?>
    <div class="span3">
        <label class="checkbox" for="ctax-<?php print $id; ?>">
            <input type="checkbox" name="taxonomy[]" value="<?php print $id; ?>" id="ctax-<?php print $id; ?>" <?php print $obj->AcceptedTaxonomy($id) ? 'checked' : ''; ?>>
            <?php print $taxonomy->GetName(); ?>
        </label>
    </div>
    <?php if ($i%4==0): ?>
</div>
<div class="row-fluid">
    <?php endif; ?>
    <?php $i++; endforeach; ?>
</div>