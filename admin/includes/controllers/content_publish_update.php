<?php

$mode = get_mode('mode');
$mode_group = explode('-', get_mode('mode'));
$pagegroup_id = isset($mode_group[1]) ? (int)$mode_group[1] : get_default_pagegroup();
$id = get_request_id();

/** DATA **/
$page_taxonomy_id = get_request_p('taxonomy', get_default_pagetaxnomy());
$page_taxonomy = get_pagetaxonomy($page_taxonomy_id);

$page_user_id = (int)get_request_p('user-id', -1);
$page_user_password_encrypted = get_request_p('user-password-encrypted', '', true);
$page_user_email = get_request_p('user-email', '', true);
$page_user_username = get_request_p('user-username', '', true);
$page_user_password = get_request_p('user-password', '', true);
$page_user_capabilities = get_request_p('user-capabilities', array());

/** UPDATE **/
$metas = array(
    PageMeta::META_TITLE => get_request_p('name', '', true),
    PageMeta::META_PERMALINK => get_request_p('permalink', '', true),
    PageMeta::META_PERMALINK => get_request_p('permalink', '', true),
    PageMeta::META_TEXT => get_request_p('text', '', true),
    PageMeta::META_IMAGE => get_request_p('image', '', true),
    PageMeta::META_TEMPLATE => get_request_p('template', '', true)
);
$args = array(
    'taxonomy' => $page_taxonomy_id,
    'type' => get_request_p('type', get_default_pagetype()),
    'group' => $pagegroup_id,
    'parent' => get_request_p('parent', null, true),
    'metas' => $metas
);
$args['parent'] = $args['parent']==='NULL' ? null : $args['parent'];

$buffer_page = Page::GetPage($id, null, false);
if (Page::Update($id, $args))
{
    register_alert('Page updated', 'success');
    
    /** USER RELATION **/
    if ($page_taxonomy->UserRelation())
    {
        if (!empty($page_user_username) && !empty($page_user_password))
        {
            if ($page_user_id !== -1)
            {
                if(!User::UsernameExists($page_user_username, $page_user_id))
                {
                    $update_args = array(
                        'email' => $page_user_email,
                        'username' => $page_user_username,
                        'capabilities' => $page_user_capabilities,
                        'page' => $id
                    );
                    if ($page_user_password !== $page_user_password_encrypted)
                    {
                        $update_args['password'] = $page_user_password;
                    }
                    if (!User::Update($page_user_id, $update_args))
                    {
                        register_alert('Failed to update user', 'error');
                    }
                }
                else
                {
                    register_alert('Failed to update user: Username exists.', 'error');
                }
            }
            else if(!User::UsernameExists($page_user_username))
            {
                $insert_args = array(
                    'email' => $page_user_email,
                    'username' => $page_user_username,
                    'password' => $page_user_password,
                    'role' => $page_taxonomy->UserRole(),
                    'capabilities' => $page_user_capabilities,
                    'page' => $id
                );
                $user_id = User::Insert($insert_args);
                if (!$user_id)
                {
                    register_alert('Failed to insert user', 'error');
                }
            }
            else
            {
                register_alert('Failed to insert user: Username exists.', 'error');
            }
        }
    }
    
    do_action('content_publish_update', $id, $pagegroup_id, $buffer_page);
    do_action('content_publish_update_' . $pagegroup_id, $id, $buffer_page);
}
else
{
    register_alert('Failed to update page', 'error');
}

/** DUPLICATTION **/
$duplicate = get_request_gp('duplicate', array());
if ($id && is_array($duplicate) && !empty($duplicate))
{
    $fromLang = get_lang();
    foreach ($duplicate AS $toLang)
    {
        $lang = get_language_info($toLang);
        if (is_active_language($toLang) && Page::Duplicate($id, $fromLang, $toLang))
        {
            register_alert('Page duplicated to ' . $lang['name'], 'success');
        }
        else
        {
            register_alert('Failed to duplicate page to ' . $lang['name'], 'error');
        }
    }
}

locate(get_admin_action_link(array('action'=>'list')));
