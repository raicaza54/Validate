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
     * $/var/www/html/validate$ php -f index.php tareas/permisosMembers
     * $/var/www/html/validate$ php -f index.php tareas/permisosResetear
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
            ['id' => 200, 'permiso' => 'mpe-empresa-listar', 'observacion' => ''] + $userDate,
            ['id' => 201, 'permiso' => 'mpe-empresa-crear', 'observacion' => ''] + $userDate,
            ['id' => 202, 'permiso' => 'mpe-empresa-propiedades', 'observacion' => ''] + $userDate,
            ['id' => 203, 'permiso' => 'mpe-importar-archivo', 'observacion' => ''] + $userDate,
            ['id' => 204, 'permiso' => 'mpe-importar-conexion', 'observacion' => ''] + $userDate,
            ['id' => 205, 'permiso' => 'mpe-exportar-csv', 'observacion' => ''] + $userDate,
            ['id' => 206, 'permiso' => 'mpe-exportar-excel', 'observacion' => ''] + $userDate,
            #[mda - 300] - Menu Datos
            ['id' => 300, 'permiso' => 'mda-datos-duplicar', 'observacion' => ''] + $userDate,
            ['id' => 301, 'permiso' => 'mda-orden-indice', 'observacion' => ''] + $userDate,
            ['id' => 302, 'permiso' => 'mda-orden-columna', 'observacion' => ''] + $userDate,
            ['id' => 303, 'permiso' => 'mda-busqueda-filtrar', 'observacion' => ''] + $userDate,
            ['id' => 304, 'permiso' => 'mda-busqueda-siguiente', 'observacion' => ''] + $userDate,
            ['id' => 305, 'permiso' => 'mda-busqueda-ir', 'observacion' => ''] + $userDate,
            #[mal - 400] - Menu Analizar
            ['id' => 400, 'permiso' => 'mal-ejecutar-recargar', 'observacion' => ''] + $userDate,
            ['id' => 401, 'permiso' => 'mal-analisis-benford', 'observacion' => ''] + $userDate,
            ['id' => 402, 'permiso' => 'mal-analisis-spider', 'observacion' => ''] + $userDate,
            ['id' => 403, 'permiso' => 'mal-analisis-manipulacion', 'observacion' => ''] + $userDate,
            ['id' => 404, 'permiso' => 'mal-analisis-materialidad', 'observacion' => ''] + $userDate,
            ['id' => 405, 'permiso' => 'mal-analisis-listas', 'observacion' => ''] + $userDate,
            ['id' => 406, 'permiso' => 'mal-documentos-dictamen', 'observacion' => ''] + $userDate,
            ['id' => 407, 'permiso' => 'mal-documentos-papeles', 'observacion' => ''] + $userDate,
            ['id' => 408, 'permiso' => 'mal-documentos-marcas', 'observacion' => ''] + $userDate,
            ['id' => 409, 'permiso' => 'mal-documentos-cxpc', 'observacion' => ''] + $userDate,
            #[mad - 500] - Menu Administrar
            #[exp - 600] - Explorador Archivos
            ['id' => 600, 'permiso' => 'exp-archivos-actualizar', 'observacion' => ''] + $userDate,
            ['id' => 601, 'permiso' => 'exp-archivos-crear', 'observacion' => ''] + $userDate,
            ['id' => 602, 'permiso' => 'exp-archivos-editar', 'observacion' => ''] + $userDate,
            ['id' => 603, 'permiso' => 'exp-archivos-borrar', 'observacion' => ''] + $userDate,
            #[mac - 700] - Modulo Archivos
            ['id' => 700, 'permiso' => 'mac-archivo-configurar', 'observacion' => ''] + $userDate,
            #[exp - 800] - Explorador Resultados
            ['id' => 800, 'permiso' => 'exp-resultados-actualizar', 'observacion' => ''] + $userDate,
            ['id' => 801, 'permiso' => 'exp-resultados-crear', 'observacion' => ''] + $userDate,
            ['id' => 802, 'permiso' => 'exp-resultados-editar', 'observacion' => ''] + $userDate,
            ['id' => 803, 'permiso' => 'exp-resultados-borrar', 'observacion' => ''] + $userDate,            
        ];
        $this->CI->db->insert_batch('sist__permisos', $data);
    }
    
    public function resetear() {
        $this->CI->db->truncate('sist__permisos_groups');
        $this->definir();
        $this->admin();
        $this->educativo();
        $this->members();
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
    
    public function members($truncate = 0) {
        if($truncate == 1)
        $this->CI->db->truncate('sist__permisos_groups');
        $userDate = [
            'created_user' => '1',
            'created_clie' => '1',
            'update_user'  => '1',
            'update_clie'  => '1',
        ];
        $data = [
            ['fk_permisos' => 200, 'fk_groups' => 2] + $userDate,
            ['fk_permisos' => 201, 'fk_groups' => 2] + $userDate,
            ['fk_permisos' => 202, 'fk_groups' => 2] + $userDate,
            ['fk_permisos' => 203, 'fk_groups' => 2] + $userDate,
            ['fk_permisos' => 401, 'fk_groups' => 2] + $userDate,
            ['fk_permisos' => 402, 'fk_groups' => 2] + $userDate,
            ['fk_permisos' => 403, 'fk_groups' => 2] + $userDate,
            ['fk_permisos' => 600, 'fk_groups' => 2] + $userDate,
            ['fk_permisos' => 601, 'fk_groups' => 2] + $userDate,
            ['fk_permisos' => 602, 'fk_groups' => 2] + $userDate,
            ['fk_permisos' => 603, 'fk_groups' => 2] + $userDate,
            ['fk_permisos' => 700, 'fk_groups' => 2] + $userDate,
            ['fk_permisos' => 800, 'fk_groups' => 2] + $userDate,
            ['fk_permisos' => 801, 'fk_groups' => 2] + $userDate,
            ['fk_permisos' => 802, 'fk_groups' => 2] + $userDate,
            ['fk_permisos' => 803, 'fk_groups' => 2] + $userDate,            
        ];
        $this->CI->db->insert_batch('sist__permisos_groups', $data);
    }
    
}
