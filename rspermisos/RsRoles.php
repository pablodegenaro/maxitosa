<?php
require ("conexion.php");

class Roles
{
    public static function todos()
    {
        return Mssql::fetch_assoc(
                mssql_query("SELECT id, descripcion, dashboard FROM Roles_app")
            );
    }

    public static function getById($key)
    {
        return Mssql::fetch_assoc(
                mssql_query("SELECT id, descripcion, dashboard FROM Roles_app WHERE id = '$key'")
            );
    }

    public static function getByName($key)
    {
        return Mssql::fetch_assoc(
                mssql_query("SELECT id, descripcion, dashboard FROM Roles_app WHERE Descripcion = '$key'")
            );
    }

    public static function getRelationRolUser($key)
    {
        return Mssql::fetch_assoc(
            mssql_query("SELECT u.* FROM Roles_app r
                    INNER JOIN ssusrs u ON u.rol_id = r.id
                WHERE r.ID = '$key'")
        );
    }

    public static function updateDashboardInModule($id, $value)
    {
        return mssql_query("update Roles_app set dashboard='$value' where id='$id'");
    }
}