<?php
require ("conexion.php");
require_once("permisos/Usuarios.php");
require_once("permisos/Permisos.php");
require_once ("permisos/Mssql.php");

class PermisosHelpers
{
    public static function verficarAcceso($ruta, $except = array()) {
        #si la ruta esta en la excepcion manda true o si retorna al menos un registro manda true sino false
        return !(!in_array($ruta, $except)) || count(Permisos::verficarPermisoPorSessionUsuario($ruta)) > 0 || ($_SESSION['dashboard']==$ruta);
    }

    public static function registrarPermisoPorRol($data) {
        $permiso = false;

        $usuarios = Usuarios::byRol($data['id'], true);
        if (is_array($usuarios)==true and count($usuarios)>0) {
            foreach ($usuarios as $usuario) {
                $data1 = array(
                    'id' => $usuario['codusua'],
                    'modulo_id' => $data['modulo_id'],
                );
                $permiso = Permisos::registrar_permiso($data1);
                if (!$permiso) break;
            }
        }

        return $permiso;
    }

    public static function registrarPermisoUsuarioPorRol($data) {
        $permiso = false;

        $permisos_rolmod = Permisos::getRolesGrupoPorRolID($data['rol_id']);
        if (is_array($permisos_rolmod) == true and count($permisos_rolmod) > 0) {
            foreach ($permisos_rolmod as $permiso) {
                $data1 = array(
                    'id' => $data['user_id'],
                    'modulo_id' => $permiso['id_modulo'],
                );
                $permiso = Permisos::registrar_permiso($data1);
                if (!$permiso) break;
            }
        }

        return $permiso;
    }

    public static function borrarPermisoPorRol($data) {
        $permiso = false;

        $usuarios = Usuarios::byRol($data['id'], true);
        if (is_array($usuarios)==true and count($usuarios)>0) {
            foreach ($usuarios as $usuario) {
                $data1 = array(
                    'id' => $usuario['codusua'],
                    'modulo_id' => $data['modulo_id'],
                );
                $permiso = Permisos::borrar_permiso($data1);
                if (!$permiso) break;
            }
        }

        return $permiso;
    }

}