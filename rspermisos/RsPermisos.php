<?php
require ("conexion.php");

class Permisos
{
    public static function getRolesGrupoPorRolID($key)
    {
        return Mssql::fetch_assoc(
                mssql_query("SELECT id, id_modulo, id_rol FROM Roles_Modulos WHERE id_rol='$key'")
            );
    }

    public static function getPermisosPorUsuarioID($key)
    {
        return Mssql::fetch_assoc(
                mssql_query("SELECT id, id_usuario, id_modulo FROM Permisos WHERE id_usuario='$key'")
            );
    }

    public static function verficarPermisoPorSessionUsuario($ruta)
    {
        $id_usuario = $_SESSION['login'];

        return Mssql::fetch_assoc(
                mssql_query("SELECT p.id, id_usuario, id_modulo, m.ruta
                FROM Permisos p
                INNER JOIN Modulos m ON m.id = p.id_modulo
                WHERE id_usuario='$id_usuario' AND m.ruta='$ruta'")
            );
    }

    public static function verficarArrayPermisoPorSessionUsuario($rutas = array())
    {
        $text_rutas = "()";
        $id_usuario = $_SESSION['login'];

        if (count($rutas) > 0) {
            $aux = "";
            //se contruye un string para listar los depositvos seleccionados
            //en caso que no haya ninguno, sera vacio
            foreach ($rutas as $ruta)
                $aux .= "'$ruta',";

            //armamos una lista de los depositos, si no existe ninguno seleccionado no se considera para realizar la consulta
            $text_rutas = "(" . substr($aux, 0, strlen($aux)-1) . ")";
        }

        return Mssql::fetch_assoc(
                mssql_query("SELECT p.id, id_usuario, id_modulo, m.ruta
                FROM Permisos p
                INNER JOIN Modulos m ON m.id = p.id_modulo
                WHERE id_usuario='$id_usuario' AND m.ruta IN $text_rutas")
            );
    }

    public static function registrar_permiso($data)
    {
        $id_usuario = $data['id'];
        $id_modulo = $data['modulo_id'];
        return mssql_query("INSERT INTO Permisos (id_usuario, id_modulo) VALUES ('$id_usuario','$id_modulo')");
    }

    public static function registrar_rolmod($data)
    {
        $id_modulo = $data['modulo_id'];
        $id_rol = $data['id'];
        return mssql_query("INSERT INTO Roles_Modulos (id_modulo, id_rol) VALUES('$id_modulo','$id_rol')");
    }

    public static function borrar_permiso($data)
    {
        $id_usuario = $data['id'];
        $id_modulo = $data['modulo_id'];
        return mssql_query("DELETE FROM Permisos WHERE id_usuario='$id_usuario' AND id_modulo='$id_modulo'");
    }

    public static function borrar_permiso_user($user_id)
    {
        return mssql_query("DELETE FROM Permisos WHERE id_usuario='$user_id'");
    }

    public static function borrar_rolmod($data)
    {
        $id_modulo = $data['modulo_id'];
        $id_rol = $data['id'];
        return mssql_query("DELETE FROM Roles_Modulos WHERE id_modulo='$id_modulo' AND id_rol='$id_rol'");
    }
}