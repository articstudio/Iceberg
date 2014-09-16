<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="well">
    <header><?php print_text('Database'); ?></header>
    
    <div class="row-fluid">
        
        <div class="span6">
            <form action="<?php print get_admin_action_link(array('action'=>'backup')); ?>" method="post" target="_blank">
                <p>
                    <button type="submit" class="btn btn-inverse btn-mini"><i class="icon-download-alt icon-white"></i></button> <?php print_text('Download backup'); ?>
                </p>
            </form>
        </div>
        
        <div class="span6">
            <div class="well">
                <header><?php print_text('Serach & Replace'); ?></header>
                <form action="<?php print get_admin_action_link(array('action'=>'search-replace')); ?>" method="post" target="_blank">
                    <p>
                        <label for="db_search"><?php print_text('Search'); ?>:</label>
                        <input type="text" name="db_search" id="db_search" class="input-block-level" value="" required />
                    </p>
                    <p>
                        <label for="db_replace"><?php print_text('Replace'); ?>:</label>
                        <input type="text" name="db_replace" id="db_replace" class="input-block-level" value="" required />
                    </p>
                    <p class="text-right">
                        <button type="submit" class="btn btn-inverse"><?php print_text('Download'); ?></button>
                    </p>
                </form>
            </div>
        </div>
        
    </div>
    
    
    
    
</div>