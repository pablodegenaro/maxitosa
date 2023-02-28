 <footer class="main-footer">
  <strong>Copyright &copy; <?php $Year = date("Y"); echo $Year; ?> <a href="https://rsistems.tech/">Rsistems Developer</a>.</strong>
  Todos los derechos reservados.
  <!-- PABLO DE GENARO -- 0412 1178800 PABLODEGENARO@GMAIL.COM -->
  <div class="float-right d-none d-sm-inline-block">
    <b>Version</b> 2.5
  </div>
</footer>
</div>
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->

<!-- DataTables  & Plugins -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="plugins/jszip/jszip.min.js"></script>
<script src="plugins/pdfmake/pdfmake.min.js"></script>
<script src="plugins/pdfmake/vfs_fonts.js"></script>
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- Select2 -->
<script src="plugins/select2/js/select2.full.min.js"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<!-- sweetalert2 -->
<script src="plugins/sweetalert2/sweetalert2.all.min.js"></script>
<!-- InputMask -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/inputmask/jquery.inputmask.min.js"></script>
<!-- date-range-picker -->
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Page script -->
<script src="Permissions.js" type="text/javascript"></script>
<script src="plugins/chart.js/Chart.min.js"></script>

<script type="text/javascript">
  $.ajax({
    async: true,
    url: `permiso_lista.php`,
    method: "POST",
    dataType: "json",
    data: { 
      id: $("#id").val(), 
      tipo: 1, 
      esMenuLateral: 1, 
      codemenu: "1"
    },
    error: function (e) {
      console.log(e.responseText);
    },
    success: function (data) {
      if (!jQuery.isEmptyObject(data)) {
        let menu = permisosMenuLateral(data);
        let colormenu = data.colormenu;
        $('.main-sidebar').removeClass('sidebar-dark-primary').removeClass('sidebar-light-primary').addClass(colormenu=='dark' ? 'sidebar-dark-primary' : 'sidebar-light-primary');
        $('#content_menu ul li:last').before(menu);
      }
    },
  });

  $(function () {


      //Bootstrap Duallistbox
    $('.duallistbox').bootstrapDualListbox({
      infoTextFiltered: '<span class="badge badge-warning">Filtrados</span> {0} de {1}',
      filterPlaceHolder: 'Filtro de Búsqueda',
      filterTextClear: 'show all',
      infoText: 'Total {0}',
      infoTextEmpty: 'Lista Vacía',
      moveSelectedLabel: 'Mover seleccionado',
      moveAllLabel: 'Mover todos',
      removeSelectedLabel: 'Remover seleccionado',
      removeAllLabel: 'Remover todos',
      selectorMinimalHeight: 200,
    })
      //Initialize Select2 Elements
    $('.select2').select2();

      //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    });
      //Date picker
    $('#reservationdate').datetimepicker({
      format: 'DD/MM/YYYY'
    });
    $('#fechadesp').datetimepicker({
      format: 'DD/MM/YYYY'
    });
  })  
</script>


<!-- Page specific script -->
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, 
      "lengthChange": false,
      "autoWidth": false,
      "buttons": ["excel", "pdf", "print", "colvis"],
      "language": texto_español_datatables
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "language": texto_español_datatables
    });

    $("#example3").DataTable({
      "responsive": true, 
      "lengthChange": false,
      "autoWidth": false,
      "iDisplayLength": 2000000,
      "buttons": ["excel", "print"],
      "language": texto_español_datatables
    }).buttons().container().appendTo('#example3_wrapper .col-md-6:eq(0)');

    $("#example4").DataTable({
      "responsive": true, 
      "lengthChange": false,
      "autoWidth": false,
      "iDisplayLength": 1000000,//Por cada 10 registros hace una paginación
      "language": texto_español_datatables
    }).buttons().container().appendTo('#example4_wrapper .col-md-6:eq(0)');

    $("#example5").DataTable({
      "lengthChange": false,
      "autoWidth": false,
      "iDisplayLength": 1000000,//Por cada 10 registros hace una paginación
      "language": texto_español_datatables
    }).buttons().container().appendTo('#example5_wrapper .col-md-6:eq(0)');


    $('#example6').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "iDisplayLength": 3000000,
      "language": texto_español_datatables
    });


    $("#example7").DataTable({
      "responsive": true, 
      "lengthChange": false,
      "autoWidth": false,
      "responsive": true,
      "ordering": false,
        // "buttons": ["excel"],
      "language": texto_español_datatables
    }).buttons().container().appendTo('#example7_wrapper .col-md-6:eq(0)');

    $('#example8').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": false,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "language": texto_español_datatables
    });

/*TABLA UTILIZADA EN CXC EMPLEADOS*/
    $("#example9").DataTable({
      "responsive": true, 
      "lengthChange": false,
      "autoWidth": false,
      "buttons": ["excel", "pdf", "print", "colvis"],
       "iDisplayLength": 15,//Por cada 10 registros hace una paginación
       "language": texto_español_datatables
     }).buttons().container().appendTo('#example9_wrapper .col-md-6:eq(0)');

    $('#example10').DataTable({
      "paging": false,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "language": texto_español_datatables
    });


  });

  //variable global utilizada para traducir los textos de datatables a lenguaje español
  const texto_español_datatables = {
    "sProcessing": "Procesando...",
    "sLengthMenu": "Mostrar _MENU_ registros",
    "sZeroRecords": "No se encontraron resultados",
    "sEmptyTable": "Ningún dato disponible en esta tabla",
    "sInfo": "Mostrando un total de _TOTAL_ registros",
    "sInfoEmpty": "Mostrando un total de 0 registros",
    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix": "",
    "sSearch": "Buscar:",
    "sUrl": "",
    "sInfoThousands": ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
      "sFirst": "Primero",
      "sLast": "Último",
      "sNext": "Siguiente",
      "sPrevious": "Anterior"
    },
    "oAria": {
      "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
      "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }
  };

  // the loader html
  const sweet_loader = '<div class="sweet_loader"><svg viewBox="0 0 140 140" width="140" height="140"><g class="outline"><path d="m 70 28 a 1 1 0 0 0 0 84 a 1 1 0 0 0 0 -84" stroke="rgba(0,0,0,0.1)" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round"></path></g><g class="circle"><path d="m 70 28 a 1 1 0 0 0 0 84 a 1 1 0 0 0 0 -84" stroke="#71BBFF" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-dashoffset="200" stroke-dasharray="300"></path></g></svg></div>';
</script>

