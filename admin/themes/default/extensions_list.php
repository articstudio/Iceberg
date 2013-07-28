<?php
$extensions = get_extensions_list();
?>

<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatable data-filter data-sort" id="extensions-list">
    <thead>
        <tr>
            <th><?php print_text('Name'); ?></th>
            <th><?php print_text('Version'); ?></th>
            <th><?php print_text('Description'); ?></th>
            <th><?php print_text('Author'); ?></th>
            <th></th>
        </tr>
    </thead>
    <?php $n=0; foreach($extensions AS $dirname => $extension): ?>
    <tr>
        <td>
            <?php print $extension['name']; ?>
        </td>
        <td>
            <?php print $extension['version']; ?>
        </td>
        <td>
            <?php print $extension['description']; ?>
        </td>
        <td>
            <?php if (empty($extension['url'])): ?>
            <?php print $extension['author']; ?>
            <?php else: ?>
            <a href="<?php print $extension['url']; ?>" target="_blank">
                <?php print $extension['author']; ?>
            </a>
            <?php endif; ?>
        </td>
        <td class="text-right">
            <?php if ($extension['active']): ?>
            <a href="<?php print get_admin_action_link(array('id'=>$dirname, 'action'=>'unactive')); ?>" class="btn btn-success"><i class="icon-ok icon-white"></i></a>
            <?php else: ?>
            <a href="<?php print get_admin_action_link(array('id'=>$dirname, 'action'=>'active')); ?>" class="btn btn-inverse"><i class="icon-ok icon-white"></i></a>
            <?php endif; ?>
        </td>
    </tr>
    <?php $n++; endforeach; ?>
</table>