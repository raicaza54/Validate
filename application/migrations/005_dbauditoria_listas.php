<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_dbauditoria_listas extends CI_Migration {

    public function __construct() {
        parent::__construct();
        $this->load->dbforge();
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
        $this->dbforge->add_column('clie__carpetas', $fields);
    }

    public function down() {
        $this->dbforge->drop_column('clie__carpetas', [
            'type','opened','disabled','selected'
        ]);
    }

}
