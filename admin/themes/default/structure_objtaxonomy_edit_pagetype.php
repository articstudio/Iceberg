<?php
$obj_id = get_request_id();
$obj = get_pagetype($obj_id);
$taxonomies = get_pagetaxonomies();
?>

<h4><?php print_text('Taxonomies'); ?></h4>
<div class="row">
    <?php $i=1; foreach ($taxonomies AS $id => $taxonomy): ?>
    <div class="col-md-3">
        <p class="radio">
            <label class="checkbox" for="ctax-<?php print $id; ?>">
                <input type="checkbox" name="taxonomy[]" value="<?php print $id; ?>" id="ctax-<?php print $id; ?>" <?php print $obj->AcceptedTaxonomy($id) ? 'checked' : ''; ?>>
                <?php print $taxonomy->GetName(); ?>
            </label>
        </p>
    </div>
    <?php if ($i%4==0): ?>
</div>
<div class="row">
    <?php endif; ?>
    <?php $i++; endforeach; ?>
</div>