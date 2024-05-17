<?php
	session_start();
	require 'funcs/conexion.php';
	require 'funcs/funcs.php';

    if(isset($_SESSION["id_usuario"])){
		$idUsuario = $_SESSION["id_usuario"];
	    $sql = "SELECT id, nombre FROM usuarios WHERE id = '$idUsuario'";
	    $result = $mysqli->query($sql);
	    $row = $result->fetch_assoc();
	}

?>

<!doctype html>
<html lang="es">
    <?php require_once('header.php'); ?>
	<body>
        <!-- Navegador -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <?php if (isset($_SESSION['id_usuario'])): ?>
                    <a class="navbar-brand" href="#"><?php echo 'Bienvenid@ '.utf8_decode($row['nombre']); ?> a Libroteca</a>
                    <a class="navbar-brand" href="logout.php">Cerrar Session</a>
                    <?php else: ?>
                    <a class="navbar-brand" href="#">¡Bienvenidos a la Demo de Libroteca!</a>
                    <a class="navbar-brand" href="index.php">¡Iniciar Sesión!</a>
                <?php endif; ?>
            </div>
        </nav>
        <!-- Contenedor principal -->
        <div class="container-fluid">
            <div class="row">
                <!-- Contenido tabla de libros -->
                <div class="container">
                    <main class="col-md-12 ms-sm-auto col-lg-12 px-md-12 ">
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h1 class="h2">Listado de Libros</h1>
                            <?php if (isset($_SESSION['id_usuario'])): ?>
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalAgregarLibro">Agregar Libro</button>
                            <?php endif; ?>
                        </div>
                        <div class="table-responsive">
                            <table id="tabla-libros" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Titulo</th>
                                        <th>Autor</th>
                                        <th>Fecha publicacion</th>
                                        <th>Genero</th>
                                        <th>Tematica</th>
                                        <?php if (isset($_SESSION['id_usuario'])): ?>
                                            <th></th>
                                        <?php endif; ?>   
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </main>
                </div>
            </div>
        </div>
	</body>
  

<!-- Modal Agregar Libro -->
<div class="modal fade" id="modalAgregarLibro" tabindex="-1" role="dialog" aria-labelledby="modalAgregarLibroLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAgregarLibroLabel">Agregar Nuevo Libro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formulario-libro">
                    <input type="hidden" id="opcion" name="opcion" value="agregar">
                    <div class="form-group">
                        <label for="titulo">Título:</label>
                        <input type="text" class="form-control" id="titulo" name="titulo">
                    </div>
                    <div class="form-group">
                        <label for="autor">Autor:</label>
                        <input type="text" class="form-control" id="autor" name="autor">
                    </div>
                    <div class="form-group">
                        <label for="genero">Género:</label>
                        <input type="text" class="form-control" id="genero" name="genero">
                    </div>
                    <div class="form-group">
                        <label for="tematica">Temática:</label>
                        <input type="text" class="form-control" id="tematica" name="tematica">
                    </div>
                </form>
                <div class="mensaje">
            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnGuardarLibro">Guardar</button>
            </div>
        </div>
        
    </div>
</div>


<!--MODAL PARA MODIFICAR-->
<div class="modal fade" id="frmEditarLibro" tabindex="-1" role="dialog" aria-labelledby="modalEditarLibroLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLibroLabel">Editar Libro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEditarLibro">
                    <input type="hidden" id="libroIdmodificar" name="libroIdmodificar">
                    <div class="form-group">
                        <label for="titulo">Título</label>
                        <input type="text" class="form-control autor" id="titulomodificar" name="titulomodificar" value="" >
                    </div>
                    <div class="form-group">
                        <label for="autor">Autor</label>
                        <input type="text" class="form-control autor" id="autormodificar" name="autormodificar" value="" >
                    </div>
                    <div class="form-group">
                        <label for="genero">Género</label>
                        <input type="text" class="form-control genero" id="generomodificar" name="generomodificar" value="" >
                    </div>
                    <div class="form-group">
                        <label for="tematica">Temática</label>
                        <input type="text" class="form-control tematica" id="tematicamodificar" name="tematicamodificar" value="" >
                    </div>
                    <div class="mensaje pb-4"></div>
                    <button type="button" class="btn btn-primary" id="guardarCambios">Guardar cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>

</html>		


<script src="assets/js/funciones.js" ></script>

<script>
    
//Funcion para cargar la tabla de libros
var cargar_libros = function () {

$('#tabla-libros').DataTable({
    // Configuración de DataTables
    "destroy": true,
    "ajax": {
        url: "libros/get_data_libros.php",
        type: "POST"
    },
    "columns": [
        { "data": "id" },
        { "data": "titulo" },
        { "data": "autor" },
        { "data": "fecha_publicacion" },
        { "data": "genero" },
        { "data": "tematica" },
        <?php if (isset($_SESSION['id_usuario'])): ?>
        {
            data: null,
            render: function (data, type, row) {
                // Crear botones de editar y eliminar con la ID del libro
                return '<button class="editar-libro btn btn-primary  p-2 m-1" data-id="' + data.id + '">Editar</button>' +
                    '<button class="eliminar-libro btn btn-danger  p-2 m-1" data-id="' + data.id + '">Eliminar</button>';
            }
        }
        <?php endif; ?>   
        
    ],
    language: {
        url: '//cdn.datatables.net/plug-ins/2.0.7/i18n/es-ES.json',
    },
});


}


$(document).ready(function () {

    cargar_libros();

});
</script>