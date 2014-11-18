<?php
$time = Time::GetConfig();
$number = Number::GetConfig();
$metatag = Metatag::GetConfig();
?>
<form action="<?php print get_admin_action_link(array('action'=>'save')); ?>" method="post" id="configuration-settings" role="form" validate>
    <div class="well">
        <div class="row">
            
            <div class="col-md-6">
                <h4><?php print_text('Metatags'); ?></h4>
                <p class="form-group">
                    <label for="metatag_title" class="control-label"><?php print_text('Title'); ?></label>
                    <input type="text" name="metatag_title" id="metatag_title" class="form-control" value="<?php print_html_attr($metatag['title']); ?>" required>
                </p>
                <p class="form-group">
                    <label for="metatag_description" class="control-label"><?php print_text('Description'); ?></label>
                    <input type="text" name="metatag_description" id="metatag_description" class="form-control" value="<?php print_html_attr($metatag['description']); ?>">
                </p>
                <p class="form-group">
                    <label for="metatag_keywords" class="control-label"><?php print_text('Keywords'); ?></label>
                    <input type="text" name="metatag_keywords" id="metatag_keywords" class="form-control" value="<?php print_html_attr($metatag['keywords']); ?>">
                </p>
                
                <h4><?php print_text('Session'); ?></h4>
                <p class="form-group">
                    <label for="session_name" class="control-label"><?php print_text('Name'); ?></label>
                    <input type="text" name="session_name" id="session_name" class="form-control" value="<?php print_html_attr(get_session_name()); ?>" required>
                    <span class="help-block"><?php print_text('Rename the session name causing the session closing'); ?></span>
                </p>
                <p class="form-group">
                    <label for="session_time" class="control-label"><?php print_text('Time'); ?></label>
                    <input type="text" name="session_time" id="session_time" class="form-control" value="<?php print_html_attr(get_session_lifetime()); ?>" required>
                </p>
            </div>
            
            <div class="col-md-6">
                <h4><?php print_text('Time'); ?></h4>
                <p class="form-group">
                    <label for="time_timezone" class="control-label"><?php print_text('Choose your timezone'); ?>:</label>
                    <select name="time_timezone" id="time_timezone" class="form-control">
                        <?php foreach (get_timezones() AS $key => $value ): ?>
                        <option value="<?php print_html_attr($key); ?>" <?php print ($time['timezone']===$key) ? 'selected' : '' ; ?>><?php print $value; ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <p class="form-group">
                    <label for="time_time_format" class="control-label"><?php print_text('Time format'); ?>:</label>
                    <input type="text" name="time_time_format" id="time_time_format" class="form-control" value="<?php print_html_attr($time['time_format']); ?>" required>
                    <span class="help-block"><a href="http://es1.php.net/manual/en/function.strftime.php" target="_blank">PHP strftime</a></span>
                </p>
                <p class="form-group">
                    <label for="time_date_format" class="control-label"><?php print_text('Date format'); ?>:</label>
                    <input type="text" name="time_date_format" id="time_date_format" class="form-control" value="<?php print_html_attr($time['date_format']); ?>" required>
                    <span class="help-block"><a href="http://es1.php.net/manual/en/function.strftime.php" target="_blank">PHP strftime</a></span>
                </p>
                <p class="form-group">
                    <label for="datetime_format" class="control-label"><?php print_text('Datetime format'); ?>:</label>
                    <input type="text" name="datetime_format" id="datetime_format" class="form-control" value="<?php print_html_attr($time['datetime_format']); ?>" required>
                    <span class="help-block"><a href="http://es1.php.net/manual/en/function.strftime.php" target="_blank">PHP strftime</a></span>
                </p>
            </div>
        </div>
        <h4><?php print_text('Numbers'); ?></h4>
        <div class="row">
            <div class="col-md-4">
                <p class="form-group">
                    <label for="number_decimals" class="control-label"><?php print_text('Number of decimal points'); ?>:</label>
                    <input type="number" name="number_decimals" id="number_decimals" class="form-control" value="<?php print_html_attr($number['decimals']); ?>" required />
                </p>
            </div>
            <div class="col-md-4">
                <p class="form-group">
                    <label for="number_decimals_point" class="control-label"><?php print_text('Separator for the decimal point'); ?>:</label>
                    <input type="text" name="number_decimals_point" id="number_decimals_point" class="form-control" value="<?php print_html_attr($number['decimals_point']); ?>" required />
                </p>
            </div>
            <div class="col-md-4">
                <p class="form-group">
                    <label type="text" for="number_thousands_point" class="control-label"><?php print_text('Thousands separator'); ?>:</label>
                    <input type="text" name="number_thousands_point" id="number_thousands_point" class="form-control" value="<?php print_html_attr($number['thousands_point']); ?>" required />
                </p>
            </div>
        </div>
        
        <div class="form-actions text-right">
            <button type="submit" class="btn btn-large btn-success"><span class="glyphicon glyphicon-ok"></span> <?php print_text('Save'); ?></button>
        </div>
    </div>
</form>


