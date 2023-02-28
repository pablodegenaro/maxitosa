<?php

class Mssql
{
    public static function fetch_assoc($query) {
        $data = Array();

        if (mssql_num_rows($query)) {
            while ($row = mssql_fetch_assoc($query)) {
                $data[] = $row;
            }
            mssql_free_result($query);
        }

        return $data;
    }
}