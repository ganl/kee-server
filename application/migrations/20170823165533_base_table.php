<?php
/**
 * Created by PhpStorm.
 * User: ganl
 * Date: 2017/8/23
 * Time: 16:55
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_base_table extends CI_Migration
{

    public function up()
    {
        if (!$this->db->table_exists('tenant_test')) {
            $this->dbforge->add_field(array(
                'id' => array(
                    'type' => 'serial',
                ),
                'tenant_name' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                ),
                'tenant_type' => array(
                    'type' => 'INTEGER',
                    'default' => 0,
                ),
                'description' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => TRUE,
                ),
                'create_time' => array(
                    'type' => 'BIGINT',
                ),
                'enabled' => array(
                    'type' => 'boolean',
                    'default' => TRUE
                ),
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('tenant_test');
        }
    }

    public function down()
    {
        if ($this->db->table_exists('tenant_test')) {
            $this->dbforge->drop_table('tenant_test');
        }
    }
}