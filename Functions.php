<?php
require ("conexion.php");
require_once("permisos/Modulos.php");
require_once("permisos/Menu.php");
require_once("permisos/Permisos.php");
require_once("permisos/Roles.php");

class Functions {

    public static function rdecimal($number, $precision = 1, $separator = '.', $separatorDecimal = ',') {
        $numberParts = explode($separator, $number);
        if ($precision == 0) {
            $response = number_format(floatval($numberParts[0]), 0, $separatorDecimal, $separator);
        } else {
            $response = number_format(floatval($numberParts[0]), 0, ",", ".");
            if (count($numberParts) > 1) {
                $response .= $separatorDecimal;
                $response .= substr(
                    $numberParts[1],
                    0,
                    $precision
                );
            }
        }

        return $response;
    }

    public static function selectListMenus($id, $selectChangeToNone = false, $id_menu_except = -1)
    {
        $output = '';
        $datos = Menu::todosWithProyect();
        $seleccionado = Menu::getById($id);
        $haySeleccionado = (is_array($seleccionado) == true and count($seleccionado) > 0);


        $output .= '<option value="-1">' . ( $selectChangeToNone ? 'Ninguno' : '--Seleccione--' ). '</option>';
        if (is_array($datos) == true and count($datos) > 0)
        {
            foreach ($datos as $key => $row)
            {
                if($id_menu_except != $row['id'])
                {
                    if ($haySeleccionado and ($row['id']==$seleccionado[0]['id']) )
                        $output .= '<option value="' . $row['id'] . '" selected>' . $row["proyecto"] .' : '. $row['nombre'] . '</option>';
                    else
                        $output .= '<option value="' . $row['id'] .'">'. $row["proyecto"] .' : '. $row['nombre'] .'</option>';
                }
            }
        }

        return $output;
    }

    public static function selectListRoles($id)
    {
        $output = '';
        $datos = Roles::todos();
        $seleccionado = Roles::getById($id);
        $haySeleccionado = (count($seleccionado) > 0);


        $output .= '<option value="-1">--Seleccione--</option>';
        if (is_array($datos) == true and count($datos) > 0)
        {
            foreach ($datos as $key => $row)
            {
                if ($haySeleccionado and ($row['id']==$seleccionado[0]['id']) )
                    $output .= '<option value="' . $row['id'] . '" selected>' . $row['descripcion'] . '</option>';
                else
                    $output .= '<option value="' . $row['id'] .'">'. $row['descripcion'] .'</option>';
            }
        }

        return $output;
    }

    public static function selectListDashboard($id)
    {
        $output = '';
        $datos = Modulos::allWithDashboad();
        $seleccionado = Modulos::getByRoute($id);
        $haySeleccionado = (count($seleccionado) > 0);


        $output .= '<option value="-1">--Seleccione--</option>';
        if (is_array($datos) == true and count($datos) > 0)
        {
            foreach ($datos as $key => $row)
            {
                if ($haySeleccionado and ($row['id']==$seleccionado[0]['id']) )
                    $output .= '<option value="' . $row['ruta'] . '" selected>' . $row['nombre'] . '</option>';
                else
                    $output .= '<option value="' . $row['ruta'] .'">'. $row['nombre'] .'</option>';
            }
        }

        return $output;
    }

    public static function selectListSocios($arr, $codclie)
    {
        $output = '';

        $output .= '<option value="-1">--Seleccione--</option>';
        if (is_array($arr) == true and count($arr) > 0)
        {
            foreach ($arr as $key => $row)
            {
                if (($row['CodClie']==$codclie) )
                    $output .= '<option value="' . $row['CodClie'] . '" selected>'. $row['CodClie'] . ' - ' . $row['Descrip'] .'</option>';
                else
                    $output .= '<option value="' . $row['CodClie'] .'">' . $row['CodClie'] . ' - ' . $row['Descrip'] .'</option>';
            }
        }

        return $output;
    }

    public static function organigramaMenusWithModules($codemenu="1", $id, $type = -1, $type_id = -1, $itsForSideMenu = false, $isChildren = false, &$countModules = 0){
        $output = $sub_array = array();


        /*****************************/
        /*                           */
        /*    MODULOS SIN PADRES     */
        /*                           */
        /*****************************/
        $modulosPrincipal = array();
        $modulos_sin_padres = Modulos::withoutFather($itsForSideMenu);
        if (is_array($modulos_sin_padres) == true and count($modulos_sin_padres) > 0 and !$isChildren)
        {
            $arr_permissions_by_type = array();

            $sub_array['title'] = '';
            $sub_array['icon'] = '';
            $sub_array['children'] = array();
            # el parametro tipo:
            #        0 el tipo es rol
            #        1 el tipo es usuario
            switch ($type) {
                case 0: $arr_permissions_by_type = Permisos::getRolesGrupoPorRolID($type_id); break;
                case 1: $arr_permissions_by_type = Permisos::getPermisosPorUsuarioID($type_id); break;
            }

            $modulosInDB = array();
            foreach ($arr_permissions_by_type as $arr) { $modulosInDB[] = $arr['id_modulo']; }
            foreach ($modulos_sin_padres as $key1 => $modulo) {
                $isSelected = in_array($modulo['id'], $modulosInDB);

                if ($isSelected==true)
                    $countModules+=1;

                $modulosPrincipal[] = array(
                    'id' 	    => $modulo['id'],
                    'name'      => $modulo['nombre'],
                    'route'     => $modulo['ruta'],
                    'icon'      => $modulo['icono'],
                    'selected'  => $isSelected
                );
            }

            $sub_array['modules'] = $modulosPrincipal;

            # hacemos una condicion que:
            #   si es para el menu lateral, preguntamos si tiene al menos un modulo seleccionado, si no tiene, no muestra dicho menu
            #   si no es para menu lateral, es para el modulo de permisos de usuario, lista los seleccionados y no seleccionados
            if($itsForSideMenu==true)
            {
                #si tiene al menos modulo seleccinado, agregamos al array
                if ($countModules>0) {
                    $output[] = $sub_array;
                }

                # si no es hijo, reiniciamos el contador
                if (!$isChildren) {
                    $countModules = 0;
                }
            }
            else {
                $output[] = $sub_array;
            }
        }


        /******************************/
        /*                            */
        /*      MENUS CON HIJOS       */
        /*                            */
        /******************************/
        $hijos = ($id==-1) ? Menu::withoutFather($codemenu) : Menu::getChildren($id, $codemenu);
        if (is_array($hijos) == true and count($hijos) > 0)
        {
            foreach ($hijos as $key => $hijo) {

                if ($codemenu=='0') {
                    $sub_array['title'] = $hijo['proyecto']." : ".$hijo['nombre'];
                    $sub_array['icon'] = $hijo['icono'];
                } else {
                    $sub_array['title'] = $hijo['nombre'];
                    $sub_array['icon'] = $hijo['icono'];
                }
                

                # verifica si existe hijos para aplicar recursion
                $existenHijos = Menu::getChildren($hijo['id'], $codemenu);
                $sub_array['children'] = (is_array($existenHijos) == true and count($existenHijos) > 0)
                ? Functions::organigramaMenusWithModules($codemenu, $hijo['id'], $type, $type_id, $itsForSideMenu, true, $countModules)
                : array();

                 # verificamos si tiene modulos
                $modulosMenu = array();
                $existenModulos = Modulos::getByMenuId($hijo['id'], $itsForSideMenu);
                if (is_array($existenModulos) == true and count($existenModulos) > 0) {
                    $arr_permissions_by_type = array();

                    # el parametro tipo:
                    #        0 el tipo es rol
                    #        1 el tipo es usuario
                    switch ($type) {
                        case 0: $arr_permissions_by_type = Permisos::getRolesGrupoPorRolID($type_id); break;
                        case 1: $arr_permissions_by_type = Permisos::getPermisosPorUsuarioID($type_id); break;
                    }

                    $modulosInDB = array();
                    foreach ($arr_permissions_by_type as $arr) { $modulosInDB[] = $arr['id_modulo']; }
                    foreach ($existenModulos as $key1 => $modulo) {
                        $isSelected = in_array($modulo['id'], $modulosInDB);

                        if ($isSelected==true)
                            $countModules+=1;

                        $modulosMenu[] = array(
                            'id' 	    => $modulo['id'],
                            'name'      => $modulo['nombre'],
                            'route'     => $modulo['ruta'],
                            'icon'      => $modulo['icono'],
                            'selected'  => $isSelected
                        );
                    }
                }

                $sub_array['modules'] = $modulosMenu;


                # hacemos una condicion que:
                #   si es para el menu lateral, preguntamos si tiene al menos un modulo seleccionado, si no tiene, no muestra dicho menu
                #   si no es para menu lateral, es para el modulo de permisos de usuario, lista los seleccionados y no seleccionados
                if($itsForSideMenu==true)
                {
                    #si tiene al menos modulo seleccinado, agregamos al array
                    if ($countModules>0) {
                        $output[] = $sub_array;
                    }

                    # si no es hijo, reiniciamos el contador
                    if (!$isChildren) {
                        $countModules = 0;
                    }
                }
                else {
                    $output[] = $sub_array;
                }

            }
        }
        return $output;
    }

}