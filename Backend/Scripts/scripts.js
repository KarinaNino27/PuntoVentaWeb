window.onload=function(){        
    /* Inicializar la tabla para que le aplique el plugin que mejora 
    la representación de las tablas */
    $('#tblRegistros').DataTable(
        {
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            },
            "createdRow": function ( row, data, index ) {
                if (data[2]==0) {
                    //Colorear toda la fila cuando la existencia sea 0
                    $('td',row).eq(2).addClass('table-danger');
                }
            },
            "order": [[ 2, "asc" ],[ 0, "asc" ]],
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Exportar a Excel'
                },
                {
                    extend: 'pdfHtml5',
                    text: 'Exportar a PDF'
                }
            ]
        } 
    );

    document.querySelectorAll("btn btn-danger").addEventListener("click", confirmacion);
}
function confirmacion(e){
    if(confirm("¿Estas seguro que desea eliminar este producto?")){
        return true;
    }else{
        e.preventDefault();
    }
}

