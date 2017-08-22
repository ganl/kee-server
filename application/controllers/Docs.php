<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Docs extends MX_Controller {

    protected $view_data;

    public function index()
	{
        $this->load->helper('url');
		// API Doc page only accessible during development/testing environments
		if (in_array(ENVIRONMENT, array('development', 'testing')))
		{
            $this->view_data['site_name'] = config_item('site_name');
			$this->load->view('docs', $this->view_data);
		}
		else
		{
			redirect('api/docs');
		}
	}
}
