<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = 'errors/page_missing';
$route['translate_uri_dashes'] = FALSE;

//$route['^(\w{2})/(.*)$'] = '$2';
//$route['^(\w{2})$'] = $route['default_controller'];

/*
| -------------------------------------------------------------------------
| Additional routes on top of codeigniter-restserver
| -------------------------------------------------------------------------
| Examples from rule: "api/(:any)/(:num)"
|	- [GET]		/api/users/1 ==> Users Controller's id_get($id)
|	- [POST]	/api/users/1 ==> Users Controller's id_post($id)
|	- [PUT]		/api/users/1 ==> Users Controller's id_put($id)
|	- [DELETE]	/api/users/1 ==> Users Controller's id_delete($id)
|
| Examples from rule: "api/(:any)/(:num)/(:any)"
|	- [GET]		/api/users/1/subitem ==> Users Controller's subitem_get($parent_id)
|	- [POST]	/api/users/1/subitem ==> Users Controller's subitem_post($parent_id)
|	- [PUT]		/api/users/1/subitem ==> Users Controller's subitem_put($parent_id)
|	- [DELETE]	/api/users/1/subitem ==> Users Controller's subitem_delete($parent_id)
*/


//$route['api/(:any)/(:num)']				= 'user/$1/id/$2';
//$route['api/user/(:any)/(:num)/(:any)']		= 'user/$1/$3/$2';


/*
$route['api/v1']						= "api_v1";
$route['api/v1/(:any)']					= "api_v1/$1";
$route['api/v1/(:any)/(:num)']			= "api_v1/$1/id/$2";
$route['api/v1/(:any)/(:num)/(:any)']	= "api_v1/$1/$3/$2";
$route['api/v1/(:any)/(:any)']			= "api_v1/$1/$2";
*/
$route['api/example/users/(:num)']                               = 'api/example/users/id/$1'; // Example 4
$route['api/example/users/(:num)(\.)([a-zA-Z0-9_-]+)(.*)']   = 'api/example/users/id/$1/format/$3$4'; // Example 8

//$route['api/user/(:any)/(:num)']                            = 'example/$1/id/$2';
//$route['api/user/(:any)/(:num)/(:any)']                     = 'example/$1/$3/$2';

$route['user/(:any)']                                              = 'user/user/$1';

$route['node/(:any)']                                              = 'node/node/$1';
$route['node/(:any)/(:num)']                                      = 'node/node/$1/$2';
//$route['node/(:any)/(:num)/(:any)']                         = 'node/node/$1/$3/$2';

//$route['api/node']                                          = 'node/index';

$route['dir']                                                        = 'folder/dir';
$route['dir(\/)(:any)']                                            = 'folder/dir/$2';
