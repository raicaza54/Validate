<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dbauditoria_017{
    private $CI;
    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->dbforge();
    }

    public function up() {
        $fields = [
            'path_logotipo' => [
                'type'       => 'VARCHAR',
                'constraint' => '1024',
                'comment'    => 'ruta del logotipo de la empresa',
                'null'       => TRUE,
                'after'      => 'comercial'
            ],
            'usar_logotipo' => [
                'type'       => 'VARCHAR',
                'constraint' => '5',
                'comment'    => 'si el logotipo de la empresa sera utilizado en los pdfs de analisis si/no',
                'null'       => TRUE,
                'default'    => 'no',
                'after'      => 'path_logotipo'
            ],
            'firma' => [
                'type'       => 'VARCHAR',
                'constraint' => '2048',
                'comment'    => 'datos que se utilizaran para la firma',
                'null'       => TRUE,
                'after'      => 'usar_logotipo'
            ],
            'usar_firma' => [
                'type'       => 'VARCHAR',
                'constraint' => '5',
                'comment'    => 'si la firma sera utilizada en los archivos pdfs de analisis si/no',
                'null'       => TRUE,
                'default'    => 'no',
                'after'      => 'firma'
            ],
            'usar_demo' => [
                'type'       => 'VARCHAR',
                'constraint' => '5',
                'comment'    => 'empresa demostracion si/no',
                'null'       => TRUE,
                'default'    => 'no',
                'after'      => 'firma'
            ],
        ];
        $this->CI->dbforge->add_column('clie__clientes', $fields);
    }
    
    public function down() {
        $this->CI->dbforge->drop_column('clie__clientes', 'path_logotipo');
        $this->CI->dbforge->drop_column('clie__clientes', 'usar_logotipo');
        $this->CI->dbforge->drop_column('clie__clientes', 'firma');
        $this->CI->dbforge->drop_column('clie__clientes', 'usar_firma');
        $this->CI->dbforge->drop_column('clie__clientes', 'usar_demo');
   }    
}