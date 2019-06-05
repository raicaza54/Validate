<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dbauditoria_011{
    private $CI;
    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->dbforge();
    }

    public function up() {
        $this->CI->dbforge->drop_table('clie__analisis', TRUE);
        $this->CI->dbforge->add_field([
            'id'            => [
                'type'       => 'BIGINT',
                'constraint' => '20',
                'unsigned'   => TRUE,
            ],
            'fk_empresas'   => [
                'type'       => 'INT',
                'constraint' => '11',
                'unsigned'   => TRUE,
            ],
            'ejecucion'     => [
                'type'       => 'BIGINT',
                'constraint' => '20',
                'unsigned'   => TRUE,
                'null'       => TRUE
            ],
            'analisis'      => [
                'type'    => 'TEXT',
                'comment' => 'Cadena serializada con el retorno del analisis',
                'null'    => FALSE
            ],
            'analisis_tipo' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'comment'    => 'tipo de analisis: benford, spider, manipulacion',
                'null'       => FALSE
            ],
            'pdf'           => [
                'type'       => 'VARCHAR',
                'constraint' => '2',
                'comment'    => '0: pdf no generado, 1: pdf generado',
                'default'    => 0,
                'null'       => FALSE
            ],
            'update_pdf'    => [
                'type'    => 'datetime',
                'comment' => 'Fecha y hora de generacion de pdf',
                'null'    => TRUE
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
        $this->CI->dbforge->add_key('fk_empresas');
        $this->CI->dbforge->create_table('clie__analisis');
    }
    
    public function down() {
        $this->CI->dbforge->drop_table('clie__analisis', TRUE);
    }    
}