<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_dbauditoria_listas extends CI_Migration {

    public function __construct() {
        parent::__construct();
        $this->load->dbforge();
    }

    public function up() {
        $this->dbforge->drop_table('clie__carpetas', TRUE);
        $this->dbforge->add_field([
            'id'             => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
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
                'type'       => 'INT',
                'constraint' => '11',
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
            'created_clier'  => [
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
        $this->dbforge->add_field("`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->dbforge->add_field("`update_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('clie__carpetas');

        $this->dbforge->drop_table('clie__archivos', TRUE);
        $this->dbforge->add_field([
            'id'            => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
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
            'columnas'      => [
                'type'       => 'VARCHAR',
                'constraint' => '512',
                'comment'    => 'Se definen los tipos de datos en las columnas',
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
            'created_clier' => [
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
        $this->dbforge->add_field("`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->dbforge->add_field("`update_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('clie__archivos');

        $this->dbforge->drop_table('clie__archivos_detalle', TRUE);
        $this->dbforge->add_field([
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
            'created_clier' => [
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
        $this->dbforge->add_field("`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->dbforge->add_field("`update_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('clie__archivos_detalle');
    }

    public function down() {
        $this->dbforge->drop_table('clie__carpetas', TRUE);
        $this->dbforge->drop_table('clie__archivos', TRUE);
        $this->dbforge->drop_table('clie__archivos_detalle', TRUE);
    }

}
