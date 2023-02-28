<?php
set_time_limit(0);
require_once ("Mssql.php");

class Menu
{
    public static function todos()
    {
        return Mssql::fetch_assoc(
            mssql_query("SELECT id, nombre, menu_orden, menu_padre, icono, status FROM Menu ORDER BY nombre")
        );
    }

    public static function getById($key)
    {
        return Mssql::fetch_assoc(
                mssql_query("SELECT id, nombre, menu_orden, menu_padre, icono, status FROM Menu WHERE id='$key'")
            );
    }

    public static function withoutFather()
    {
        return Mssql::fetch_assoc(
                mssql_query("SELECT id, nombre, menu_orden, menu_padre, icono, status FROM Menu WHERE menu_padre = -1 ORDER BY menu_orden ASC")
            );
    }

    public static function getChildren($key)
    {
        return Mssql::fetch_assoc(
                mssql_query("SELECT id, nombre, menu_orden, menu_padre, icono, status FROM Menu WHERE menu_padre='$key' ORDER BY menu_orden ASC")
            );
    }

    public static function updateMenuFatherInMenu($id, $menu_id)
    {
        return Mssql::fetch_assoc(
            mssql_query("UPDATE Menu set menu_padre='$menu_id' where id='$id'")
        );
    }
}