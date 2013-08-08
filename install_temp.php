<?php

            /* DOMAINS TABLE */
            $done = Domain::DB_CreateTable();
            if (!$done) { array_push($install_sms, array('type'=>'alert', 'text'=>'Create DOMAINS table.') ); $install_nerrors++; }
            
            /* DOMAIN DATA */
            $domainId = $done = Domain::DB_Insert(array(
                'name' => get_base_url(false)
            ));
            if (!$done) { array_push($install_sms, array('type'=>'alert', 'text'=>'Insert URL Base configuration to DOMAINS.') ); $install_nerrors++; }
            
            /* DOMAIN ALIAS */
            $domainAliases = json_decode(get_request_gp('domainslist', '[]', true), true);
            if (!empty($domainAliases)) {
                foreach ($domainAliases AS $alias) {
                    Domain::DB_Insert(array(
                        'name' => $alias,
                        'pid' => $domainId
                    ));
                    if (!$done) { array_push($install_sms, array('type'=>'alert', 'text'=>'Insert URL alias configuration to DOMAINS.') ); $install_nerrors++; }
                }
            }
            
            /* USERS TABLE */
            $done = User::DB_CreateTable();
            if (!$done) { array_push($install_sms, array('type'=>'alert', 'text'=>'Create USERS table.') ); $install_nerrors++; }

            /* ROOT USER */
            $userId = $done = User::DB_Insert(array(
                'email' => get_request_gp('email', '', true),
                'username' => get_request_gp('username', '', true),
                'password' => User::EnctryptPassword(get_request_gp('password', '', true)),
                'level' => 999
            ));
            if (!$done) { array_push($install_sms, array('type'=>'alert', 'text'=>'Insert Root configuration to USERS.') ); $install_nerrors++; }
            
            /* USERS META TABLE */
            $done = db_create_table(
                ICEBERG_DB_PREFIX . ICEBERG_DB_USERS_METAS,
                array(
                    '`id` BIGINT( 20 ) NOT NULL AUTO_INCREMENT PRIMARY KEY',
                    '`pid` BIGINT( 20 )',
                    '`did` BIGINT( 20 ) NOT NULL',
                    '`name` VARCHAR( 150 )',
                    '`value` LONGTEXT NOT NULL',
                    '`language` VARCHAR( 150 )'
                ),
                array(
                    'pid',
                    'did',
                    'name',
                    'language'
                )
            );
            if (!$done) { array_push($install_sms, array('type'=>'alert', 'text'=>'Create USERS METAS table.') ); $install_nerrors++; }
            
            /* USERS2DOMAINS TABLE */
            $done = db_create_table(
                ICEBERG_DB_PREFIX . ICEBERG_DB_USERS2DOMAINS,
                array(
                    '`id` BIGINT( 20 ) NOT NULL AUTO_INCREMENT PRIMARY KEY',
                    '`uid` BIGINT( 20 ) NOT NULL',
                    '`did` BIGINT( 20 ) NOT NULL'
                ),
                array(
                    'uid',
                    'did'
                )
            );
            if (!$done) { array_push($install_sms, array('type'=>'alert', 'text'=>'Create USERS to DOMAINS table.') ); $install_nerrors++; }
            
            /* USERS2DOMAINS DATA */
            $done = db_insert(
                ICEBERG_DB_PREFIX . ICEBERG_DB_USERS2DOMAINS,
                array('uid', 'did'),
                array(
                    $userId,
                    $domainId
                )
            );
            if (!$done) { array_push($install_sms, array('type'=>'alert', 'text'=>'Insert Root for domain association to USERS 2 DOMAINS.') ); $install_nerrors++; }
            
            /* CONFIG TABLE */
            $done = Config::DB_CreateTable();
            if (!$done) { array_push($install_sms, array('type'=>'alert', 'text'=>'Create CONFIG table.') ); $install_nerrors++; }
            
            /* REQUEST */
            Request::SaveConfig(array());
            
            /* ROUTING */
            Routing::SaveConfig(array());
            
            /* ROUTING */
            Session::SaveConfig(array(
                'name' => ICEBERG_SESSION_NAME,
                'time' => ICEBERG_SESSION_TIME
            ));
            
            
            /* TIMEZONE CONFIG */
            $done = db_insert(
                ICEBERG_DB_PREFIX . ICEBERG_DB_CONFIG,
                array('did', 'name', 'value'),
                array(
                    $domainId,
                    TimeZones::$CONFIG_KEY,
                    $timezone
                )
            );
            if (!$done) { array_push($install_sms, array('type'=>'alert', 'text'=>'Insert Time Zone configuration to CONFIG.') ); $install_nerrors++; }

            /* DEFAULT LANGUAGE CONFIG */
            $done = db_insert(
                ICEBERG_DB_PREFIX . ICEBERG_DB_CONFIG,
                array('did', 'name', 'value'),
                array(
                    $domainId,
                    Config::KEY_LANGUAGE_DEFAULT,
                    get_lang()
                )
            );
            if (!$done) { array_push($install_sms, array('type'=>'alert', 'text'=>'Insert Default Language configuration to CONFIG.') ); $install_nerrors++; }

            /* LANGUAGES CONFIG */
            $done = db_insert(
                ICEBERG_DB_PREFIX . ICEBERG_DB_CONFIG,
                array('did', 'name', 'value'),
                array(
                    $domainId,
                    Config::KEY_LANGUAGES,
                    json_encode($__LANGUAGES)
                )
            );
            if (!$done) { array_push($install_sms, array('type'=>'alert', 'text'=>'Insert Languages configuration to CONFIG.') ); $install_nerrors++; }

            /* USERS CONFIG */
            $done = db_insert(
                ICEBERG_DB_PREFIX . ICEBERG_DB_CONFIG,
                array('did', 'name', 'value'),
                array(
                    $domainId,
                    User::$CONFIG_KEY,
                    '{"multisession":true,"sessionname":"' . mysql_escape( ICEBERG_SESSION_NAME ) . '","sessiontime":' . mysql_escape( ICEBERG_SESSION_TIME ) . ',"min-admin-level":90,"levels":[{"level":999,"name":"Root"},{"level":200,"name":"Administrator"},{"level":100,"name":"Editor"},{"level":90,"name":"Translator"},{"level":2,"name":"User"},{"level":1,"name":"Anonymous"},{"level":0,"name":"Banned"}]}'
                )
            );
            if (!$done) { array_push($install_sms, array('type'=>'alert', 'text'=>'Insert Users configuration to CONFIG.') ); $install_nerrors++; }

            /* THEME CONFIG */
            $done = db_insert(
                ICEBERG_DB_PREFIX . ICEBERG_DB_CONFIG,
                array('did', 'name', 'value'),
                array(
                    $domainId,
                    Config::KEY_THEME,
                    '{"frontend":{"name":"Iceberg default theme","dirname":"default"},"backend":{"name":"Iceberg default theme","dirname":"default"}}'
                )
            );
            if (!$done) { array_push($install_sms, array('type'=>'alert', 'text'=>'Insert Theme configuration to CONFIG.') ); $install_nerrors++; }

            /* MAINTENANCE CONFIG */
            $done = db_insert(
                ICEBERG_DB_PREFIX . ICEBERG_DB_CONFIG,
                array('did', 'name', 'value'),
                array(
                    $domainId,
                    Config::KEY_MAINTENANCE,
                    '{"active":1,"permanent":1,"strat":0,"stop":0,"ips":""}'
                )
            );
            if (!$done) { array_push($install_sms, array('type'=>'alert', 'text'=>'Insert Maintenance configuration to CONFIG.') ); $install_nerrors++; }

            /* DEFAULT METATAGS CONFIG */
            $done = db_insert(
                ICEBERG_DB_PREFIX . ICEBERG_DB_CONFIG,
                array('did', 'name', 'value'),
                array(
                    $domainId,
                    Config::KEY_METATAGS_DEFAULT,
                    '{title:"",description:"",keywords:""}'
                )
            );
            if (!$done) { array_push($install_sms, array('type'=>'alert', 'text'=>'Insert Maintenance configuration to CONFIG.') ); $install_nerrors++; }

            /* EXTENSIONS CONFIG */
            $done = db_insert(
                ICEBERG_DB_PREFIX . ICEBERG_DB_CONFIG,
                array('did', 'name', 'value'),
                array(
                    $domainId,
                    Config::KEY_EXTENSIONS,
                    '{}'
                )
            );
            if (!$done) { array_push($install_sms, array('type'=>'alert', 'text'=>'Insert Extensions configuration to CONFIG.') ); $install_nerrors++; }

            /* MENUBAR CONFIG */
            $done = db_insert(
                ICEBERG_DB_PREFIX . ICEBERG_DB_CONFIG,
                array('did', 'name', 'value'),
                array(
                    $domainId,
                    Config::KEY_MENUBAR,
                    '{}'
                )
            );
            if (!$done) { array_push($install_sms, array('type'=>'alert', 'text'=>'Insert Extensions configuration to CONFIG.') ); $install_nerrors++; }
            
            /* TAXONOMY TABLE */
            $done = db_create_table(
                ICEBERG_DB_PREFIX . ICEBERG_DB_TAXONOMY,
                array(
                    '`id` BIGINT( 20 ) NOT NULL AUTO_INCREMENT PRIMARY KEY',
                    '`pid` BIGINT( 20 )',
                    '`did` BIGINT( 20 ) NOT NULL',
                    '`name` VARCHAR( 150 )',
                    '`value` LONGTEXT NOT NULL',
                    '`language` VARCHAR( 150 )',
                    '`count` BIGINT( 20 ) NOT NULL DEFAULT \'0\''
                ),
                array(
                    'pid',
                    'did',
                    'name',
                    'language',
                    'count'
                )
            );
            if (!$done) { array_push($install_sms, array('type'=>'alert', 'text'=>'Create TAXONOMY table.') ); $install_nerrors++; }
            
            /* PAGES TABLE */
            $done = db_create_table(
                ICEBERG_DB_PREFIX . ICEBERG_DB_PAGES,
                array(
                    '`id` BIGINT( 20 ) NOT NULL AUTO_INCREMENT PRIMARY KEY',
                    '`pid` BIGINT( 20 )',
                    '`did` BIGINT( 20 ) NOT NULL',
                    '`taxonomy` BIGINT( 20 ) NOT NULL',
                    '`type` VARCHAR( 20 )',
                    '`count` BIGINT( 20 ) NOT NULL DEFAULT \'0\'',
                    '`status` INT( 3 ) NOT NULL DEFAULT \'1\'',
                    '`created` DATETIME NOT NULL',
                    '`created_uid` BIGINT( 20 ) NOT NULL DEFAULT \'-1\'',
                    '`edited` DATETIME NOT NULL',
                    '`edited_uid` BIGINT( 20 ) NOT NULL DEFAULT \'-1\''
                ),
                array(
                    'pid',
                    'did',
                    'taxonomy',
                    'count',
                    'status'
                )
            );
            if (!$done) { array_push($install_sms, array('type'=>'alert', 'text'=>'Create PAGES table.') ); $install_nerrors++; }
            
            /* PAGES METAS TABLE */
            $done = db_create_table(
                ICEBERG_DB_PREFIX . ICEBERG_DB_PAGES_METAS,
                array(
                    '`id` BIGINT( 20 ) NOT NULL AUTO_INCREMENT PRIMARY KEY',
                    '`pid` BIGINT( 20 )',
                    '`did` BIGINT( 20 ) NOT NULL',
                    '`taxonomy` BIGINT( 20 ) NOT NULL',
                    '`name` VARCHAR( 150 )',
                    '`value` LONGTEXT NOT NULL',
                    '`language` VARCHAR( 150 )'
                ),
                array(
                    'pid',
                    'did',
                    'taxonomy',
                    'name',
                    'language'
                )
            );
            if (!$done) { array_push($install_sms, array('type'=>'alert', 'text'=>'Create METAS table.') ); $install_nerrors++; }
            
            /* ACTIONS TABLE */
            $done = db_create_table(
                ICEBERG_DB_PREFIX . ICEBERG_DB_ACTIONS,
                array(
                    '`id` BIGINT( 20 ) NOT NULL AUTO_INCREMENT PRIMARY KEY',
                    '`did` BIGINT( 20 ) NOT NULL',
                    '`event` VARCHAR( 150 ) NOT NULL',
                    '`function` VARCHAR( 150 ) NOT NULL',
                    '`priority` INT( 3 ) NOT NULL DEFAULT \'10\'',
                    '`arguments` INT( 3 ) NOT NULL DEFAULT \'1\''
                ),
                array(
                    'did',
                    'event',
                    'function',
                    'priority',
                    'arguments'
                )
            );
            if (!$done) { array_push($install_sms, array('type'=>'alert', 'text'=>'Create ACTIONS table.') ); $install_nerrors++; }
