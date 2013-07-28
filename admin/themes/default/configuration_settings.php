<?php
$time = Time::GetConfig();
$number = Number::GetConfig();
$metatag = Metatag::GetConfig();
?>
<p>Session settings</p>
<form action="<?php print get_admin_action_link(array('action'=>'save')); ?>" method="post" id="configuration-settings" validate>
    <div class="well">
        <header><?php print_text('Settings'); ?></header>
        
        <div class="row-fluid">
            
            <div class="span6">
                <p>
                    <label for="metatag_title"><?php print_text('Title'); ?>:</label>
                    <input type="text" name="metatag_title" id="metatag_title" class="input-block-level" value="<?php print_html_attr($metatag['title']); ?>" required />
                </p>
                <p>
                    <label for="metatag_description"><?php print_text('Description'); ?>:</label>
                    <input type="text" name="metatag_description" id="metatag_description" class="input-block-level" value="<?php print_html_attr($metatag['description']); ?>" />
                </p>
                <p>
                    <label for="metatag_keywords"><?php print_text('Keywords'); ?>:</label>
                    <input type="text" name="metatag_keywords" id="metatag_keywords" class="input-block-level" value="<?php print_html_attr($metatag['keywords']); ?>" />
                </p>
            </div>
            
            <div class="span6">
                <p>
                    <label for="time_timezone"><?php print_text('Choose your timezone'); ?>:</label>
                    <select name="time_timezone" id="time_timezone" class="input-block-level">
                        <?php foreach (get_timezones() AS $key => $value ): ?>
                        <option value="<?php print_html_attr($key); ?>" <?php print ($time['timezone']===$key) ? 'selected' : '' ; ?>><?php print $value; ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <p>
                    <label for="time_time_format"><?php print_text('Time format'); ?>:</label>
                    <input type="text" name="time_time_format" id="time_time_format" class="input-block-level" value="<?php print_html_attr($time['time_format']); ?>" required />
                    <small><a href="http://es1.php.net/manual/en/function.strftime.php" target="_blank">PHP strftime</a></small>
                </p>
                <p>
                    <label for="time_date_format"><?php print_text('Date format'); ?>:</label>
                    <input type="text" name="time_date_format" id="time_date_format" class="input-block-level" value="<?php print_html_attr($time['date_format']); ?>" required />
                    <small><a href="http://es1.php.net/manual/en/function.strftime.php" target="_blank">PHP strftime</a></small>
                </p>
                <p>
                    <label for="datetime_format"><?php print_text('Datetime format'); ?>:</label>
                    <input type="text" name="datetime_format" id="datetime_format" class="input-block-level" value="<?php print_html_attr($time['datetime_format']); ?>" required />
                    <small><a href="http://es1.php.net/manual/en/function.strftime.php" target="_blank">PHP strftime</a></small>
                </p>
                <div class="row-fluid">
                    <div class="span4">
                        <p>
                            <label for="number_decimals"><?php print_text('Number of decimal points'); ?>:</label>
                            <input type="number" name="number_decimals" id="number_decimals" class="input-block-level" value="<?php print_html_attr($number['decimals']); ?>" required />
                        </p>
                    </div>
                    <div class="span4">
                        <p>
                            <label for="number_decimals_point"><?php print_text('Separator for the decimal point'); ?>:</label>
                            <input type="text" name="number_decimals_point" id="number_decimals_point" class="input-block-level" value="<?php print_html_attr($number['decimals_point']); ?>" required />
                        </p>
                    </div>
                    <div class="span4">
                        <p>
                            <label type="text" for="number_thousands_point"><?php print_text('Thousands separator'); ?>:</label>
                            <input type="text" name="number_thousands_point" id="number_thousands_point" class="input-block-level" value="<?php print_html_attr($number['thousands_point']); ?>" required />
                        </p>
                    </div>
                </div>
            </div>
            
        </div>
        
        <div class="form-actions text-right">
            <button type="submit" class="btn btn-large btn-success"><?php print_text('Save'); ?> <i class="icon-ok-circle icon-white"></i></button>
        </div>
    </div>
</form>


