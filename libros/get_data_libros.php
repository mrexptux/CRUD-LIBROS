<?php
    
include("../funcs/conexion.php");

$query = "SELECT * FROM libros";
$resultado = mysqli_query($mysqli, $query);
$arreglo = ["data" => []]; 
if ($resultado) {
    while ($data = mysqli_fetch_assoc($resultado)) {
        $arreglo["data"][] = $data; // Almacena cada fila obtenida en el arreglo
    }
    mysqli_free_result($resultado); // Libera el resultado de la memoria
} else {
    error_log('Fallo en la consulta: ' . mysqli_error($mysqli));
}

mysqli_close($mysqli); 

header('Content-Type: application/json'); 
echo json_encode($arreglo); 

?>