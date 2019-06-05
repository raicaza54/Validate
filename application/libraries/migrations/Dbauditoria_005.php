<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dbauditoria_005{
    private $CI;
    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->dbforge();
    }

    public function up() {
        $fields = [
            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'comment'    => 'tipo de icono en el arbol',
                'null'       => TRUE
            ],
            'opened' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
                'comment'    => 'Si el directorio se muestra desplegado',
                'null'       => TRUE,
                'default'    => 0
            ],
            'archivos_id' => [
                'type'       => 'BIGINT',
                'constraint' => '20',
                'comment'    => 'clave foranea de archivo si es necesario',
                'null'       => TRUE,
            ],
            'disabled' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
                'comment'    => 'Si esta habilitado/deshabilitado el item',
                'null'       => TRUE,
                'default'    => 0
            ],
            'selected' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
                'comment'    => 'Si esta seleccionado por defecto',
                'null'       => TRUE,
                'default'    => 0
            ],
        ];
        $this->CI->dbforge->add_column('clie__carpetas', $fields);
    }

    public function down() {
        $this->CI->dbforge->drop_column('clie__carpetas', 'type');
        $this->CI->dbforge->drop_column('clie__carpetas', 'opened');
        $this->CI->dbforge->drop_column('clie__carpetas', 'disabled');
        $this->CI->dbforge->drop_column('clie__carpetas', 'selected');
    }

}