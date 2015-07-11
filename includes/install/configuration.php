<header id="page-header">
    <h2><?php print_text('CONFIGURATION'); ?></h2>
    <ol class="breadcrumb">
        <li><?php print_text('Install'); ?></li>
        <li class="active"><?php print_text('Configuration'); ?></li>
    </ol>
</header>

<div class="progress">
    <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:50%;">
        <span class="sr-only">50%</span>
    </div>
    <div class="progress-bar progress-bar-warning progress-bar-striped active" style="width: 35%">
        <span class="sr-only">35%</span>
    </div>
</div>

<?php $alerts = get_install_alerts(); foreach ($alerts AS $sms): ?>
<?php if ($sms['type']==='title'): ?>
<h3><?php print_text( $sms['text'] ); ?></h3>
<?php elseif ($sms['type']==='success'): ?>
<p class="alert alert-success"><i class="glyphicon glyphicon-ok"></i> <?php print_text( $sms['text'] ); ?></p>
<?php else: ?>
<p class="alert alert-danger"><i class="glyphicon glyphicon-warning-sign"></i> <?php print_text( $sms['text'] ); ?></p>
<?php endif; ?>
<?php endforeach; ?>

<form action="<?php print get_install_next_step_link(); ?>" method="post" id="form-config" role="form">
    <input type="hidden" name="nonce" value="<?php echo nonce_make('iceberg_install'); ?>">
    
    <p><?php print_text('To install the ICEBERG fill out the form with the necessary data. If you have any questions please contact your administrator.'); ?></p>
    
    <?php
    $timezones = get_timezones();
    $default_timezone = date_default_timezone_get();
    ?>
    <hr>
    <h4><?php print_text('Time Zone'); ?></h4>
    <div class="form-group">
        <label for="timezone" class="control-label"><?php print_text('Choose your timezone'); ?></label>
        <select name="timezone" id="timezone" class="form-control">
            <?php foreach ($timezones AS $key => $value ): ?>
            <option value="<?php print_html_attr($key); ?>" <?php echo ($default_timezone===$key) ? 'selected' : '' ; ?>><?php echo $value; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <hr>
    <h4><?php print_text('Domain and alias'); ?></h4>
    <p><?php printf( _T('The URL installation is: %s'), '<strong>'.get_base_url().'</strong>' ); ?></p>
    <p><?php printf( _T('The Domain installation is: %s'), '<strong>'.get_request_domain().'</strong>' ); ?></p>
    <p><?php print_text('If you use aliases for this domain, plis add them'); ?>:</p>
    <div class="row">
        <div class="col-md-6">
            <p class="form-group">
                <label for="domain" class="control-label"><?php print_text('Domain'); ?></label>
                <input type="text" id="domain" class="form-control" placeholder="<?php print_text('Domain'); ?>">
            </p>
            <p class="form-group">
                <button id="domain-plus" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-plus"></i> <?php print_text( 'ADD' ); ?></button>
            </p>
        </div>
        <div class="col-md-6">
            <p class="form-group">
                <label for="domains" class="control-label"><?php print_text('Domains'); ?></label>
                <select id="domains" class="form-control" multiple="multiple"></select>
                <input type="hidden" name="domainslist" id="domainslist" value="" />
            </p>
            <p class="form-group">
                <button id="domain-minus" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-minus"></i> <?php print_text( 'REMOVE' ); ?></button>
            </p>
        </div>
    </div>
    
    
    <hr>
    <h4><?php print_text('Root access'); ?></h4>
    <div class="row">
        <div class="col-md-6">
            <p class="form-group">
                <label for="username" class="control-label"><?php print_text('User'); ?></label>
                <input type="text" name="username" id="username" class="form-control" placeholder="<?php print_text('User'); ?>">
            </p>
            <p class="form-group">
                <label for="email" class="control-label"><?php print_text('E-mail'); ?></label>
                <input type="text" name="email" id="email" class="form-control" placeholder="<?php print_text('E-mail'); ?>">
            </p>
        </div>
        <div class="col-md-6">
            <p class="form-group">
                <label for="password" class="control-label"><?php print_text('Password'); ?></label>
                <input type="text" name="password" id="password" class="form-control" placeholder="<?php print_text('Password'); ?>">
            </p>
            <p class="form-group">
                <button id="password-generate" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-wrench"></i> <?php print_text( 'GENERATE' ); ?></button>
            </p>
        </div>
    </div>
    
    
    <?php
    $collates = get_mysql_collates();
    ?>
    <hr>
    <h4><?php print_text('Database'); ?></h4>
    <div class="row">
        <div class="col-md-6">
            <p class="form-group">
                <label for="dbprefix" class="control-label"><?php print_text('Prefix'); ?></label>
                <input type="text" name="dbprefix" id="dbprefix" value="iceberg_" class="form-control" placeholder="<?php print_text('Prefix'); ?>">
            </p>
        </div>
    </div>
    <p><?php print_text('Add the Database Connections'); ?>:</p>
    <div class="row">
        <div class="col-md-6">
            <p class="form-group">
                <label for="dbhost" class="control-label"><?php print_text('Host'); ?></label>
                <input type="text" name="dbhost" id="dbhost" value="localhost" class="form-control" placeholder="<?php print_text('Host'); ?>">
            </p>
            <p class="form-group">
                <label for="dbport" class="control-label"><?php print_text('Port'); ?></label>
                <input type="text" name="dbport" id="dbport" value="3306" class="form-control" placeholder="<?php print_text('Port'); ?>">
            </p>
            <p class="form-group">
                <label for="dbname" class="control-label"><?php print_text('DB Name'); ?></label>
                <input type="text" name="dbname" id="dbname" class="form-control" placeholder="<?php print_text('DB Name'); ?>">
            </p>
            <p class="form-group">
                <label for="dbuser" class="control-label"><?php print_text('User'); ?></label>
                <input type="text" name="dbuser" id="dbuser" class="form-control" placeholder="<?php print_text('User'); ?>">
            </p>
            <p class="form-group">
                <label for="dbpass" class="control-label"><?php print_text('Password'); ?></label>
                <input type="text" name="dbpass" id="dbpass" class="form-control" placeholder="<?php print_text('Password'); ?>">
            </p>
            <p class="form-group">
                <label for="dbcollate" class="control-label"><?php print_text('Collate'); ?></label>
                <select name="dbcollate" id="dbcollate" class="form-control">
                    <?php foreach ($collates AS $key => $value ): ?>
                    <option value="<?php print_html_attr($key); ?>"><?php echo $value; ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
            <p class="form-group">
                <button id="db-plus" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-plus"></i> <?php print_text( 'ADD' ); ?></button>
            </p>
        </div>
        <div class="col-md-6">
            <p class="form-group">
                <label for="dbs" class="control-label"><?php print_text('Databases'); ?></label>
                <select name="dbs" id="dbs" class="form-control" multiple="multiple" size="23"></select>
                <input type="hidden" name="dbslist" id="dbslist" value="" />
            </p>
            <p class="form-group">
                <button id="db-minus" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-minus"></i> <?php print_text( 'REMOVE' ); ?></button>
            </p>
        </div>
    </div>
    
    <div class="form-actions text-right">
        <button type="submit" class="btn btn-default"><?php print_text('Next step'); ?> <span class="glyphicon glyphicon-chevron-right"></span></button>
    </div>
</form>
