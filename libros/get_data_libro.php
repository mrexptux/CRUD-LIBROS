<?php
    
include("../funcs/conexion.php");

$idlibro = isset($_POST['idlibro']) ? $_POST['idlibro'] : null; 

$query = "SELECT * FROM libros WHERE id = ?";
$stmt = $mysqli->prepare($query);

if (!$stmt) {
    die("Error");
}

$stmt->bind_param("i", $idlibro);
$stmt->execute();
$resultado = $stmt->get_result();

$arreglo = ["data" => []];

while ($data = $resultado->fetch_assoc()) {
    $arreglo["data"][] = $data;
}

echo json_encode($arreglo);

$resultado->free();
$mysqli->close();
?>
