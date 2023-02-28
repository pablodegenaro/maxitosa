<?php
$factor = $_POST['factor'];

 $proce_factor = mssql_query("EXEC [Factor_APP] @factor =$factor ");
 if ($proce_factor){
		echo "<script>alert('Factor actualizado');</script>";
		echo "<script language=Javascript> location.href=\"principal1.php?page=cambiar_factor&mod=1\";</script>";
	}else{
		echo "<script>alert('Error, No se ha podido actualizar el factor');</script>";
		echo "<script language=Javascript> location.href=\"principal1.php?page=cambiar_factor&mod=1&id=$id\";</script>";
	}
?>