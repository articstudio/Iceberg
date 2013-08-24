<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="well">
    <header><?php print_text('Database'); ?></header>
    
    <form action="<?php print get_admin_action_link(array('action'=>'backup')); ?>" method="post" id="configuration-settings" target="_blank">
        <p>
            <button type="submit" class="btn btn-inverse btn-mini"><i class="icon-download-alt icon-white"></i></button> <?php print_text('Download backup'); ?>
        </p>
    </form>
    
</div>