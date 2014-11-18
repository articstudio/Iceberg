<header id="article-header">
    <h1><?php print_text('CONFIGURATION'); ?></h1>
    <span><?php print_text('Path'); ?>:</span>&nbsp;<?php print_text('Install'); ?> &gt; <?php print_text('Configuration'); ?>
</header>

<div id="article-content">
    
    <div class="alert alert-info">
        <h4><?php print_text('Progress'); ?></h4>
        <div class="progress progress-striped active">
            <div class="bar bar-success" style="width: 50%;"></div>
            <div class="bar" style="width: 35%;"></div>
        </div>
    </div>
    
    
    <?php foreach (get_install_alerts() AS $sms): ?>
    <?php if ($sms['type']==='title'): ?>
    <h3><?php print_text( $sms['text'] ); ?></h3>
    <?php elseif ($sms['type']==='success'): ?>
    <p class="alert alert-success"><i class="icon-ok icon-white"></i> <?php print_text( $sms['text'] ); ?></p>
    <?php else: ?>
    <p class="alert alert-error"><i class="icon-warning-sign icon-white"></i> <?php print_text( $sms['text'] ); ?></p>
    <?php endif; ?>
    <? endforeach; ?>
    
    
    <form action="<?php print get_install_next_step_link(); ?>" method="post" id="formconfig">
        <fieldset>
            <input type="hidden" name="nonce" value="<?php echo nonce_make('iceberg_install'); ?>" />
            
            <p><?php print_text('To install the ICEBERG fill out the form with the necessary data. If you have any questions please contact your administrator.'); ?></p>
            
            <hr />
            
            <h5><?php print_text('Time Zone'); ?></h5>
            <div class="row-fluid">
                <div class="span6">
                    <label for="timezone"><?php print_text('Choose your timezone'); ?>:</label>
                    <select name="timezone" id="timezone" class="input-block-level">
                        <?php foreach (get_timezones() AS $key => $value ): ?>
                        <option value="<?php print_html_attr($key); ?>" <?php print (date_default_timezone_get()===$key) ? 'selected' : '' ; ?>><?php printf( $value ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <hr />

            <h5><?php print_text('Domain and alias'); ?></h5>
            <p><?php printf( _T('The URL installation is: %s'), '<strong>'.get_base_url().'</strong>' ); ?></p>
            <p><?php printf( _T('The Domain installation is: %s'), '<strong>'.get_request_domain().'</strong>' ); ?></p>
            <p><?php print_text('If you use aliases for this domain, plis add them'); ?>:</p>
            <div class="row-fluid">
                <div class="span6">
                    <input type="text" name="domain" id="domain" class="input-block-level" placeholder="<?php print_text('Domain'); ?>" />
                    <a href="javascript:addDomain();" title="<?php print_html_attr( _T('ADD') ); ?>" class="btn btn-inverse"><i class="icon-plus-sign icon-white"></i> <?php print_text( 'ADD' ); ?></a>
                </div>
                <div class="span6">
                    <select name="domains" id="domains" class="input-block-level" multiple="multiple"></select>
                    <a href="javascript:removeDomain();" title="<?php print_html_attr( _T('REMOVE') ); ?>" class="btn btn-inverse"><i class="icon-minus-sign icon-white"></i> <?php print_text( 'REMOVE' ); ?></a>
                    <input type="hidden" name="domainslist" id="domainslist" value="" />
                </div>
            </div>
            
            <hr />
            
            <h5><?php print_text('Root access'); ?></h5>
            <div class="row-fluid">
                <div class="span6">
                    <input type="text" name="username" id="username" class="input-block-level" placeholder="<?php print_text('User'); ?>" />
                    <br />
                    <input type="text" name="email" id="email" class="input-block-level" placeholder="<?php print_text('E-mail'); ?>" />
                </div>
                <div class="span6">
                    <input type="text" name="password" id="password" class="input-block-level" placeholder="<?php print_text('Password'); ?>" />
                    <p class="text-right">
                        <a href="javascript:generatePassword();" title="<?php print_html_attr( _T('GENERATE') ); ?>" class="btn btn-inverse"><i class="icon-wrench icon-white"></i> <?php print_text( 'GENERATE' ); ?></a>
                    </p>
                </div>
            </div>
            
            <hr />
            
            <h5><?php print_text('Data Base'); ?></h5>
            <div class="row-fluid">
                <div class="span4">
                    <label for="dbprefix" class=""><?php print_text('Prefix:'); ?></label>
                    <input type="text" name="dbprefix" id="dbprefix" value="iceberg_" class="input-block-level" />
                </div>
            </div>
            
            <p><?php print_text('Add the Data Base Connections'); ?>:</p>
            <div class="row-fluid">
                <div class="span4">
                    <input type="text" name="dbhost" id="dbhost" value="localhost" class="input-block-level" placeholder="<?php print_text('Host'); ?>" />
                    <br />
                    <input type="text" name="dbport" id="dbport" value="3306" class="input-block-level" placeholder="<?php print_text('Port'); ?>" />
                    <br />
                    <input type="text" name="dbname" id="dbname" class="input-block-level" placeholder="<?php print_text('DB Name'); ?>" />
                    <br />
                    <input type="text" name="dbuser" id="dbuser" class="input-block-level" placeholder="<?php print_text('User'); ?>" />
                    <br />
                    <input type="text" name="dbpass" id="dbpass" class="input-block-level" placeholder="<?php print_text('Password'); ?>" />
                    <br />
                    <select name="dbcollate" id="dbcollate" class="input-block-level" placeholder="<?php print_text('Collate'); ?>">
                        <?php foreach (get_mysql_collates() AS $key => $value ): ?>
                        <option value="<?php print_html_attr($key); ?>"><?php printf( $value ); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <a href="javascript:addDB();" title="<?php print_html_attr( _T('ADD') ); ?>" class="btn btn-inverse"><i class="icon-plus-sign icon-white"></i> <?php print_text( 'ADD' ); ?></a>
                </div>
                <div class="span8">
                    <select name="dbs" id="dbs" class="input-block-level" multiple></select>
                    <a href="javascript:removeDB();" title="<?php print_html_attr( _T('REMOVE') ); ?>" class="btn btn-inverse"><i class="icon-minus-sign icon-white"></i> <?php print_text( 'REMOVE' ); ?></a>
                    <input type="hidden" name="dbslist" id="dbslist" value="" />
                </div>
            </div>
            
        </fieldset>
        

    </form>

    <div class="form-actions text-right">
        <a href="javascript:nextStep3();" title="<?php print_html_attr( _T('Next step') ); ?>" class="btn btn-inverse"><?php print_text('Next step'); ?> <i class="icon-chevron-right icon-white"></i></a>
    </div>
</div>

<script>
    var step3_error = {
        title: "<?php print_html_attr(_T('CONFIGURATION')); ?>",
        content: "<?php print_html_attr(_T('To go to the next step you must complete all required fields.')); ?>"
    }
</script>