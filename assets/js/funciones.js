
//Cargaremos los datos en el datable por ajax,
$(document).ready(function () {

    agregarLibros();
    eliminarLibro();
    editar_libro();

});

//Funcion para cargar los datos de la base de datos al modal de libros.
var editar_libro = function () {

    $(document).on('click', '.editar-libro', function () {

        var idLibro = $(this).data('id'); // Obtener el ID del libro desde el atributo data-id

        // Hacer una llamada AJAX para obtener los datos del libro
        $.ajax({
            url: 'libros/get_data_libro.php', // URL del script PHP para obtener los datos del libro
            method: 'POST',
            data: { idlibro: idLibro },
            success: function (response) {

                var libro = JSON.parse(response); // Parsear la respuesta JSON

                // Cargar los datos en el formulario del modal
                $('#libroIdmodificar').val(libro.data[0].id);
                $('#titulomodificar').val(libro.data[0].titulo);
                $('#autormodificar').val(libro.data[0].autor);
                $('#generomodificar').val(libro.data[0].genero);
                $('#tematicamodificar').val(libro.data[0].tematica);

                // Mostrar el modal
                $('#frmEditarLibro').modal('show');
            },
            error: function () {
                // En caso de un error en la llamada AJAX, mostrar un mensaje de error
                Swal.fire(
                    '¡Error!',
                    'Ha ocurrido un error al obtener los datos del libro.',
                    'error'
                );
            }
        });
    });
}

//Guardamos los libros finales
// Manejar el evento clic para el botón de guardar cambios
$('#guardarCambios').click(function () {

    var idLibro = $('#libroIdmodificar').val().trim();
    var titulo = $('#titulomodificar').val().trim();
    var autor = $('#autormodificar').val().trim();
    var genero = $('#generomodificar').val().trim();
    var tematica = $('#tematicamodificar').val().trim();

    //Validar los campos del formulario
    if (titulo === '' || autor === '' || genero === '' || tematica === '') {
        Swal.fire(
            '¡Error!',
            'Todos los campos son obligatorios.',
            'error'
        );
        return;
    }

    // Hacer una llamada AJAX para guardar los cambios
    $.ajax({
        url: 'libros/libro_manager.php', // URL del script PHP para actualizar los datos del libro
        method: 'POST',
        data: {
            idlibro: idLibro,
            titulo: titulo,
            autor: autor,
            genero: genero,
            tematica: tematica,
            opcion: "modificar"
        },
        error: function () {
            // En caso de un error en la llamada AJAX, mostrar un mensaje de error
            Swal.fire(
                '¡Error!',
                'Ha ocurrido un error al procesar la solicitud.',
                'error'
            );
        }
    }).done(function (info) {
        var json_info = JSON.parse(info);
        mostrar_mensaje(json_info);
        cargar_libros();
    });
});


//Funcion para eliminar un libro
var eliminarLibro = function () {

    // Evento clic para el botón para abrir el modal de eliminar libro.
    $(document).on('click', '.eliminar-libro', function () {

        var idlibro = $(this).data('id');

        // Mostrar el modal de confirmación con SweetAlert
        Swal.fire({
            title: '¿Estás seguro de eliminar los libros?',
            text: "¡No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Realizar la llamada AJAX para eliminar el libro
                $.ajax({
                    url: 'libros/libro_manager.php', // URL del script PHP para eliminar el libro
                    method: 'POST',
                    data: {
                        idlibro: idlibro,
                        opcion: "eliminar"
                    }
                }).done(function (info) {
                    var json_info = JSON.parse(info);
                    mostrar_mensaje(json_info);
                    cargar_libros();
                });
            }
        });
    });
}

//Funcion para poder añadir un nuevo libro
var agregarLibros = function () {

    $('#btnGuardarLibro').click(function () {
        // Obtener los valores de los campos del formulario
        var titulo = $('#titulo').val();
        var autor = $('#autor').val();
        var genero = $('#genero').val();
        var tematica = $('#tematica').val();
        var opcion = $('#opcion').val();

        if (titulo === '' || autor === '' || genero === '' || tematica === '') {

            Swal.fire({
                title: '¡Acuerdate que tienes que rellenar todos los campos',
                text: 'Este es un mensaje de advertencia',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });

        } else {

            $.ajax({
                method: "POST",
                "url": "libros/libro_manager.php",
                data: {
                    "titulo": titulo,
                    "autor": autor,
                    "genero": genero,
                    "tematica": tematica,
                    "opcion": opcion
                }

            }).done(function (info) {
                var json_info = JSON.parse(info);
                mostrar_mensaje(json_info);
                cargar_libros();
            });
        }

    });

}

//Funcion para eliminar un libro
var eliminar = function () {

    $("#eliminar-libro").on("click", function (e) {
        e.preventDefault();

        $.ajax({
            method: "POST",
            "url": "libros/libro_manager.php",
            data: { "idlibro": idlibro, "opcion": opcion }

        }).done(function (info) {

            var json_info = JSON.parse(info);
            mostrar_mensaje(json_info);
            limpiar_datos();
            cargar_libros();

        });
    });

}

var mostrar_mensaje = function (informacion) {
    const mensajes = {
        BIEN: {
            texto: "<strong>Bien!</strong> Se han guardado los cambios correctamente.",
            color: "#379911"
        },
        ERROR: {
            texto: "<strong>Error</strong>, no se ejecutó la consulta.",
            color: "#C9302C"
        },
        EXISTE: {
            texto: "<strong>Información!</strong> el usuario ya existe.",
            color: "#5b94c5"
        },
        VACIO: {
            texto: "<strong>Advertencia!</strong> debe llenar todos los campos solicitados.",
            color: "#ddb11d"
        }
    };

    // Mensaje por defecto si la respuesta no es conocida
    const defaultMensaje = {
        texto: "Respuesta no reconocida.",
        color: "#333"
    };

    // Coge la respuesta recibida o pon la por defecto si no esta
    const { texto, color } = mensajes[informacion.respuesta] || defaultMensaje;

    // Update the DOM once rather than in each case
    $(".mensaje")
        .html(texto)
        .css({ "color": color })
        .fadeOut(5000, function () {
            $(this).html("").fadeIn(3000);
        });
}


var limpiar_datos = function () {
    $("#opcion").val("modificar");
    $("#titulo").val("");
    $("#autor").val("").focus();
    $("#genero").val("");
    $("#tematica").val("");
}



















