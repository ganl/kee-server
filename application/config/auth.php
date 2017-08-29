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

/*
 | Users table column and Roles table column you want to join WITH.
 |
 | Joins from users.id
 | Joins from roles.id
 */
$config['join']['tenant']  = 'tenant_id';
$config['join']['users']  = 'user_id';
$config['join']['roles']  = 'role_id';
$config['join']['groups'] = 'group_id';

// auth config
$config['identity']                   = 'username';         // You can use any unique column in your table as identity column. The values in this column, alongside password, will be used for login purposes
$config['track_login_attempts']       = TRUE;               // Track the number of failed login attempts for each user or ip.
$config['track_login_ip_address']     = TRUE;               // Track login attempts by IP Address, if FALSE will track based on identity. (Default: TRUE)
$config['maximum_login_attempts']     = 3;                  // The maximum number of failed login attempts.
$config['lockout_time']               = 600;                // The number of seconds to lockout an account due to exceeded attempts

$config['message_start_delimiter']    = '#';                // Message start delimiter
$config['message_end_delimiter']      = '\n\n';             // Message end delimiter
$config['error_start_delimiter']      = '#';                // Error message start delimiter
$config['error_end_delimiter']        = '\n\n';             // Error message end delimiter

// Hash Method

$config['hash_method']                = 'bcrypt';           /* Name of selected hashing algorithm (e.g. "md5", "sha1", "sha256", "haval160,4", etc..)
                                                               run hash_algos() for know your all supported algorithms
                                                               bcrypt, use PHP's own password_hash() function with BCrypt, needs PHP5.5 or higher (CI Compact password.php)*/

$config['default_rounds']             = 8;                  // This does not apply if random_rounds is set to true
$config['random_rounds']              = FALSE;
$config['min_rounds']                 = 5;
$config['max_rounds']                 = 9;
$config['salt_prefix']                = version_compare(PHP_VERSION, '5.3.7', '<') ? '$2a$' : '$2y$';
$config['salt_length'] = 22;
$config['store_salt']  = FALSE;

$config['use_password_hash']          = FALSE;              // default use to password_hash algorithm (PASSWORD_DEFAULT, PASSWORD_BCRYPT) , will ignore hash_method
$config['password_hash_algo']         = PASSWORD_BCRYPT;    // password_hash algorithm (PASSWORD_DEFAULT, PASSWORD_BCRYPT) , hash_method = bcrypt
$config['password_hash_options']      = array();            // password_hash options array , hash_method = bcrypt

$config['track_login_attempts']       = TRUE;               // Track the history of login attempts for each user or ip.
$config['track_login_ip_address']     = TRUE;               // Track login attempts by IP Address
$config['captcha_login_attempts']     = 5;                  // Login Attempts to display CAPTCHA
$config['maximum_login_attempts']     = 12;                  // The maximum number of failed login attempts.
$config['lockout_time']               = 900;                // The number of seconds to lockout an account due to exceeded attempts
