<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dbauditoria_013{
    private $CI;
    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->dbforge();
    }

    public function up() {
        $this->CI->dbforge->drop_table('clie__consecutivos', TRUE);
        $this->CI->dbforge->add_field([
            'id'            => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'fk_clientes'   => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'identificador de clientes',
                'null'       => FALSE
            ],            
            'fk_analisis'   => [
                'type'       => 'BIGINT',
                'constraint' => '20',
                'comment'    => 'identificador de analisis',
                'null'       => FALSE
            ],            
            'correlativo'   => [
                'type'       => 'BIGINT',
                'constraint' => '20',
                'comment'    => 'numero correlativo por cliente',
                'null'       => TRUE
            ],
            'consecutivo'   => [
                'type'       => 'VARCHAR',
                'constraint' => '512',
                'comment'    => 'numero de consecutivo para pdf',
                'null'       => TRUE
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
        $this->CI->dbforge->add_key('correlativo');
        $this->CI->dbforge->add_key('consecutivo');
        $this->CI->dbforge->add_key('fk_clientes');
        $this->CI->dbforge->add_key('fk_analisis');
        $this->CI->dbforge->create_table('clie__consecutivos');
    }
    
    public function down() {
        $this->CI->dbforge->drop_table('clie__consecutivos', TRUE);
    }    
}