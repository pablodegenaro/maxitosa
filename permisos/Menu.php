<?php
set_time_limit(0);
require_once ("Mssql.php");

class Menu
{
    public static function todos()
    {
        return Mssql::fetch_assoc(
            mssql_query("SELECT id, nombre, menu_orden, menu_padre, icono, codemenu, status FROM Menu ORDER BY nombre")
        );
    }

    public static function todosWithProyect()
    {
        return Mssql::fetch_assoc(
            mssql_query("SELECT menu.id, menu.nombre, menu_orden, menu_padre, icono, menu.codemenu, p.nombre proyecto, status 
                        FROM Menu INNER JOIN RSPROYECTO p ON p.correlpro=menu.codemenu 
                        ORDER BY menu.codemenu, nombre")
        );
    }

    public static function getById($key)
    {
        return Mssql::fetch_assoc(
                mssql_query("SELECT id, nombre, menu_orden, menu_padre, icono, codemenu, status FROM Menu WHERE id='$key'")
            );
    }

    public static function withoutFather($codemenu='1')
    {
        if ($codemenu=='0'){
            return Mssql::fetch_assoc(
                mssql_query("SELECT menu.id, menu.nombre, menu_orden, menu_padre, icono, menu.codemenu, p.nombre proyecto, status 
                            FROM Menu INNER JOIN RSPROYECTO p ON p.correlpro=menu.codemenu 
                            WHERE menu_padre = -1 ORDER BY codemenu, menu_orden ASC")
            );
        } else {
            return Mssql::fetch_assoc(
                mssql_query("SELECT menu.id, menu.nombre, menu_orden, menu_padre, icono, menu.codemenu, p.nombre proyecto, status 
                            FROM Menu INNER JOIN RSPROYECTO p ON p.correlpro=menu.codemenu 
                            WHERE codemenu='$codemenu' AND menu_padre = -1 ORDER BY menu_orden ASC")
            );
        }
    }

    public static function getChildren($key, $codemenu='1')
    {
        if ($codemenu=='0'){
            return Mssql::fetch_assoc(
                mssql_query("SELECT menu.id, menu.nombre, menu_orden, menu_padre, icono, menu.codemenu, p.nombre proyecto, status 
                            FROM Menu INNER JOIN RSPROYECTO p ON p.correlpro=menu.codemenu 
                            WHERE menu_padre='$key' ORDER BY codemenu, menu_orden ASC")
            );
        } else {
            return Mssql::fetch_assoc(
                mssql_query("SELECT menu.id, menu.nombre, menu_orden, menu_padre, icono, menu.codemenu, p.nombre proyecto, status 
                            FROM Menu INNER JOIN RSPROYECTO p ON p.correlpro=menu.codemenu 
                            WHERE codemenu='$codemenu' AND menu_padre='$key' ORDER BY menu_orden ASC")
            );
        }
        
    }

    public static function updateMenuFatherInMenu($id, $menu_id)
    {
        return Mssql::fetch_assoc(
            mssql_query("UPDATE Menu set menu_padre='$menu_id' where id='$id'")
        );
    }
}