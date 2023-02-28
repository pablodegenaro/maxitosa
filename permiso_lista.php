<?php
require("Functions.php");

$id = $_POST['id'];
$tipo = $_POST['tipo'];
$esMenuLateral = $_POST['esMenuLateral']==1; #variable entera, que utilizamos en forma de bandera para condicionar la obtencionde datos
$codemenu = isset($_POST['codemenu']) ? $_POST['codemenu'] : "1"; #menu a listar

$output = Functions::organigramaMenusWithModules($codemenu, -1, $tipo, $id, $esMenuLateral);
$output['colormenu'] = $codemenu==2 ? 'light' : 'dark';

echo json_encode($output);
