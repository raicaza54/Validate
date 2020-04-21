<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dbauditoria_015{
    private $CI;
    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->dbforge();
    }

    public function up() {
        $this->CI->dbforge->drop_table('clie__condicion_cuenta', TRUE);
        $this->CI->dbforge->add_field([
            'id'            => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'fk_users'   => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'identificador de usuario',
                'null'       => FALSE
            ],
            'cuenta'   => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'comment'    => 'codigo de cuenta',
                'null'       => FALSE
            ],
            'porcentaje'   => [
                'type'       => 'DECIMAL',
                'constraint' => '11,3',
                'comment'    => 'valor decimal de porcentaje entre valor y la base',
                'null'       => FALSE
            ],
            'fk_empresas'   => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'identificador de la empresa',
                'null'       => FALSE
            ],
            'tolerancia'   => [
                'type'       => 'DECIMAL',
                'constraint' => '11,3',
                'comment'    => 'valor decimal de tolertancia positiva o negativa',
                'null'       => FALSE
            ],
            'observacion'   => [
                'type'       => 'VARCHAR',
                'constraint' => '1024',
                'comment'    => 'observaciones',
                'null'       => TRUE
            ],
            'created_user'  => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'id usuario creador'
            ],
            'created_clie'  => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'id cliente creador'
            ],
            'update_user'   => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'ultimo id usuario actualizo'
            ],
            'update_clie'   => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'ultimo id cliente actualizo'
            ],
        ]);
        $this->CI->dbforge->add_field("`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->CI->dbforge->add_field("`update_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->add_key('fk_users');
        $this->CI->dbforge->add_key('fk_empresas');
        $this->CI->dbforge->add_key('fk_cuenta');
        $this->CI->dbforge->create_table('clie__condicion_cuenta');
    }
    
    public function down() {
        $this->CI->dbforge->drop_table('clie__condicion_cuenta', TRUE);
    }    
}