<?php
$r_canonicals = get_routing_canonicals();
$r_canonical = get_routing_canonical();

$r_types = get_routing_types();
$r_type = get_routing_type();

$canonical = get_domain();
$canonical_childs = get_domains_by_parent($canonical->id);
$languages = get_active_langs();
$default_language = get_language_default();
$domains_by_language = get_routing_domains_by_language();
$domains = get_routing_domains();
?>
<form action="<?php print get_admin_action_link(array('action'=>'save')); ?>" method="post" id="configuration-routing" role="form" validate>
    <div class="well">
        <div class="row">
            
            <div class="col-md-6">
                <h4><?php print_text('Conincal'); ?></h4>
                <?php foreach ($r_canonicals AS $r_canonical_loop_id => $r_canonical_loop): ?>
                <p class="radio">
                    <label for="routing_canonical_<?php echo $r_canonical_loop_id; ?>" class="radio">
                        <input type="radio" name="routing_canonical" id="routing_canonical_<?php echo $r_canonical_loop_id; ?>" value="<?php echo $r_canonical_loop_id; ?>" <?php echo $r_canonical==$r_canonical_loop_id ? 'checked' : ''; ?> required />
                        <?php echo $r_canonical_loop; ?>
                    </label>
                </p>
                <?php endforeach; ?>
            </div>
            
            <div class="col-md-6">
                <h4><?php print_text('Routing type'); ?></h4>
                <?php foreach ($r_types AS $r_types_loop_id => $r_types_loop): ?>
                <p class="radio">
                    <label for="routing_type_<?php echo $r_types_loop_id; ?>" class="radio">
                        <input type="radio" name="routing_type" id="routing_type_<?php echo $r_types_loop_id; ?>" value="<?php echo $r_types_loop_id; ?>" <?php echo $r_type==$r_types_loop_id ? 'checked' : ''; ?> required />
                        <?php echo $r_types_loop['name']; ?><br/>
                        <em><?php echo $r_types_loop['example']; ?></em>
                    </label>
                </p>
                <?php endforeach; ?>
            </div>
            
        </div>
        
        
        
        <div class="row">
            <div class="col-md-4">
                <h4><?php print_text('Domains'); ?></h4>
                <ul class="list-unstyled language-list" id="domains-list" data-sortable="droppable" data-sortable-connect=".language-list">
                    <?php foreach($canonical_childs AS $domain_id => $domain): ?>
                    <?php if (!in_array($domain_id, $domains)): ?>
                    <li id="<?php print $domain_id; ?>">
                        <p><?php print $domain->name; ?></p>
                    </li>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="col-md-8">
                <?php foreach ($languages AS $locale => $lang): $language_domains = isset($domains_by_language[$locale]) ? $domains_by_language[$locale] : array(); ?>
                <h4><img src="<?php print get_flag_url($lang['flag']); ?>" alt="<?php print $locale; ?>" /> <?php echo $lang['name']; ?></h4>
                <ul class="list-unstyled language-list" id="language-<?php print $locale; ?>" data-sortable="droppable" data-sortable-connect=".language-list" data-sortable-update="#language-domains-<?php print $locale; ?>">
                    <?php foreach ($language_domains AS $language_domain_id): ?>
                    <?php if (isset($canonical_childs[$language_domain_id])): $language_domain = $canonical_childs[$language_domain_id]; ?>
                    <li id="<?php print $language_domain_id; ?>">
                        <p><?php print $language_domain->name; ?></p>
                    </li>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
                <input type="hidden" id="language-domains-<?php print $locale; ?>" name="language_domains[<?php print $locale; ?>]" value="<?php print implode(',', $language_domains); ?>">
                <?php endforeach; ?>
            </div>
        </div>
        
        
        
        <div class="form-actions text-right">
            <button type="submit" class="btn btn-large btn-success"><?php print_text('Save'); ?> <i class="icon-ok-circle icon-white"></i></button>
        </div>
    </div>
</form>



