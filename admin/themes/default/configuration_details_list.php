<?php
$language = get_language_info();
?>
<div class="row">
    <div class="col-md-6">
        <h4><?php print_text('Configuration'); ?></h4>
        <?php
        $args = array(
            'id' => 'config-list',
            'allconfig' => true,
            'attrs' => array(
                'data-new' => get_admin_action_link(array('action'=>'new','allconfig'=>1)),
                'data-paginate' => 20
            ),
            'classes' => array('data-sort', 'data-filter')
        );
        $table = new TableConfigs($args);
        $table->loadItems();
        $table->show();
        ?>
    </div>
    <div class="col-md-6">
        <h4><img src="<?php print get_base_url() . $language['flag']; ?>" alt="<?php print_html_attr($language['name']); ?>" class="flag"> <?php print $language['name']; ?> - <?php print_text('Configuration'); ?></h4>
        <?php
        $args = array(
            'id' => 'config-lang-list',
            'attrs' => array(
                'data-new' => get_admin_action_link(array('action'=>'new')),
                'data-paginate' => 8
            ),
            'classes' => array('data-sort', 'data-filter')
        );
        $table = new TableConfigs($args);
        $table->loadItems();
        $table->show();
        ?>
    </div>
</div>

