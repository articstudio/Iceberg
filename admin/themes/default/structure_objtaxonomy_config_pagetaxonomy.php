<?php
$obj_id = get_request_id();
$obj = get_pagetaxonomy($obj_id);

$taxonomy_elements = $obj->GetElements();
?>

<?php foreach ($taxonomy_elements AS $e_name => $element): ?>

<h4><?php print_text('Element'); ?>: <?php print $element->GetName(); ?> <small>(<?php print $e_name; ?>)</small></h4>
<?php $element->FormConfig(); ?>

<?php endforeach; ?>
