<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dbauditoria_004{
    private $CI;
    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->dbforge();
    }

    public function up() {
        $this->CI->dbforge->drop_table('clie__carpetas', TRUE);
        $this->CI->dbforge->add_field([
            'id'             => [
                'type'           => 'BIGINT',
                'constraint'     => '20',
                'unsigned'       => TRUE,
            ],
            'fk_empresas'    => [
                'type'       => 'INT',
                'constraint' => '11',
                'unsigned'   => TRUE,
            ],
            'order'          => [
                'type'       => 'INT',
                'constraint' => '3',
                'comment'    => 'ordenar',
                'null'       => TRUE
            ],
            'parent_id'      => [
                'type'       => 'BIGINT',
                'constraint' => '20',
                'comment'    => 'registro padre',
                'null'       => TRUE
            ],
            'label'          => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
                'comment'    => 'nombre del nodo',
                'null'       => TRUE
            ],
            'have_childrens' => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'registro hijo',
                'default'    => 0
            ],
            'deleted_at'   => [
                'type'       => 'INT',
                'constraint' => '1',
                'comment'    => 'borrado logico',
                'default'    => 0
            ],
            'observacion'    => [
                'type'       => 'VARCHAR',
                'constraint' => '1024',
                'comment'    => 'observaciones',
                'null'       => TRUE
            ],
            'created_user'   => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'id usuario creador'
            ],
            'created_clie'  => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'id cliente creador'
            ],
            'update_user'    => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'ultimo id usuario actualizo'
            ],
            'update_clie'    => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'ultimo id cliente actualizo'
            ],
        ]);
        $this->CI->dbforge->add_field("`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->CI->dbforge->add_field("`update_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table('clie__carpetas');

        $this->CI->dbforge->drop_table('clie__archivos', TRUE);
        $this->CI->dbforge->add_field([
            'id'            => [
                'type'           => 'BIGINT',
                'constraint'     => '20',
                'unsigned'       => TRUE,
            ],
            'fk_carpetas'   => [
                'type'       => 'INT',
                'constraint' => '11',
                'unsigned'   => TRUE,
            ],
            'nombre'        => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
                'comment'    => 'nombre de archivo',
                'null'       => TRUE
            ],
            'ext'          => [
                'type'       => 'VARCHAR',
                'constraint' => '25',
                'comment'    => 'extension del archivo',
                'null'       => TRUE,
                'default'    => 'xlsx'
            ],
            'tipo'          => [
                'type'       => 'VARCHAR',
                'constraint' => '25',
                'comment'    => 'tipo de archivo, mov: movimiento, blp: balance de prueba, cxc: cuentas por cobrar, cxp: cuentas por pagar',
                'null'       => TRUE,
                'default'    => 'xlsx'
            ],
            'file_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'comment'    => 'nombre de archivo en directorio config[path_file]',
                'null'       => TRUE,
            ],
            'columnas'      => [
                'type'       => 'VARCHAR',
                'constraint' => '512',
                'comment'    => 'Se definen los tipos de datos en las columnas',
                'null'       => TRUE,
                'default'    => '{"campo1":["Campo","string"],"campo2":["Campo","string"],"campo3":["Campo","string"],"campo4":["Campo","string"],"campo5":["Campo","string"],"campo6":["Campo","string"],"campo7":["Campo","string"],"campo8":["Campo","string"],"campo9":["Campo","string"],"campo10":["Campo","string"],"campo11":["Campo","string"],"campo12":["Campo","string"],"campo13":["Campo","string"],"campo14":["Campo","string"],"campo15":["Campo","string"],"campo16":["Campo","string"],"campo17":["Campo","string"],"campo18":["Campo","string"],"campo19":["Campo","string"],"campo20":["Campo","string"]}'
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
            'created_clie' => [
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
        $this->CI->dbforge->create_table('clie__archivos');

        $this->CI->dbforge->drop_table('clie__archivos_detalle', TRUE);
        $this->CI->dbforge->add_field([
            'id'            => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'fk_archivos'   => [
                'type'       => 'INT',
                'constraint' => '11',
                'unsigned'   => TRUE,
            ],
            'linea'   => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'comment'    => 'tipo de linea ? f:fila:e:encabezado',
                'null'       => TRUE
            ],            
            'campo1'        => ['type' => 'VARCHAR', 'constraint' => '100', 'comment' => 'campo', 'null' => TRUE],
            'campo2'        => ['type' => 'VARCHAR', 'constraint' => '100', 'comment' => 'campo', 'null' => TRUE],
            'campo3'        => ['type' => 'VARCHAR', 'constraint' => '100', 'comment' => 'campo', 'null' => TRUE],
            'campo4'        => ['type' => 'VARCHAR', 'constraint' => '100', 'comment' => 'campo', 'null' => TRUE],
            'campo5'        => ['type' => 'VARCHAR', 'constraint' => '100', 'comment' => 'campo', 'null' => TRUE],
            'campo6'        => ['type' => 'VARCHAR', 'constraint' => '100', 'comment' => 'campo', 'null' => TRUE],
            'campo7'        => ['type' => 'VARCHAR', 'constraint' => '100', 'comment' => 'campo', 'null' => TRUE],
            'campo8'        => ['type' => 'VARCHAR', 'constraint' => '100', 'comment' => 'campo', 'null' => TRUE],
            'campo9'        => ['type' => 'VARCHAR', 'constraint' => '100', 'comment' => 'campo', 'null' => TRUE],
            'campo10'       => ['type' => 'VARCHAR', 'constraint' => '100', 'comment' => 'campo', 'null' => TRUE],
            'campo11'       => ['type' => 'VARCHAR', 'constraint' => '100', 'comment' => 'campo', 'null' => TRUE],
            'campo12'       => ['type' => 'VARCHAR', 'constraint' => '100', 'comment' => 'campo', 'null' => TRUE],
            'campo13'       => ['type' => 'VARCHAR', 'constraint' => '100', 'comment' => 'campo', 'null' => TRUE],
            'campo14'       => ['type' => 'VARCHAR', 'constraint' => '100', 'comment' => 'campo', 'null' => TRUE],
            'campo15'       => ['type' => 'VARCHAR', 'constraint' => '100', 'comment' => 'campo', 'null' => TRUE],
            'campo16'       => ['type' => 'VARCHAR', 'constraint' => '100', 'comment' => 'campo', 'null' => TRUE],
            'campo17'       => ['type' => 'VARCHAR', 'constraint' => '100', 'comment' => 'campo', 'null' => TRUE],
            'campo18'       => ['type' => 'VARCHAR', 'constraint' => '100', 'comment' => 'campo', 'null' => TRUE],
            'campo19'       => ['type' => 'VARCHAR', 'constraint' => '100', 'comment' => 'campo', 'null' => TRUE],
            'campo20'       => ['type' => 'VARCHAR', 'constraint' => '100', 'comment' => 'campo', 'null' => TRUE],
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
            'created_clie' => [
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
        $this->CI->dbforge->create_table('clie__archivos_detalle');
    }

    public function down() {
        $this->CI->dbforge->drop_table('clie__carpetas', TRUE);
        $this->CI->dbforge->drop_table('clie__archivos', TRUE);
        $this->CI->dbforge->drop_table('clie__archivos_detalle', TRUE);
    }

}
