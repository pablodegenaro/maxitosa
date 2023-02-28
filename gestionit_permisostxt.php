<?php
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
require ("conexion.php");
require ("funciones.php");
require_once ("Functions.php");
require_once ("permisos/Mssql.php");

switch ($_GET["op"]) {
    case "leer_archivo":
    $i = 0;
    $output = array();

    $operacion = $_POST['opera'];

    if (!empty($_FILES["file"]["name"])) {
            $allowed_file_types = array('.txt', '.TXT'); // tipos de archivos aceptados

            $ubicacionTemporal = $_FILES['file']['tmp_name'];
            $nombreArchivo = $_FILES['file']['name'];
            $rutaServidor = "./files_temp/";
            $file_basename = substr($nombreArchivo, 0, strripos($nombreArchivo, '.')); // Verifica la extención del archivo
            $file_ext = substr($nombreArchivo, strripos($nombreArchivo, '.')); // Verifica el nombre del archivo
            $filesize = $_FILES["file"]["size"];
            
            if (!file_exists($rutaServidor)) {
                // verifica que la carpeta exista
                mkdir($rutaServidor, 0777, true);
            }

            # mover del temporal al directorio actual
            if (in_array($file_ext, $allowed_file_types) && ($filesize < 500000)) 
            {
                # renombrar
                $nuevoNombre = sprintf("%s.%s", uniqid(), str_replace('.', '', $file_ext));
                $resp = move_uploaded_file($ubicacionTemporal, $rutaServidor.$nuevoNombre); // Mueve el archivo a su ubicación correspondiente

                if ($resp==true && file_exists($rutaServidor.$nuevoNombre)==true) {
                    
                    switch ($operacion) {
                        case 1: # ----------------OPERACION DE ACTUALIZACION----------------
                        $fp = fopen($rutaServidor.$nuevoNombre, "r");
                        while (!feof($fp)){
                                # lectura de la linea
                            $linea = fgets($fp);
                            
                                # comprueba que sea "Menu" (01)
                            if (substr($linea,0,2)=="01") {
                                $id = intval(trim(substr($linea,2,10)));
                                $menu_orden = intval(trim(substr($linea,12,3)));
                                $status = intval(trim(substr($linea,15,1)));
                                $menu_padre = trim(substr($linea,16,10));
                                $icono = base64_decode(trim(substr($linea,26,50)));
                                $nombre = base64_decode(trim(substr($linea,76,50)));

                                $query = mssql_query("SELECT id, nombre FROM Menu WHERE id='$id'");
                                if (mssql_num_rows($query) == 0) {
                                    mssql_query("SET IDENTITY_INSERT [dbo].[Menu] ON 
                                        INSERT INTO Menu (id, nombre, menu_orden, menu_padre, icono, status) VALUES('$id','$nombre','$menu_orden','$menu_padre','$icono','$status')
                                        SET IDENTITY_INSERT [dbo].[Menu] OFF
                                        ");
                                } else {
                                    mssql_query("UPDATE Menu SET nombre='$nombre', menu_orden='$menu_orden', menu_padre='$menu_padre', icono='$icono', status='$status' WHERE id='$id'");
                                }
                            }

                                # comprueba que sea "Modulo" (02)
                            elseif (substr($linea,0,2)=="02") {
                                $id = intval(trim(substr($linea,2,10)));
                                $modulo_orden = intval(trim(substr($linea,12,3)));
                                $menu_id = intval(trim(substr($linea,15,10)));
                                $status = trim(substr($linea,25,1));
                                $ruta = base64_decode(trim(substr($linea,26,80)));
                                $icono = base64_decode(trim(substr($linea,106,80)));
                                $nombre = base64_decode(trim(substr($linea,186,80)));

                                $query = mssql_query("SELECT id, nombre FROM Modulos WHERE id='$id'");
                                if (mssql_num_rows($query) == 0) {
                                    mssql_query("SET IDENTITY_INSERT [dbo].[Modulos] ON 
                                        INSERT INTO Modulos (id, nombre, icono, ruta, modulo_orden, menu_id, status) VALUES('$id','$nombre','$icono','$ruta','$modulo_orden','$menu_id','$status')
                                        SET IDENTITY_INSERT [dbo].[Modulos] OFF
                                        ");
                                } else {
                                    mssql_query("UPDATE Modulos SET nombre='$nombre', icono='$icono', ruta='$ruta', modulo_orden='$modulo_orden', menu_id='$menu_id', status='$status' WHERE id='$id'");
                                }
                            }
                            
                                # comprueba que sea "Permisos" (03)
                            elseif (substr($linea,0,2)=="03") {
                                $id = intval(trim(substr($linea,2,10)));
                                $id_modulo = intval(trim(substr($linea,12,10)));
                                $id_usuario = base64_decode(trim(substr($linea,22,50)));

                                $query = mssql_query("SELECT id FROM Permisos WHERE id='$id' AND id_usuario='$id_usuario' AND id_modulo='$id_modulo'");
                                if (mssql_num_rows($query) == 0) {
                                    mssql_query("SET IDENTITY_INSERT [dbo].[Permisos] ON 
                                        INSERT INTO Permisos (id, id_usuario, id_modulo) VALUES('$id','$id_usuario','$id_modulo')
                                        SET IDENTITY_INSERT [dbo].[Permisos] OFF
                                        ");
                                }
                            }

                                # comprueba que sea "Roles_app" (04)
                            elseif (substr($linea,0,2)=="04") {
                                $id = intval(trim(substr($linea,2,10)));
                                $descripcion = base64_decode(trim(substr($linea,12,50)));

                                $query = mssql_query("SELECT id, descripcion FROM Roles_app WHERE id='$id'");
                                if (mssql_num_rows($query) == 0) {
                                    mssql_query("SET IDENTITY_INSERT [dbo].[Roles_app] ON 
                                        INSERT INTO Roles_app (id, descripcion) VALUES('$id','$descripcion')
                                        SET IDENTITY_INSERT [dbo].[Roles_app] OFF
                                        ");
                                } else {
                                    mssql_query("UPDATE Roles_app SET descripcion='$descripcion' WHERE id='$id'");
                                }
                            }
                            
                                # comprueba que sea "Roles_Modulos" (05)
                            elseif (substr($linea,0,2)=="05") {
                                $id = intval(trim(substr($linea,2,10)));
                                $id_modulo = intval(trim(substr($linea,12,10)));
                                $id_rol = intval(trim(substr($linea,22,10)));

                                $query = mssql_query("SELECT id FROM Roles_Modulos WHERE id='$id' AND id_modulo='$id_modulo' AND id_rol='$id_rol'");
                                if (mssql_num_rows($query) == 0) {
                                    mssql_query("SET IDENTITY_INSERT [dbo].[Roles_Modulos] ON 
                                        INSERT INTO Roles_Modulos (id, id_modulo, id_rol) VALUES('$id','$id_modulo','$id_rol')
                                        SET IDENTITY_INSERT [dbo].[Roles_Modulos] OFF
                                        ");
                                }
                            }
                            
                                # comprueba que sea "SSUSRS" (06)
                            elseif (substr($linea,0,2)=="06") {
                                $rol_id = intval(trim(substr($linea,2,10)));
                                $Access = intval(trim(substr($linea,12,1)));
                                $CodUsua = base64_decode(trim(substr($linea,13,50)));

                                $query = mssql_query("SELECT CodUsua FROM SSUSRS WHERE CodUsua='$CodUsua'");
                                if (mssql_num_rows($query) > 0) {
                                    if ($rol_id==0) {
                                        mssql_query("UPDATE SSUSRS SET rol_id=NULL, Access='$Access' WHERE CodUsua='$CodUsua'");
                                    } else {
                                        mssql_query("UPDATE SSUSRS SET rol_id='$rol_id', Access='$Access' WHERE CodUsua='$CodUsua'");
                                    }
                                    
                                }
                            }
                        }
                        fclose($fp);
                        break;

                        case 2: # ----------------OPERACION DE REEMPLAZO----------------
                        $fp = fopen($rutaServidor.$nuevoNombre, "r");
                        if (!feof($fp)) {
                            mssql_query("TRUNCATE TABLE Menu");
                            mssql_query("TRUNCATE TABLE Modulos");
                            mssql_query("TRUNCATE TABLE Permisos");
                            mssql_query("TRUNCATE TABLE Roles_app");
                            mssql_query("TRUNCATE TABLE Roles_Modulos");
                        }
                        while (!feof($fp)){
                                # lectura de la linea
                            $linea = fgets($fp);
                            
                                # comprueba que sea "Menu" (01)
                            if (substr($linea,0,2)=="01") {
                                $id = intval(trim(substr($linea,2,10)));
                                $menu_orden = intval(trim(substr($linea,12,3)));
                                $status = intval(trim(substr($linea,15,1)));
                                $menu_padre = trim(substr($linea,16,10));
                                $icono = base64_decode(trim(substr($linea,26,50)));
                                $nombre = base64_decode(trim(substr($linea,76,50)));

                                mssql_query("SET IDENTITY_INSERT [dbo].[Menu] ON 
                                    INSERT INTO Menu (id, nombre, menu_orden, menu_padre, icono, status) VALUES('$id','$nombre','$menu_orden','$menu_padre','$icono','$status')
                                    SET IDENTITY_INSERT [dbo].[Menu] OFF
                                    ");
                            }

                                # comprueba que sea "Modulo" (02)
                            elseif (substr($linea,0,2)=="02") {
                                $id = intval(trim(substr($linea,2,10)));
                                $modulo_orden = intval(trim(substr($linea,12,3)));
                                $menu_id = intval(trim(substr($linea,15,10)));
                                $status = trim(substr($linea,25,1));
                                $ruta = base64_decode(trim(substr($linea,26,80)));
                                $icono = base64_decode(trim(substr($linea,106,80)));
                                $nombre = base64_decode(trim(substr($linea,186,80)));

                                mssql_query("SET IDENTITY_INSERT [dbo].[Modulos] ON 
                                    INSERT INTO Modulos (id, nombre, icono, ruta, modulo_orden, menu_id, status) VALUES('$id','$nombre','$icono','$ruta','$modulo_orden','$menu_id','$status')
                                    SET IDENTITY_INSERT [dbo].[Modulos] OFF
                                    ");
                            }
                            
                                # comprueba que sea "Permisos" (03)
                            elseif (substr($linea,0,2)=="03") {
                                $id = intval(trim(substr($linea,2,10)));
                                $id_modulo = intval(trim(substr($linea,12,10)));
                                $id_usuario = base64_decode(trim(substr($linea,22,50)));

                                mssql_query("SET IDENTITY_INSERT [dbo].[Permisos] ON 
                                    INSERT INTO Permisos (id, id_usuario, id_modulo) VALUES('$id','$id_usuario','$id_modulo')
                                    SET IDENTITY_INSERT [dbo].[Permisos] OFF
                                    ");
                            }

                                # comprueba que sea "Roles_app" (04)
                            elseif (substr($linea,0,2)=="04") {
                                $id = intval(trim(substr($linea,2,10)));
                                $descripcion = base64_decode(trim(substr($linea,12,50)));

                                mssql_query("SET IDENTITY_INSERT [dbo].[Roles_app] ON 
                                    INSERT INTO Roles_app (id, descripcion) VALUES('$id','$descripcion')
                                    SET IDENTITY_INSERT [dbo].[Roles_app] OFF
                                    ");
                            }
                            
                                # comprueba que sea "Roles_Modulos" (05)
                            elseif (substr($linea,0,2)=="05") {
                                $id = intval(trim(substr($linea,2,10)));
                                $id_modulo = intval(trim(substr($linea,12,10)));
                                $id_rol = intval(trim(substr($linea,22,10)));

                                mssql_query("SET IDENTITY_INSERT [dbo].[Roles_Modulos] ON 
                                    INSERT INTO Roles_Modulos (id, id_modulo, id_rol) VALUES('$id','$id_modulo','$id_rol')
                                    SET IDENTITY_INSERT [dbo].[Roles_Modulos] OFF
                                    ");
                            }
                            
                                # comprueba que sea "SSUSRS" (06)
                            elseif (substr($linea,0,2)=="06") {
                                $rol_id = intval(trim(substr($linea,2,10)));
                                $Access = intval(trim(substr($linea,12,1)));
                                $CodUsua = base64_decode(trim(substr($linea,13,50)));

                                $query = mssql_query("SELECT CodUsua FROM SSUSRS WHERE CodUsua='$CodUsua'");
                                if (mssql_num_rows($query) > 0) {
                                    if ($rol_id==0) {
                                        mssql_query("UPDATE SSUSRS SET rol_id=NULL, Access='$Access' WHERE CodUsua='$CodUsua'");
                                    } else {
                                        mssql_query("UPDATE SSUSRS SET rol_id='$rol_id', Access='$Access' WHERE CodUsua='$CodUsua'");
                                    }
                                    
                                }
                            }
                        }
                        fclose($fp);
                        break;
                    }

                    unlink($rutaServidor.$nuevoNombre);
                } else {
                    $data = array(
                        "errors" => "ERROR AL PROCESAR EL ARCHIVO TXT. <br> consulte con el administrador"
                    );
                    $error = true;
                }
            } else {
                $data = array(
                    "errors" => "archivo TXT muy pesado."
                );
            }
        } else {
            $data = array(
                "errors" => "Archivo no disponible en servidor."
            );
        }

        # envio de respuesta
        if (!isset($data['errors'])) {
            $output = array(
                "mensaje" => "Se ingresó exitosamente!",
                "icono"   => "success"
            );
        } else {
            # muestra un mensaje
            # de error
            $output = array(
                "mensaje" => $data['errors'],
                "icono"   => "error"
            );
        }

        echo json_encode($output);
        break;
    }