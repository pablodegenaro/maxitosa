<?php
set_time_limit(0);
require ("conexion.php");
require_once ("Mssql.php");

class Modulos
{
    public static function todos()
    {
        return Mssql::fetch_assoc(
                mssql_query("SELECT id, nombre, icono, ruta, modulo_orden, menu_id, status FROM Modulos ORDER BY modulo_orden ASC")
            );
    }

    public static function todosActivos()
    {
        return Mssql::fetch_assoc(
                mssql_query("SELECT id, nombre, icono, ruta, modulo_orden, menu_id, status FROM Modulos WHERE status = 1 ORDER BY modulo_orden ASC")
            );
    }

    public static function allWithDashboad()
    {
        return Mssql::fetch_assoc(
            mssql_query("SELECT id, nombre, icono, ruta, modulo_orden, menu_id, status FROM Modulos WHERE ruta LIKE 'dashboard_%' ORDER BY modulo_orden ASC")
        );
    }

    public static function getById($key)
    {
        return Mssql::fetch_assoc(
                mssql_query("SELECT id, nombre, icono, ruta, modulo_orden, menu_id, status FROM Modulos WHERE id='$key'")
            );
    }

    public static function getByName($key)
    {
        return Mssql::fetch_assoc(
                mssql_query("SELECT id, nombre, icono, ruta, modulo_orden, menu_id, status FROM Modulos WHERE nombre='$key' ORDER BY modulo_orden ASC")
            );
    }

    public static function getByRoute($key)
    {
        return Mssql::fetch_assoc(
                mssql_query("SELECT id, nombre, icono, ruta, modulo_orden, menu_id, status FROM Modulos WHERE ruta='$key'")
            );
    }

    public static function getByMenuId($key, $includeNoActive = false)
    {
        $condition = $includeNoActive ? ' AND status=1' : '';

        return Mssql::fetch_assoc(
                mssql_query("SELECT id, nombre, icono, ruta, modulo_orden, menu_id, status FROM Modulos WHERE menu_id='$key' $condition ORDER BY modulo_orden ASC")
            );
    }

    public static function withoutFather($includeNoActive = false)
    {
        $condition = $includeNoActive ? ' AND status=1' : '';

        return Mssql::fetch_assoc(
            mssql_query("SELECT id, nombre, icono, ruta, modulo_orden, menu_id, status FROM Modulos WHERE menu_id = -1 AND ruta NOT LIKE 'dashboard_%' $condition ORDER BY modulo_orden ASC")
        );
    }

    public static function updateMenuIdInModule($modulo_id, $menu_id)
    {
        return mssql_query("update Modulos set menu_id='$menu_id' where id='$modulo_id'");
    }
}