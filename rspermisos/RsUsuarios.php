<?php
require ("conexion.php");

class Usuarios
{
    public static function todos($includeUserMaster = false)
    {
        $condition = !$includeUserMaster ? " AND codusUa != '001'" : '';
        return Mssql::fetch_assoc(
            mssql_query("SELECT codusua, descrip, email, rol_id, Access, RTRIM(LTRIM(SUBSTRING(SDATA2,193,1)+SUBSTRING(SDATA1,77,1))) AS nivel 
                                FROM ssusrs WHERE codusUa != '001' ORDER BY descrip ASC")
        );
    }

    public static function byUserName($key, $includeUserMaster = false)
    {
        $condition = !$includeUserMaster ? " AND codusUa != '001'" : '';
        return Mssql::fetch_assoc(
            mssql_query("SELECT codusua, descrip, email, rol_id, Access, RTRIM(LTRIM(SUBSTRING(SDATA2,193,1)+SUBSTRING(SDATA1,77,1))) AS nivel 
                                FROM ssusrs WHERE descrip = '$key' AND codusUa != '001' ORDER BY descrip ASC")
        );
    }

    public static function byCodUsua($key, $includeUserMaster = false)
    {
        $condition = !$includeUserMaster ? " AND codusUa != '001'" : '';
        return Mssql::fetch_assoc(
            mssql_query("SELECT codusua, descrip, email, rol_id, Access, RTRIM(LTRIM(SUBSTRING(SDATA2,193,1)+SUBSTRING(SDATA1,77,1))) AS nivel 
                                FROM ssusrs WHERE codusua = '$key' AND codusUa != '001' ORDER BY descrip ASC")
        );
    }

    public static function byRol($key, $includeUserMaster = false)
    {
        $condition = !$includeUserMaster ? " AND codusUa != '001'" : '';
        return Mssql::fetch_assoc(
            mssql_query("SELECT codusua, descrip, email, rol_id, Access, RTRIM(LTRIM(SUBSTRING(SDATA2,193,1)+SUBSTRING(SDATA1,77,1))) AS nivel 
                                FROM ssusrs WHERE rol_id = '$key' $condition ORDER BY descrip ASC")
        );
    }

    public static function saveRol($usuario_id, $rol_id)
    {
        return mssql_query("UPDATE ssusrs SET rol_id='$rol_id' WHERE codusua='$usuario_id'");
    }
}