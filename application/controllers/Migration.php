<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Ejecucion via terminal
 * /var/www/html/validate php -f index.php migration version 1
 * this->migration->version(2)ejecutará el método up de
 * las migraciones 001 y 002 y el método down de las superiores
 */
class Migration extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if(!is_cli()){
            echo "Operación no permitida";
        }
    }

    public function version($id) {
        $class = 'dbauditoria_'.str_pad($id,3,0,STR_PAD_LEFT);
        echo "Class: ".$class."\n";
        $this->load->library('migrations/'.$class);
        $this->$class->up();
    }
    
    public function downgrade($id) {
        $class = 'dbauditoria_'.str_pad($id,3,0,STR_PAD_LEFT);
        $this->load->library('migrations/'.$class);
        $this->$class->down();
    }
    
    public function proveedores_ficticios() {
        $sheet = $this->csvimport->get_array('/home/kevin/validate/archivos/listas/Proveedores_Ficticio_20062019.csv', FALSE, FALSE, FALSE, ';');
        foreach ($sheet['result'] as $value) {
            $data[] = [
                'lista'          => 'Proveedores Ficticios',
                'nombre'         => $value['NOMBRE O RAZON SOCIAL'],
                'aka'            => '',
                'identificacion' => $value['NIT'],
                'otros'          => serialize([
                    'DIRECCION SECCIONAL'       => $value['DIRECCION SECCIONAL'],
                    'N º RESOLUCIÓN'            => $value['N º RESOLUCIÓN'],
                    'FECHA1'                    => $value['FECHA1'],
                    'PUBLICACIÓN  ART 671 E.T.' => $value['PUBLICACIÓN  ART 671 E.T.'],
                    'FECHA2'                    => $value['FECHA2'],
                ]),
                'created_user'   => 1,
                'created_clie'   => 1,
                'update_user'    => 1,
                'update_clie'    => 1,
            ];
        }
        print_r($data);
        $this->db->truncate('sist__listas');
        $this->db->insert_batch('sist__listas', $data);
    }

}