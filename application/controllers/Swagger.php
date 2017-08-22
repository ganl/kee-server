<?php
/**
 * Created by PhpStorm.
 * User: ganl
 * Date: 2017/8/22
 * Time: 14:34
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Swagger extends MX_Controller
{
    // Output Swagger JSON
    public function index()
    {
        // folders which include files with Swagger annotations
        $paths = array(
            APPPATH . '/core',
            APPPATH . '/controllers',
            APPPATH . 'modules/'
        );
        $swagger = \Swagger\scan($paths);

        // output JSON
        header('Content-Type: application/json');
        echo $swagger;
    }
}
