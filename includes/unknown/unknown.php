<?php
Request::Response(404);
$domains = get_domains_canonicals();
?>
<h1>UNKNOWN DOMAIN</h1>
<ul>
    <?php foreach ($domains AS $domain_id => $domain): $childs = get_domains_by_parent($domain_id); ?>
    <li>
        <a href="<?php echo $domain->GetCanonicalDomain(); ?>" title="<?php print_html_attr($domain->GetCanonicalName()); ?>"><?php echo $domain->GetCanonicalName(); ?></a>
        <?php if (!empty($childs)): ?>
        <ul>
            <?php foreach ($childs AS $child): ?>
            <?php echo $child->name; ?>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </li>
    <?php endforeach; ?>
</ul>