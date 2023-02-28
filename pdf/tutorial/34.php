<?PHP



/*  Comenzamos a armar el documento  */
$output="{\\rtf1";   //<-- Iniciamos un documento RTF

$output.= "{\\fs48 Libros publicados}"; //<-- Texto de tama�o 48 para el T�tulo
$output.= "\\par ";  //<-- ENTER       

$output.= "{\\fs30 Material did�ctico}"; //<-- Texto de tama�o 30 para el Subt�tulo
$output.= "\\par ";  //<-- ENTER
$output.= "\\par ";  //<-- ENTER


/* INICIO DE LA TABLA */

$output.= "{ ";  //<-- Inicio de la tabla

$output.= "\\trgaph70"; //<-- m�rgenes izquierdo y derecho de las celdas=70
$output.= "\\trleft-10"; // <-- Posici�n izquierda la primera celda = -10

/*  Definici�n de las celdas de datos. Se definen 4 columnas */
$output.= "
\\clbrdrl\\brdrw10\\brdrs
\\clbrdrt\\brdrw10\\brdrs
\\clbrdrr\\brdrw10\\brdrs
\\clbrdrb\\brdrw10\\brdrs 
\\cellx500

\\clbrdrl\\brdrw10\\brdrs
\\clbrdrt\\brdrw10\\brdrs
\\clbrdrr\\brdrw10\\brdrs
\\clbrdrb\\brdrw10\\brdrs 
\\cellx2500

\\clbrdrl\\brdrw10\\brdrs
\\clbrdrt\\brdrw10\\brdrs
\\clbrdrr\\brdrw10\\brdrs
\\clbrdrb\\brdrw10\\brdrs 
\\cellx5000

\\clbrdrl\\brdrw10\\brdrs
\\clbrdrt\\brdrw10\\brdrs
\\clbrdrr\\brdrw10\\brdrs
\\clbrdrb\\brdrw10\\brdrs 
\\cellx8700
";


/*Introducci�n de los t�tulos en el primer rengl�n*/
$output.= "{\\fs24\\b ";  //<-- Fuente de tama�o 24 y en negrita
$output.= "
No \\cell 
T�tulo \\cell 
Autor \\cell 
Descripci�n \\cell 
}"; 
$output.= " \\row "; //<-- Fin del rengl�n de encabezado

/* Introducci�n de los datos */
 $datos= array();
 $datos[]= array("1", "PHP para tontos" , 
                        "Brizuela, Guillermina" , 
                        "Este es un libro ficticio utilizado como Demo");
 $datos[]= array("2", "La inversi�n prudente" , 
                      "Luis Carlos Jemio" , 
                      "Impacto del bonosol sobre la familia, la equidad social.");
 $datos[]= array("3", "Dise�o de proyectos de tecnolog�a educativa" , 
                      "Victor de la Rocha" , 
                      "Con una propuesta totalmente visual, el video se convierte en ...");
                                                                   
foreach($datos as $v)
{
 $output.= " {$v[0]}\\cell {$v[1]}\\cell {$v[2]}\\cell {$v[3]}\\cell \n";
 $output.= "\\row "; //<-- Fin del rengl�n
}

$output.= "} ";  //<-- fin de la tabla

$output.= "\\par ";  //<-- ENTER


$output.="}"; //<-- Terminador del RTF


/* En los encabezados indicamos que se trata de un documento de MS-WORD
  y en el nombre de archivo le ponemos la extensi�n RTF.            */
header('Content-type: application/msword');
header('Content-Disposition: inline; filename=ejemplo1.rtf'); 
/*  Enviamos el documento completo a la salida  */
echo $output; 
?>
