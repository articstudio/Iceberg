<?php
$obj_id = get_request_id();
$obj = get_pagegroup($obj_id);
$types = get_pagetypes();
?>

<h4><?php print_text('Types'); ?></h4>
<div class="row">
    <?php $i=1; foreach ($types AS $id => $type): ?>
    <div class="col-md-3">
        <p class="radio">
            <label class="checkbox" for="ctype-<?php print $id; ?>">
                <input type="checkbox" name="type[]" value="<?php print $id; ?>" id="ctype-<?php print $id; ?>" <?php print $obj->AcceptedType($id) ? 'checked' : ''; ?>>
                <?php print $type->GetName(); ?>
            </label>
        </p>
    </div>
    <?php if ($i%4==0): ?>
</div>
<div class="row">
    <?php endif; ?>
    <?php $i++; endforeach; ?>
</div>