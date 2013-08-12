<?php
$obj_id = get_request_id();
$obj = get_pagegroup($obj_id);
$types = get_pagetypes();
?>

<h5><?php print_text('Types'); ?></h5>
<div class="row-fluid">
    <?php $i=1; foreach ($types AS $id => $type): ?>
    <div class="span3">
        <label class="checkbox" for="ctype-<?php print $id; ?>">
            <input type="checkbox" name="type[]" value="<?php print $id; ?>" id="ctype-<?php print $id; ?>" <?php print $obj->AcceptedType($id) ? 'checked' : ''; ?>>
            <?php print $type->GetName(); ?>
        </label>
    </div>
    <?php if ($i%4==0): ?>
</div>
<div class="row-fluid">
    <?php endif; ?>
    <?php $i++; endforeach; ?>
</div>