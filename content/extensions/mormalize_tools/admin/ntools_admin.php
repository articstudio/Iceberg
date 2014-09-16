
<div class="well">
    <header><?php print_text('Normalization tools'); ?></header>
    
    <div class="row-fluid">
        
        <div class="span6">
            <form action="<?php print get_admin_action_link(array('action'=>'nptr')); ?>" method="post">
                <p>
                    <button type="submit" class="btn btn-inverse btn-mini"><i class="icon-wrench icon-white"></i></button> <?php print_text('Normalize pages taxonomy relations'); ?>
                </p>
            </form>
            <form action="<?php print get_admin_action_link(array('action'=>'spp')); ?>" method="post">
                <p>
                    <button type="submit" class="btn btn-inverse btn-mini"><i class="icon-wrench icon-white"></i></button> <?php print_text('Sanitize pages permalinks'); ?>
                </p>
            </form>
        </div>
        
        <div class="span6">
            <div class="well">
                <header><?php print_text('Serach & Replace Metas'); ?></header>
                <form action="<?php print get_admin_action_link(array('action'=>'search-replace-metas')); ?>" method="post">
                    <p>
                        <label for="txt_search"><?php print_text('Search'); ?>:</label>
                        <input type="text" name="txt_search" id="txt_search" class="input-block-level" value="" required />
                    </p>
                    <p>
                        <label for="txt_replace"><?php print_text('Replace'); ?>:</label>
                        <input type="text" name="txt_replace" id="txt_replace" class="input-block-level" value="" required />
                    </p>
                    <p class="text-right">
                        <button type="submit" class="btn btn-inverse"><?php print_text('Serach & Replace'); ?></button>
                    </p>
                </form>
            </div>
        </div>
        
    </div>
    
    
    
    
</div>