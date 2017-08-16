<?php
/**
 * Created by PhpStorm.
 * User: ganl
 * Date: 2017/8/15
 * Time: 09:26
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// tables
$config['tables'] = array(
    'tenant'                          => 'tenant',
    'users'                           => 'users',
    'roles'                           => 'roles',
    'role_user'                       => 'role_user',
    'perms'                           => 'permissions',
    'perm_to_role'                    => 'perms_role',
    'groups'                          => 'user_groups',
    'group_to_group'                  => 'group_to_group',
    'user_to_group'                   => 'user_to_group',
    'perm_to_group'                   => 'perm_to_group',
    'login_attempts'                  => 'login_attempts',
);


// auth config
$config['identity']                   = 'username';             // You can use any unique column in your table as identity column. The values in this column, alongside password, will be used for login purposes
$config['track_login_attempts']       = TRUE;                // Track the number of failed login attempts for each user or ip.
$config['track_login_ip_address']     = TRUE;                // Track login attempts by IP Address, if FALSE will track based on identity. (Default: TRUE)
$config['maximum_login_attempts']     = 3;                   // The maximum number of failed login attempts.
$config['lockout_time']               = 600;                 // The number of seconds to lockout an account due to exceeded attempts

$config['message_start_delimiter'] = '#'; 	// Message start delimiter
$config['message_end_delimiter']   = '\n\n'; 	// Message end delimiter
$config['error_start_delimiter']   = '#';		// Error message start delimiter
$config['error_end_delimiter']     = '\n\n';	// Error message end delimiter
