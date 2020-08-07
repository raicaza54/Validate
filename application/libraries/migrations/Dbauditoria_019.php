<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dbauditoria_019{
    private $CI;
    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->dbforge();
    }

    public function up() {
        $this->CI->dbforge->drop_table('clie__dictamen', TRUE);
        $this->CI->dbforge->add_field([
            'id'           => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'fk_clientes'  => [
                'type'       => 'INT',
                'constraint' => '11',
                'unsigned'   => TRUE,
                'null'       => FALSE
            ],
            'fk_users'  => [
                'type'       => 'INT',
                'constraint' => '11',
                'unsigned'   => TRUE,
                'null'       => FALSE
            ],
            'fk_empresas'   => [
                'type'       => 'INT',
                'constraint' => '11',
                'unsigned'   => TRUE,
            ],
            'estado'   => [
                'type'       => 'TINYINT',
                'constraint' => '3',
                'unsigned'   => TRUE,
                'comment'    => '1:activo 0:descartado',
                'default'    => 1,
            ],            
            'data'   => [
                'type'       => 'TEXT',
                'null'       => TRUE
            ],            
            'fk_users'  => [
                'type'       => 'INT',
                'constraint' => '11',
                'unsigned'   => TRUE,
                'null'       => FALSE
            ],
            'observacion'  => [
                'type'       => 'VARCHAR',
                'constraint' => '1024',
                'comment'    => 'observaciones',
                'null'       => TRUE
            ],
            'created_user' => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'id usuario creador'
            ],
            'created_clie' => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'id cliente creador'
            ],
            'update_user'  => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'ultimo id usuario actualizo'
            ],
            'update_clie'  => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'ultimo id cliente actualizo'
            ],
        ]);
        $this->CI->dbforge->add_field("`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->CI->dbforge->add_field("`update_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table('clie__dictamen');
    }
    
    public function down() {
        $this->CI->dbforge->drop_table('clie__dictamen', TRUE);
   }    
}