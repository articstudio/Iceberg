<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="well">
    <div class="row">
        
        <div class="col-md-6">
            <form action="<?php print get_admin_action_link(array('action'=>'backup')); ?>" method="post" target="_blank" id="backup-form" role="form">
                <p class="form-group">
                    <button type="submit" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-download-alt"></span> <?php print_text('Download backup'); ?></button>
                </p>
            </form>
            <form action="<?php print get_install_reinstall_link(); ?>" method="post" id="reinstall-form" class="well" role="form" validate>
                <div class="form-group">
                    <button type="submit" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-refresh"></span> <?php print_text('Reinstall'); ?></button>
                    <span class="checkbox-inline">
                        <label class="checkbox" for="reinstall-confirm">
                            <input type="checkbox" name="reinstall-confirm" id="reinstall-confirm" value="1" required>
                            <?php print_text('Confirm reinstall'); ?>
                        </label>
                    </span>
                </div>
            </form>
        </div>
        
        <div class="col-md-6">
            <h4><?php print_text('Serach & Replace'); ?></h4>
            <form action="<?php print get_admin_action_link(array('action'=>'searchreplace')); ?>" method="post" target="_blank" id="searchreplace-form" role="form" validate>
                <p class="form-group">
                    <label for="db_search" class="control-label"><?php print_text('Search'); ?>:</label>
                    <input type="text" name="db_search" id="db_search" class="form-control" value="" required>
                </p>
                <p class="form-group">
                    <label for="db_replace" class="control-label"><?php print_text('Replace'); ?>:</label>
                    <input type="text" name="db_replace" id="db_replace" class="form-control" value="" required>
                </p>
                <p class="form-group text-right">
                    <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-download-alt"></span> <?php print_text('Download'); ?></button>
                </p>
            </form>
        </div>
        
    </div>
    
    
    
    
</div>