function is_int (mixedVar) {
    //   example 1: is_int(23)
    //   returns 1: true
    //   example 2: is_int('23')
    //   returns 2: false
    //   example 3: is_int(23.5)
    //   returns 3: false
    //   example 4: is_int(true)
    //   returns 4: false
    return mixedVar === +mixedVar && isFinite(mixedVar) && !(mixedVar % 1)
}

function permisosRecursion(data, pretitle) {
    let output = '';
    // recorremos la variable data, que es los menu:
    // si es la primera vez es los menu padre
    // si ya entro en proceso recursivo es menu hijo
    $.each(data, function(idx, opt) {
        if (is_int(Number(idx))) {
            let { title, children, modules } = opt;

            if (title !== '') {
                // agregamos el titulo del menu
                output += '<div class="row ' + (pretitle.length === 0 ? 'mt-3' : '') + '">' +
                '<div class="col">' +
                ( pretitle.length === 0
                    ? '<div class="form-group"><h4 class="">'+ title +'</h4></div>'
                    : '<div class="form-group"><h5 class="pl-3">'+ title +'</h5></div>'
                    ) +
                '</div>' +
                '</div>';

                // verificamos si tiene menus hijos el menu padre
                if (!jQuery.isEmptyObject(children)) {
                    // si existen hijos realiza el proceso recursivo
                    output += permisosRecursion(children, ((pretitle.length > 0) ? (pretitle+' --> '+title) : title));
                }

                // luego verificamos si el menu posee modulos dependientes
                let temp = '<div class="row mt-1">';
                if (!jQuery.isEmptyObject(modules))
                {
                    $.each(modules, function(idx, opt) {
                        let { id, name, selected } = opt;

                        temp += '<div class="col-6 form-group pl-5">' +
                        '<div class="custom-control custom-switch custom-switch-off-light custom-switch-on-saint">' +
                        '<input id="modulo_'+id+'" onchange="guardar(\''+ id +'\')" type="checkbox" class="custom-control-input" '+ (selected ? 'checked':'') +'>' +
                        '<label for="modulo_'+id+'" class="custom-control-label">' + ((pretitle.length > 0) ? (pretitle+' --> '+title) : title) + ' --> ' + name + '</label>' +
                        '</div>' +
                        '</div>';
                    });
                }
                else if (jQuery.isEmptyObject(children) && jQuery.isEmptyObject(modules)) {
                    // si no tiene modulos ni hijos, imprimimos un mensaje
                    temp += '<div class="col-12 form-group">' +
                    '<div class="custom-control">' +
                    '<span class="badge badge-warning">Sin Módulos para este menú</span>' +
                    '</div>' +
                    '</div>';
                }
                temp += '</div>';

                output += temp;

            }
            // en caso que el title este vacio es porque son modulos sin menu
            else {
                let temp = '<div class="row mt-1">';
                if (!jQuery.isEmptyObject(modules))
                {
                    $.each(modules, function(idx, opt) {
                        let { id, name, selected } = opt;

                        temp += '<div class="col-6 form-group pl-5">' +
                        '<div class="custom-control custom-switch custom-switch-off-light custom-switch-on-success">' +
                        '<input id="modulo_'+id+'" onchange="guardar(\''+ id +'\')" type="checkbox" class="custom-control-input" '+ (selected ? 'checked':'') +'>' +
                        '<label for="modulo_'+id+'" class="custom-control-label">' + name + '</label>' +
                        '</div>' +
                        '</div>';
                    });
                }
                temp += '</div>';

                output += temp;
            }
        }
    });

    // retornamos el string html generado
    return output;
}

function permisosMenuLateral(data) {
    let output = '';
    // recorremos la variable data, que es los menu:
    // si es la primera vez es los menu padre
    // si ya entro en proceso recursivo es menu hijo
    $.each(data, function(idx, opt) {
        if (is_int(Number(idx))) {
            let { title, children, modules, icon } = opt;

            if (title !== '') {
                output += '<li class="nav-item has-treeview">';

                // agregamos el titulo del menu
                output += '<a href="#" class="nav-link">' +
                '<i class="nav-icon '+ icon +'"></i>' +
                '<p>'+ title +'<i class="fas fa-angle-left right"></i></p>' +
                '</a>';

                // verificamos si tiene menus hijos el menu padre
                if (!jQuery.isEmptyObject(children)) {
                    output += '<ul class="nav nav-treeview">';
                    // si existen hijos realiza el proceso recursivo
                    output += permisosMenuLateral(children);

                    output += '</ul>';
                }

                // luego verificamos si el menu posee modulos dependientes
                if (!jQuery.isEmptyObject(modules))
                {
                    output += '<ul class="nav nav-treeview">';
                    $.each(modules, function(idx, opt) {
                        let { name, route, icon, selected } = opt;

                        if (selected) {
                            output +=
                            '<li class="nav-item">' +
                            '<a href="principal1.php?page='+ route.replace(".php", "")+'&mod=1" class="nav-link">' +
                            '<i class="nav-icon '+ icon +'"></i>' +
                            '<p>'+ name +'</p>' +
                            '</a>' +
                            '</li>';
                        }
                    });
                    output += '</ul>';
                }
                output += '</li>';
            }
            // en caso que el title este vacio es porque son modulos sin menu
            else {
                if (!jQuery.isEmptyObject(modules))
                {
                    $.each(modules, function(idx, opt) {
                        let { name, route, icon, selected } = opt;

                        if (selected) {
                            output +=
                            '<li class="nav-item">' +
                            '<a href="principal1.php?page='+ route.replace(".php", "")+'&mod=1" class="nav-link">' +
                            '<i class="nav-icon '+ icon +'"></i>' +
                            '<p>'+ name +'</p>' +
                            '</a>' +
                            '</li>';
                        }
                    });
                }
            }
        }
        
    });


    // retornamos el string html generado
    return output;
}