<?php
$domains = get_domains_canonicals();
$default = get_domain_id();

/* data-new="<?php print get_admin_action_link(array('action'=>'new')); ?>" */
?>

<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatable" id="domains-configuration-list">
    <thead>
        <tr>
            <th><?php print_text('Name'); ?></th>
            <th><?php print_text('Alias'); ?></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($domains AS $domain_id => $domain): ?>
        <tr id="<?php print $domain_id; ?>">
            <td>
                <?php print $domain->name; ?>
            </td>
            <td>
                <?php $childs = get_domains_by_parent($domain_id); foreach ($childs AS $child): ?>
                <?php echo $child->name; ?><br/>
                <?php endforeach; ?>
            </td>
            <td class="text-right">
                <?php if ($domain_id==$default): ?>

                <a href="<?php print get_admin_action_link(array('id'=>$domain_id, 'action'=>'edit')); ?>" class="btn btn-inverse"><i class="icon-pencil icon-white"></i></a>
                <button class="btn btn-danger disabled"><i class="icon-trash icon-white"></i></button>

                <?php else: ?>
                
                <a href="<?php print get_admin_action_link(array('id'=>$domain_id, 'action'=>'edit')); ?>" class="btn btn-inverse"><i class="icon-pencil icon-white"></i></a>
                <a href="<?php print get_admin_action_link(array('id'=>$domain_id, 'action'=>'remove')); ?>" class="btn btn-danger" confirm="<?php print_html_attr(_T('Are you sure to remove this item?')); ?>"><i class="icon-trash"></i></a>

                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>