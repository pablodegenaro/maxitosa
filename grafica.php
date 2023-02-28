<?php 
require ("conexion.php");
set_time_limit(0);
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');


$query = mssql_query("WITH Meses AS
	(
		SELECT 1 AS Mes UNION ALL SELECT Mes + 1 FROM Meses WHERE Mes < 12
		),
	Operaciones AS
	(
		SELECT
		DATENAME(MONTH, DATEADD(MONTH, m.Mes, -1)) AS mes, 
		COALESCE(o.Cuenta, 0) AS total
		FROM
		Meses m
		LEFT JOIN 
		(
			SELECT
			MONTH(c.FechaE) AS [Mes], SUM(c.MtoTotal) AS [Cuenta]
			FROM
			SAFACT c where c.FechaE BETWEEN '2022-01-01' AND '2022-12-31' and c.TipoFac ='A'

			GROUP BY MONTH(c.FechaE)
			) o ON m.Mes = o.Mes	   
		)
	SELECT * FROM Operaciones");
$valD = $jsonD = array();
for ($i=0; $i < mssql_num_rows($query); $i++) {
	$valD['mes'] = mssql_result($query, $i, 'mes');
	$valD['total'] = mssql_result($query, $i, 'total');
                //print_r($valD); echo '<br>';
	echo json_encode($valD);
}




