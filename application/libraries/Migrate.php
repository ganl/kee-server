<?php
/**
 * Created by PhpStorm.
 * User: ganl
 * Date: 2017/8/23
 * Time: 16:16
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');


class Migrate
{
    protected $forge_db;
    protected $my_util;
    protected $my_forge;
    private $_migration_table = 'migrations';

    public function __construct()
    {
        $this->config->load('database');

        $this->forge_db = $this->load->database(config_item('db_forge'), true);
        $this->my_util = $this->load->dbutil($this->forge_db, true);

        //If not exist database `i2soft`
        if (!$this->my_util->database_exists(config_item('db_config_prod')['database']))
        {
            $this->my_forge = $this->load->dbforge($this->forge_db, true);
            if(!$this->my_forge->create_database(config_item('db_config_prod')['database'])){ //create database `i2soft`
                log_message('error', 'Database not exist !');
                return false;
            }else{
                log_message('info', 'Create database success !');
            }
        }

        $this->load->dbutil();
        $this->load->dbforge();

        // If the migrations table is missing, make it
        if ( ! $this->db->table_exists($this->_migration_table))
        {
            $this->dbforge->add_field(array(
                'version' => array('type' => 'BIGINT', 'constraint' => 20),
            ));

            $this->dbforge->create_table($this->_migration_table);

            $this->db->insert($this->_migration_table, array('version' => 0));
        }

    }

    public function __get($name)
    {
        return get_instance()->$name;
    }

    public function upgrade(){
        $this->load->library('migration');
        if ($this->migration->current() === true) {
            log_message('error', $this->migration->error_string());
        }
    }
}