<?php
$domains = get_domains_tree();
?>

<div class="DTTT btn-group">
    <a href="#" class="btn">
        <i class="icon-plus"></i> <?php print_text('New'); ?>
    </a>
</div>
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered treetable data-expandable">
    <thead>
        <tr>
            <th><?php print_text('Name'); ?></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($domains AS $k => $v): ?>
        <tr data-tt-id="<?php print $k ;?>">
            <td><?php print $v->name; ?></td>
            <td class="text-right">
                <a href="<?php print get_admin_action_link(array('id'=>$k, 'action'=>'edit')); ?>" class="btn btn-inverse"><i class="icon-pencil icon-white"></i></a>
            </td>
        </tr>
        <?php foreach ($v->alias AS $kk => $vv): ?>
        <tr data-tt-id="<?php print $kk ;?>" data-tt-parent-id="<?php print $k ;?>">
            <td><?php print $vv->name; ?></td>
            <td></td>
        </tr>
        <?php endforeach; ?>
        <?php endforeach; ?>
    </tbody>
</table>