<?php
$maintenance = Maintenance::GetConfig();
$start_date = strftime('%m/%d/%Y', (int)$maintenance['start']);
$start_hour = strftime('%H', (int)$maintenance['start']);
$start_minute = strftime('%M', (int)$maintenance['start']);
$start_second = strftime('%S', (int)$maintenance['start']);
$stop_date = strftime('%m/%d/%Y', (int)$maintenance['stop']);
$stop_hour = strftime('%H', (int)$maintenance['stop']);
$stop_minute = strftime('%M', (int)$maintenance['stop']);
$stop_second = strftime('%S', (int)$maintenance['stop']);
?>

<form action="<?php print get_admin_action_link(array('action'=>'save')); ?>" method="post" id="configuration-maintenance" validate>
    <div class="well">
        <header><?php print_text('Maintenance'); ?></header>
        
        <div class="row-fluid">
            
            <div class="span4">
                <p>
                    <label for="maintenance_active" class="checkbox">
                        <input type="checkbox" name="maintenance_active" id="maintenance_active" value="1" <?php print $maintenance['active'] ? 'checked' : ''; ?> />
                        <?php print_text('Active'); ?>
                    </label>
                </p>
                <br />
                <br />
                <p>
                    <label for="maintenance_permanent" class="checkbox">
                        <input type="checkbox" name="maintenance_permanent" id="maintenance_permanent" value="1" <?php print $maintenance['permanent'] ? 'checked' : ''; ?> />
                        <?php print_text('Permanent'); ?>
                    </label>
                </p>
            </div>
            
            <div class="span4">
                <div class="text-center">
                    <p class="text-center"><?php print_text('Start date'); ?></p>
                    <input type="text" class="input-medium text-center" datepicker="inline" name="maintenance_start_date" id="maintenance_start_date" value="<?php print $start_date; ?>" data-date="<?php print $start_date; ?>" data-date-format="mm/dd/yyyy" />
                    <p class="text-center">
                        <select name="maintenance_start_hour" id="maintenance_start_hour" class="input-mini">
                            <?php for($i=0; $i<24; $i++): ?>
                            <option value="<?php print $i; ?>" <?php print $i==$start_hour ? 'selected' : '' ;?>><?php printf('%02u', $i); ?></option>
                            <?php endfor; ?>
                        </select>
                        :
                        <select name="maintenance_start_minute" id="maintenance_start_minute" class="input-mini">
                            <?php for($i=0; $i<60; $i++): ?>
                            <option value="<?php print $i; ?>" <?php print $i==$start_minute ? 'selected' : '' ;?>><?php printf('%02u', $i); ?></option>
                            <?php endfor; ?>
                        </select>
                        :
                        <select name="maintenance_start_second" id="maintenance_start_second" class="input-mini">
                            <?php for($i=0; $i<60; $i++): ?>
                            <option value="<?php print $i; ?>" <?php print $i==$start_second ? 'selected' : '' ;?>><?php printf('%02u', $i); ?></option>
                            <?php endfor; ?>
                        </select>
                    </p>
                </div>
            </div>
            
            <div class="span4">
                <div class="text-center">
                    <p class="text-center"><?php print_text('Stop date'); ?></p>
                    <input type="text" class="input-medium text-center" datepicker="inline" name="maintenance_stop_date" id="maintenance_stop_date" value="<?php print $stop_date; ?>" data-date="<?php print $stop_date; ?>" data-date-format="mm/dd/yyyy" />
                    <p class="text-center">
                        <select name="maintenance_stop_hour" id="maintenance_stop_hour" class="input-mini">
                            <?php for($i=0; $i<24; $i++): ?>
                            <option value="<?php print $i; ?>" <?php print $i==$stop_hour ? 'selected' : '' ;?>><?php printf('%02u', $i); ?></option>
                            <?php endfor; ?>
                        </select>
                        :
                        <select name="maintenance_stop_minute" id="maintenance_stop_minute" class="input-mini">
                            <?php for($i=0; $i<60; $i++): ?>
                            <option value="<?php print $i; ?>" <?php print $i==$stop_minute ? 'selected' : '' ;?>><?php printf('%02u', $i); ?></option>
                            <?php endfor; ?>
                        </select>
                        :
                        <select name="maintenance_stop_second" id="maintenance_stop_second" class="input-mini">
                            <?php for($i=0; $i<60; $i++): ?>
                            <option value="<?php print $i; ?>" <?php print $i==$stop_second ? 'selected' : '' ;?>><?php printf('%02u', $i); ?></option>
                            <?php endfor; ?>
                        </select>
                    </p>
                </div>
            </div>
        </div>
        
        <p>
            <label for="maintenance_allowed"><?php print_text('Allowed IPs'); ?>:</label>
            <input type="text" name="maintenance_allowed" id="maintenance_allowed" class="input-block-level" value="<?php print_html_attr($maintenance['allowed']); ?>" />
            <small><?php print_text('Your IP address'); ?>: <?php print getIP(); ?></small>
        </p>
        
        <div class="form-actions text-right">
            <button type="submit" class="btn btn-large btn-success"><?php print_text('Save'); ?> <i class="icon-ok-circle icon-white"></i></button>
        </div>
    </div>
</form>
