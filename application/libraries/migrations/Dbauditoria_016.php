<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dbauditoria_016{
    private $CI;
    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->dbforge();
    }

    public function up() {
        $this->CI->dbforge->drop_table('clie__empresas_config', TRUE);
        $this->CI->dbforge->add_field([
            'id'           => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'fk_empresas'  => [
                'type'       => 'INT',
                'constraint' => '11',
                'unsigned'   => TRUE,
                'null'       => FALSE
            ],
            'columnas_movnat'     => [
                'type'       => 'VARCHAR',
                'constraint' => '2048',
                'comment'    => 'definicion de columnas por defecto, los datos son serializados, movimientos por naturaleza y valor',
                'null'       => TRUE
            ],
            'columnas_movdhb'     => [
                'type'       => 'VARCHAR',
                'constraint' => '2048',
                'comment'    => 'definicion de columnas por defecto, los datos son serializados, movimientos por debitos y creditos',
                'null'       => TRUE
            ],
            'columnas_blp'     => [
                'type'       => 'VARCHAR',
                'constraint' => '2048',
                'comment'    => 'definicion de columnas por defecto, los datos son serializados, balances de prueba',
                'null'       => TRUE
            ],
            'columnas_cxc'     => [
                'type'       => 'VARCHAR',
                'constraint' => '2048',
                'comment'    => 'definicion de columnas por defecto, los datos son serializados, cuentas por cobrar',
                'null'       => TRUE
            ],
            'columnas_cxp'     => [
                'type'       => 'VARCHAR',
                'constraint' => '2048',
                'comment'    => 'definicion de columnas por defecto, los datos son serializados, cuentas por pagar',
                'null'       => TRUE
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
        $this->CI->dbforge->add_key('fk_empresas');
        $this->CI->dbforge->create_table('clie__empresas_config');
    }
    
    public function down() {
        $this->CI->dbforge->drop_table('clie__empresas_config', TRUE);
    }    
}