<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Definicion de Permisos
 * 
 * @Copyright   GEO INFORMATIC SOLUTIONS SAS
 * @Author      Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @Description Se definen los permisos de sistema
 * @LastUpdate  2019-05-04
 * 
 * Grupos definidos
 * 1 = admin
 * 2 = members
 * 3 = educativo
 */
class DBPermisos {
    
    private $CI;
    
    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->dbforge();
    }
    
    /**
     * Definicion de permisos de usuario, cada identificador tiene 100 posiciones
     * si por alguna razon se supera este valor se debe añadir un digito al final
     * por ejemplo 199 -> 1000, 1001, 1002
     * $/var/www/html/validate$ php -f index.php tareas/permisos
     * $/var/www/html/validate$ php -f index.php tareas/permisosAdmin
     * $/var/www/html/validate$ php -f index.php tareas/permisosEducativo
     */
    public function definir() {
        $this->CI->db->truncate('sist__permisos');
        $userDate = [
            'created_user' => '1',
            'created_clie' => '1',
            'update_user'  => '1',
            'update_clie'  => '1',
        ];
        $data = [
            #[roo - 100] - Root
            ['id' => 100, 'permiso' => 'root-all', 'observacion' => 'Usuario principal todos los permisos'] + $userDate,
            #[mpe - 200] - Menu Principal
            ['id' => 200, 'permiso' => 'mpe-empresa-listar'] + $userDate,
            ['id' => 201, 'permiso' => 'mpe-empresa-crear'] + $userDate,
            ['id' => 202, 'permiso' => 'mpe-empresa-propiedades'] + $userDate,
            ['id' => 203, 'permiso' => 'mpe-importar-archivo'] + $userDate,
            ['id' => 204, 'permiso' => 'mpe-importar-conexion'] + $userDate,
            ['id' => 205, 'permiso' => 'mpe-exportar-csv'] + $userDate,
            ['id' => 206, 'permiso' => 'mpe-exportar-excel'] + $userDate,
            #[mda - 300] - Menu Datos
            ['id' => 300, 'permiso' => 'mda-datos-duplicar'] + $userDate,
            ['id' => 301, 'permiso' => 'mda-orden-indice'] + $userDate,
            ['id' => 302, 'permiso' => 'mda-orden-columna'] + $userDate,
            ['id' => 303, 'permiso' => 'mda-busqueda-filtrar'] + $userDate,
            ['id' => 304, 'permiso' => 'mda-busqueda-siguiente'] + $userDate,
            ['id' => 305, 'permiso' => 'mda-busqueda-ir'] + $userDate,
            #[mal - 400] - Menu Analizar
            ['id' => 400, 'permiso' => 'mal-ejecutar-recargar'] + $userDate,
            ['id' => 401, 'permiso' => 'mal-analisis-benford'] + $userDate,
            ['id' => 402, 'permiso' => 'mal-analisis-spider'] + $userDate,
            ['id' => 403, 'permiso' => 'mal-analisis-manipulacion'] + $userDate,
            ['id' => 404, 'permiso' => 'mal-analisis-materialidad'] + $userDate,
            ['id' => 405, 'permiso' => 'mal-analisis-listas'] + $userDate,
            ['id' => 406, 'permiso' => 'mal-documentos-dictamen'] + $userDate,
            ['id' => 407, 'permiso' => 'mal-documentos-papeles'] + $userDate,
            ['id' => 408, 'permiso' => 'mal-documentos-marcas'] + $userDate,
            ['id' => 409, 'permiso' => 'mal-documentos-cxpc'] + $userDate,
            #[mad - 500] - Menu Administrar
            #[exp - 600] - Explorador Archivos/Resultados
            ['id' => 600, 'permiso' => 'exp-archivos-actualizar'] + $userDate,
            ['id' => 601, 'permiso' => 'exp-archivos-crear'] + $userDate,
            ['id' => 602, 'permiso' => 'exp-archivos-editar'] + $userDate,
            ['id' => 603, 'permiso' => 'exp-archivos-borrar'] + $userDate,
            #[mac - 700] - Modulo Archivos
            ['id' => 700, 'permiso' => 'mac-archivo-configurar'] + $userDate,
        ];
        $this->CI->db->insert_batch('sist__permisos', $data);
    }
    
    public function resetear() {
        $this->CI->db->truncate('sist__permisos_groups');
        $this->admin();
        $this->educativo();
    }
    
    public function admin($truncate = 0) {
        if($truncate == 1)
        $this->CI->db->truncate('sist__permisos_groups');
        $userDate = [
            'created_user' => '1',
            'created_clie' => '1',
            'update_user'  => '1',
            'update_clie'  => '1',
        ];
        $data = [
            ['fk_permisos' => 100, 'fk_groups' => 1, 'observacion' => 'Root usuario principal con todos los permisos'] + $userDate,
        ];
        $this->CI->db->insert_batch('sist__permisos_groups', $data);
    }
    
    public function educativo($truncate = 0) {
        if($truncate == 1)
        $this->CI->db->truncate('sist__permisos_groups');
        $userDate = [
            'created_user' => '1',
            'created_clie' => '1',
            'update_user'  => '1',
            'update_clie'  => '1',
        ];
        $data = [
            ['fk_permisos' => 200, 'fk_groups' => 3] + $userDate,
            ['fk_permisos' => 401, 'fk_groups' => 3] + $userDate,
            ['fk_permisos' => 402, 'fk_groups' => 3] + $userDate,
            ['fk_permisos' => 403, 'fk_groups' => 3] + $userDate,
            ['fk_permisos' => 600, 'fk_groups' => 3] + $userDate,
            ['fk_permisos' => 700, 'fk_groups' => 3] + $userDate,
        ];
        $this->CI->db->insert_batch('sist__permisos_groups', $data);
    }
    
}
